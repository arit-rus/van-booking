<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('ข้อมูลบุคลากร') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('ข้อมูลจากระบบบุคลากร มหาวิทยาลัยเทคโนโลยีราชมงคลสุวรรณภูมิ') }}
        </p>
    </header>

    @if($hrdPerson)
    
        <div class="mt-6 space-y-4">
            <!-- Profile Photo and Name -->
            <div class="flex items-center space-x-6 mb-6">
                <div class="flex-shrink-0">
                    @if($hrdPerson->person_picture)
                        <img class="h-24 w-24 rounded-full object-cover border-4 border-indigo-100 shadow-lg" 
                             src="https://hrd.rmutsb.ac.th/upload/his/person/photo/{{ $hrdPerson->person_picture }}" 
                             alt="{{ $hrdPerson->fname_th }}"
                             onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($hrdPerson->fname_th) }}&size=96&background=6366f1&color=fff';">
                    @else
                        <div class="h-24 w-24 rounded-full bg-indigo-100 flex items-center justify-center border-4 border-indigo-50 shadow-lg">
                            <span class="text-3xl font-bold text-indigo-600">{{ mb_substr($hrdPerson->fname_th, 0, 1) }}</span>
                        </div>
                    @endif
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">
                        {{ $hrdPerson->title_name_th }}{{ $hrdPerson->fname_th }} {{ $hrdPerson->lname_th }}
                    </h3>
                    <p class="text-gray-500">{{ $hrdPerson->pos_name_th ?? '' }}</p>
                    @if($hrdPerson->statuslist_name)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1 {{ $hrdPerson->statuslist_name == 'ปฏิบัติงาน' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $hrdPerson->statuslist_name }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- ชื่อ-นามสกุล -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <dt class="text-sm font-medium text-gray-500">ชื่อ-นามสกุล</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-900">
                        {{ $hrdPerson->title_name_th }}{{ $hrdPerson->fname_th }} {{ $hrdPerson->lname_th }}
                    </dd>
                </div>

                <!-- เลขบัตรประชาชน -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <dt class="text-sm font-medium text-gray-500">เลขบัตรประชาชน</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-900">
                        {{ $hrdPerson->id_card ? substr($hrdPerson->id_card, 0, 1) . '-XXXX-XXXXX-XX-' . substr($hrdPerson->id_card, 12, 1) : '-' }}
                    </dd>
                </div>

                <!-- ประเภทบุคลากร -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <dt class="text-sm font-medium text-gray-500">ประเภทบุคลากร</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-900">
                        {{ $hrdPerson->rate_id ?? '-' }}
                    </dd>
                </div>

                <!-- ตำแหน่ง -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <dt class="text-sm font-medium text-gray-500">ตำแหน่ง</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-900">
                        {{ $hrdPerson->pos_name_th ?? '-' }}
                    </dd>
                </div>

                <!-- หน่วยงาน -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <dt class="text-sm font-medium text-gray-500">หน่วยงาน</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-900">
                        {{ $hrdPerson->unit_name_th ?? '-' }}
                    </dd>
                </div>

                <!-- คณะ/สำนัก -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <dt class="text-sm font-medium text-gray-500">คณะ/สำนัก</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-900">
                        {{ $hrdPerson->faculty_name_th ?? '-' }}
                    </dd>
                </div>

                <!-- ตำแหน่งทางวิชาการ/บริหาร -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <dt class="text-sm font-medium text-gray-500">ตำแหน่งทางวิชาการ/บริหาร</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-900">
                        {{ $hrdPerson->position_name ?? '-' }}
                    </dd>
                </div>

                <!-- สถานะ -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <dt class="text-sm font-medium text-gray-500">สถานะ</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ $hrdPerson->statuslist_name == 'ปฏิบัติงาน' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $hrdPerson->statuslist_name ?? '-' }}
                        </span>
                    </dd>
                </div>

                <!-- มหาวิทยาลัย -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <dt class="text-sm font-medium text-gray-500">ศูนย์พื้นที่</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-900">
                        {{ $hrdPerson->campus_name_th ?? '-' }}
                    </dd>
                </div>
            </div>
        </div>
    @else
        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">ไม่พบข้อมูลบุคลากร</h3>
                    <p class="mt-2 text-sm text-yellow-700">
                        ระบบไม่พบข้อมูลบุคลากรที่ตรงกับบัญชีผู้ใช้นี้ กรุณาติดต่อฝ่ายบุคลากรหากต้องการความช่วยเหลือ
                    </p>
                </div>
            </div>
        </div>
    @endif
</section>
