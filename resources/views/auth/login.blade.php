<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cake & Bakery A&R</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700&display=swap');
        body { font-family: 'Outfit', sans-serif; }
        .font-serif-custom { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-white flex items-center justify-center h-screen overflow-hidden">
    
    <div class="flex w-full h-full">
        <!-- Left Side (Branding & Image) -->
        <div class="hidden md:flex md:w-1/2 relative flex-col justify-center items-center overflow-hidden">
            <!-- Full Height Bread Image Background -->
            <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" class="absolute inset-0 w-full h-full object-cover object-center" alt="Aneka Roti">
            <!-- Overlay gradasi agar teks tetap terbaca -->
            <div class="absolute inset-0 bg-gradient-to-t from-[#831843]/90 via-[#831843]/50 to-[#831843]/30"></div>

            <!-- Text & Logo Area -->
            <div class="text-center relative z-10 flex flex-col items-center p-12">
                <!-- Bakery Icon/Logo -->
                <div class="mb-6 drop-shadow-lg w-28 h-28 rounded-full overflow-hidden border-4 border-white/30 bg-white/10 flex items-center justify-center shadow-2xl">
                    <img src="{{ asset('storage/Logo.jpeg') }}" alt="A&R Logo" class="w-full h-full object-cover scale-[1.35]">
                </div>
                <h1 class="text-4xl lg:text-5xl font-serif-custom font-bold text-white mb-4 drop-shadow-md">TOKO A&R</h1>
                <p class="text-lg lg:text-xl text-pink-50 font-medium leading-relaxed drop-shadow-md">Roti dan Kue Berkualitas</p>
            </div>
        </div>

        <!-- Right Side (Login Form) -->
        <div class="w-full md:w-1/2 flex items-center justify-center p-8 bg-white relative">
            <div class="w-full max-w-[420px]">
                
                <div class="text-center mb-12">
                    <h2 class="text-[32px] font-extrabold text-[#111827] mb-3 tracking-tight">Selamat Datang</h2>
                    <p class="text-[#4b5563] text-lg font-medium">Silakan login untuk melanjutkan</p>
                </div>

                @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6 text-sm font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                @if (isset($errors) && $errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-8 text-sm font-medium">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-[15px] font-bold text-[#1f2937] mb-2.5">Username</label>
                        <input type="text" name="username" value="{{ old('username') }}" class="w-full px-5 py-3.5 rounded-[10px] border border-[#d1d5db] focus:border-[#db2777] focus:ring-4 focus:ring-[#fce7f3] outline-none transition text-[#374151] placeholder-[#9ca3af] text-[15px]" placeholder="Masukkan username" required>
                    </div>

                    <div class="pt-2">
                        <div class="flex justify-between items-center mb-2.5">
                            <label class="block text-[15px] font-bold text-[#1f2937]">Password</label>
                            <a href="{{ route('password.request') }}" class="text-[13px] font-semibold text-[#db2777] hover:text-[#be185d] transition-colors">
                                Lupa kata sandi?
                            </a>
                        </div>
                        <div class="relative">
                            <input type="password" name="password" id="passwordInput" class="w-full px-5 py-3.5 rounded-[10px] border border-[#d1d5db] focus:border-[#db2777] focus:ring-4 focus:ring-[#fce7f3] outline-none transition text-[#374151] placeholder-[#9ca3af] text-[15px]" placeholder="Masukkan password" required>
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-[#9ca3af] hover:text-[#db2777] focus:outline-none transition-colors">
                                <svg id="eyeIcon" class="h-[22px] w-[22px]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="pt-6">
                        <!-- Modified to Pink Theme as requested -->
                        <button type="submit" class="w-full bg-[#db2777] hover:bg-[#be185d] text-white font-bold py-4 rounded-[10px] shadow-lg shadow-[#fbcfe8] hover:shadow-[#f9a8d4] transition-all duration-300 text-[16px]">
                            Login
                        </button>
                    </div>

                    <div class="mt-6 text-center">
                        <p class="text-[14px] text-[#4b5563]">Belum punya akun? 
                            <a href="{{ route('register') }}" class="font-bold text-[#db2777] hover:text-[#be185d] transition-colors">Daftar sebagai reseller</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const pwdInput = document.getElementById('passwordInput');
            const eyeIcon = document.getElementById('eyeIcon');
            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
            } else {
                pwdInput.type = 'password';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }
    </script>
</body>
</html>
