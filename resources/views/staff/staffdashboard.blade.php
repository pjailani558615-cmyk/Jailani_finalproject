 @php use Illuminate\Support\Str; @endphp
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Blood Donation System</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Staff Sidebar -->
    <div class="staffbar">
        <img src="{{ asset('images/MORO.jpg') }}" alt="MORO Logo">
        <a href="#" onclick="showSection('staffaccount')" class="nav-link">1 Staff Account</a>
        <a href="#" onclick="showSection('donationdb')" class="nav-link">2 Donation DB</a>
        <a href="#" onclick="showSection('requestdb')" class="nav-link">3 Request DB</a>
        <a href="#" onclick="showSection('notify')" class="nav-link">4 Notify</a>
        <a href="#" onclick="showSection('unit')" class="nav-link">5 Blood Units</a>
        <a href="{{ route('staff.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
        <form id="logout-form" action="{{ route('staff.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    <!-- Main Staff Content -->
    <div class="staff-content">
        <!-- Staff Account Section (Default Active) -->
        <section id="staffaccount" class="staff active">
            <h1>Staff Account Dashboard</h1>
            <div style="background: #f4f194; padding: 25px; border-radius: 8px; margin: 20px 0;">
                <h3>Welcome, {{ auth()->user()->name }}!</h3>
                <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                <p><strong>Age:</strong> {{ auth()->user()->age }} years</p>
            </div>

            <!-- Quick Stats -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">
                <div style="background: #FEFDF1; padding: 20px; border-radius: 8px;">
                    <h4>Total Donations</h4>
                    <h2>{{ $stats['total_donations'] ?? 0 }}</h2>
                </div>
                <div style="background: #FEFDF1; padding: 20px; border-radius: 8px;">
                    <h4>Total Requests</h4>
                    <h2>{{ $stats['total_requests'] ?? 0 }}</h2>
                </div>
            </div>
        </section>

        <!-- Donation Database -->
        <section id="donationdb" class="staff">
            <h1>Donation Database</h1>
            <button onclick="refreshTable('donations')" style="background: #28a; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer;">🔄 Refresh</button>
            
            @if (session('donation-updated'))
                <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 6px; margin: 10px 0;">
                    {{ session('donation-updated') }}
                </div>
            @endif

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
                            <td><span style="background: #753B2F; color: #FEFDF1">{{ $donation->bloodtype }}</span></td>
                            <td>{{ $donation->weight }}kg</td>
                            <td>{{ $donation->dateoflastdonation?->format('M d, Y') ?? 'Never' }}</td>
                            <td>{{ $donation->disease ? 'Yes' : 'No' }}</td>
                            <td>
                                <span class="status-{{ $donation->status ?? 'pending' }}">
                                    {{ $donation->status ?? 'Pending' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="13" style="text-align: center; padding: 40px;">No donations yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <!-- Request Database -->
        <section id="requestdb" class="staff">
            <h1>Request Database</h1>
            <button onclick="refreshTable('requests')" style="background: #28a; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer;">🔄 Refresh</button>
            
            @if (session('request-updated'))
                <div style="background: #d4edda; color: #28a7; padding: 10px; border-radius: 6px; margin: 10px 0;">
                    {{ session('request-updated') }}
                </div>
            @endif

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
                            <td><span style="background: #753B2F; color: #FEFDF1">{{ $request->required_blood_type }}</span></td>
                            <td>{{ $request->units }}</td>
                            <td><span style="color: {{ $request->urgency == 'emergency' ? '#dc3545' : '#ffc107' }};">{{ ucfirst($request->urgency) }}</span></td>
                            <td>{{ $request->request_datetime?->format('M d, Y H:i') }}</td>
                            <td>
                                <span class="status-{{ $request->status }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="14" style="text-align: center; padding: 40px;">No requests yet</td></tr>
                    @endforelse
                </tbody>
  </table>
</section>

        <!-- Notification Section -->
        <section id="notify" class="staff">
            <h1>Send Notifications</h1>
            
            @if (session('notification-sent'))
                <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                    ✅ Notification sent successfully!
                </div>
            @endif

            <form action="{{ route('staff.notify.send') }}" method="POST" style="background: #f4f194;">
                @csrf
                <label for="recipient_id">Recipient ID</label>
                <input type="text" 
                       id="recipient_id" 
                       name="recipient_id" 
                       value="{{ old('recipient_id') }}"
                       required 
                       placeholder="Enter recipient's user ID">

                <label for="sender_id">Sender ID</label>
                <input type="text" 
                       id="sender_id" 
                       name="sender_id" 
                       value="{{ old('sender_id') }}"
                       required 
                       placeholder="Enter sender's user ID">

                <label for="message">Message</label>
                <select id="message" name="message" required>
                <option value="">Select Message</option>
                <option value="pending" {{ old('message') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ old('message') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ old('message') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>

                <button type="submit" style="background: #753B2F; color: #FEFDF1;">📧 Send Notification</button>
            </form>

            <!-- Recent Notifications Log -->
            <h3 style="margin-top: 40px; color: #000000;">Recent Notifications</h3>
            <table style="margin-top: 15px;">
                <thead>
                    <tr><th>Date</th><th>Recipient</th><th>Subject</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse($recent_notifications as $notification)
                        <tr>
                            <td>{{ $notification->created_at->format('M d, H:i') }}</td>
                            <td>{{ $notification->recipient_id }}</td>
                            <td>{{ Str::limit($notification->subject, 30) }}</td>
                            <td><span style="color: #155724;">Sent</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="padding: 20px;">No notifications sent yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <!-- Blood Unit Registration -->
        <section id="unit" class="staff">
            <h1>Blood Unit Management</h1>
            
            <!-- CRUD Buttons -->
            <div style="margin-bottom: 20px;">
                <button onclick="showForm('create-unit')" style="background: #28a745; color: white;">➕ Create Unit</button>
                <button onclick="refreshTable('units')" style="background: #28a; color: white;">🔄 Refresh</button>
            </div>

            @if (session('unit-created'))
                <div style="background: #d4edda; color: #28a745; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                    ✅ New blood unit registered!
                </div>
            @endif

            <!-- Create Unit Form (Hidden by default) -->
            <div id="create-unit-form" style="background: #f4f194; padding: 20px; border-radius: 8px; margin-bottom: 20px; display: none;">
                <h3 style="margin-top: 0;">Register New Blood Unit</h3>
                <form action="{{ route('staff.unit.store') }}" method="POST">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div>
                             <label>Donation ID</label>
                             <input type="text" name="donation_id" required placeholder="Enter Donation ID">
                        </div>
                        <div>
                            <label>Blood Type</label>
                            <input type="text" name="blood_type" required placeholder="A+, O-, etc.">
                        </div>
                         <div>
                           <label>Request ID</label>
                           <input type="text" name="request_id" required placeholder="Enter Request ID">
                        </div>
                        <div>
                            <label>Volume (ml)</label>
                            <input type="number" name="volume" min="350" max="500" value="450" required>
                        </div>
                        <div>
                            <label>Expiry Date</label>
                            <input type="date" name="expiry_date" required>
                        </div>
                    </div>
                    <button type="submit" style="margin-top: 20px;">💉 Register Unit</button>
                    <button type="button" onclick="hideForm('create-unit')" style="margin-left: 10px;">Cancel</button>
                </form>
            </div>

            <!-- Edit Unit Form (Hidden by default) -->
<div id="edit-unit-form" style="background: #f4f194; padding: 20px; border-radius: 8px; margin-bottom: 20px; display: none;">
    <h3 style="margin-top: 0;">Edit Blood Unit</h3>
    <form id="edit-unit-form-tag" action="" method="POST">
        @csrf
        @method('PUT')
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div>
                <label>Donation ID</label>
                <input type="text" name="donation_id" id="edit-donation-id" required>
            </div>
            <div>
                <label>Blood Type</label>
                <input type="text" name="blood_type" id="edit-blood-type" required>
            </div>
            <div>
                <label>Request ID</label>
                <input type="text" name="request_id" id="edit-request-id">
            </div>
            <div>
                <label>Volume (ml)</label>
                <input type="number" name="volume" id="edit-volume" min="350" max="500" required>
            </div>
            <div>
                <label>Expiry Date</label>
                <input type="date" name="expiry_date" id="edit-expiry-date" required>
            </div>
        </div>
        <button type="submit" style="margin-top: 20px;">💾 Save Changes</button>
        <button type="button" onclick="document.getElementById('edit-unit-form').style.display='none'" style="margin-left: 10px;">Cancel</button>
    </form>
</div>

            <!-- Blood Units Table -->
            <table>
                <thead>
                    <tr>
                        <th>Unit ID</th>
                        <th>Donation ID</th>
                        <th>Blood Type</th>
                        <th>Request ID</th>
                        <th>Volume</th>
                        <th>Expires</th>
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
                            <td style="color: {{ $unit->expiry_date < now() ? '#dc3545' : '#28a745' }}">
                                {{ $unit->expiry_date?->format('M d') }}
                            </td>
                            <td>
                                <button onclick="editUnit({{ $unit->id }})" style="background: #ffc107;">Edit</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" style="text-align: center; padding: 40px;">No blood units registered</td></tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </div>

    <!-- Staff Tab Switcher JavaScript (same as donor dashboard) -->
    <script>
    'use strict';

    // Get all sections and nav links
    const sections = document.querySelectorAll('.staff');
    const navLinks = document.querySelectorAll('.staffbar a[href="#"]');
    const bloodUnits = @json($blood_units);

    function showForm(id) {
        // Find the element by adding '-form' to the passed id to match your HTML
        document.getElementById(id + '-form').style.display = 'block';
    }

    function hideForm(id) {
        // Set display to 'none' to hide it again
        document.getElementById(id + '-form').style.display = 'none';
    }
    
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
 
      function editUnit(id) {
        const unit = bloodUnits.find(u => u.id === id);
        if (!unit) return alert('Unit not found.');

        // Populate form fields
        document.getElementById('edit-donation-id').value  = unit.donation_id;
        document.getElementById('edit-blood-type').value   = unit.blood_type;
        document.getElementById('edit-request-id').value   = unit.request_id ?? '';
        document.getElementById('edit-volume').value       = unit.volume;
        document.getElementById('edit-expiry-date').value  = unit.expiry_date?.split('T')[0] ?? '';

        // Set form action to the correct route
        document.getElementById('edit-unit-form-tag').action = `/staff/unit/${id}`;

        // Show the form
        document.getElementById('edit-unit-form').style.display = 'block';
        document.getElementById('edit-unit-form').scrollIntoView({ behavior: 'smooth' });
    }

     // Utility functions
    function refreshTable(table) {
        location.reload();
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
        if (e.key === '1') showSection('staffaccount');
        if (e.key === '2') showSection('donationdb');
        if (e.key === '3') showSection('requestdb');
        if (e.key === '4') showSection('notify');
        if (e.key === '5') showSection('unit');
    });

    // Auto-show Account tab on load
    window.addEventListener('load', () => {
        showSection('staffaccount');
    });

    // Hash-based tab switching (e.g., /dashboard#donation)
    window.addEventListener('hashchange', function() {
        const hash = window.location.hash.replace('#', '');
        if (hash && document.getElementById(hash)) {
            showSection(hash);
        }
    });

    </script>
