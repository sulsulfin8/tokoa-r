<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Lupa Kata Sandi - Toko A&R</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #fdf2f8; margin: 0; padding: 30px 15px; color: #374151;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 560px; background-color: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(131, 24, 67, 0.08); border: 1px solid #fbcfe8;">
        <!-- Header -->
        <tr>
            <td align="center" style="background: linear-gradient(135deg, #831843 0%, #be185d 100%); padding: 35px 20px; color: #ffffff;">
                <h1 style="margin: 0; font-size: 26px; font-weight: 800; letter-spacing: 2px;">TOKO A&R</h1>
                <p style="margin: 5px 0 0; font-size: 14px; color: #fce7f3; letter-spacing: 1px;">Cake & Bakery Berkualitas</p>
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td style="padding: 40px 35px;">
                <h2 style="margin: 0 0 15px; color: #111827; font-size: 20px; font-weight: 700;">Permintaan Reset Kata Sandi</h2>
                <p style="margin: 0 0 20px; font-size: 15px; line-height: 1.6; color: #4b5563;">
                    Halo <strong>{{ $user->name ?? 'Pengguna' }}</strong>,
                </p>
                <p style="margin: 0 0 25px; font-size: 15px; line-height: 1.6; color: #4b5563;">
                    Kami menerima permintaan untuk mereset kata sandi akun Anda. Gunakan kode OTP 6-digit di bawah ini untuk memverifikasi akun Anda:
                </p>

                <!-- OTP Box -->
                <div style="background-color: #fff1f2; border: 2px dashed #f43f5e; border-radius: 14px; padding: 25px; text-align: center; margin: 25px 0;">
                    <span style="font-size: 12px; font-weight: 700; color: #9f1239; text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 8px;">KODE OTP ANDA</span>
                    <span style="font-size: 38px; font-weight: 800; letter-spacing: 12px; color: #be185d; font-family: monospace; display: inline-block; padding-left: 12px;">{{ $otp }}</span>
                    <span style="display: block; font-size: 12px; color: #881337; margin-top: 10px;">Berlaku selama 15 menit</span>
                </div>

                <p style="margin: 25px 0 0; font-size: 13px; line-height: 1.6; color: #6b7280;">
                    <strong>Penting:</strong> Jangan berikan kode OTP ini kepada siapa pun termasuk pihak yang mengatasnamakan Toko A&R. Jika Anda tidak pernah meminta reset kata sandi, abaikan email ini.
                </p>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #fdf2f8; padding: 20px 30px; border-top: 1px solid #fce7f3; text-align: center; font-size: 12px; color: #9ca3af;">
                <p style="margin: 0 0 5px;">Email ini dikirim secara otomatis oleh server: <strong>sulsulfin8@gmail.com</strong></p>
                <p style="margin: 0;">&copy; {{ date('Y') }} Toko A&R Bakery. Seluruh hak cipta dilindungi.</p>
            </td>
        </tr>
    </table>
</body>
</html>
