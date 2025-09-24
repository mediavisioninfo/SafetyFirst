<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; padding: 20px; color: #333;">
    <table style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 8px; padding: 20px; border: 1px solid #ddd;">
        <tr>
            <td style="text-align: center;">
                <h2 style="color: #0d6efd;">Safety First</h2>
                <p style="font-size: 16px;">Dear {{ $name }},</p>
                <p style="font-size: 15px;">
                    Your One-Time Password (OTP) for verification is:
                </p>
                <h1 style="background: #f1f3f5; padding: 15px; border-radius: 6px; display: inline-block; letter-spacing: 4px; color: #000;">
                    {{ $otp }}
                </h1>
                <p style="font-size: 14px; margin-top: 20px;">
                    This code is valid for <strong>10 minutes</strong>. Please do not share it with anyone.
                    Safety First will never ask you for your OTP via phone or email.
                </p>
                <p style="font-size: 14px; color: #6c757d; margin-top: 30px;">
                    If you did not request this code, please contact our support immediately.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
