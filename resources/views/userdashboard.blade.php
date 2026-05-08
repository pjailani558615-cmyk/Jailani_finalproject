<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Blood Donation System</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* ===== CSS VARIABLES ===== */
        :root {
            --red:       #753B2F;
            --red-dark:  #5a2d23;
            --yellow:    #f4f194;
            --yellow-dk: #e8e46a;
            --bg:        #faf9f4;
            --text:      #1a1a1a;
            --muted:     #6b6b6b;
            --border:    #e0ddd0;
            --sidebar-w: 230px;
            --radius:    10px;
            --shadow:    0 2px 12px rgba(0,0,0,.08);
            --font:      'DM Sans', sans-serif;
        }

        /* ===== RESET ===== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: var(--font);
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        /* ===== MOBILE TOP BAR ===== */
        .topbar {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 56px;
            background: var(--red);
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            z-index: 200;
            box-shadow: 0 2px 8px rgba(0,0,0,.2);
        }
        .topbar .logo-small {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .topbar .logo-small img {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,.4);
        }
        .topbar .logo-small span {
            color: #fff;
            font-weight: 600;
            font-size: 15px;
            letter-spacing: .3px;
        }
        .hamburger {
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .hamburger span {
            display: block;
            width: 22px;
            height: 2px;
            background: #fff;
            border-radius: 2px;
            transition: all .25s;
        }
        .hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .hamburger.open span:nth-child(2) { opacity: 0; }
        .hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--red);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 32px 0 24px;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            transition: transform .3s cubic-bezier(.4,0,.2,1);
        }
        .sidebar img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255,255,255,.3);
            margin-bottom: 28px;
        }
        .sidebar-nav {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding: 0 12px;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 16px;
            border-radius: var(--radius);
            color: rgba(255,255,255,.75);
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: background .2s, color .2s;
        }
        .nav-link:hover,
        .nav-link.active {
            background: rgba(255,255,255,.15);
            color: #fff;
        }
        .nav-link .nav-num {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: rgba(255,255,255,.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            flex-shrink: 0;
        }
        .sidebar-logout {
            margin-top: auto;
            padding: 0 12px;
            width: 100%;
        }
        .sidebar-logout a {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: var(--radius);
            color: rgba(255,255,255,.65);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: background .2s, color .2s;
        }
        .sidebar-logout a:hover {
            background: rgba(255,255,255,.12);
            color: #fff;
        }

        /* Overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.45);
            z-index: 99;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-w);
            flex: 1;
            padding: 40px 40px 40px 20px;
            max-width: 820px;
        }

        /* ===== SECTIONS ===== */
        .page-content { display: none; }
        .page-content.active { display: block; }

        /* ===== SECTION TITLE ===== */
        .page-content h1 {
            font-size: clamp(20px, 4vw, 26px);
            font-weight: 600;
            color: var(--red-dark);
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--border);
        }
        .page-content h3 {
            font-size: 14px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .8px;
            margin: 28px 0 14px;
        }

        /* ===== ACCOUNT CARD ===== */
        .account-card {
            background: var(--yellow);
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: var(--shadow);
        }
        .account-card h3 {
            font-size: 18px;
            color: var(--red-dark);
            text-transform: none;
            letter-spacing: 0;
            margin: 0 0 16px;
        }
        .account-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px 24px;
        }
        .account-field {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .account-field span:first-child {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: var(--muted);
            font-weight: 600;
        }
        .account-field span:last-child {
            font-size: 15px;
            font-weight: 500;
        }

        /* ===== NOTIFICATIONS ===== */
        .notif-list { margin-top: 28px; }
        .notif-list > h3 {
            font-size: 16px;
            color: var(--text);
            text-transform: none;
            letter-spacing: 0;
            margin-bottom: 14px;
        }
        .notif-item {
            background: var(--yellow);
            border-left: 4px solid var(--red);
            padding: 14px 16px;
            border-radius: 0 var(--radius) var(--radius) 0;
            margin-bottom: 10px;
            box-shadow: var(--shadow);
        }
        .notif-item p { margin: 0 0 6px; font-size: 14px; line-height: 1.5; }
        .notif-item small { color: var(--muted); font-size: 12px; }
        .notif-empty {
            background: var(--yellow);
            padding: 16px;
            border-radius: var(--radius);
            color: var(--muted);
            font-size: 14px;
        }

        /* ===== FORMS ===== */
        form label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 5px;
        }
        form input,
        form select,
        form textarea {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-family: var(--font);
            font-size: 14px;
            background: #fff;
            color: var(--text);
            margin-bottom: 16px;
            transition: border-color .2s, box-shadow .2s;
            -webkit-appearance: none;
            appearance: none;
        }
        form input:focus,
        form select:focus {
            outline: none;
            border-color: var(--red);
            box-shadow: 0 0 0 3px rgba(117,59,47,.12);
        }

        /* Two-column form grid */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 20px;
        }
        .form-grid .full { grid-column: 1 / -1; }

        /* Checkbox row */
        .checkbox-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .checkbox-row input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin: 0;
            cursor: pointer;
            accent-color: var(--red);
            flex-shrink: 0;
        }
        .checkbox-row label {
            margin: 0;
            font-size: 14px;
            font-weight: 500;
            color: var(--text);
            cursor: pointer;
        }
        .field-hint {
            font-size: 12px;
            color: var(--muted);
            margin-top: -12px;
            margin-bottom: 16px;
        }

        /* Submit button */
        form button[type="submit"] {
            width: 100%;
            padding: 14px;
            background: var(--red);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: var(--font);
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s, transform .1s;
            margin-top: 8px;
            letter-spacing: .3px;
        }
        form button[type="submit"]:hover { background: var(--red-dark); }
        form button[type="submit"]:active { transform: scale(.99); }

        /* Success alert */
        .alert-success {
            background: #edfaed;
            color: #1a6b1a;
            border: 1px solid #b6e6b6;
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .topbar { display: flex; }
            body { padding-top: 56px; }

            .sidebar {
                transform: translateX(-100%);
                top: 56px;
                padding-top: 20px;
            }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.open { display: block; }

            .main-content {
                margin-left: 0;
                padding: 24px 16px 40px;
            }

            .account-grid { grid-template-columns: 1fr; gap: 10px; }

            .form-grid { grid-template-columns: 1fr; }
            .form-grid .full { grid-column: auto; }
        }

        @media (max-width: 480px) {
            .main-content { padding: 20px 12px 40px; }
            .account-card { padding: 18px; }
        }
    </style>
