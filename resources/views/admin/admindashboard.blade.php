<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Blood Donation System</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* ===== CSS VARIABLES ===== */
        :root {
            --red:       #753B2F;
            --red-dark:  #5a2d23;
            --yellow:    #f4f194;
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
            width: 34px; height: 34px;
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
            width: 22px; height: 2px;
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
            width: 90px; height: 90px;
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
            cursor: pointer;
        }
        .nav-link:hover,
        .nav-link.active {
            background: rgba(255,255,255,.15);
            color: #fff;
        }
        .nav-link .nav-num {
            width: 22px; height: 22px;
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
            min-width: 0;
        }

        /* ===== SECTIONS ===== */
        .page-content { display: none; }
        .page-content.active { display: block; }

        /* ===== SECTION TITLE ===== */
        .page-content > h1 {
            font-size: clamp(20px, 4vw, 26px);
            font-weight: 600;
            color: var(--red-dark);
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: 2px solid var(--border);
        }
        .section-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .8px;
            margin: 32px 0 12px;
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
            margin: 0 0 16px;
            font-weight: 600;
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

        /* ===== BUTTON ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: var(--font);
            font-size: 14px;
            font-weight: 600;
            transition: opacity .2s, transform .1s;
            text-decoration: none;
        }
        .btn:hover  { opacity: .85; }
        .btn:active { transform: scale(.98); }
        .btn-primary { background: var(--red); color: #fff; }

        /* ===== TABLE ===== */
        .table-wrap {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            min-width: 560px;
        }
        thead { background: var(--red); color: #fff; }
        th, td { padding: 11px 14px; text-align: left; border-bottom: 1px solid var(--border); }
        tbody tr:hover { background: #faf8f0; }
        td:empty::after { content: '—'; color: var(--muted); }

        .badge-blood {
            background: var(--red);
            color: #fff;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }
        .badge-role {
            background: #e8e0fa;
            color: #5a3d8a;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
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
            <span>Admin Portal</span>
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
            <a href="#" class="nav-link active" data-section="adminaccount">
                <span class="nav-num">1</span> Admin Account
            </a>
            <a href="#" class="nav-link" data-section="generalreport">
                <span class="nav-num">2</span> General Report
            </a>
        </nav>

        <div class="sidebar-logout">
            <a href="{{ route('admin.logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                &#x2192; Logout
            </a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display:none;">
                @csrf
            </form>
        </div>
    </div>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="main-content">

        <!-- ── 1. Admin Account ── -->
        <section id="adminaccount" class="page-content active">
            <h1>Admin Account</h1>

            <div class="account-card">
                <h3>Welcome, {{ auth()->user()->name }}!</h3>
                <div class="account-grid">
                    <div class="account-field">
                        <span>Email</span>
                        <span>{{ auth()->user()->email }}</span>
                    </div>
                    <div class="account-field">
                        <span>Age</span>
                        <span>{{ $admin->age ?? auth()->user()->age }} years</span>
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
        </section>

        <!-- ── 2. General Report ── -->
        <section id="generalreport" class="page-content">
            <h1>MORO General Report</h1>

            <form method="POST" action="{{ route('report.pdf') }}" style="margin-bottom: 28px;">
                @csrf
                <button type="submit" class="btn btn-primary">&#128196; Generate PDF Report</button>
            </form>

            <!-- Users -->
            <h3 class="section-label">Users</h3>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Sex</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->age }}</td>
                                <td>{{ ucfirst($user->sex) }}</td>
                                <td>{{ $user->email }}</td>
                                <td style="color:var(--muted);">•••••••</td>
                                <td><span class="badge-role">{{ ucfirst($user->role) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Donors -->
            <h3 class="section-label">Donors</h3>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Donor ID</th>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Sex</th>
                            <th>Age</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Blood Type</th>
                            <th>Weight</th>
                            <th>Last Donation</th>
                            <th>Disease?</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($donors as $donor)
                            <tr>
                                <td>{{ $donor->id }}</td>
                                <td>{{ $donor->user_id }}</td>
                                <td>{{ $donor->name }}</td>
                                <td>{{ ucfirst($donor->sex) }}</td>
                                <td>{{ $donor->age }}</td>
                                <td>{{ $donor->phone }}</td>
                                <td>{{ $donor->email }}</td>
                                <td>{{ $donor->address }}</td>
                                <td><span class="badge-blood">{{ $donor->blood_type }}</span></td>
                                <td>{{ $donor->weight }}kg</td>
                                <td>{{ $donor->last_donation }}</td>
                                <td>{{ $donor->has_disease ? 'Yes' : 'No' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Requestors -->
            <h3 class="section-label">Requestors</h3>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User ID</th>
                            <th>Type</th>
                            <th>Patient Name</th>
                            <th>Age</th>
                            <th>Sex</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Blood Type</th>
                            <th>Qty</th>
                            <th>Urgency</th>
                            <th>Date &amp; Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requestors as $requestor)
                            <tr>
                                <td>{{ $requestor->id }}</td>
                                <td>{{ $requestor->user_id }}</td>
                                <td>{{ ucfirst($requestor->requester_type) }}</td>
                                <td>{{ $requestor->patient_name }}</td>
                                <td>{{ $requestor->patient_age }}</td>
                                <td>{{ ucfirst($requestor->patient_sex) }}</td>
                                <td>{{ $requestor->phone }}</td>
                                <td>{{ $requestor->email }}</td>
                                <td>{{ $requestor->address }}</td>
                                <td><span class="badge-blood">{{ $requestor->required_blood_type }}</span></td>
                                <td>{{ $requestor->quantity }}</td>
                                <td>
                                    <span style="color: {{ $requestor->urgency == 'emergency' ? '#dc3545' : '#856404' }}; font-weight:500;">
                                        {{ ucfirst($requestor->urgency) }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($requestor->created_at)->format('M d, Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Blood Units -->
            <h3 class="section-label">Blood Units</h3>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Unit ID</th>
                            <th>Donation ID</th>
                            <th>Blood Type</th>
                            <th>Request ID</th>
                            <th>Expiry Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bloodUnits as $unit)
                            <tr>
                                <td>#{{ $unit->id }}</td>
                                <td>{{ $unit->donation_id }}</td>
                                <td><span class="badge-blood">{{ $unit->blood_type }}</span></td>
                                <td>{{ $unit->request_id ?? '—' }}</td>
                                <td style="color: {{ \Carbon\Carbon::parse($unit->expiry_date) < now() ? '#dc3545' : '#28a745' }}; font-weight:500;">
                                    {{ \Carbon\Carbon::parse($unit->expiry_date)->format('M d, Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

    </div><!-- /.main-content -->

    <script>
    (function () {
        'use strict';

        const sections = document.querySelectorAll('.page-content');
        const navLinks  = document.querySelectorAll('.sidebar .nav-link');

        // ===== SECTION SWITCHING =====
        function showSection(id) {
            sections.forEach(s => s.classList.remove('active'));
            navLinks.forEach(l => l.classList.remove('active'));

            const target = document.getElementById(id);
            if (target) target.classList.add('active');

            const activeLink = document.querySelector(`.nav-link[data-section="${id}"]`);
            if (activeLink) activeLink.classList.add('active');

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
            if (e.key === '1') showSection('adminaccount');
            if (e.key === '2') showSection('generalreport');
        });

        // Hash routing
        function handleHash() {
            const hash = window.location.hash.replace('#', '');
            if (hash && document.getElementById(hash)) { showSection(hash); return true; }
            return false;
        }
        window.addEventListener('hashchange', handleHash);
        window.addEventListener('load', () => { handleHash() || showSection('adminaccount'); });

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

        hamburger.addEventListener('click', () =>
            sidebar.classList.contains('open') ? closeSidebar() : openSidebar()
        );
        overlay.addEventListener('click', closeSidebar);
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSidebar(); });

    })();
    </script>

</body>
</html>