<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>IPM | Verifikasi Email</title>
</head>
<body style="margin:0; padding:0; background-color:#f1f3f6; font-family: Arial, sans-serif;">
    <table width="100%" height="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" valign="top" style="padding:40px 0;">
                <!-- Card -->
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="padding:40px; text-align:center;">
                            <!-- Header -->
                            <h2 style=" text-center; margin:0 0 20px 0; color:#4B0082;">MANAGEMENT_IPM</h2>

                            <!-- Greeting -->
                            <p style="font-size:16px; color:#111827; margin:0 0 10px 0;">
                                Halo <b>{{ $user->email }}</b>,
                            </p>
                            <p style="font-size:16px; color:#111827; margin:0 0 30px 0;">
                                Klik tombol di bawah ini untuk memverifikasi email Anda:
                            </p>

                            <!-- Button -->
                            <a href="{{ $verifyUrl }}"
                               style="background-color:#4B0082; color:#F7C900FF; padding:12px 24px; text-decoration:none; border-radius:6px; font-weight:bold; display:inline-block; margin-bottom:30px;">
                                ✅ Verifikasi Email
                            </a>

                            <!-- Info -->
                            <p style="font-size:14px; color:#6b7280; margin:0 0 10px 0;">
                                Jika Anda tidak melakukan login, abaikan email ini.
                            </p>
                            <p style="font-size:14px; color:#6b7280; margin:0 0 20px 0;">
                                Link ini hanya berlaku selama <b>30 menit</b>.
                            </p>

                            <!-- Signature -->
                            <p style="font-size:14px; color:#111827; margin:0;">
                                Terima kasih,<br>
                                <b>management_ipm</b>
                            </p>
                        </td>
                    </tr>
                </table>
                <!-- End Card -->
            </td>
        </tr>
    </table>
</body>
</html>
