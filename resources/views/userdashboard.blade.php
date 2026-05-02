<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Blood Donation System</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <img src="{{ asset('images/MORO.jpg') }}" alt="MORO Logo">
        <a href="#" onclick="showSection('account')" class="nav-link">1 Account</a>
        <a href="#" onclick="showSection('donation')" class="nav-link">2 Donation</a>
        <a href="#" onclick="showSection('request')" class="nav-link">3 Request</a>
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Account Section (Active by default) -->
        <section id="account" class="page-content active">
            <h1>Your Account</h1>
            <div style="background: #f4f194; padding: 20px; border-radius: 8px; margin: 20px 0;">
                <h3>Welcome, {{ auth()->user()->name }}!</h3>
                <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                <p><strong>Age:</strong> {{ auth()->user()->age }} years</p>
                <p><strong>Gender:</strong> {{ ucfirst(auth()->user()->sex) }}</p>
                <p><strong>Member since:</strong> {{ auth()->user()->created_at->format('M d, Y') }}</p>
            </div>
            <!-- Notifications inbox -->
<div style="margin-top: 24px;">
    <h3 style="margin-bottom: 12px;">📬 Notifications</h3>

    @if($notifications->isEmpty())
        <p style="color: #000000; background: #F4F194">No notifications yet.</p>
    @else
        @foreach($notifications as $notif)
            <div style="
    background: #f4f194;
    border-left: 4px solid #753B2F;
    padding: 14px 16px;
    border-radius: 6px;
    margin-bottom: 10px;
">
    <p style="margin: 0 0 6px;">{{ $notif->message }}</p>
    <small style="color: #888;">
        From: <strong>{{ $notif->sender->name }}</strong> &bull;
        {{ $notif->created_at->diffForHumans() }}
    </small>
</div>
        @endforeach
    @endif
