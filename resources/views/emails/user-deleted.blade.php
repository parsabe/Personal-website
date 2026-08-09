<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Deletion Notice - Parsa Besharat</title>
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
            background: linear-gradient(180deg, rgba(244, 63, 94, 0.14) 0%, rgba(20, 28, 46, 0) 100%);
        }
        .avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            border: 2px solid #f43f5e;
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
            font-size: 11px;
            color: #f43f5e;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 700;
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
        .notice-banner {
            background-color: rgba(244, 63, 94, 0.12);
            border: 1px solid rgba(244, 63, 94, 0.3);
            border-radius: 14px;
            padding: 14px 18px;
            margin-bottom: 24px;
            color: #fda4af;
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .text {
            font-size: 14px;
            line-height: 1.6;
            color: #cbd5e1;
            margin-bottom: 24px;
        }
        .audit-card {
            background-color: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 24px;
        }
        .audit-item {
            margin-bottom: 12px;
            font-size: 13px;
            line-height: 1.5;
        }
        .audit-item:last-child {
            margin-bottom: 0;
        }
        .audit-label {
            color: #94a3b8;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            display: block;
            margin-bottom: 2px;
        }
        .audit-val {
            color: #ffffff;
            font-weight: 600;
        }
        .audit-val-rose {
            color: #fda4af;
            font-weight: 700;
            font-family: monospace;
        }
        .contact-box {
            background-color: rgba(99, 102, 241, 0.08);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 14px;
            padding: 16px;
            font-size: 13px;
            color: #c7d2fe;
            line-height: 1.5;
            margin-bottom: 28px;
        }
        .contact-box a {
            color: #818cf8;
            text-decoration: underline;
            font-weight: 700;
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
            color: #cbd5e1;
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
                <div class="brand-subtitle">Account Administration & Security</div>
            </div>

            <!-- Content Body -->
            <div class="content">
                <h2 class="greeting">Hello {{ $userName }},</h2>
                
                <div class="notice-banner">
                    ⚠️ ACCOUNT DELETION NOTICE
                </div>

                <p class="text">
                    This email is an official notification to clarify that your user account registered under <strong>{{ $userEmail }}</strong> has been deleted from the <strong>Parsa Besharat Portal</strong> by system administrator <strong>Parsa Besharat</strong> (parsabe99@gmail.com).
                </p>

                <!-- Audit Details Box -->
                <div class="audit-card">
                    <div class="audit-item">
                        <span class="audit-label">Target Account Name</span>
                        <span class="audit-val">{{ $userName }}</span>
                    </div>
                    <div class="audit-item">
                        <span class="audit-label">Registered Email Address</span>
                        <span class="audit-val">{{ $userEmail }}</span>
                    </div>
                    <div class="audit-item">
                        <span class="audit-label">Deletion Clarification / Reason</span>
                        <span class="audit-val-rose">{{ $reason }}</span>
                    </div>
                    <div class="audit-item">
                        <span class="audit-label">Timestamp of Deletion</span>
                        <span class="audit-val" style="color: #94a3b8; font-size: 12px;">{{ $deletedAt }}</span>
                    </div>
                </div>

                <!-- Contact & Support Info -->
                <div class="contact-box">
                    <strong>💬 Need Clarification or Assistance?</strong><br>
                    If you have questions regarding this administrative action or wish to request further details, you can reach out directly to Parsa Besharat via email at <a href="mailto:parsabe99@gmail.com">parsabe99@gmail.com</a>.
                </div>

                <p class="text" style="margin-bottom: 0;">
                    Best regards,<br>
                    <strong style="color: #ffffff;">Parsa Besharat</strong><br>
                    <span style="font-size: 12px; color: #94a3b8;">Platform Owner & Lead Engineer</span>
                </p>
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
