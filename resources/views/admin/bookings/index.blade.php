<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                จัดการคำขอจอง
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filters -->
            <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
                <form method="GET" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">สถานะ</label>
                        <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>ทั้งหมด</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>รออนุมัติ</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>อนุมัติแล้ว</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>ไม่อนุมัติ</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>เสร็จสิ้น</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">วันที่เดินทาง</label>
                        <input type="date" name="date" value="{{ request('date') }}" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        กรอง
                    </button>
                    <a href="{{ route('admin.bookings') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                        ล้าง
                    </a>
                </form>
            </div>

            <!-- Bookings Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ผู้ขอ</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">วันที่/เวลา</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">เส้นทาง</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ที่นั่ง</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">รถ</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สถานะ</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">การดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($bookings as $booking)
                                <tr class="{{ $booking->status === 'pending' ? 'bg-yellow-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $booking->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $booking->start_date->format('d/m/Y') }} {{ $booking->start_time }} น.</div>
                                        <div class="text-sm text-gray-500">ถึง {{ $booking->end_date ? $booking->end_date->format('d/m/Y') : '-' }} {{ $booking->end_time }} น.</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ Str::limit($booking->pickup_location, 20) }}</div>
                                        <div class="text-sm text-gray-500">→ {{ Str::limit($booking->destination, 20) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $booking->seats_requested }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $booking->van->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $booking->status_badge }}">
                                            {{ $booking->status_text }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if($booking->status === 'pending')
                                            <a href="{{ route('admin.bookings.show', $booking) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-gradient-to-r from-amber-400 to-orange-500 text-white rounded-lg font-medium shadow-sm hover:from-amber-500 hover:to-orange-600 transition-all duration-150">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                                </svg>
                                                ตรวจสอบ
                                            </a>
                                        @else
                                            <a href="{{ route('admin.bookings.show', $booking) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-indigo-600 bg-indigo-50 rounded-lg font-medium hover:bg-indigo-100 hover:text-indigo-700 transition-colors duration-150">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                ดู
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                        ไม่พบข้อมูลการจอง
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">
                    {{ $bookings->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