</div>
        </section>

        <!-- Donation Form Section -->
        <section id="donation" class="page-content">
            <h1>Schedule Donation (Free Health Check-up)</h1>
            
            @if (session('donation-success'))
                <div style="background: #efe; color: #060; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                    {{ session('donation-success') }}
                </div>
            @endif

            <form action="{{ route('donation.store') }}" method="POST">
                @csrf
                <h3>Personal Information</h3>
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="fullname" value="{{ old('fullname', auth()->user()->name) }}" required>

                <label for="sex">Sex</label>
                <select id="sex" name="sex" required>
                    <option value="">Select</option>
                    <option value="male" {{ old('sex', auth()->user()->sex) == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('sex', auth()->user()->sex) == 'female' ? 'selected' : '' }}>Female</option>
                </select>

                <label for="age">Age</label>
                <input type="number" id="age" name="age" value="{{ old('age', auth()->user()->age) }}" min="18" max="65" required>

                <h3>Contact Information</h3>
                <label for="donation_phone">Phone No</label>
                <input type="tel" id="donation_phone" name="phone" value="{{ old('donation_phone') }}" required>

                <label for="donation_email">Email</label>
                <input type="email" id="donation_email" name="email" value="{{ old('donation_email', auth()->user()->email) }}" required>

                <label for="address">Address</label>
                <input type="text" id="address" name="address" value="{{ old('address') }}" required>

                <h3>Medical Information</h3>
                <label for="bloodtype">Blood Type</label>
                <select id="bloodtype" name="bloodtype" required>
                    <option value="">Select Blood Type</option>
                    <option value="A-" {{ old('bloodtype') == 'A-' ? 'selected' : '' }}>A-</option>
                    <option value="A+" {{ old('bloodtype') == 'A+' ? 'selected' : '' }}>A+</option>
                    <option value="B-" {{ old('bloodtype') == 'B-' ? 'selected' : '' }}>B-</option>
                    <option value="B+" {{ old('bloodtype') == 'B+' ? 'selected' : '' }}>B+</option>
                    <option value="AB-" {{ old('bloodtype') == 'AB-' ? 'selected' : '' }}>AB-</option>
                    <option value="AB+" {{ old('bloodtype') == 'AB+' ? 'selected' : '' }}>AB+</option>
                    <option value="O-" {{ old('bloodtype') == 'O-' ? 'selected' : '' }}>O-</option>
                    <option value="O+" {{ old('bloodtype') == 'O+' ? 'selected' : '' }}>O+</option>
                </select>

                <label for="weight">Weight (kg)</label>
                <input type="number" id="weight" name="weight" min="50" value="{{ old('weight') }}" required>

                <label for="dateoflastdonation">Date of Last Donation</label>
                <input type="date" id="dateoflastdonation" name="dateoflastdonation" value="{{ old('dateoflastdonation') }}">

                <p style="font-size: 14px; color: #000000;">Leave blank if first donation</p>

                <label style="display: flex; align-items: center; justify-content: center; width: fit-content; margin-left: auto; margin-right: auto;" class="checkbox-wrapper">
                    <input type="checkbox" class="checkbox-wrapper" id="disease" name="disease" value="yes" {{ old('disease') ? 'checked' : '' }}>
                    <span style="margin-left: 10px; font-weight: bold;">Do you have any disease or current illness?</span>
                </label>

                <button type="submit">Submit Donation Request</button>
            </form>
        </section>

        <!-- Blood Request Section -->
        <section id="request" class="page-content">
            <h1>Blood Request</h1>
            
            @if (session('request-success'))
                <div style="background: #efe; color: #060; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                    {{ session('request-success') }}
                </div>
            @endif

            <form action="{{ route('request.store') }}" method="POST">
                @csrf
                <h3>Requester Information</h3>
                <label for="requester_type">Requester Type</label>
                <select id="requester_type" name="requester_type" required>
                    <option value="">Select Type</option>
                    <option value="hospital" {{ old('requester_type') == 'hospital' ? 'selected' : '' }}>Hospital</option>
                    <option value="patient" {{ old('requester_type') == 'patient' ? 'selected' : '' }}>Single Patient</option>
                </select>

                <label for="patient_name">Patient Name</label>
                <input type="text" id="patient_name" name="patient_name" value="{{ old('patient_name') }}" required>

                <label for="patient_age">Patient Age</label>
                <input type="number" id="patient_age" name="patient_age" value="{{ old('patient_age') }}" required>

                <label for="patient_sex">Patient Sex</label>
                <select id="patient_sex" name="patient_sex" required>
                    <option value="">Select</option>
                    <option value="male" {{ old('patient_sex') == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('patient_sex') == 'female' ? 'selected' : '' }}>Female</option>
                </select>

                <h3>Contact Information</h3>
                <label for="request_phone">Phone No</label>
                <input type="tel" id="request_phone" name="phone" value="{{ old('request_phone') }}" required>

                <label for="request_email">Email</label>
                <input type="email" id="request_email" name="email" value="{{ old('request_email', auth()->user()->email) }}" required>

                <label for="address">Address</label>
                <input type="text" id="address" name="address" value="{{ old('address') }}" required>

                <h3>Blood Request Details</h3>
                <label for="required_blood_type">Required Blood Type</label>
                <select id="required_blood_type" name="required_blood_type" required>
                    <!-- Same blood type options as donation -->
                    <option value="">Select Blood Type</option>
                    <option value="A-" {{ old('required_blood_type') == 'A-' ? 'selected' : '' }}>A-</option>
                    <option value="A+" {{ old('required_blood_type') == 'A+' ? 'selected' : '' }}>A+</option>
                    <option value="B-" {{ old('required_blood_type') == 'B-' ? 'selected' : '' }}>B-</option>
                    <option value="B+" {{ old('required_blood_type') == 'B+' ? 'selected' : '' }}>B+</option>
                    <option value="AB-" {{ old('required_blood_type') == 'AB-' ? 'selected' : '' }}>AB-</option>
                    <option value="AB+" {{ old('required_blood_type') == 'AB+' ? 'selected' : '' }}>AB+</option>
                    <option value="O-" {{ old('required_blood_type') == 'O-' ? 'selected' : '' }}>O-</option>
                    <option value="O+" {{ old('required_blood_type') == 'O+' ? 'selected' : '' }}>O+</option>
                </select>

                <label for="units">Units Required</label>
                <input type="number" id="units" name="units" min="1" max="10" value="{{ old('units', 1) }}" required>

                <label for="urgency">Urgency</label>
                <select id="urgency" name="urgency" required>
                    <option value="">Select Urgency</option>
                    <option value="normal" {{ old('urgency') == 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="emergency" {{ old('urgency') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                </select>

                <label for="request_datetime">Preferred Date & Time</label>
                <input type="datetime-local" id="request_datetime" name="request_datetime" value="{{ old('request_datetime', now()->addDay()->format('Y-m-d\TH:i')) }}" required>

                <button type="submit">Submit Blood Request</button>
            </form>
        </section>
    </div>

   <script>
// ===== DASHBOARD TAB SWITCHER =====
(function() {
    'use strict';

    // Get all sections and nav links
    const sections = document.querySelectorAll('.page-content');
    const navLinks = document.querySelectorAll('.sidebar a[href="#"]');
    
    // Show specific section and update active states
    function showSection(sectionId) {
        // Hide all sections
        sections.forEach(section => {
            section.classList.remove('active');
        });
        
        // Show target section
        const targetSection = document.getElementById(sectionId);
        if (targetSection) {
            targetSection.classList.add('active');
            targetSection.scrollIntoView({ behavior: 'smooth' });
        }
        
        // Update active nav link (optional visual feedback)
        navLinks.forEach(link => {
            link.classList.remove('active');
        });
        event?.target?.classList.add('active');
    }

    // Add click listeners to nav links
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const sectionId = this.getAttribute('onclick').match(/'([^']+)'/)[1];
            showSection(sectionId);
        });
    });

    // Keyboard navigation (accessibility)
    document.addEventListener('keydown', function(e) {
        if (e.key === '1') showSection('account');
        if (e.key === '2') showSection('donation');
        if (e.key === '3') showSection('request');
    });

    // Auto-show Account tab on load
    window.addEventListener('load', () => {
        showSection('account');
    });

    // Hash-based tab switching (e.g., /dashboard#donation)
    window.addEventListener('hashchange', function() {
        const hash = window.location.hash.replace('#', '');
        if (hash && document.getElementById(hash)) {
            showSection(hash);
        }
    });

})();
</script>

</body>
</html>
