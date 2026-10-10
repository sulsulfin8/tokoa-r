<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - Cake & Bakery A&R</title>
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
            <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" class="absolute inset-0 w-full h-full object-cover object-center" alt="Aneka Roti">
            <div class="absolute inset-0 bg-gradient-to-t from-[#831843]/90 via-[#831843]/50 to-[#831843]/30"></div>

            <div class="text-center relative z-10 flex flex-col items-center p-12">
                <div class="mb-6 drop-shadow-lg w-28 h-28 rounded-full overflow-hidden border-4 border-white/30 bg-white/10 flex items-center justify-center shadow-2xl">
                    <img src="{{ asset('storage/Logo.jpeg') }}" alt="A&R Logo" class="w-full h-full object-cover scale-[1.35]">
                </div>
                <h1 class="text-4xl lg:text-5xl font-serif-custom font-bold text-white mb-4 drop-shadow-md">TOKO A&R</h1>
                <p class="text-lg lg:text-xl text-pink-50 font-medium leading-relaxed drop-shadow-md">Roti dan Kue Berkualitas</p>
            </div>
        </div>

        <!-- Right Side (Verify OTP Form) -->
        <div class="w-full md:w-1/2 flex items-center justify-center p-8 bg-white relative overflow-y-auto">
            <div class="w-full max-w-[420px]">
                
                <a href="{{ route('password.request') }}" class="inline-flex items-center text-sm font-semibold text-[#db2777] hover:text-[#be185d] mb-6 transition-colors group">
                    <svg class="w-4 h-4 mr-1.5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Ganti Email / Username
                </a>

                <div class="mb-6">
                    <div class="w-12 h-12 rounded-2xl bg-pink-100 text-[#db2777] flex items-center justify-center mb-4 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h2 class="text-[28px] font-extrabold text-[#111827] mb-2 tracking-tight">Verifikasi Kode OTP</h2>
                    <p class="text-[#4b5563] text-[14px] leading-relaxed">
                        Kode OTP telah dikirim ke: <strong class="text-[#db2777] font-bold">{{ $email }}</strong>. Silakan masukkan kode dan tentukan kata sandi baru Anda.
                    </p>
                </div>

                @if (isset($errors) && $errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6 text-sm font-medium">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6 text-sm font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div>
                        <label class="block text-[14px] font-bold text-[#1f2937] mb-2">Kode OTP (6 Digit)</label>
                        <input type="text" name="otp" id="otpInput" maxlength="6" pattern="[0-9]*" inputmode="numeric" placeholder="------" class="w-full text-center text-3xl font-extrabold tracking-[0.6em] py-3.5 rounded-[10px] border border-[#d1d5db] focus:border-[#db2777] focus:ring-4 focus:ring-[#fce7f3] outline-none transition text-[#1f2937] placeholder-[#d1d5db]" required autofocus>
                        <p class="text-[12px] text-gray-500 mt-1.5 text-center">Berlaku selama 15 menit</p>
                    </div>

                    <div class="pt-2">
                        <label class="block text-[14px] font-bold text-[#1f2937] mb-1.5">Kata Sandi Baru</label>
                        <div class="relative">
                            <input type="password" name="password" id="passwordInput" placeholder="Minimal 6 karakter" class="w-full px-4 py-3 rounded-[10px] border border-[#d1d5db] focus:border-[#db2777] focus:ring-4 focus:ring-[#fce7f3] outline-none transition text-[#374151] text-[15px]" required>
                            <button type="button" onclick="togglePassword('passwordInput', 'eyeIcon1')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-[#9ca3af] hover:text-[#db2777] focus:outline-none transition-colors">
                                <svg id="eyeIcon1" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[14px] font-bold text-[#1f2937] mb-1.5">Konfirmasi Kata Sandi Baru</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="passwordConfirmInput" placeholder="Ulangi kata sandi baru" class="w-full px-4 py-3 rounded-[10px] border border-[#d1d5db] focus:border-[#db2777] focus:ring-4 focus:ring-[#fce7f3] outline-none transition text-[#374151] text-[15px]" required>
                            <button type="button" onclick="togglePassword('passwordConfirmInput', 'eyeIcon2')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-[#9ca3af] hover:text-[#db2777] focus:outline-none transition-colors">
                                <svg id="eyeIcon2" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="w-full bg-[#db2777] hover:bg-[#be185d] text-white font-bold py-3.5 rounded-[10px] shadow-lg shadow-[#fbcfe8] hover:shadow-[#f9a8d4] transition-all duration-300 text-[15px] flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Reset & Simpan Kata Sandi</span>
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center border-t border-gray-100 pt-4">
                    <p class="text-[13px] text-[#4b5563] mb-2">Tidak menerima email kode OTP?</p>
                    <form action="{{ route('password.resend') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="font-bold text-[#db2777] hover:text-[#be185d] text-sm hover:underline transition">
                            Kirim Ulang Kode OTP
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const pwdInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
            if (pwdInput.type === 'password') {
                pwdInput.type = 'text';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
            } else {
                pwdInput.type = 'password';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }

        // Auto numeric only on OTP
        const otpInput = document.getElementById('otpInput');
        if (otpInput) {
            otpInput.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }
    </script>
</body>
</html>
