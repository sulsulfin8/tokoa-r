<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - Cake & Bakery A&R</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700&display=swap');
        body { font-family: 'Outfit', sans-serif; }
        .font-serif-custom { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-[#faf5f8] flex items-center justify-center h-screen overflow-hidden">
    
    <div class="flex w-full h-full">
        <!-- Left Side (Branding & Image) -->
        <div class="hidden md:flex md:w-1/2 relative flex-col justify-center items-center overflow-hidden">
            <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" class="absolute inset-0 w-full h-full object-cover object-center" alt="Aneka Roti">
            <div class="absolute inset-0 bg-gradient-to-t from-[#831843]/95 via-[#831843]/60 to-[#831843]/30"></div>

            <div class="text-center relative z-10 flex flex-col items-center p-12">
                <div class="mb-6 drop-shadow-2xl w-28 h-28 rounded-full overflow-hidden border-4 border-white/40 bg-white/10 flex items-center justify-center shadow-2xl backdrop-blur-sm">
                    <img src="{{ asset('storage/Logo.jpeg') }}" alt="A&R Logo" class="w-full h-full object-cover scale-[1.35]">
                </div>
                <h1 class="text-4xl lg:text-5xl font-serif-custom font-bold text-white mb-4 drop-shadow-lg">TOKO A&R</h1>
                <p class="text-lg lg:text-xl text-pink-100 font-medium leading-relaxed drop-shadow-md">Roti dan Kue Berkualitas</p>
                
                <div class="mt-8 flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/15 backdrop-blur-md border border-white/20 text-white/90 text-xs font-semibold tracking-wide">
                    <svg class="w-4 h-4 text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span>Pemulihan Akun Aman & Cepat</span>
                </div>
            </div>
        </div>

        <!-- Right Side (Forgot Password Form) -->
        <div class="w-full md:w-1/2 flex items-center justify-center p-6 sm:p-10 bg-gradient-to-br from-[#fffbfa] via-[#fff5f8] to-[#fdf2f8] relative overflow-y-auto">
            
            <!-- Ambient Decorative Blurs -->
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-gradient-to-br from-pink-300/30 to-rose-200/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-16 w-80 h-80 bg-gradient-to-tr from-pink-200/30 to-purple-200/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="w-full max-w-[440px] relative z-10">
                
                <!-- Back Link Button -->
                <div class="mb-6">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold text-pink-700 bg-white hover:bg-pink-50 border border-pink-200/80 shadow-sm transition-all duration-200 group hover:shadow">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform duration-200 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span>Kembali ke Halaman Login</span>
                    </a>
                </div>

                <!-- Main Glass Card Container -->
                <div class="bg-white/85 backdrop-blur-xl border border-pink-100/90 p-8 sm:p-9 rounded-3xl shadow-[0_15px_45px_rgba(219,39,119,0.08)]">
                    
                    <!-- Header with Glowing Icon -->
                    <div class="mb-7">
                        <div class="relative inline-flex mb-5">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#db2777] to-[#be185d] text-white flex items-center justify-center shadow-lg shadow-pink-500/35 ring-4 ring-pink-100/80">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </div>
                            <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-pink-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-pink-500"></span>
                            </span>
                        </div>
                        <h2 class="text-[28px] font-extrabold text-gray-900 tracking-tight leading-snug">Lupa Kata Sandi?</h2>
                        <p class="text-gray-500 text-sm mt-1.5 leading-relaxed">
                            Jangan khawatir! Masukkan <strong class="text-gray-700">email</strong> atau <strong class="text-gray-700">username</strong> akun Anda untuk menerima 6 digit kode OTP verifikasi.
                        </p>
                    </div>

                    @if (isset($errors) && $errors->any())
                        <div class="bg-red-50/90 border-l-4 border-red-500 text-red-700 p-4 rounded-xl mb-6 text-sm font-medium flex items-start gap-2 shadow-sm">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="bg-green-50/90 border-l-4 border-green-500 text-green-700 p-4 rounded-xl mb-6 text-sm font-medium flex items-start gap-2 shadow-sm">
                            <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('password.email') }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-gray-800 mb-2">Email atau Username</label>
                            <div class="relative">
                                <input type="text" name="email_or_username" value="{{ old('email_or_username') }}" class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-gray-200 focus:border-[#db2777] focus:ring-4 focus:ring-pink-100/90 outline-none transition-all duration-200 text-gray-800 placeholder-gray-400 text-sm font-medium bg-white shadow-sm" placeholder="Masukkan email atau username terdaftar" required autofocus>
                                <div class="absolute left-4 top-3.5 text-gray-400 pointer-events-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Info Card Keamanan -->
                        <div class="flex items-start gap-3 p-3.5 rounded-xl bg-pink-50/80 border border-pink-100 text-xs text-pink-900">
                            <div class="w-6 h-6 rounded-lg bg-pink-200/70 flex items-center justify-center text-pink-700 flex-shrink-0 mt-0.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <span class="leading-relaxed font-medium">
                                Kode OTP 6 digit bersifat rahasia dan akan berlaku selama <strong class="text-pink-700">15 menit</strong>.
                            </span>
                        </div>

                        <div class="pt-1">
                            <button type="submit" class="w-full bg-gradient-to-r from-[#db2777] via-[#be185d] to-[#9d174d] hover:from-[#be185d] hover:to-[#831843] text-white font-bold py-3.5 rounded-xl shadow-lg shadow-pink-600/30 hover:shadow-pink-600/45 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 text-sm flex items-center justify-center gap-2 group cursor-pointer">
                                <span>Kirim Kode OTP</span>
                                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>

                        <div class="text-center pt-3 border-t border-gray-100">
                            <p class="text-xs text-gray-500">Sudah ingat kata sandi akun? 
                                <a href="{{ route('login') }}" class="font-bold text-[#db2777] hover:text-[#be185d] transition-colors underline underline-offset-4 decoration-pink-300 hover:decoration-[#db2777]">Login di sini</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
