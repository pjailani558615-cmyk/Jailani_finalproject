@php use Illuminate\Support\Str; @endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Blood Donation System</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        /* ===== CSS VARIABLES (mirrored from user dashboard) ===== */
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
            align-items: flex-start;
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
           padding: 24px 40px 40px 24px;
           min-width: 0;
           width: calc(100% - var(--sidebar-w));
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
        .page-content h3.section-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .8px;
            margin: 28px 0 14px;
        }

        /* ===== ACCOUNT / WELCOME CARD ===== */
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

        /* ===== STATS GRID ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 16px;
            margin-top: 20px;
        }
        .stat-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px;
            box-shadow: var(--shadow);
        }
        .stat-card span {
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: var(--muted);
            font-weight: 600;
            margin-bottom: 8px;
        }
        .stat-card strong {
            display: block;
            font-size: 36px;
            font-weight: 600;
            color: var(--red);
            line-height: 1;
        }

        /* ===== ALERT ===== */
        .alert-success {
            background: #edfaed;
            color: #1a6b1a;
            border: 1px solid #b6e6b6;
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        /* ===== BUTTON ROW ===== */
        .btn-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 16px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: var(--font);
            font-size: 14px;
            font-weight: 500;
            transition: opacity .2s, transform .1s;
        }
        .btn:hover  { opacity: .85; }
        .btn:active { transform: scale(.98); }
        .btn-primary   { background: var(--red);  color: #fff; }
        .btn-secondary { background: #28aacc;      color: #fff; }
        .btn-success   { background: #28a745;      color: #fff; }
        .btn-warning   { background: #ffc107;      color: #333; }
        .btn-cancel    { background: #ddd;         color: #333; }

        /* ===== TABLE ===== */
        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            min-width: 600px;
        }
        thead { background: var(--red); color: #fff; }
        th, td { padding: 11px 14px; text-align: left; border-bottom: 1px solid var(--border); }
        tbody tr:hover { background: #faf8f0; }

        .badge-blood {
            background: var(--red);
            color: #fff;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
            font-weight: 600;
        }
        .status-pending  { color: #856404; background: #fff3cd; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 500; }
        .status-approved { color: #155724; background: #d4edda; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 500; }
        .status-rejected { color: #721c24; background: #f8d7da; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 500; }

        /* ===== PAGINATION ===== */
        .pagination-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 16px;
            padding-top: 14px;
            border-top: 1px solid var(--border);
        }
        .pagination-info {
            font-size: 13px;
            color: var(--muted);
        }
        .pagination-controls {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .pg-btn {
            min-width: 34px;
            height: 34px;
            padding: 0 8px;
            border: 1.5px solid var(--border);
            border-radius: 6px;
            background: #fff;
            color: var(--text);
            font-family: var(--font);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: background .18s, border-color .18s, color .18s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .pg-btn:hover:not(:disabled) {
            background: var(--yellow);
            border-color: var(--red);
            color: var(--red-dark);
        }
        .pg-btn.active {
            background: var(--red);
            border-color: var(--red);
            color: #fff;
        }
        .pg-btn:disabled {
            opacity: .4;
            cursor: not-allowed;
        }

        /* ===== FORMS ===== */
        .form-panel {
            background: var(--yellow);
            border-radius: var(--radius);
            padding: 24px;
            margin-bottom: 24px;
            display: none;
            box-shadow: var(--shadow);
        }
        .form-panel h3 {
            font-size: 16px;
            font-weight: 600;
            color: var(--red-dark);
            margin: 0 0 20px;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 20px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 16px;
        }
        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="number"],
        input[type="date"],
        input[type="tel"],
        input[type="email"],
        select,
        textarea {
            width: 100%;
            padding: 11px 14px;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-family: var(--font);
            font-size: 14px;
            background: #fff;
            color: var(--text);
            transition: border-color .2s, box-shadow .2s;
            -webkit-appearance: none;
            appearance: none;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--red);
            box-shadow: 0 0 0 3px rgba(117,59,47,.12);
        }
        textarea { min-height: 100px; resize: vertical; }

        /* Notify form */
        .notify-form {
            background: var(--yellow);
            padding: 24px;
            border-radius: var(--radius);
            max-width: 520px;
            box-shadow: var(--shadow);
        }
        .notify-form .form-group { margin-bottom: 16px; }

        form button[type="submit"].btn-submit {
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
        form button[type="submit"].btn-submit:hover  { background: var(--red-dark); }
        form button[type="submit"].btn-submit:active { transform: scale(.99); }

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
                width: 100%;
                padding: 24px 16px 40px;
            }
            .account-grid { grid-template-columns: 1fr; gap: 10px; }
            .form-grid    { grid-template-columns: 1fr; }
            .stats-grid   { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 480px) {
            .main-content { padding: 20px 12px 40px; }
            .stats-grid   { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <!-- ===== MOBILE TOP BAR ===== -->
    <div class="topbar">
        <div class="logo-small">
            <img src="{{ asset('images/MORO.jpg') }}" alt="MORO Logo">
            <span>Staff Portal</span>
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
            <a href="#" class="nav-link active" data-section="staffaccount">
                <span class="nav-num">1</span> Staff Account
            </a>
            <a href="#" class="nav-link" data-section="donationdb">
                <span class="nav-num">2</span> Donation DB
            </a>
            <a href="#" class="nav-link" data-section="requestdb">
                <span class="nav-num">3</span> Request DB
            </a>
            <a href="#" class="nav-link" data-section="notify">
                <span class="nav-num">4</span> Notify
            </a>
            <a href="#" class="nav-link" data-section="unit">
                <span class="nav-num">5</span> Blood Units
            </a>
        </nav>

        <div class="sidebar-logout">
            <a href="{{ route('staff.logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                &#x2192; Logout
            </a>
            <form id="logout-form" action="{{ route('staff.logout') }}" method="POST" style="display:none;">
                @csrf
            </form>
        </div>
    </div>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="main-content">

        <!-- ── 1. Staff Account ── -->
        <section id="staffaccount" class="page-content active">
            <h1>Staff Account Dashboard</h1>

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
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <span>Total Donations</span>
                    <strong>{{ $stats['total_donations'] ?? 0 }}</strong>
                </div>
                <div class="stat-card">
                    <span>Total Requests</span>
                    <strong>{{ $stats['total_requests'] ?? 0 }}</strong>
                </div>
            </div>
        </section>

        <!-- ── 2. Donation Database ── -->
        <section id="donationdb" class="page-content">
            <h1>Donation Database</h1>

            <div class="btn-row">
                <button class="btn btn-secondary" onclick="location.reload()">&#x21BB; Refresh</button>
            </div>

            @if (session('donation-updated'))
                <div class="alert-success">{{ session('donation-updated') }}</div>
            @endif

            <div class="table-wrap">
                <table id="donation-table">
                    <thead>
                        <tr>
                            <th>ID</th><th>User ID</th><th>Name</th><th>Sex</th><th>Age</th>
                            <th>Phone</th><th>Email</th><th>Address</th><th>Blood Type</th>
                            <th>Weight</th><th>Last Donation</th><th>Disease?</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="donation-tbody">
                        @forelse($donations as $donation)
                            <tr>
                                <td>{{ $donation->id }}</td>
                                <td>{{ $donation->user_id }}</td>
                                <td>{{ $donation->fullname }}</td>
                                <td>{{ ucfirst($donation->sex) }}</td>
                                <td>{{ $donation->age }}</td>
                                <td>{{ $donation->phone }}</td>
                                <td>{{ $donation->email }}</td>
                                <td>{{ Str::limit($donation->address, 20) }}</td>
                                <td><span class="badge-blood">{{ $donation->bloodtype }}</span></td>
                                <td>{{ $donation->weight }}kg</td>
                                <td>{{ $donation->dateoflastdonation?->format('M d, Y') ?? 'Never' }}</td>
                                <td>{{ $donation->disease ? 'Yes' : 'No' }}</td>
                                <td>
                                    <span class="status-{{ $donation->status ?? 'pending' }}">
                                        {{ ucfirst($donation->status ?? 'Pending') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="13" style="text-align:center;padding:40px;color:var(--muted);">No donations yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Donation Pagination -->
            <div class="pagination-bar" id="donation-pagination-bar">
                <span class="pagination-info" id="donation-pagination-info"></span>
                <div class="pagination-controls" id="donation-pagination-controls"></div>
            </div>
        </section>

        <!-- ── 3. Request Database ── -->
        <section id="requestdb" class="page-content">
            <h1>Request Database</h1>

            <div class="btn-row">
                <button class="btn btn-secondary" onclick="location.reload()">&#x21BB; Refresh</button>
            </div>

            @if (session('request-updated'))
                <div class="alert-success">{{ session('request-updated') }}</div>
            @endif

            <div class="table-wrap">
                <table id="request-table">
                    <thead>
                        <tr>
                            <th>ID</th><th>User ID</th><th>Type</th><th>Patient</th><th>Age</th><th>Sex</th>
                            <th>Phone</th><th>Email</th><th>Address</th><th>Blood Type</th><th>Units</th>
                            <th>Urgency</th><th>Date/Time</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="request-tbody">
                        @forelse($requests as $request)
                            <tr>
                                <td>{{ $request->id }}</td>
                                <td>{{ $request->user_id }}</td>
                                <td>{{ ucfirst($request->requester_type) }}</td>
                                <td>{{ $request->patient_name }}</td>
                                <td>{{ $request->patient_age }}</td>
                                <td>{{ ucfirst($request->patient_sex) }}</td>
                                <td>{{ $request->phone }}</td>
                                <td>{{ $request->email }}</td>
                                <td>{{ Str::limit($request->address, 20) }}</td>
                                <td><span class="badge-blood">{{ $request->required_blood_type }}</span></td>
                                <td>{{ $request->units }}</td>
                                <td>
                                    <span style="color: {{ $request->urgency == 'emergency' ? '#dc3545' : '#856404' }}; font-weight:500;">
                                        {{ ucfirst($request->urgency) }}
                                    </span>
                                </td>
                                <td>{{ $request->request_datetime?->format('M d, Y H:i') }}</td>
                                <td>
                                    <span class="status-{{ $request->status }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="14" style="text-align:center;padding:40px;color:var(--muted);">No requests yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Request Pagination -->
            <div class="pagination-bar" id="request-pagination-bar">
                <span class="pagination-info" id="request-pagination-info"></span>
                <div class="pagination-controls" id="request-pagination-controls"></div>
            </div>
        </section>

        <!-- ── 4. Notifications ── -->
        <section id="notify" class="page-content">
            <h1>Send Notifications</h1>

            @if (session('notification-sent'))
                <div class="alert-success">&#10003; Notification sent successfully!</div>
            @endif

            <form action="{{ route('staff.notify.send') }}" method="POST" class="notify-form">
                @csrf

                <div class="form-group">
                    <label for="recipient_id">Recipient ID</label>
                    <input type="text" id="recipient_id" name="recipient_id"
                           value="{{ old('recipient_id') }}" required
                           placeholder="Enter recipient's user ID">
                </div>

                <div class="form-group">
                    <label for="sender_id">Sender ID</label>
                    <input type="text" id="sender_id" name="sender_id"
                           value="{{ old('sender_id') }}" required
                           placeholder="Enter sender's user ID">
                </div>

                <div class="form-group">
                    <label for="message">Message</label>
                    <select id="message" name="message" required>
                        <option value="">Select Message</option>
                        <option value="pending"  {{ old('message') == 'pending'  ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ old('message') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ old('message') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <button type="submit" class="btn-submit">&#128231; Send Notification</button>
            </form>

            <h3 class="section-label" style="margin-top:36px;">Recent Notifications</h3>
            <div class="table-wrap">
                <table id="notify-table">
                    <thead>
                        <tr><th>Date</th><th>Recipient</th><th>Subject</th><th>Status</th></tr>
                    </thead>
                    <tbody id="notify-tbody">
                        @forelse($recent_notifications as $notification)
                            <tr>
                                <td>{{ $notification->created_at->format('M d, H:i') }}</td>
                                <td>{{ $notification->recipient_id }}</td>
                                <td>{{ Str::limit($notification->subject, 30) }}</td>
                                <td><span class="status-approved">Sent</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" style="padding:24px;color:var(--muted);">No notifications sent yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Notify Pagination -->
            <div class="pagination-bar" id="notify-pagination-bar">
                <span class="pagination-info" id="notify-pagination-info"></span>
                <div class="pagination-controls" id="notify-pagination-controls"></div>
            </div>
        </section>

        <!-- ── 5. Blood Units ── -->
        <section id="unit" class="page-content">
            <h1>Blood Unit Management</h1>

            <div class="btn-row">
                <button class="btn btn-success" onclick="showForm('create-unit')">&#43; Create Unit</button>
                <button class="btn btn-secondary" onclick="location.reload()">&#x21BB; Refresh</button>
            </div>

            @if (session('unit-created'))
                <div class="alert-success">&#10003; New blood unit registered!</div>
            @endif

            <!-- Create Unit Form -->
            <div id="create-unit-form" class="form-panel">
                <h3>Register New Blood Unit</h3>
                <form action="{{ route('staff.unit.store') }}" method="POST">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Donation ID</label>
                            <input type="text" name="donation_id" required placeholder="Enter Donation ID">
                        </div>
                        <div class="form-group">
                            <label>Blood Type</label>
                            <input type="text" name="blood_type" required placeholder="A+, O-, etc.">
                        </div>
                        <div class="form-group">
                            <label>Request ID</label>
                            <input type="text" name="request_id" required placeholder="Enter Request ID">
                        </div>
                        <div class="form-group">
                            <label>Volume (ml)</label>
                            <input type="number" name="volume" min="350" max="500" value="450" required>
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label>Expiry Date</label>
                            <input type="date" name="expiry_date" required>
                        </div>
                    </div>
                    <div class="btn-row" style="margin-top:8px;">
                        <button type="submit" class="btn btn-primary">&#128137; Register Unit</button>
                        <button type="button" class="btn btn-cancel" onclick="hideForm('create-unit')">Cancel</button>
                    </div>
                </form>
            </div>

            <!-- Edit Unit Form -->
            <div id="edit-unit-form" class="form-panel">
                <h3>Edit Blood Unit</h3>
                <form id="edit-unit-form-tag" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Donation ID</label>
                            <input type="text" name="donation_id" id="edit-donation-id" required>
                        </div>
                        <div class="form-group">
                            <label>Blood Type</label>
                            <input type="text" name="blood_type" id="edit-blood-type" required>
                        </div>
                        <div class="form-group">
                            <label>Request ID</label>
                            <input type="text" name="request_id" id="edit-request-id">
                        </div>
                        <div class="form-group">
                            <label>Volume (ml)</label>
                            <input type="number" name="volume" id="edit-volume" min="350" max="500" required>
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label>Expiry Date</label>
                            <input type="date" name="expiry_date" id="edit-expiry-date" required>
                        </div>
                    </div>
                    <div class="btn-row" style="margin-top:8px;">
                        <button type="submit" class="btn btn-primary">&#128190; Save Changes</button>
                        <button type="button" class="btn btn-cancel"
                                onclick="document.getElementById('edit-unit-form').style.display='none'">Cancel</button>
                    </div>
                </form>
            </div>

            <!-- Blood Units Table -->
            <div class="table-wrap">
                <table id="unit-table">
                    <thead>
                        <tr>
                            <th>Unit ID</th><th>Donation ID</th><th>Blood Type</th>
                            <th>Request ID</th><th>Volume</th><th>Expires</th><th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="unit-tbody">
                        @forelse($blood_units as $unit)
                            <tr>
                                <td>#{{ $unit->id }}</td>
                                <td>{{ $unit->donation_id }}</td>
                                <td><span class="badge-blood">{{ $unit->blood_type }}</span></td>
                                <td>{{ $unit->request_id ?? '-' }}</td>
                                <td>{{ $unit->volume }}ml</td>
                                <td style="color: {{ $unit->expiry_date < now() ? '#dc3545' : '#28a745' }}; font-weight:500;">
                                    {{ $unit->expiry_date?->format('M d, Y') }}
                                </td>
                                <td>
                                    <button class="btn btn-warning" onclick="editUnit({{ $unit->id }})">Edit</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--muted);">No blood units registered</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Unit Pagination -->
            <div class="pagination-bar" id="unit-pagination-bar">
                <span class="pagination-info" id="unit-pagination-info"></span>
                <div class="pagination-controls" id="unit-pagination-controls"></div>
            </div>
        </section>

    </div><!-- /.main-content -->

    <script>
    (function () {
        'use strict';

        const sections = document.querySelectorAll('.page-content');
        const navLinks  = document.querySelectorAll('.sidebar .nav-link');
        const bloodUnits = @json($blood_units);

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
            const map = { '1':'staffaccount', '2':'donationdb', '3':'requestdb', '4':'notify', '5':'unit' };
            if (map[e.key]) showSection(map[e.key]);
        });

        // Hash routing
        function handleHash() {
            const hash = window.location.hash.replace('#', '');
            if (hash && document.getElementById(hash)) { showSection(hash); return true; }
            return false;
        }
        window.addEventListener('hashchange', handleHash);
        window.addEventListener('load', () => { handleHash() || showSection('staffaccount'); });

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

        // ===== FORM HELPERS =====
        window.showForm = function(id) { document.getElementById(id + '-form').style.display = 'block'; };
        window.hideForm = function(id) { document.getElementById(id + '-form').style.display = 'none'; };

        // ===== EDIT UNIT =====
        window.editUnit = function(id) {
            const unit = bloodUnits.find(u => u.id === id);
            if (!unit) return alert('Unit not found.');

            document.getElementById('edit-donation-id').value = unit.donation_id;
            document.getElementById('edit-blood-type').value  = unit.blood_type;
            document.getElementById('edit-request-id').value  = unit.request_id ?? '';
            document.getElementById('edit-volume').value      = unit.volume;
            document.getElementById('edit-expiry-date').value = unit.expiry_date?.split('T')[0] ?? '';

            document.getElementById('edit-unit-form-tag').action = `/staff/unit/${id}`;
            document.getElementById('edit-unit-form').style.display = 'block';
            document.getElementById('edit-unit-form').scrollIntoView({ behavior: 'smooth' });
        };

        // ===== SUBMIT FEEDBACK =====
        document.querySelectorAll('form button[type="submit"]').forEach(btn => {
            btn.closest('form')?.addEventListener('submit', () => {
                btn.textContent = 'Submitting…';
                btn.disabled = true;
            });
        });

        // ===== PAGINATION ENGINE =====
        const ROWS_PER_PAGE = 8;

        /**
         * Sets up client-side pagination for a table tbody.
         * @param {string} tbodyId       - ID of the <tbody> element
         * @param {string} infoId        - ID of the info <span>
         * @param {string} controlsId    - ID of the controls container
         */
        function initPagination(tbodyId, infoId, controlsId) {
            const tbody    = document.getElementById(tbodyId);
            const infoEl   = document.getElementById(infoId);
            const ctrlEl   = document.getElementById(controlsId);

            if (!tbody || !infoEl || !ctrlEl) return;

            // Collect all data rows (exclude empty-state colspan rows)
            const allRows = Array.from(tbody.querySelectorAll('tr'));

            // If there's only one row and it spans all columns (empty state), hide pagination bar
            if (allRows.length === 1 && allRows[0].querySelector('td[colspan]')) {
                const bar = ctrlEl.closest('.pagination-bar');
                if (bar) bar.style.display = 'none';
                return;
            }

            // Also hide if 8 or fewer rows — no pagination needed
            if (allRows.length <= ROWS_PER_PAGE) {
                const bar = ctrlEl.closest('.pagination-bar');
                if (bar) bar.style.display = 'none';
                return;
            }

            const totalRows  = allRows.length;
            const totalPages = Math.ceil(totalRows / ROWS_PER_PAGE);
            let currentPage  = 1;

            function render(page) {
                currentPage = page;
                const start = (page - 1) * ROWS_PER_PAGE;
                const end   = start + ROWS_PER_PAGE;

                allRows.forEach((row, i) => {
                    row.style.display = (i >= start && i < end) ? '' : 'none';
                });

                // Info text
                const displayEnd = Math.min(end, totalRows);
                infoEl.textContent = `Showing ${start + 1}–${displayEnd} of ${totalRows} rows`;

                // Rebuild controls
                ctrlEl.innerHTML = '';

                // Prev button
                const prevBtn = createPgBtn('&#8592;', page === 1, () => render(page - 1));
                ctrlEl.appendChild(prevBtn);

                // Page number buttons (show max 5 around current)
                const range = pageRange(currentPage, totalPages);
                let lastNum = 0;
                range.forEach(num => {
                    if (num - lastNum > 1) {
                        // Ellipsis
                        const ellipsis = document.createElement('span');
                        ellipsis.textContent = '…';
                        ellipsis.style.cssText = 'padding:0 6px;color:var(--muted);font-size:13px;align-self:center;';
                        ctrlEl.appendChild(ellipsis);
                    }
                    const pgBtn = createPgBtn(num, false, () => render(num));
                    if (num === currentPage) pgBtn.classList.add('active');
                    ctrlEl.appendChild(pgBtn);
                    lastNum = num;
                });

                // Next button
                const nextBtn = createPgBtn('&#8594;', page === totalPages, () => render(page + 1));
                ctrlEl.appendChild(nextBtn);
            }

            function createPgBtn(label, disabled, onClick) {
                const btn = document.createElement('button');
                btn.className = 'pg-btn';
                btn.innerHTML = label;
                btn.disabled  = disabled;
                if (!disabled) btn.addEventListener('click', onClick);
                return btn;
            }

            function pageRange(current, total) {
                // Always show first, last, current, and 1 neighbor on each side
                const delta = 1;
                const range = new Set();
                range.add(1);
                range.add(total);
                for (let i = Math.max(2, current - delta); i <= Math.min(total - 1, current + delta); i++) {
                    range.add(i);
                }
                return Array.from(range).sort((a, b) => a - b);
            }

            // Initial render
            render(1);
        }

        // Init pagination for each table
        initPagination('donation-tbody', 'donation-pagination-info', 'donation-pagination-controls');
        initPagination('request-tbody',  'request-pagination-info',  'request-pagination-controls');
        initPagination('notify-tbody',   'notify-pagination-info',   'notify-pagination-controls');
        initPagination('unit-tbody',     'unit-pagination-info',     'unit-pagination-controls');

    })();
    </script>
</body>
</html>