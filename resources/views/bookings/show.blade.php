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
                            <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $booking->status_badge }}">
                                {{ $booking->status_text }}
                            </span>
                            <p class="text-sm text-gray-500 mt-2">
                                สร้างเมื่อ {{ $booking->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        @if($booking->status === 'pending')
                            <form action="{{ route('bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('ยืนยันการยกเลิก?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-100 text-red-700 rounded-md hover:bg-red-200">
                                    ยกเลิกการจอง
                                </button>
                            </form>
                        @endif
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
