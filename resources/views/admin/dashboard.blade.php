<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            แดชบอร์ดผู้ดูแลระบบ
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">รถทั้งหมด</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_vans'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">รถพร้อมใช้</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['active_vans'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">รออนุมัติ</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_bookings'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">จองวันนี้</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['approved_today'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">ผู้ใช้งาน</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_users'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Pending Bookings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">คำขอรออนุมัติ</h3>
                            <a href="{{ route('admin.bookings', ['status' => 'pending']) }}" class="text-sm text-indigo-600 hover:text-indigo-800">ดูทั้งหมด →</a>
                        </div>
                        @if($pendingBookings->isEmpty())
                            <p class="text-gray-500 text-sm">ไม่มีคำขอที่รออนุมัติ</p>
                        @else
                            <div class="space-y-3">
                                @foreach($pendingBookings as $booking)
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="block p-3 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $booking->user->name }}</p>
                                                <p class="text-sm text-gray-600">{{ $booking->pickup_location }} → {{ $booking->destination }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-medium text-gray-900">{{ $booking->start_date->format('d/m/Y') }}</p>
                                                <p class="text-xs text-gray-500">{{ $booking->seats_requested }} ที่นั่ง</p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Today's Trips -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">การเดินทางวันนี้</h3>
                            <span class="text-sm text-gray-500">{{ now()->format('d/m/Y') }}</span>
                        </div>
                        @if($todayBookings->isEmpty())
                            <p class="text-gray-500 text-sm">ไม่มีการเดินทางวันนี้</p>
                        @else
                            <div class="space-y-3">
                                @foreach($todayBookings as $booking)
                                    <div class="p-3 bg-green-50 rounded-lg">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-medium text-gray-900">
                                                    {{ $booking->start_time }} น. - {{ $booking->van->name ?? 'N/A' }}
                                                    @if($booking->van)
                                                        <span class="text-xs text-gray-500">({{ $booking->van->license_plate }})</span>
                                                    @endif
                                                </p>
                                                <p class="text-sm text-gray-600">{{ $booking->pickup_location }} → {{ $booking->destination }}</p>
                                                <p class="text-xs text-gray-500 mt-1">{{ $booking->user->name }} ({{ $booking->seats_requested }} คน)</p>
                                            </div>
                                            <form action="{{ route('admin.bookings.complete', $booking) }}" method="POST" onsubmit="return confirm('ยืนยันว่าการเดินทางนี้เสร็จสิ้นแล้ว?')">
                                                @csrf
                                                <button type="submit" class="text-xs px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                                                    เสร็จสิ้น
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('admin.vans.index') }}" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">จัดการรถตู้</h4>
                        <p class="text-sm text-gray-500">เพิ่ม แก้ไข ลบรถตู้</p>
                    </div>
                </a>

                <a href="{{ route('admin.bookings') }}" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition flex items-center">
                    <div class="p-3 bg-green-100 rounded-full mr-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">รายการจอง</h4>
                        <p class="text-sm text-gray-500">ดูและจัดการคำขอจอง</p>
                    </div>
                </a>

                <a href="{{ route('admin.vans.create') }}" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full mr-4">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">เพิ่มรถใหม่</h4>
                        <p class="text-sm text-gray-500">ลงทะเบียนรถตู้ใหม่</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
