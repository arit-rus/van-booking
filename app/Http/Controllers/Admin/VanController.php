<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Van;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VanController extends Controller
{
    /**
     * Display a listing of vans.
     */
    public function index()
    {
        $user = Auth::user();
        $query = Van::withCount('bookings');

        // Filter by department for department admins
        if (!$user->isSuperAdmin() && $user->isDepartmentAdmin()) {
            $query->where('owner_department', $user->getAdminDepartment());
        }

        $vans = $query->paginate(10);
        return view('admin.vans.index', compact('vans'));
    }

    /**
     * Show the form for creating a new van.
     */
    public function create()
    {
        return view('admin.vans.create');
    }

    /**
     * Store a newly created van.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'license_plate' => 'required|string|max:20|unique:vans',
            'campus' => 'required|in:huntra,wasukri,nonthaburi,suphanburi',
            'owner_department' => 'required|in:gad,subnon,subwa,subsu',
            'capacity' => 'required|integer|min:1|max:50',
            'status' => 'required|in:active,maintenance',
            'description' => 'nullable|string',
        ]);

        // Department admins can only create vans for their department
        if (!$user->isSuperAdmin() && $user->isDepartmentAdmin()) {
            $validated['owner_department'] = $user->getAdminDepartment();
        }

        Van::create($validated);

        return redirect()->route('admin.vans.index')
            ->with('success', 'เพิ่มรถตู้เรียบร้อยแล้ว');
    }

    /**
     * Show the form for editing a van.
     */
    public function edit(Van $van)
    {
        // Check department access
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $user->isDepartmentAdmin()) {
            if ($van->owner_department !== $user->getAdminDepartment()) {
                abort(403, 'ไม่มีสิทธิ์แก้ไขรถของหน่วยงานอื่น');
            }
        }

        return view('admin.vans.edit', compact('van'));
    }

    /**
     * Update the specified van.
     */
    public function update(Request $request, Van $van)
    {
        // Check department access
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $user->isDepartmentAdmin()) {
            if ($van->owner_department !== $user->getAdminDepartment()) {
                abort(403, 'ไม่มีสิทธิ์แก้ไขรถของหน่วยงานอื่น');
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'license_plate' => 'required|string|max:20|unique:vans,license_plate,' . $van->id,
            'campus' => 'required|in:huntra,wasukri,nonthaburi,suphanburi',
            'owner_department' => 'required|in:gad,subnon,subwa,subsu',
            'capacity' => 'required|integer|min:1|max:50',
            'status' => 'required|in:active,maintenance',
            'description' => 'nullable|string',
        ]);

        // Department admins cannot change owner_department
        if (!$user->isSuperAdmin() && $user->isDepartmentAdmin()) {
            $validated['owner_department'] = $van->owner_department;
        }

        $van->update($validated);

        return redirect()->route('admin.vans.index')
            ->with('success', 'แก้ไขข้อมูลรถตู้เรียบร้อยแล้ว');
    }

    /**
     * Remove the specified van.
     */
    public function destroy(Van $van)
    {
        // Check department access
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $user->isDepartmentAdmin()) {
            if ($van->owner_department !== $user->getAdminDepartment()) {
                abort(403, 'ไม่มีสิทธิ์ลบรถของหน่วยงานอื่น');
            }
        }

        // Check if van has active bookings
        $activeBookings = $van->bookings()
            ->whereIn('status', ['pending', 'approved'])
            ->where('start_date', '>=', today())
            ->count();

        if ($activeBookings > 0) {
            return back()->with('error', 'ไม่สามารถลบรถที่มีการจองอยู่ได้');
        }

        $van->delete();

        return redirect()->route('admin.vans.index')
            ->with('success', 'ลบรถตู้เรียบร้อยแล้ว');
    }
}
