<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ขอจองรถตู้
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- User Info Section -->
            @if($hrdPerson)
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-white">
                    <h3 class="text-lg font-semibold mb-4">ข้อมูลผู้ขอจอง</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-indigo-200 text-sm">ชื่อ-สกุล</p>
                            <p class="font-medium">{{ $hrdPerson->full_name_th }}</p>
                        </div>
                        <div>
                            <p class="text-indigo-200 text-sm">ตำแหน่ง</p>
                            <p class="font-medium">{{ $hrdPerson->pos_name_th ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-indigo-200 text-sm">หน่วยงาน</p>
                            <p class="font-medium">{{ $hrdPerson->unit_name_th ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-indigo-200 text-sm">คณะ/สำนัก</p>
                            <p class="font-medium">{{ $hrdPerson->faculty_name_th ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('bookings.store') }}" id="bookingForm" enctype="multipart/form-data">
                        @csrf

                        <!-- Start Date/Time -->
                        <div class="mb-6">
                            <p class="block text-sm font-medium text-gray-700 mb-3">ตั้งแต่วันที่ <span class="text-red-500">*</span></p>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="start_date" class="block text-xs text-gray-500 mb-1">วันที่เริ่มต้น</label>
                                    <input type="date" name="start_date" id="start_date" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}" required>
                                    @error('start_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="start_time" class="block text-xs text-gray-500 mb-1">เวลา</label>
                                    <select name="start_time" id="start_time" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="">-- เลือกเวลา --</option>
                                        @for($h = 0; $h < 24; $h++)
                                            @for($m = 0; $m < 60; $m += 30)
                                                @php $time = sprintf('%02d:%02d', $h, $m); @endphp
                                                <option value="{{ $time }}" {{ old('start_time') === $time ? 'selected' : '' }}>{{ $time }} น.</option>
                                            @endfor
                                        @endfor
                                    </select>
                                    @error('start_time')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- End Date/Time -->
                        <div class="mb-6">
                            <p class="block text-sm font-medium text-gray-700 mb-3">ถึงวันที่ <span class="text-red-500">*</span></p>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="end_date" class="block text-xs text-gray-500 mb-1">วันที่สิ้นสุด</label>
                                    <input type="date" name="end_date" id="end_date" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        value="{{ old('end_date') }}" min="{{ date('Y-m-d') }}" required>
                                    @error('end_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="end_time" class="block text-xs text-gray-500 mb-1">เวลา</label>
                                    <select name="end_time" id="end_time" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="">-- เลือกเวลา --</option>
                                        @for($h = 0; $h < 24; $h++)
                                            @for($m = 0; $m < 60; $m += 30)
                                                @php $time = sprintf('%02d:%02d', $h, $m); @endphp
                                                <option value="{{ $time }}" {{ old('end_time') === $time ? 'selected' : '' }}>{{ $time }} น.</option>
                                            @endfor
                                        @endfor
                                    </select>
                                    @error('end_time')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Seats -->
                        <div class="mb-6">
                            <label for="seats_requested" class="block text-sm font-medium text-gray-700 mb-2">จำนวนที่นั่ง <span class="text-red-500">*</span></label>
                            <input type="number" name="seats_requested" id="seats_requested" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                value="{{ old('seats_requested', 1) }}" min="1" max="15" required>
                            @error('seats_requested')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Requested Department -->
                        <div class="mb-6">
                            <label for="requested_department" class="block text-sm font-medium text-gray-700 mb-2">หน่วยงานเจ้าของรถที่ขอใช้ <span class="text-red-500">*</span></label>
                            <select name="requested_department" id="requested_department" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">-- เลือกหน่วยงาน --</option>
                                @foreach(\App\Models\Van::DEPARTMENT_LABELS as $value => $label)
                                    <option value="{{ $value }}" {{ old('requested_department') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('requested_department')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pickup Location & Destination -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <label for="pickup_location" class="block text-sm font-medium text-gray-700 mb-2">สถานที่รอรถ <span class="text-red-500">*</span></label>
                                <input type="text" name="pickup_location" id="pickup_location" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('pickup_location') }}" placeholder="เช่น หน้าอาคารสำนักงานอธิการบดี" required>
                                @error('pickup_location')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="destination" class="block text-sm font-medium text-gray-700 mb-2">ปลายทาง <span class="text-red-500">*</span></label>
                                <input type="text" name="destination" id="destination" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    value="{{ old('destination') }}" placeholder="เช่น ศูนย์ประชุมแห่งชาติสิริกิติ์" required>
                                @error('destination')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Purpose -->
                        <div class="mb-6">
                            <label for="purpose" class="block text-sm font-medium text-gray-700 mb-2">วัตถุประสงค์ <span class="text-red-500">*</span></label>
                            <textarea name="purpose" id="purpose" rows="3" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="ระบุวัตถุประสงค์ในการเดินทาง" required>{{ old('purpose') }}</textarea>
                            @error('purpose')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Attachment -->
                        <div class="mb-6">
                            <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">เอกสารแนบ (ถ้ามี)</label>
                            <input type="file" name="attachment" id="attachment" 
                                class="w-full rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <p class="mt-1 text-xs text-gray-500">รองรับไฟล์ PDF, Word, รูปภาพ (สูงสุด 5MB)</p>
                            @error('attachment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Passengers Section -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-medium text-gray-700">รายชื่อผู้โดยสาร (ถ้ามี)</label>
                                <button type="button" id="addPassenger" class="inline-flex items-center gap-1 px-3 py-1.5 text-indigo-600 bg-indigo-50 rounded-lg font-medium hover:bg-indigo-100 transition-colors duration-150">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    เพิ่มผู้โดยสาร
                                </button>
                            </div>
                            <div id="passengersContainer" class="space-y-3">
                                <!-- Passenger rows will be added here -->
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('bookings.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 border-2 border-gray-200 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 hover:border-gray-300 transition-all duration-200">
                                ยกเลิก
                            </a>
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-semibold shadow-lg shadow-indigo-500/30 hover:from-indigo-700 hover:to-purple-700 hover:shadow-indigo-500/40 hover:-translate-y-0.5 transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                ส่งคำขอจอง
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let passengerCount = 0;
        
        document.getElementById('addPassenger').addEventListener('click', function() {
            const container = document.getElementById('passengersContainer');
            const row = document.createElement('div');
            row.className = 'flex space-x-2 items-center';
            row.innerHTML = `
                <input type="text" name="passengers[${passengerCount}][name]" placeholder="ชื่อ-นามสกุล" 
                    class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <input type="text" name="passengers[${passengerCount}][department]" placeholder="หน่วยงาน" 
                    class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 px-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;
            container.appendChild(row);
            passengerCount++;
        });
    </script>
</x-app-layout>
