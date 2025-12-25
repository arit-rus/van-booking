<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('admin.users.index') }}" class="mr-4 text-gray-500 hover:text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                แก้ไขสิทธิ์ผู้ใช้
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- User Info -->
                    <div class="mb-6 pb-6 border-b">
                        <div class="flex items-center">
                            <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center">
                                <span class="text-indigo-600 font-bold text-xl">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                @if($user->department)
                                    <p class="text-sm text-gray-500">หน่วยงาน: {{ $user->department }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Edit Form -->
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">กำหนดสิทธิ์</label>
                            <select name="role" id="role" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="toggleDepartment()">
                                @foreach($roles as $value => $label)
                                    <option value="{{ $value }}" {{ $user->role === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Department for Drivers -->
                        <div class="mb-6" id="department-section" style="{{ $user->role === 'driver' ? '' : 'display: none;' }}">
                            <label for="department" class="block text-sm font-medium text-gray-700 mb-2">หน่วยงานที่สังกัด <span class="text-red-500">*</span></label>
                            <select name="department" id="department" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- เลือกหน่วยงาน --</option>
                                @foreach($departments as $value => $label)
                                    <option value="{{ $value }}" {{ $user->department === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">พนักงานขับรถจะแสดงในรายการเลือกเฉพาะคำขอจากหน่วยงานเดียวกัน</p>
                        </div>

                        <!-- Role Description -->
                        <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">คำอธิบายสิทธิ์</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li><strong class="text-gray-800">ผู้ใช้ทั่วไป:</strong> ขอจองรถได้อย่างเดียว</li>
                                <li><strong class="text-blue-600">พนักงานขับรถ:</strong> สามารถเลือกมอบหมายตอนอนุมัติการจอง (ต้องระบุหน่วยงาน)</li>
                                <li><strong class="text-red-600">Super Admin:</strong> จัดการทุกอย่างในระบบ</li>
                                <li><strong class="text-purple-600">Admin หน่วยงาน:</strong> อนุมัติ/ปฏิเสธคำขอของหน่วยงานที่รับผิดชอบ</li>
                            </ul>
                        </div>

                        <script>
                            function toggleDepartment() {
                                var role = document.getElementById('role').value;
                                var deptSection = document.getElementById('department-section');
                                if (role === 'driver') {
                                    deptSection.style.display = 'block';
                                } else {
                                    deptSection.style.display = 'none';
                                }
                            }
                        </script>

                        @if($user->id === auth()->id())
                            <div class="mb-6 bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
                                <p class="text-sm text-yellow-700">
                                    <strong>หมายเหตุ:</strong> คุณไม่สามารถลดสิทธิ์ตัวเองได้
                                </p>
                            </div>
                        @endif

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 border-2 border-gray-200 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 hover:border-gray-300 transition-all duration-200">
                                ยกเลิก
                            </a>
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-semibold shadow-lg shadow-indigo-500/30 hover:from-indigo-700 hover:to-purple-700 hover:shadow-indigo-500/40 hover:-translate-y-0.5 transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                บันทึก
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
