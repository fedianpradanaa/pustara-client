<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $appName }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f3f5f9;font-family:Arial,Helvetica,sans-serif;color:#333333;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f5f9;padding:32px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background-color:#ffffff;border-radius:8px;overflow:hidden;">
                    <tr>
                        <td align="center" style="background-color:#435ebe;padding:24px 16px;">
                            <span style="font-size:22px;font-weight:bold;color:#ffffff;">{{ $appName }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px 32px 8px 32px;">
                            <h2 style="margin:0 0 16px 0;font-size:20px;color:#25396f;">{{ $greeting }}</h2>
                            @foreach ($lines as $line)
                                <p style="margin:0 0 14px 0;font-size:15px;line-height:1.6;">{{ $line }}</p>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:12px 32px 24px 32px;">
                            <a href="{{ $actionUrl }}" style="display:inline-block;background-color:#435ebe;color:#ffffff;text-decoration:none;font-weight:bold;font-size:15px;padding:12px 28px;border-radius:6px;">{{ $actionText }}</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:0 32px 24px 32px;">
                            @foreach ($footerLines as $line)
                                <p style="margin:0 0 10px 0;font-size:13px;line-height:1.5;color:#6b7280;">{{ $line }}</p>
                            @endforeach
                            <p style="margin:16px 0 0 0;font-size:12px;line-height:1.5;color:#9ca3af;word-break:break-all;">
                                Jika tombol tidak berfungsi, salin dan buka tautan berikut di browser Anda:<br>
                                <a href="{{ $actionUrl }}" style="color:#435ebe;">{{ $actionUrl }}</a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="background-color:#f9fafb;padding:16px;font-size:12px;color:#9ca3af;">
                            Email ini dikirim otomatis, mohon tidak membalas. &copy; {{ date('Y') }} {{ $appName }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
