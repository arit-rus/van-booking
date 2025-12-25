<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Van;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        // Only super admins can manage users
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'เฉพาะ Super Admin เท่านั้นที่สามารถจัดการสิทธิ์ผู้ใช้');
        }

        $query = User::query();

        // Search by name or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role !== 'all') {
            if ($request->role === 'admin_all') {
                $query->where(function($q) {
                    $q->where('role', 'admin')
                      ->orWhere('role', 'like', 'admin_%');
                });
            } else {
                $query->where('role', $request->role);
            }
        }

        $users = $query->orderBy('name')->paginate(20);

        // Get available roles
        $roles = [
            'user' => 'ผู้ใช้ทั่วไป',
            'driver' => 'พนักงานขับรถ',
            'admin' => 'Super Admin',
            'admin_gad' => 'Admin กองกลาง',
            'admin_subnon' => 'Admin กองบริหารทรัพยากรนนทบุรี',
            'admin_subwa' => 'Admin กองกลาง งานบริการ ศูนย์วาสุกรี',
            'admin_subsu' => 'Admin กองบริหารทรัพยากรสุพรรณบุรี',
        ];

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show edit form for user role.
     */
    public function edit(User $user)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'เฉพาะ Super Admin เท่านั้นที่สามารถจัดการสิทธิ์ผู้ใช้');
        }

        $roles = [
            'user' => 'ผู้ใช้ทั่วไป',
            'driver' => 'พนักงานขับรถ',
            'admin' => 'Super Admin',
            'admin_gad' => 'Admin กองกลาง',
            'admin_subnon' => 'Admin กองบริหารทรัพยากรนนทบุรี',
            'admin_subwa' => 'Admin กองกลาง งานบริการ ศูนย์วาสุกรี',
            'admin_subsu' => 'Admin กองบริหารทรัพยากรสุพรรณบุรี',
        ];

        $departments = \App\Models\Van::DEPARTMENT_LABELS;

        return view('admin.users.edit', compact('user', 'roles', 'departments'));
    }

    /**
     * Update user role.
     */
    public function update(Request $request, User $user)
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'เฉพาะ Super Admin เท่านั้นที่สามารถจัดการสิทธิ์ผู้ใช้');
        }

        // Prevent self-demotion
        if ($user->id === Auth::id() && $request->role !== 'admin') {
            return back()->with('error', 'ไม่สามารถลดสิทธิ์ตัวเองได้');
        }

        $rules = [
            'role' => 'required|in:user,driver,admin,admin_gad,admin_subnon,admin_subwa,admin_subsu',
        ];

        // Require department for drivers
        if ($request->role === 'driver') {
            $rules['department'] = 'required|in:gad,subnon,subwa,subsu';
        }

        $validated = $request->validate($rules);

        $updateData = ['role' => $validated['role']];
        
        // Set department for drivers
        if ($request->role === 'driver' && isset($validated['department'])) {
            $updateData['department'] = $validated['department'];
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')
            ->with('success', 'อัปเดตสิทธิ์ของ ' . $user->name . ' เรียบร้อยแล้ว');
    }
}
