@php use Illuminate\Support\Str; @endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Blood Donation System</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        /* ── Reset & Base ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: sans-serif;
            background: #f5f5f5;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ── Sidebar / Top-nav ── */
        .staffbar {
            background: #753B2F;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            padding: 20px 16px;
            width: 220px;
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }

        .staffbar img {
            width: 80px;
            margin-bottom: 24px;
            align-self: center;
        }

        .staffbar a {
            color: #FEFDF1;
            text-decoration: none;
            padding: 10px 12px;
            border-radius: 6px;
            width: 100%;
            margin-bottom: 4px;
            font-size: 14px;
            display: block;
            transition: background 0.2s;
        }

        .staffbar a:hover,
        .staffbar a.active { background: rgba(255,255,255,0.15); }

        /* Mobile hamburger */
        .hamburger {
            display: none;
            position: fixed;
            top: 12px; left: 12px;
            z-index: 200;
            background: #753B2F;
            border: none;
            border-radius: 6px;
            padding: 8px 10px;
            cursor: pointer;
            flex-direction: column;
            gap: 5px;
        }

        .hamburger span {
            display: block;
            width: 22px; height: 2px;
            background: #FEFDF1;
            border-radius: 2px;
            transition: all 0.3s;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 90;
        }

        /* ── Main content ── */
        .staff-content {
            margin-left: 220px;
            padding: 24px;
            flex: 1;
            min-width: 0;
        }

        /* ── Sections ── */
        .staff { display: none; }
        .staff.active { display: block; }

        .staff h1 {
            font-size: clamp(20px, 4vw, 28px);
            color: #753B2F;
            margin-bottom: 20px;
        }

        /* ── Welcome card ── */
        .welcome-card {
            background: #f4f194;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        /* ── Stats grid ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 16px;
            margin-top: 24px;
        }

        .stat-card {
            background: #FEFDF1;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e0dfc0;
        }

        .stat-card h4 { font-size: 13px; color: #666; margin-bottom: 8px; }
        .stat-card h2 { font-size: 32px; color: #753B2F; }

        /* ── Alerts ── */
        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 16px;
            font-size: 14px;
        }

        /* ── Buttons ── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: opacity 0.2s;
        }
        .btn:hover { opacity: 0.85; }
        .btn-primary { background: #753B2F; color: #FEFDF1; }
        .btn-secondary { background: #28aacc; color: white; }
        .btn-success  { background: #28a745; color: white; }
        .btn-warning  { background: #ffc107; color: #333; }
        .btn-cancel   { background: #ccc; color: #333; }

        .btn-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 16px;
        }

        /* ── Table wrapper (horizontal scroll on mobile) ── */
        .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            min-width: 600px;
        }

        thead { background: #753B2F; color: #FEFDF1; }
        th, td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #eee; }
        tbody tr:hover { background: #fafaf0; }

        .badge-blood {
            background: #753B2F;
            color: #FEFDF1;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            white-space: nowrap;
        }

        /* ── Status badges ── */
        .status-pending  { color: #856404; background: #fff3cd; padding: 2px 8px; border-radius: 4px; font-size: 12px; }
        .status-approved { color: #155724; background: #d4edda; padding: 2px 8px; border-radius: 4px; font-size: 12px; }
        .status-rejected { color: #721c24; background: #f8d7da; padding: 2px 8px; border-radius: 4px; font-size: 12px; }

        /* ── Forms ── */
        .form-panel {
            background: #f4f194;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }

        .form-panel h3 { margin-top: 0; margin-bottom: 16px; color: #333; }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .form-group { display: flex; flex-direction: column; gap: 6px; }

        label { font-size: 13px; font-weight: 600; color: #444; }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            background: #fff;
        }

        input:focus, select:focus, textarea:focus {
            outline: 2px solid #753B2F;
            border-color: transparent;
        }

        /* Notify form full-width fields */
        .notify-form .form-group { margin-bottom: 14px; }
        .notify-form textarea { min-height: 100px; resize: vertical; }

        /* ── Responsive breakpoints ── */

        /* Tablet */
        @media (max-width: 900px) {
            .staff-content { padding: 16px; }
        }

        /* Mobile */
        @media (max-width: 640px) {
            .hamburger { display: flex; }

            .staffbar {
                transform: translateX(-100%);
                width: 260px;
            }

            .staffbar.open { transform: translateX(0); }
            .sidebar-overlay.open { display: block; }

            .staff-content {
                margin-left: 0;
                padding: 60px 14px 20px;
            }

            .form-grid { grid-template-columns: 1fr; }

            .stats-grid { grid-template-columns: 1fr 1fr; }

            .btn { font-size: 13px; padding: 8px 12px; }

            table { font-size: 12px; }
            th, td { padding: 8px 10px; }
        }

        @media (max-width: 380px) {
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <!-- Hamburger (mobile only) -->
    <button class="hamburger" id="hamburger" aria-label="Open menu">
        <span></span><span></span><span></span>
    </button>

    <!-- Overlay (mobile only) -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Staff Sidebar -->
    <div class="staffbar" id="staffbar">
        <img src="{{ asset('images/MORO.jpg') }}" alt="MORO Logo">
        <a href="#" onclick="showSection('staffaccount')" class="nav-link">1 Staff Account</a>
        <a href="#" onclick="showSection('donationdb')" class="nav-link">2 Donation DB</a>
        <a href="#" onclick="showSection('requestdb')" class="nav-link">3 Request DB</a>
        <a href="#" onclick="showSection('notify')" class="nav-link">4 Notify</a>
        <a href="#" onclick="showSection('unit')" class="nav-link">5 Blood Units</a>
        <a href="{{ route('staff.logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           style="margin-top: auto; color: #f8c4b4;">
            Logout
        </a>
        <form id="logout-form" action="{{ route('staff.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    <!-- Main Content -->
    <div class="staff-content">

        <!-- ── 1. Staff Account ── -->
        <section id="staffaccount" class="staff active">
            <h1>Staff Account Dashboard</h1>

            <div class="welcome-card">
                <h3>Welcome, {{ auth()->user()->name }}!</h3>
                <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                <p><strong>Age:</strong> {{ auth()->user()->age }} years</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <h4>Total Donations</h4>
                    <h2>{{ $stats['total_donations'] ?? 0 }}</h2>
                </div>
                <div class="stat-card">
                    <h4>Total Requests</h4>
                    <h2>{{ $stats['total_requests'] ?? 0 }}</h2>
                </div>
            </div>
        </section>

        <!-- ── 2. Donation Database ── -->
        <section id="donationdb" class="staff">
            <h1>Donation Database</h1>

            <div class="btn-row">
                <button class="btn btn-secondary" onclick="refreshTable('donations')">&#x21BB; Refresh</button>
            </div>

            @if (session('donation-updated'))
                <div class="alert-success">{{ session('donation-updated') }}</div>
            @endif

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th><th>User ID</th><th>Name</th><th>Sex</th><th>Age</th>
                            <th>Phone</th><th>Email</th><th>Address</th><th>Blood Type</th>
                            <th>Weight</th><th>Last Donation</th><th>Disease?</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
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
                            <tr><td colspan="13" style="text-align:center;padding:40px;">No donations yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <!-- ── 3. Request Database ── -->
        <section id="requestdb" class="staff">
            <h1>Request Database</h1>

            <div class="btn-row">
                <button class="btn btn-secondary" onclick="refreshTable('requests')">&#x21BB; Refresh</button>
            </div>

            @if (session('request-updated'))
                <div class="alert-success">{{ session('request-updated') }}</div>
            @endif

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th><th>User ID</th><th>Type</th><th>Patient</th><th>Age</th><th>Sex</th>
                            <th>Phone</th><th>Email</th><th>Address</th><th>Blood Type</th><th>Units</th>
                            <th>Urgency</th><th>Date/Time</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
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
                            <tr><td colspan="14" style="text-align:center;padding:40px;">No requests yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <!-- ── 4. Notifications ── -->
        <section id="notify" class="staff">
            <h1>Send Notifications</h1>

            @if (session('notification-sent'))
                <div class="alert-success">&#10003; Notification sent successfully!</div>
            @endif

            <form action="{{ route('staff.notify.send') }}" method="POST"
                  class="notify-form"
                  style="background:#f4f194; padding:20px; border-radius:8px; max-width:520px;">
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

                <div style="margin-top:16px;">
                    <button type="submit" class="btn btn-primary">&#128231; Send Notification</button>
                </div>
            </form>

            <h3 style="margin-top:36px; margin-bottom:12px;">Recent Notifications</h3>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr><th>Date</th><th>Recipient</th><th>Subject</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse($recent_notifications as $notification)
                            <tr>
                                <td>{{ $notification->created_at->format('M d, H:i') }}</td>
                                <td>{{ $notification->recipient_id }}</td>
                                <td>{{ Str::limit($notification->subject, 30) }}</td>
                                <td><span class="status-approved">Sent</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" style="padding:20px;">No notifications sent yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <!-- ── 5. Blood Units ── -->
        <section id="unit" class="staff">
            <h1>Blood Unit Management</h1>

            <div class="btn-row">
                <button class="btn btn-success" onclick="showForm('create-unit')">&#43; Create Unit</button>
                <button class="btn btn-secondary" onclick="refreshTable('units')">&#x21BB; Refresh</button>
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
                        <div class="form-group">
                            <label>Expiry Date</label>
                            <input type="date" name="expiry_date" required>
                        </div>
                    </div>
                    <div class="btn-row" style="margin-top:16px;">
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
                        <div class="form-group">
                            <label>Expiry Date</label>
                            <input type="date" name="expiry_date" id="edit-expiry-date" required>
                        </div>
                    </div>
                    <div class="btn-row" style="margin-top:16px;">
                        <button type="submit" class="btn btn-primary">&#128190; Save Changes</button>
                        <button type="button" class="btn btn-cancel"
                                onclick="document.getElementById('edit-unit-form').style.display='none'">Cancel</button>
                    </div>
                </form>
            </div>

            <!-- Blood Units Table -->
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Unit ID</th><th>Donation ID</th><th>Blood Type</th>
                            <th>Request ID</th><th>Volume</th><th>Expires</th><th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($blood_units as $unit)
                            <tr>
                                <td>#{{ $unit->id }}</td>
                                <td>{{ $unit->donation_id }}</td>
                                <td><strong>{{ $unit->blood_type }}</strong></td>
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
                            <tr><td colspan="7" style="text-align:center;padding:40px;">No blood units registered</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

    </div><!-- /.staff-content -->

    <script>
    'use strict';

    const sections  = document.querySelectorAll('.staff');
    const navLinks  = document.querySelectorAll('.staffbar a[href="#"]');
    const bloodUnits = @json($blood_units);

    /* ── Sidebar toggle (mobile) ── */
    const hamburger = document.getElementById('hamburger');
    const staffbar  = document.getElementById('staffbar');
    const overlay   = document.getElementById('sidebar-overlay');

    function openSidebar()  { staffbar.classList.add('open'); overlay.classList.add('open'); }
    function closeSidebar() { staffbar.classList.remove('open'); overlay.classList.remove('open'); }

    hamburger.addEventListener('click', () =>
        staffbar.classList.contains('open') ? closeSidebar() : openSidebar()
    );
    overlay.addEventListener('click', closeSidebar);

    /* ── Section switching ── */
    function showSection(sectionId) {
        sections.forEach(s => s.classList.remove('active'));

        const target = document.getElementById(sectionId);
        if (target) {
            target.classList.add('active');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        navLinks.forEach(l => l.classList.remove('active'));

        const activeLink = [...navLinks].find(l =>
            l.getAttribute('onclick')?.includes(`'${sectionId}'`)
        );
        if (activeLink) activeLink.classList.add('active');

        closeSidebar();
    }

    /* ── Form helpers ── */
    function showForm(id) { document.getElementById(id + '-form').style.display = 'block'; }
    function hideForm(id) { document.getElementById(id + '-form').style.display = 'none'; }

    /* ── Edit unit ── */
    function editUnit(id) {
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
    }

    /* ── Refresh ── */
    function refreshTable() { location.reload(); }

    /* ── Nav click listeners ── */
    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const match = this.getAttribute('onclick')?.match(/'([^']+)'/);
            if (match) showSection(match[1]);
        });
    });

    /* ── Keyboard shortcuts ── */
    document.addEventListener('keydown', e => {
        const map = { '1': 'staffaccount', '2': 'donationdb', '3': 'requestdb', '4': 'notify', '5': 'unit' };
        if (map[e.key] && document.activeElement.tagName !== 'INPUT') showSection(map[e.key]);
    });

    /* ── Hash navigation ── */
    window.addEventListener('hashchange', () => {
        const hash = window.location.hash.replace('#', '');
        if (hash && document.getElementById(hash)) showSection(hash);
    });

    /* ── Initial load ── */
    window.addEventListener('load', () => showSection('staffaccount'));
    </script>
</body>
</html>