</head>
<body>

    <!-- ===== MOBILE TOP BAR ===== -->
    <div class="topbar">
        <div class="logo-small">
            <img src="{{ asset('images/MORO.jpg') }}" alt="MORO Logo">
            <span>Blood Donation</span>
        </div>
        <button class="hamburger" id="hamburgerBtn" aria-label="Toggle menu">
            <span></span><span></span><span></span>
        </button>
    </div>

    <!-- Sidebar overlay (mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- ===== SIDEBAR ===== -->
    <div class="sidebar" id="sidebar">
        <img src="{{ asset('images/MORO.jpg') }}" alt="MORO Logo">

        <nav class="sidebar-nav">
            <a href="#" class="nav-link active" data-section="account">
                <span class="nav-num">1</span> Account
            </a>
            <a href="#" class="nav-link" data-section="donation">
                <span class="nav-num">2</span> Donation
            </a>
            <a href="#" class="nav-link" data-section="request">
                <span class="nav-num">3</span> Request
            </a>
        </nav>

        <div class="sidebar-logout">
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                &#x2192; Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                @csrf
            </form>
        </div>
    </div>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="main-content">

        <!-- Account Section -->
        <section id="account" class="page-content active">
            <h1>Your Account</h1>

            <div class="account-card">
                <h3>Welcome, {{ auth()->user()->name }}!</h3>
                <div class="account-grid">
                    <div class="account-field">
                        <span>Email</span>
                        <span>{{ auth()->user()->email }}</span>
                    </div>
                    <div class="account-field">
                        <span>Age</span>
                        <span>{{ auth()->user()->age }} years</span>
                    </div>
                    <div class="account-field">
                        <span>Gender</span>
                        <span>{{ ucfirst(auth()->user()->sex) }}</span>
                    </div>
                    <div class="account-field">
                        <span>Member Since</span>
                        <span>{{ auth()->user()->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Notifications -->
            <div class="notif-list">
                <h3>📬 Notifications</h3>
                @if($notifications->isEmpty())
                    <div class="notif-empty">No notifications yet.</div>
                @else
                    @foreach($notifications as $notif)
                        <div class="notif-item">
                            <p>{{ $notif->message }}</p>
                            <small>
                                From: <strong>{{ $notif->sender->name }}</strong> &bull;
                                {{ $notif->created_at->diffForHumans() }}
                            </small>
                        </div>
                    @endforeach
                @endif
            </div>
        </section>

        <!-- Donation Section -->
        <section id="donation" class="page-content">
            <h1>Schedule Donation (Free Health Check-up)</h1>

            @if (session('donation-success'))
                <div class="alert-success">{{ session('donation-success') }}</div>
            @endif

            <form action="{{ route('donation.store') }}" method="POST">
                @csrf

                <h3>Personal Information</h3>
                <div class="form-grid">
                    <div class="full">
                        <label for="fullname">Full Name</label>
                        <input type="text" id="fullname" name="fullname"
                               value="{{ old('fullname', auth()->user()->name) }}" required>
                    </div>
                    <div>
                        <label for="sex">Sex</label>
                        <select id="sex" name="sex" required>
                            <option value="">Select</option>
                            <option value="male"   {{ old('sex', auth()->user()->sex) == 'male'   ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('sex', auth()->user()->sex) == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    <div>
                        <label for="age">Age</label>
                        <input type="number" id="age" name="age"
                               value="{{ old('age', auth()->user()->age) }}" min="18" max="65" required>
                    </div>
                </div>

                <h3>Contact Information</h3>
                <div class="form-grid">
                    <div>
                        <label for="donation_phone">Phone No</label>
                        <input type="tel" id="donation_phone" name="phone"
                               value="{{ old('donation_phone') }}" required>
                    </div>
                    <div>
                        <label for="donation_email">Email</label>
                        <input type="email" id="donation_email" name="email"
                               value="{{ old('donation_email', auth()->user()->email) }}" required>
                    </div>
                    <div class="full">
                        <label for="d_address">Address</label>
                        <input type="text" id="d_address" name="address"
                               value="{{ old('address') }}" required>
                    </div>
                </div>

                <h3>Medical Information</h3>
                <div class="form-grid">
                    <div>
                        <label for="bloodtype">Blood Type</label>
                        <select id="bloodtype" name="bloodtype" required>
                            <option value="">Select Blood Type</option>
                            @foreach(['A-','A+','B-','B+','AB-','AB+','O-','O+'] as $bt)
                                <option value="{{ $bt }}" {{ old('bloodtype') == $bt ? 'selected' : '' }}>{{ $bt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="weight">Weight (kg)</label>
                        <input type="number" id="weight" name="weight"
                               min="50" value="{{ old('weight') }}" required>
                    </div>
                    <div class="full">
                        <label for="dateoflastdonation">Date of Last Donation</label>
                        <input type="date" id="dateoflastdonation" name="dateoflastdonation"
                               value="{{ old('dateoflastdonation') }}">
                        <p class="field-hint">Leave blank if this is your first donation.</p>
                    </div>
                </div>

                <div class="checkbox-row">
                    <input type="checkbox" id="disease" name="disease"
                           value="yes" {{ old('disease') ? 'checked' : '' }}>
                    <label for="disease">Do you have any disease or current illness?</label>
                </div>

                <button type="submit">Submit Donation Request</button>
            </form>
        </section>

        <!-- Blood Request Section -->
        <section id="request" class="page-content">
            <h1>Blood Request</h1>

            @if (session('request-success'))
                <div class="alert-success">{{ session('request-success') }}</div>
            @endif

            <form action="{{ route('request.store') }}" method="POST">
                @csrf

                <h3>Requester Information</h3>
                <div class="form-grid">
                    <div class="full">
                        <label for="requester_type">Requester Type</label>
                        <select id="requester_type" name="requester_type" required>
                            <option value="">Select Type</option>
                            <option value="hospital" {{ old('requester_type') == 'hospital' ? 'selected' : '' }}>Hospital</option>
                            <option value="patient"  {{ old('requester_type') == 'patient'  ? 'selected' : '' }}>Single Patient</option>
                        </select>
                    </div>
                    <div>
                        <label for="patient_name">Patient Name</label>
                        <input type="text" id="patient_name" name="patient_name"
                               value="{{ old('patient_name') }}" required>
                    </div>
                    <div>
                        <label for="patient_age">Patient Age</label>
                        <input type="number" id="patient_age" name="patient_age"
                               value="{{ old('patient_age') }}" required>
                    </div>
                    <div class="full">
                        <label for="patient_sex">Patient Sex</label>
                        <select id="patient_sex" name="patient_sex" required>
                            <option value="">Select</option>
                            <option value="male"   {{ old('patient_sex') == 'male'   ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('patient_sex') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                </div>

                <h3>Contact Information</h3>
                <div class="form-grid">
                    <div>
                        <label for="request_phone">Phone No</label>
                        <input type="tel" id="request_phone" name="phone"
                               value="{{ old('request_phone') }}" required>
                    </div>
                    <div>
                        <label for="request_email">Email</label>
                        <input type="email" id="request_email" name="email"
                               value="{{ old('request_email', auth()->user()->email) }}" required>
                    </div>
                    <div class="full">
                        <label for="r_address">Address</label>
                        <input type="text" id="r_address" name="address"
                               value="{{ old('address') }}" required>
                    </div>
                </div>

                <h3>Blood Request Details</h3>
                <div class="form-grid">
                    <div>
                        <label for="required_blood_type">Required Blood Type</label>
                        <select id="required_blood_type" name="required_blood_type" required>
                            <option value="">Select Blood Type</option>
                            @foreach(['A-','A+','B-','B+','AB-','AB+','O-','O+'] as $bt)
                                <option value="{{ $bt }}" {{ old('required_blood_type') == $bt ? 'selected' : '' }}>{{ $bt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="units">Units Required</label>
                        <input type="number" id="units" name="units"
                               min="1" max="10" value="{{ old('units', 1) }}" required>
                    </div>
                    <div>
                        <label for="urgency">Urgency</label>
                        <select id="urgency" name="urgency" required>
                            <option value="">Select Urgency</option>
                            <option value="normal"    {{ old('urgency') == 'normal'    ? 'selected' : '' }}>Normal</option>
                            <option value="emergency" {{ old('urgency') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                        </select>
                    </div>
                    <div>
                        <label for="request_datetime">Preferred Date & Time</label>
                        <input type="datetime-local" id="request_datetime" name="request_datetime"
                               value="{{ old('request_datetime', now()->addDay()->format('Y-m-d\TH:i')) }}" required>
                    </div>
                </div>

                <button type="submit">Submit Blood Request</button>
            </form>
        </section>

    </div><!-- end .main-content -->

    <script>
    (function () {
        'use strict';

        // ===== TAB SWITCHING =====
        const sections = document.querySelectorAll('.page-content');
        const navLinks  = document.querySelectorAll('.sidebar .nav-link');

        function showSection(id) {
            sections.forEach(s => s.classList.remove('active'));
            navLinks.forEach(l => l.classList.remove('active'));

            const target = document.getElementById(id);
            if (target) target.classList.add('active');

            const activeLink = document.querySelector(`.nav-link[data-section="${id}"]`);
            if (activeLink) activeLink.classList.add('active');

            // Scroll main content to top on mobile
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        navLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                showSection(this.dataset.section);
                closeSidebar();
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function (e) {
            if (['INPUT','SELECT','TEXTAREA'].includes(e.target.tagName)) return;
            if (e.key === '1') showSection('account');
            if (e.key === '2') showSection('donation');
            if (e.key === '3') showSection('request');
        });

        // Hash-based routing
        function handleHash() {
            const hash = window.location.hash.replace('#', '');
            if (hash && document.getElementById(hash)) showSection(hash);
        }
        window.addEventListener('hashchange', handleHash);
        window.addEventListener('load', () => {
            handleHash() || showSection('account');
        });

        // ===== MOBILE HAMBURGER =====
        const hamburger = document.getElementById('hamburgerBtn');
        const sidebar    = document.getElementById('sidebar');
        const overlay    = document.getElementById('sidebarOverlay');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('open');
            hamburger.classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
            hamburger.classList.remove('open');
            document.body.style.overflow = '';
        }
        hamburger.addEventListener('click', () => {
            sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
        });
        overlay.addEventListener('click', closeSidebar);

        // Close sidebar on Escape
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeSidebar();
        });

        // ===== Auto-select first empty field in active section =====
        document.querySelectorAll('form button[type="submit"]').forEach(btn => {
            btn.closest('form')?.addEventListener('submit', () => {
                btn.textContent = 'Submitting…';
                btn.disabled = true;
            });
        });

    })();
    </script>

</body>
</html>
