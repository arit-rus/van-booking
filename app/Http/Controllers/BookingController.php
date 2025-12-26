<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Van;
use App\Models\HrdPerson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingController extends Controller
{
    /**
     * Display a listing of user's bookings.
     */
    public function index()
    {
        $bookings = Auth::user()->bookings()
            ->with(['van'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create()
    {
        $vans = Van::where('status', 'active')->get();
        
        // Get HRD person data for current user
        $hrdPerson = HrdPerson::findByIdCard(Auth::user()->idcard);
        
        return view('bookings.create', compact('vans', 'hrdPerson'));
    }

    /**
     * Store a newly created booking.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_date' => 'required|date|after_or_equal:start_date',
            'end_time' => 'required',
            'seats_requested' => 'required|integer|min:1|max:15',
            'pickup_location' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'purpose' => 'required|string',
            'requested_department' => 'required|in:gad,subnon,subwa,subsu',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'passengers' => 'nullable|array',
            'passengers.*.name' => 'required_with:passengers|string|max:255',
            'passengers.*.department' => 'nullable|string|max:255',
        ]);

        // Handle file upload
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('booking_attachments', 'public');
        }

        $booking = Auth::user()->bookings()->create([
            'start_date' => $validated['start_date'],
            'start_time' => $validated['start_time'],
            'end_date' => $validated['end_date'],
            'end_time' => $validated['end_time'],
            'seats_requested' => $validated['seats_requested'],
            'pickup_location' => $validated['pickup_location'],
            'destination' => $validated['destination'],
            'purpose' => $validated['purpose'],
            'requested_department' => $validated['requested_department'],
            'attachment_path' => $attachmentPath,
            'status' => 'pending',
        ]);

        // Add passengers if provided
        if (!empty($validated['passengers'])) {
            foreach ($validated['passengers'] as $passenger) {
                $booking->passengers()->create([
                    'name' => $passenger['name'],
                    'department' => $passenger['department'] ?? null,
                ]);
            }
        }

        return redirect()->route('bookings.index')
            ->with('success', 'ส่งคำขอจองรถเรียบร้อยแล้ว รอการอนุมัติจากผู้ดูแลระบบ');
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking)
    {
        // Ensure user can only view their own bookings
        if ($booking->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $booking->load(['van', 'driver', 'passengers', 'approver']);
        
        // Get fellow travelers from other approved bookings using the same van on overlapping dates
        $fellowTravelers = collect();
        if ($booking->van_id && $booking->status === 'approved') {
            $otherBookings = Booking::with(['user', 'passengers'])
                ->where('id', '!=', $booking->id)
                ->where('van_id', $booking->van_id)
                ->where('status', 'approved')
                ->where('start_date', '<=', $booking->end_date)
                ->where('end_date', '>=', $booking->start_date)
                ->get();
            
            foreach ($otherBookings as $otherBooking) {
                // Add the booking owner
                $fellowTravelers->push([
                    'name' => $otherBooking->user->name,
                    'department' => $otherBooking->user->department ?? null,
                    'is_owner' => true,
                    'booking_destination' => $otherBooking->destination,
                ]);
                
                // Add passengers from other bookings
                foreach ($otherBooking->passengers as $passenger) {
                    $fellowTravelers->push([
                        'name' => $passenger->name,
                        'department' => $passenger->department,
                        'is_owner' => false,
                        'booking_destination' => $otherBooking->destination,
                    ]);
                }
            }
        }
        
        return view('bookings.show', compact('booking', 'fellowTravelers'));
    }

    /**
     * Download booking as PDF.
     */
    public function downloadPdf(Booking $booking)
    {
        // Ensure user can only download their own bookings
        if ($booking->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $booking->load(['van', 'driver', 'passengers', 'approver', 'user']);
        
        $pdf = Pdf::loadView('bookings.pdf', compact('booking'));
        $pdf->setPaper('a4', 'portrait');
        
        $filename = 'booking_' . str_pad($booking->id, 6, '0', STR_PAD_LEFT) . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Cancel a booking.
     */
    public function destroy(Booking $booking)
    {
        // Only allow cancellation of own pending bookings
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return back()->with('error', 'ไม่สามารถยกเลิกการจองที่ได้รับการอนุมัติแล้ว');
        }

        $booking->delete();

        return redirect()->route('bookings.index')
            ->with('success', 'ยกเลิกการจองเรียบร้อยแล้ว');
    }
}
