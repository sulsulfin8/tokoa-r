<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email_or_username' => 'required'
        ], [
            'email_or_username.required' => 'Email atau username wajib diisi.'
        ]);

        $identity = trim($request->email_or_username);

        // Cari user berdasarkan email atau username
        $user = User::where('email', $identity)
                    ->orWhere('username', $identity)
                    ->first();

        if (!$user) {
            return back()->withErrors([
                'email_or_username' => 'Akun dengan email atau username tersebut tidak ditemukan.'
            ])->withInput();
        }

        // Jika user belum memiliki email (misal akun default), tapi input berbentuk email
        if (empty($user->email)) {
            if (filter_var($identity, FILTER_VALIDATE_EMAIL)) {
                $user->email = $identity;
                $user->save();
            } else {
                return back()->withErrors([
                    'email_or_username' => 'Akun ini belum memiliki email terdaftar. Silakan hubungi admin atau login untuk menambahkan email.'
                ])->withInput();
            }
        }

        // Generate 6 digit angka OTP
        $otp = (string) random_int(100000, 999999);

        // Simpan ke database password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => $otp,
                'created_at' => Carbon::now()
            ]
        );

        // Simpan email ke session untuk tahap verifikasi
        session(['otp_email' => $user->email]);

        // Kirim email OTP
        $sentReal = false;
        try {
            Mail::send('emails.otp', ['otp' => $otp, 'user' => $user], function ($msg) use ($user) {
                $msg->to($user->email)
                    ->from('sulsulfin8@gmail.com', 'Toko A&R')
                    ->subject('Kode OTP Lupa Kata Sandi - Toko A&R');
            });
            $sentReal = true;
        } catch (\Throwable $e) {
            Log::warning('Pengiriman email OTP gagal: ' . $e->getMessage());
            $sentReal = false;
        }

        $message = "Kode OTP telah dikirimkan ke {$user->email}.";
        if (!$sentReal) {
            $message .= " (Mode Dev: Kode OTP Anda adalah {$otp})";
        }

        return redirect()->route('password.otp')->with('success', $message);
    }

    public function showOtpForm()
    {
        $email = session('otp_email');
        if (!$email) {
            return redirect()->route('password.request')->withErrors([
                'email_or_username' => 'Silakan masukkan email atau username Anda terlebih dahulu.'
            ]);
        }

        return view('auth.verify-otp', compact('email'));
    }

    public function verifyOtpAndReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric|digits:6',
            'password' => 'required|min:6|confirmed'
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.digits' => 'Kode OTP harus berupa 6 digit angka.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.'
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record || $record->token !== $request->otp) {
            return back()->withErrors([
                'otp' => 'Kode OTP salah atau tidak valid.'
            ])->withInput();
        }

        // Cek kedaluwarsa 15 menit
        if (Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            return back()->withErrors([
                'otp' => 'Kode OTP telah kedaluwarsa (berlaku 15 menit). Silakan minta kode baru.'
            ])->withInput();
        }

        // Update password user
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        // Hapus token yang sudah digunakan
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        session()->forget('otp_email');

        return redirect()->route('login')->with('success', 'Kata sandi Anda berhasil diperbarui! Silakan login dengan kata sandi baru.');
    }

    public function resendOtp()
    {
        $email = session('otp_email');
        if (!$email) {
            return redirect()->route('password.request');
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('password.request');
        }

        $otp = (string) random_int(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => $otp,
                'created_at' => Carbon::now()
            ]
        );

        $sentReal = false;
        try {
            Mail::send('emails.otp', ['otp' => $otp, 'user' => $user], function ($msg) use ($user) {
                $msg->to($user->email)
                    ->from('sulsulfin8@gmail.com', 'Toko A&R')
                    ->subject('Kode OTP Baru Lupa Kata Sandi - Toko A&R');
            });
            $sentReal = true;
        } catch (\Throwable $e) {
            Log::warning('Pengiriman email OTP gagal: ' . $e->getMessage());
            $sentReal = false;
        }

        $message = "Kode OTP baru telah dikirimkan ke {$user->email}.";
        if (!$sentReal) {
            $message .= " (Mode Dev: Kode OTP Anda adalah {$otp})";
        }

        return back()->with('success', $message);
    }
}
