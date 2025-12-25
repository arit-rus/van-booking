<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('admin.vans.index') }}" class="mr-4 text-gray-500 hover:text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                เพิ่มรถตู้ใหม่
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.vans.store') }}">
                        @csrf

                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">ชื่อรถ <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                value="{{ old('name') }}" placeholder="เช่น รถตู้หมายเลข 1" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="license_plate" class="block text-sm font-medium text-gray-700 mb-2">ทะเบียนรถ <span class="text-red-500">*</span></label>
                            <input type="text" name="license_plate" id="license_plate" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                value="{{ old('license_plate') }}" placeholder="เช่น กก 1234 กทม" required>
                            @error('license_plate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="campus" class="block text-sm font-medium text-gray-700 mb-2">ศูนย์พื้นที่ <span class="text-red-500">*</span></label>
                            <select name="campus" id="campus" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @foreach(\App\Models\Van::CAMPUS_LABELS as $value => $label)
                                    <option value="{{ $value }}" {{ old('campus') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('campus')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="owner_department" class="block text-sm font-medium text-gray-700 mb-2">หน่วยงานเจ้าของรถ <span class="text-red-500">*</span></label>
                            <select name="owner_department" id="owner_department" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                @foreach(\App\Models\Van::DEPARTMENT_LABELS as $value => $label)
                                    <option value="{{ $value }}" {{ old('owner_department') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('owner_department')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="capacity" class="block text-sm font-medium text-gray-700 mb-2">จำนวนที่นั่ง <span class="text-red-500">*</span></label>
                            <input type="number" name="capacity" id="capacity" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                value="{{ old('capacity', 12) }}" min="1" max="50" required>
                            @error('capacity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">สถานะ <span class="text-red-500">*</span></label>
                            <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>พร้อมใช้งาน</option>
                                <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>ซ่อมบำรุง</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">รายละเอียดเพิ่มเติม</label>
                            <textarea name="description" id="description" rows="3" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="เช่น รถตู้ Toyota Commuter สีขาว">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-3">
                            <a href="{{ route('admin.vans.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 border-2 border-gray-200 rounded-xl text-gray-700 font-semibold hover:bg-gray-50 hover:border-gray-300 transition-all duration-200">
                                ยกเลิก
                            </a>
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-xl font-semibold shadow-lg shadow-emerald-500/30 hover:from-emerald-600 hover:to-green-700 hover:shadow-emerald-500/40 hover:-translate-y-0.5 transition-all duration-200">
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
