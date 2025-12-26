<x-guest-layout>
    <div class="w-full max-w-6xl mx-auto px-4 py-8 flex flex-col lg:flex-row items-center justify-center gap-12">
      

        <!-- Right Side - Login Card -->
        <div class="w-full max-w-md">
            <div class="backdrop-blur-xl bg-white/20 rounded-3xl shadow-2xl border border-white/30 p-8">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white/30 rounded-full mb-4">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white">เข้าสู่ระบบ</h2>
                </div>

                <x-validation-errors class="mb-4" />
                
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-300 bg-green-500/20 p-3 rounded-lg">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Username -->
                    <div class="mb-5">
                        <label for="email" class="block text-sm font-medium text-white mb-2">
                            Username
                        </label>
                        <input id="email" 
                               type="text" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus 
                               autocomplete="username"
                               placeholder="กรอก Username"
                               class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-xl text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all duration-300">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mb-5">
                        <label for="password" class="block text-sm font-medium text-white mb-2">
                            Password
                        </label>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="current-password"
                               placeholder="กรอก Password"
                               class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-xl text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all duration-300">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center mb-6">
                        <input id="remember_me" 
                               type="checkbox" 
                               name="remember"
                               class="w-4 h-4 rounded bg-white/20 border-white/30 text-blue-500 focus:ring-white/50">
                        <label for="remember_me" class="ml-2 text-sm text-white/90">
                            {{ __('Remember me') }}
                        </label>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" 
                            class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-transparent">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        เข้าสู่ระบบ
                    </button>

                    <!-- Help Text -->
                    <p class="mt-6 text-center text-sm text-white/70">
                        ใช้ Username และ Password เดียวกันกับ Internet
                    </p>
                </form>
            </div>
        </div>

          <!-- Left Side - Title -->
        <div class="text-center lg:text-left lg:flex-1">
            <h2 class="text-2xl md:text-3xl font-bold text-black drop-shadow-lg mb-4">
                ระบบขอใช้รถราชการ
            </h2>
            <p class="text-xl text-black drop-shadow">
                มหาวิทยาลัยเทคโนโลยีราชมงคลสุวรรณภูมิ
            </p>
        </div> 
    </div>
</x-guest-layout>
