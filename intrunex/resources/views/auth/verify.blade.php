<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Email — IntruNex</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @vite(['resources/css/app.css','resources/js/app.js'])

    <style>
        :root{
            --nx-green:#00ff88;
            --nx-green-dim:#86ffc8;
            --nx-bg:#000;
        }
        body{
            font-family:'JetBrains Mono', monospace;
            background:var(--nx-bg);
            color:var(--nx-green-dim);
        }
        .verify-container{
            background:#0a0a0a;
            border:1px solid rgba(0,255,136,.3);
            border-radius:.75rem;
            padding:2rem;
            width:100%;
            max-width:28rem;
            margin:auto;
            text-align:center;
        }
        h1{
            color:var(--nx-green);
            margin-bottom:1.5rem;
            font-weight:700;
            font-size:1.5rem;
            text-shadow:0 0 8px rgba(0,255,136,.6);
        }
        p{
            margin-bottom:1rem;
            line-height:1.5;
        }
        .alert{
            background:rgba(0,255,136,.1);
            border:1px solid rgba(0,255,136,.3);
            color:var(--nx-green);
            padding:.75rem 1rem;
            border-radius:.5rem;
            margin-bottom:1rem;
            font-size:.9rem;
        }
        button{
            background:var(--nx-green);
            color:black;
            font-weight:700;
            padding:.6rem 1.2rem;
            border-radius:.5rem;
            transition:.2s ease;
        }
        button:hover{
            background:#31ffa7;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">

    <div class="verify-container">
        <h1>Verify Your Email</h1>

        @if (session('resent'))
            <div class="alert">
                A fresh verification link has been sent to your email address.
            </div>
        @endif

        <p>Before proceeding, please check your email for a verification link.</p>
        <p>If you did not receive the email, you can request another below.</p>

        <form method="POST" action="{{ route('verification.resend') }}">
            @csrf
            <button type="submit">Send Verification Email Again</button>
        </form>
    </div>

</body>
</html>
