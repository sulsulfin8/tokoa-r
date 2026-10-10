@extends('layouts.app')
@section('title', 'Edit Profil')
@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-pink-100">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight">Pengaturan Profil</h2>
            <p class="text-sm text-gray-500 mt-0.5">Kelola informasi data diri dan kata sandi akun Anda</p>
        </div>
        <span class="px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-pink-100 text-pink-700 border border-pink-200">
            Role: {{ ucfirst($user->role ?? 'User') }}
        </span>
    </div>

    @if(isset($errors) && $errors->any())
    <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-sm text-red-600 space-y-1">
        <p class="font-bold flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Terdapat kesalahan pada input:
        </p>
        <ul class="list-disc list-inside text-xs pl-2">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Info Profil Card -->
        <div class="md:col-span-1 bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center text-center">
            <div class="relative mb-4">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'Guest') }}&background=831843&color=ffffff&size=160" alt="Avatar" class="w-28 h-28 rounded-full shadow-lg border-4 border-pink-100 object-cover">
                <span class="absolute bottom-1 right-1 w-5 h-5 bg-green-500 border-2 border-white rounded-full"></span>
            </div>
            <h3 class="text-lg font-bold text-gray-800">{{ $user->name }}</h3>
            <p class="text-xs text-gray-500 font-medium">@<span>{{ $user->username }}</span></p>
            <div class="mt-4 pt-4 border-t border-gray-100 w-full text-left space-y-2 text-xs">
                <div class="flex justify-between text-gray-600">
                    <span>Role Akun:</span>
                    <span class="font-bold text-pink-600 capitalize">{{ $user->role }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Email:</span>
                    <span class="font-medium text-gray-800">{{ $user->email ?? '-' }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Terdaftar:</span>
                    <span class="font-medium text-gray-800">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Form Edit -->
        <div class="md:col-span-2 bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
            <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm text-gray-800 font-medium" required>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm text-gray-800 font-medium" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="contoh@email.com" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm text-gray-800 font-medium">
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <h4 class="text-sm font-bold text-gray-800 mb-1">Ubah Kata Sandi (Opsional)</h4>
                    <p class="text-xs text-gray-500 mb-4">Kosongkan jika Anda tidak ingin merubah kata sandi akun saat ini.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Password Baru</label>
                            <input type="password" name="password" placeholder="Minimal 6 karakter" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm text-gray-800">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-pink-500 focus:ring-2 focus:ring-pink-200 outline-none transition text-sm text-gray-800">
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3">
                    <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-bold px-6 py-2.5 rounded-xl shadow-sm hover:shadow-md transition text-sm flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
