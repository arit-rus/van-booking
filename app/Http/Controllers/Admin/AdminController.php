<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Van;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Display admin dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Base query - filter by department for department admins
        $bookingsQuery = Booking::query();
        if (!$user->isSuperAdmin() && $user->isDepartmentAdmin()) {
            $bookingsQuery->where('requested_department', $user->getAdminDepartment());
        }
        
        $stats = [
            'total_vans' => Van::count(),
            'active_vans' => Van::where('status', 'active')->count(),
            'pending_bookings' => (clone $bookingsQuery)->where('status', 'pending')->count(),
            'approved_today' => (clone $bookingsQuery)->where('status', 'approved')
                ->whereDate('start_date', today())
                ->count(),
            'total_users' => User::where('role', 'user')->count(),
        ];

        $pendingBookings = (clone $bookingsQuery)->with(['user', 'van'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $todayBookings = (clone $bookingsQuery)->with(['user', 'van'])
            ->where('status', 'approved')
            ->whereDate('start_date', today())
            ->orderBy('start_time')
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingBookings', 'todayBookings'));
    }

    /**
     * Display all bookings.
     */
    public function bookings(Request $request)
    {
        $user = Auth::user();
        $query = Booking::with(['user', 'van']);

        // Filter by department for department admins
        if (!$user->isSuperAdmin() && $user->isDepartmentAdmin()) {
            $query->where('requested_department', $user->getAdminDepartment());
        }

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->has('date')) {
            $query->whereDate('start_date', $request->date);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Show booking details.
     */
    public function showBooking(Booking $booking)
    {
        // Check department access
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $user->isDepartmentAdmin()) {
            if ($booking->requested_department !== $user->getAdminDepartment()) {
                abort(403, 'ไม่มีสิทธิ์ดูคำขอของหน่วยงานนี้');
            }
        }

        $booking->load(['user', 'van', 'driver', 'passengers', 'approver']);
        
        // Filter vans and drivers by the booking's requested department
        $requestedDept = $booking->requested_department;
        
        $vans = Van::where('status', 'active')
            ->where('owner_department', $requestedDept)
            ->get();
            
        $drivers = User::where('role', 'driver')
            ->where('department', $requestedDept)
            ->orderBy('name')
            ->get();

        return view('admin.bookings.show', compact('booking', 'vans', 'drivers'));
    }

    /**
     * Approve a booking.
     */
    public function approve(Request $request, Booking $booking)
    {
        // Check department access
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $user->isDepartmentAdmin()) {
            if ($booking->requested_department !== $user->getAdminDepartment()) {
                abort(403, 'ไม่มีสิทธิ์อนุมัติคำขอของหน่วยงานนี้');
            }
        }

        $validated = $request->validate([
            'van_id' => 'required|exists:vans,id',
            'driver_id' => 'nullable|exists:users,id',
            'admin_notes' => 'nullable|string',
        ]);

        // Check van availability
        $van = Van::findOrFail($validated['van_id']);
        $availableSeats = $van->getAvailableSeats($booking->start_date);

        if ($availableSeats < $booking->seats_requested) {
            return back()->with('error', 'ที่นั่งไม่เพียงพอ (ว่าง: ' . $availableSeats . ' ที่นั่ง)');
        }

        $booking->update([
            'van_id' => $validated['van_id'],
            'driver_id' => $validated['driver_id'] ?? null,
            'status' => 'approved',
            'admin_notes' => $validated['admin_notes'],
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('admin.bookings')
            ->with('success', 'อนุมัติการจองเรียบร้อยแล้ว');
    }

    /**
     * Reject a booking.
     */
    public function reject(Request $request, Booking $booking)
    {
        // Check department access
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $user->isDepartmentAdmin()) {
            if ($booking->requested_department !== $user->getAdminDepartment()) {
                abort(403, 'ไม่มีสิทธิ์ปฏิเสธคำขอของหน่วยงานนี้');
            }
        }

        $validated = $request->validate([
            'admin_notes' => 'required|string',
        ]);

        $booking->update([
            'status' => 'rejected',
            'admin_notes' => $validated['admin_notes'],
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('admin.bookings')
            ->with('success', 'ปฏิเสธการจองเรียบร้อยแล้ว');
    }

    /**
     * Mark booking as completed.
     */
    public function complete(Booking $booking)
    {
        // Check department access
        $user = Auth::user();
        if (!$user->isSuperAdmin() && $user->isDepartmentAdmin()) {
            if ($booking->requested_department !== $user->getAdminDepartment()) {
                abort(403, 'ไม่มีสิทธิ์ดำเนินการกับคำขอของหน่วยงานนี้');
            }
        }

        $booking->update(['status' => 'completed']);

        return back()->with('success', 'บันทึกการเดินทางเสร็จสิ้นแล้ว');
    }
}
