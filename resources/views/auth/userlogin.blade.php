<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Login - Blood Donation System</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --crimson:     #9B1C1C;
            --crimson-deep:#6B0F0F;
            --gold:        #C9A84C;
            --cream:       #FEFDF1;
            --cream-dim:   #F5F0E0;
            --ink:         #1A0A0A;
            --muted:       #7A6060;
            --error-bg:    #FEE2E2;
            --error-txt:   #991B1B;
            --ok-bg:       #D1FAE5;
            --ok-txt:      #065F46;
            --radius:      12px;
            --shadow:      0 20px 60px rgba(155,28,28,.18), 0 4px 16px rgba(0,0,0,.10);
        }

        html, body {
            min-height: 100%;
            font-family: 'DM Sans', sans-serif;
            background: var(--crimson-deep);
            background-image:
                radial-gradient(ellipse 80% 60% at 20% 10%, rgba(201,168,76,.15) 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at 80% 90%, rgba(155,28,28,.35) 0%, transparent 60%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: clamp(16px, 4vw, 40px);
        }

        /* ── Card ── */
        .login-container {
            width: 100%;
            max-width: 440px;
            background: var(--cream);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: clamp(28px, 6vw, 52px) clamp(24px, 6vw, 48px);
            position: relative;
            overflow: hidden;
        }

        /* Decorative top bar */
        .login-container::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--crimson), var(--gold), var(--crimson));
        }

        /* ── Logo ── */
        .login-container > img {
            display: block;
            width: clamp(72px, 22vw, 96px);
            height: clamp(72px, 22vw, 96px);
            object-fit: contain;
            border-radius: 50%;
            margin: 0 auto clamp(12px, 3vw, 20px);
            border: 3px solid var(--cream-dim);
            box-shadow: 0 4px 16px rgba(155,28,28,.2);
        }

        /* ── Heading ── */
        h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(1.6rem, 5vw, 2.2rem);
            font-weight: 700;
            color: var(--crimson-deep);
            text-align: center;
            letter-spacing: .02em;
            margin-bottom: clamp(20px, 4vw, 32px);
            line-height: 1.2;
        }

        /* ── Alerts ── */
        .alert {
            border-radius: 8px;
            padding: 12px 14px;
            margin-bottom: 20px;
            font-size: .875rem;
            line-height: 1.5;
        }
        .alert-error { background: var(--error-bg); color: var(--error-txt); border-left: 4px solid var(--error-txt); }
        .alert-error ul { padding-left: 16px; }
        .alert-success { background: var(--ok-bg); color: var(--ok-txt); border-left: 4px solid var(--ok-txt); }

        /* ── Form ── */
        form { display: flex; flex-direction: column; gap: 4px; }

        label {
            display: block;
            font-size: .78rem;
            font-weight: 500;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--muted);
            margin-top: 14px;
            margin-bottom: 5px;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid var(--cream-dim);
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 1rem;
            color: var(--ink);
            background: #fff;
            transition: border-color .2s, box-shadow .2s;
            -webkit-appearance: none;
            appearance: none;
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--crimson);
            box-shadow: 0 0 0 3px rgba(155,28,28,.12);
        }

        /* ── Submit button ── */
        button[type="submit"] {
            margin-top: 24px;
            padding: 13px;
            background: linear-gradient(135deg, var(--crimson), var(--crimson-deep));
            color: var(--cream);
            font-family: 'DM Sans', sans-serif;
            font-size: 1rem;
            font-weight: 500;
            letter-spacing: .04em;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: transform .15s, box-shadow .15s, opacity .15s;
            box-shadow: 0 4px 14px rgba(155,28,28,.35);
            width: 100%;
            min-height: 48px; /* touch-friendly */
        }

        button[type="submit"]:hover  { opacity: .9; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(155,28,28,.4); }
        button[type="submit"]:active { transform: translateY(0); opacity: 1; }

        /* ── Footer link ── */
        .footer-link {
            text-align: center;
            margin-top: 22px;
            font-size: .9rem;
            color: var(--muted);
        }

        .footer-link a {
            color: var(--crimson);
            font-weight: 500;
            text-decoration: none;
            border-bottom: 1px solid rgba(155,28,28,.3);
            transition: border-color .2s;
        }

        .footer-link a:hover { border-color: var(--crimson); }

        /* ── Mobile tweaks ── */
        @media (max-width: 480px) {
            body { padding: 12px; align-items: flex-start; padding-top: 24px; }
            .login-container { border-radius: 10px; }
        }

        @media (max-width: 360px) {
            .login-container { padding: 24px 18px; }
        }
    </style>
</head>
<body>
    <div class="login-container">

        <img src="{{ asset('images/MORO.jpg') }}" alt="MORO Blood Bank Logo" onerror="this.style.display='none'">

        <h2>User Login</h2>

        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('userlogin') }}" method="POST">
            @csrf

            <label for="email">Email</label>
            <input type="email"
                   id="email"
                   name="email"
                   value="{{ old('email') }}"
                   placeholder="you@example.com"
                   required
                   autofocus>

            <label for="password">Password</label>
            <input type="password"
                   id="password"
                   name="password"
                   placeholder="••••••••"
                   required>

            <button type="submit">Login</button>
        </form>

        <p class="footer-link" style="color: var(--muted)">
            New donor? <a href="{{ route('userregister') }}" style="color: var(--cream);">Register here</a>
        </p>

    </div>
</body>
</html>