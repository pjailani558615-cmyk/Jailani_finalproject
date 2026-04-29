<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Blood Donation System</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <!-- Top Navigation for Staff/Admin -->
        <nav class="top-nav">
            <a href="/staff-register" class="nav-btn">Staff</a>
            <a href="/admin-register" class="nav-btn">Admin</a>
        </nav>

        <!-- MORO Logo -->
        <img src="{{ asset('images/MORO.jpg') }}" alt="MORO Logo" onerror="this.style.display='none'">

        <h2>Admin Login</h2>

        <!-- Laravel Error Display -->
        @if ($errors->any())
            <div style="background: #fee; color: #c33; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Laravel Success Message -->
        @if (session('success'))
            <div style="background: #efe; color: #060; padding: 10px; border-radius: 4px; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Laravel Login Form -->
        <form action="{{ route('admin.login') }}" method="POST">
            @csrf
            
            <label for="email">Email</label>
            <input type="email" 
                   id="email" 
                   name="email" 
                   value="{{ old('email') }}"
                   required 
                   autofocus>

            <label for="password">Password</label>
            <input type="password" 
                   id="password" 
                   name="password"
                   required>


            <button type="submit">Login</button>
        </form>

        <!-- Registration Link -->
        <p style="text-align: center; margin-top: 20px;">
            New Administrator? <a href="{{ route('admin.register') }}" style="color: #FEFDF1;">Register here</a>
        </p>

    </div>
</body>
</html>
