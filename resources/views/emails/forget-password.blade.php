<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #009970;">Reset Password RSUD Bangil</h2>
        
        <p>Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.</p>
        
        <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p style="font-size: 24px; text-align: center; margin: 0;">
                Kode Verifikasi Anda: <strong>{{ $code }}</strong>
            </p>
        </div>

        <p>Kode verifikasi ini akan kadaluarsa dalam 60 menit.</p>
        
        <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
        
        <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">
        
        <p style="color: #666; font-size: 12px;">
            Email ini dikirim otomatis oleh sistem RSUD Bangil. Mohon tidak membalas email ini.
        </p>
    </div>
</body>
</html>