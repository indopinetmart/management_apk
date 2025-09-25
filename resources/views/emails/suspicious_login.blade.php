<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>IPM | Percobaan Login Detected</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f4; font-family: Arial, sans-serif;">

    <table width="100%" height="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f4;">
        <tr>
            <td align="center" valign="top">
                <!-- Card container -->
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:10px; box-shadow:0 4px 8px rgba(0,0,0,0.1); margin-top:50px; padding:30px;">
                    <tr>
                        <td align="center" style="padding-bottom:20px;">
                            <h2 style="color:#333333; margin:0;">Percobaan Login Terdeteksi</h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center; color:#555555; line-height:1.6; padding-bottom:20px;">
                            <p>Halo <strong>{{ $user->name }}</strong>,</p>
                            <p>Kami mendeteksi percobaan login ke akun Anda.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:15px; background-color:#f9f9f9; border-radius:8px; text-align:left; margin-bottom:20px;">
                            <p style="margin:0 0 10px 0;"><strong>Detail percobaan login:</strong></p>
                            <ul style="padding-left:20px; margin:0; color:#555555;">
                                <li><b>IP Address:</b> {{ $frontendIp }}</li>
                                <li><b>Browser/Device:</b> {{ $userAgent }}</li>
                                <li><b>Platfrom:</b> {{ $platform }}</li>
                                <li><b>Waktu:</b> {{ now()->format('d M Y H:i:s') }}</li>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center; color:#555555; line-height:1.6; padding-bottom:20px;">
                            <p>Jika ini <strong>bukan Anda</strong>, untuk keamanan silakan klik tombol di bawah untuk logout semua sesi dan ganti password:</p>
                            <a href="{{ $resetUrl }}"
                               style="display:inline-block; background-color:#33005A; color:#ffdd00; font-weight:bold; padding:12px 25px; text-decoration:none; border-radius:8px; margin-top:10px;">
                               Reset & Logout Semua Perangkat
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center; color:#999999; font-size:12px; padding-top:20px;">
                            <p>Terima kasih,<br>Tim Keamanan</p>
                        </td>
                    </tr>
                </table>
                <!-- End Card -->
            </td>
        </tr>
    </table>

</body>
</html>
