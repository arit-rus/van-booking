<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('bookings.index') }}" class="mr-4 p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors duration-150">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                รายละเอียดการจอง
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Status Badge -->
                    <div class="mb-6 flex justify-between items-start">
                        <div>
                            <span class="inline-flex items-center gap-2 px-4 py-2 text-base font-semibold rounded-full {{ $booking->status_badge }}">
                                @if($booking->status === 'pending')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @elseif($booking->status === 'approved')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @elseif($booking->status === 'rejected')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @elseif($booking->status === 'completed')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                @endif
                                {{ $booking->status_text }}
                            </span>
                            <p class="text-sm text-gray-500 mt-2">
                                สร้างเมื่อ {{ $booking->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <!-- Download PDF Button -->
                            <a href="{{ route('bookings.pdf', $booking) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                ดาวน์โหลด PDF
                            </a>
                            @if($booking->status === 'pending')
                                <form action="{{ route('bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('ยืนยันการยกเลิก?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-2 bg-red-100 text-red-700 rounded-md hover:bg-red-200 print:hidden">
                                        ยกเลิกการจอง
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Booking Details -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">ข้อมูลการเดินทาง</h3>
                        <dl class="grid grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">ตั้งแต่วันที่</dt>
                                <dd class="text-sm text-gray-900 mt-1">
                                    {{ $booking->start_date->format('d/m/Y') }} เวลา {{ $booking->start_time }} น.
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">ถึงวันที่</dt>
                                <dd class="text-sm text-gray-900 mt-1">
                                    {{ $booking->end_date ? $booking->end_date->format('d/m/Y') : '-' }} เวลา {{ $booking->end_time }} น.
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">สถานที่รอรถ</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $booking->pickup_location }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">ปลายทาง</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $booking->destination }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">จำนวนที่นั่ง</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $booking->seats_requested }} ที่นั่ง</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">รถที่ใช้</dt>
                                <dd class="text-sm text-gray-900 mt-1">
                                    @if($booking->van)
                                        {{ $booking->van->name }} ({{ $booking->van->license_plate }})
                                    @else
                                        <span class="text-gray-400">รอการมอบหมาย</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">พนักงานขับรถ</dt>
                                <dd class="text-sm text-gray-900 mt-1">
                                    @if($booking->driver)
                                        {{ $booking->driver->name }}
                                    @else
                                        <span class="text-gray-400">รอการมอบหมาย</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Purpose -->
                    <div class="border-t pt-6 mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">วัตถุประสงค์</h3>
                        <p class="text-sm text-gray-700">{{ $booking->purpose }}</p>
                    </div>

                    <!-- Passengers -->
                    @if($booking->passengers->count() > 0)
                        <div class="border-t pt-6 mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">รายชื่อผู้โดยสาร</h3>
                            <ul class="divide-y divide-gray-200">
                                @foreach($booking->passengers as $passenger)
                                    <li class="py-2 flex justify-between">
                                        <span class="text-sm text-gray-900">{{ $passenger->name }}</span>
                                        <span class="text-sm text-gray-500">{{ $passenger->department ?? '-' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Admin Notes -->
                    @if($booking->admin_notes)
                        <div class="border-t pt-6 mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">หมายเหตุจากผู้ดูแล</h3>
                            <div class="bg-gray-50 p-4 rounded-md">
                                <p class="text-sm text-gray-700">{{ $booking->admin_notes }}</p>
                                @if($booking->approver)
                                    <p class="text-xs text-gray-500 mt-2">
                                        โดย {{ $booking->approver->name }} 
                                        เมื่อ {{ $booking->approved_at->format('d/m/Y H:i') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Fellow Travelers from Other Bookings -->
                    @if(isset($fellowTravelers) && $fellowTravelers->count() > 0)
                        @php
                            $passengerCount = $fellowTravelers->where('is_owner', false)->count();
                        @endphp
                        <div class="border-t pt-6 mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                ผู้ร่วมเดินทางจากคำขออื่น ({{ $passengerCount }} คน)
                            </h3>
                            <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                <p class="text-xs text-blue-600 mb-3">ผู้โดยสารจากคำขอจองอื่นที่ใช้รถคันเดียวกันในช่วงเวลาเดียวกัน</p>
                                <ul class="divide-y divide-blue-200">
                                    @foreach($fellowTravelers as $traveler)
                                        <li class="py-2 flex justify-between items-center">
                                            <div>
                                                <span class="text-sm font-medium text-gray-900">
                                                    {{ $traveler['name'] }}
                                                    @if($traveler['is_owner'])
                                                        <span class="text-xs bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded ml-1">ผู้ขอ</span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 text-xs bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded ml-1">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                            </svg>
                                                            ผู้โดยสาร
                                                        </span>
                                                    @endif
                                                </span>
                                                {{-- @if($traveler['department'])
                                                    <span class="text-xs text-gray-500 ml-2">{{ $traveler['department'] }}</span>
                                                @endif --}}
                                            </div>
                                            <span class="text-xs text-gray-500">→ {{ $traveler['booking_destination'] }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
