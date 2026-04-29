<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration - Blood Donation System</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="sign-up-container">
        <h2>User Registration</h2>

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

        <!-- Laravel Registration Form -->
        <form action="{{ route('userregister') }}" method="POST">
            @csrf
            
            <label for="fullname">Full Name</label>
            <input type="text" 
                   id="fullname" 
                   name="name" 
                   value="{{ old('name') }}"
                   required>

            <label for="age">Age</label>
            <input type="number" 
                   id="age" 
                   name="age" 
                   min="18" 
                   max="65"
                   value="{{ old('age') }}"
                   required>

            <label for="sex">Sex</label>
            <select id="sex" name="sex" required>
                <option value="">Select Gender</option>
                <option value="male" {{ old('sex') == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('sex') == 'female' ? 'selected' : '' }}>Female</option>
            </select>

            <label for="email">Email</label>
            <input type="email" 
                   id="email" 
                   name="email" 
                   value="{{ old('email') }}"
                   required>

            <label for="password">Password</label>
            <input type="password" 
                   id="password" 
                   name="password" 
                   required>

            <label for="password_confirmation">Confirm Password</label>
            <input type="password" 
                   id="password_confirmation" 
                   name="password_confirmation" 
                   required>

            <button type="submit">Register Donor</button>
        </form>

        <p style="text-align: center; margin-top: 20px;">
            Already registered? <a href="{{ route('userlogin') }}" style="color: #FEFDF1;">Login here</a>
        </p>
    </div>
</body>
</html>
