<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $newsletter->newsletter_title }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
            font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
        }
        .wrapper {
            max-width: 620px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,.08);
        }
        .header {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            padding: 40px 40px 32px;
            text-align: center;
        }
        .header .logo {
            width: 50px; height: 50px;
            background: rgba(255,255,255,.2);
            border-radius: 12px;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        .header h1 {
            color: #ffffff;
            font-size: 22px;
            font-weight: 700;
            margin: 0 0 8px;
            line-height: 1.3;
        }
        .header p {
            color: rgba(255,255,255,.75);
            font-size: 14px;
            margin: 0;
        }
        .greeting {
            padding: 28px 40px 0;
        }
        .greeting h2 {
            font-size: 18px;
            font-weight: 600;
            color: #0f172a;
            margin: 0 0 4px;
        }
        .greeting p {
            color: #64748b;
            font-size: 14px;
            margin: 0;
        }
        .divider {
            height: 1px;
            background: #f1f5f9;
            margin: 24px 40px;
        }
        .content {
            padding: 0 40px;
            font-size: 15px;
            line-height: 1.8;
            color: #334155;
            white-space: pre-wrap;
        }
        .footer {
            padding: 32px 40px;
            text-align: center;
            border-top: 1px solid #f1f5f9;
            margin-top: 32px;
        }
        .footer p {
            font-size: 12px;
            color: #94a3b8;
            margin: 0 0 4px;
        }
        .footer .unsubscribe {
            font-size: 11px;
            color: #cbd5e1;
        }
        .badge {
            display: inline-block;
            background: #ede9fe;
            color: #6366f1;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            margin-top: 6px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Header -->
        <div class="header">
            <div class="logo">✉️</div>
            <h1>{{ $newsletter->newsletter_title }}</h1>
            <p>Newsletter from {{ config('app.name') }}</p>
            <span class="badge">Newsletter</span>
        </div>

        <!-- Greeting -->
        <div class="greeting">
            <h2>Hello, {{ $user->name }}! 👋</h2>
            <p>You have a new newsletter from us. Here's what's inside:</p>
        </div>

        <div class="divider"></div>

        <!-- Content -->
        <div class="content">
            {!! nl2br(e($newsletter->newsletter_content)) !!}
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>You received this email because you are a registered subscriber.</p>
            <p>Sent to: <strong>{{ $user->email }}</strong></p>
            <p class="unsubscribe">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
