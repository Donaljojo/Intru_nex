<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register — IntruNex</title>
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
        .form-container{
            background:#0a0a0a;
            border:1px solid rgba(0,255,136,.3);
            border-radius:.75rem;
            padding:2rem;
            width:100%;
            max-width:26rem;
            margin:auto;
        }
        h1{
            color:var(--nx-green);
            text-align:center;
            margin-bottom:1.5rem;
            font-weight:700;
            font-size:1.5rem;
            text-shadow:0 0 8px rgba(0,255,136,.6);
        }
        label{
            display:block;
            margin-bottom:.35rem;
            font-weight:600;
            color:var(--nx-green);
        }
        input{
            width:100%;
            padding:.6rem .9rem;
            border:1px solid rgba(0,255,136,.4);
            border-radius:.5rem;
            background:black;
            color:var(--nx-green-dim);
            outline:none;
        }
        input:focus{
            border-color:var(--nx-green);
            box-shadow:0 0 8px rgba(0,255,136,.4);
        }
        button{
            background:var(--nx-green);
            color:black;
            font-weight:700;
            padding:.6rem;
            border-radius:.5rem;
            width:100%;
            transition:.2s ease;
            margin-top:1rem;
        }
        button:hover{
            background:#31ffa7;
        }
        .link{
            display:block;
            margin-top:1rem;
            text-align:center;
            color:var(--nx-green);
            text-decoration:underline dotted;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">
    <div class="form-container">
        <h1>Register</h1>
        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf
            <div>
                <label>Name</label>
                <input type="text" name="name" placeholder="Your name" required>
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="email" placeholder="you@example.com" required>
            </div>
            <div>
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div>
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" required>
            </div>
            <button type="submit">Sign Up</button>
        </form>
        <a href="{{ route('login') }}" class="link">Already have an account? Login</a>
    </div>
</body>
</html>
