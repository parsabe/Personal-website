<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password - Parsa Besharat</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #0b0f19;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #e2e8f0;
            -webkit-font-smoothing: antialiased;
        }
        .email-wrapper {
            width: 100%;
            background-color: #0b0f19;
            padding: 40px 15px;
            box-sizing: border-box;
        }
        .email-container {
            max-width: 580px;
            margin: 0 auto;
            background-color: #141c2e;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        }
        .header {
            padding: 32px 32px 24px 32px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            background: linear-gradient(180deg, rgba(255, 94, 54, 0.08) 0%, rgba(20, 28, 46, 0) 100%);
        }
        .avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            border: 2px solid #ff5e36;
            margin-bottom: 12px;
            object-fit: cover;
        }
        .brand-title {
            font-size: 20px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.5px;
            margin: 0;
        }
        .brand-subtitle {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .content {
            padding: 32px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 700;
            color: #ffffff;
            margin-top: 0;
            margin-bottom: 16px;
        }
        .text {
            font-size: 15px;
            line-height: 1.6;
            color: #cbd5e1;
            margin-bottom: 28px;
        }
        .btn-wrapper {
            text-align: center;
            margin: 36px 0;
        }
        .btn {
            display: inline-block;
            padding: 14px 36px;
            background: linear-gradient(135deg, #ff5e36 0%, #e11d48 100%);
            color: #ffffff !important;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(255, 94, 54, 0.35);
            letter-spacing: 0.5px;
        }
        .info-box {
            background-color: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 16px;
            margin-top: 24px;
            font-size: 13px;
            color: #94a3b8;
            line-height: 1.5;
        }
        .fallback-link {
            word-break: break-all;
            color: #ff5e36;
            text-decoration: underline;
            font-size: 12px;
        }
        .footer {
            padding: 24px 32px;
            text-align: center;
            background-color: #0f1624;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 12px;
            color: #64748b;
        }
        .footer a {
            color: #94a3b8;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            
            <!-- Header -->
            <div class="header">
                <img src="https://parsabe.com/images/profile.jpg" alt="Parsa Besharat" class="avatar">
                <h1 class="brand-title">Parsa Besharat</h1>
                <div class="brand-subtitle">Security & Account Services</div>
            </div>

            <!-- Content Body -->
            <div class="content">
                <h2 class="greeting">Hello {{ $userName }},</h2>
                
                <p class="text">
                    We received a request to reset the password associated with your account on <strong>Parsa Besharat Portal</strong>. Click the button below to choose a new password:
                </p>

                <!-- CTA Button -->
                <div class="btn-wrapper">
                    <a href="{{ $resetUrl }}" target="_blank" class="btn">
                        🔒 RESET PASSWORD NOW
                    </a>
                </div>

                <div class="info-box">
                    <strong>⏱ Note:</strong> This password reset link will expire in <strong>60 minutes</strong>.<br>
                    If you did not request a password reset, no further action is required and your account remains completely secure.
                </div>

                <!-- Fallback URL box -->
                <div style="margin-top: 28px; font-size: 12px; color: #64748b;">
                    If you're having trouble clicking the "RESET PASSWORD NOW" button, copy and paste the URL below into your web browser:
                    <br>
                    <a href="{{ $resetUrl }}" class="fallback-link">{{ $resetUrl }}</a>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                &copy; {{ date('Y') }} <a href="https://parsabe.com">Parsa Besharat</a>. All rights reserved.
                <br>
                TU Freiberg University &bull; Sachsen, Germany
            </div>

        </div>
    </div>
</body>
</html>
