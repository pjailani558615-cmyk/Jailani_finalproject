<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> 
</head>
<body>
    <div class="adminbar">
        <img src="{{ asset('pic/MORO.jpg') }}" alt="MORO logo">
        <a href="#" onclick="showSection('adminaccount')" class="nav-link">1 Admin Account</a>
        <a href="#" onclick="showSection('generalreport')" class="nav-link">2 General Report</a>
        <a href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    <div class="admin-content">
        <section id="adminaccount" class="admin active">
            <h1>Admin Account</h1>
            <div style="background: #FEFDF1; padding: 20px; border-radius: 8px; margin: 20px 0;">
        <h3>Welcome, {{ auth()->user()->name }}!</h3>
        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
        <p><strong>Age:</strong> {{ $admin->age ?? auth()->user()->age }} years</p>
        <p><strong>Gender:</strong> {{ ucfirst(auth()->user()->sex) }}</p>
        <p><strong>Member since:</strong> {{ ( auth()->user()->created_at)->format('M d, Y') }}</p>
</div>
        </section>

        <section id="generalreport" class="admin">
            <h1>MORO General Report</h1>
            <form method="POST" action="{{ route('report.pdf') }}" style="display: inline;">
    @csrf
    <button type="submit" style="background: #753B2F; color: #FEFDF1; hover: #000000; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">Generate PDF Report</button>
</form>

            <h3>Admin:</h3>
            <table>
                <thead>
                    <tr>
                        <th>Admin ID</th>
                        <th>Admin Name</th>
                        <th>Age</th>
                        <th>Sex</th>
                        <th>Email</th>
                        <th>Password</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Example row using Blade (will be filled from controller) -->
                    {{-- @foreach ($admins as $admin)
                        <tr>
                            <td>{{ $admin->id }}</td>
                            <td>{{ $admin->name }}</td>
                            <td>{{ $admin->age }}</td>
                            <td>{{ $admin->sex }}</td>
                            <td>{{ $admin->email }}</td>
                            <td>•••••••</td>
                        </tr>
                    @endforeach --}}
                </tbody>
            </table>

            <h3>Staff:</h3>
            <table>
                <thead>
                    <tr>
                        <th>Staff ID</th>
                        <th>Staff Name</th>
                        <th>Age</th>
                        <th>Sex</th>
                        <th>Email</th>
                        <th>Password</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach ($staffs as $staff)
                        <tr>
                            <td>{{ $staff->id }}</td>
                            <td>{{ $staff->name }}</td>
                            <td>{{ $staff->age }}</td>
                            <td>{{ $staff->sex }}</td>
                            <td>{{ $staff->email }}</td>
                            <td>•••••••</td>
                        </tr>
                    @endforeach --}}
                </tbody>
            </table>

            <h3>Users:</h3>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>User Name</th>
                        <th>Age</th>
                        <th>Sex</th>
                        <th>Email</th>
                        <th>Password</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->age }}</td>
                            <td>{{ $user->sex }}</td>
                            <td>{{ $user->email }}</td>
                            <td>•••••••</td>
                        </tr>
                    @endforeach --}}
                </tbody>
            </table>

            <h3>Donors:</h3>
            <table>
                <thead>
                    <tr>
                        <th>Donor ID</th>
                        <th>User ID</th>
                        <th>Donor Name</th>
                        <th>Sex</th>
                        <th>Age</th>
                        <th>Phone No</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Blood Type</th>
                        <th>Weight</th>
                        <th>Last Donation</th>
                        <th>Has Disease?</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach ($donors as $donor)
                        <tr>
                            <td>{{ $donor->id }}</td>
                            <td>{{ $donor->user_id }}</td>
                            <td>{{ $donor->name }}</td>
                            <td>{{ $donor->sex }}</td>
                            <td>{{ $donor->age }}</td>
                            <td>{{ $donor->phone }}</td>
                            <td>{{ $donor->email }}</td>
                            <td>{{ $donor->address }}</td>
                            <td>{{ $donor->blood_type }}</td>
                            <td>{{ $donor->weight }}</td>
                            <td>{{ $donor->last_donation }}</td>
                            <td>{{ $donor->has_disease ? 'Yes' : 'No' }}</td>
                        </tr>
                    @endforeach --}}
                </tbody>
            </table>

            <h3>Requestors:</h3>
            <table>
                <thead>
                    <tr>
                        <th>Requester ID</th>
                        <th>User ID</th>
                        <th>Requester Type</th>
                        <th>Patient Name</th>
                        <th>Patient Age</th>
                        <th>Patient Sex</th>
                        <th>Phone No</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Required Blood Type</th>
                        <th>Quantity</th>
                        <th>Urgency</th>
                        <th>Date&Time of Request</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach ($requestors as $requestor)
                        <tr>
                            <td>{{ $requestor->id }}</td>
                            <td>{{ $requestor->user_id }}</td>
                            <td>{{ $requestor->requester_type }}</td>
                            <td>{{ $requestor->patient_name }}</td>
                            <td>{{ $requestor->patient_age }}</td>
                            <td>{{ $requestor->patient_sex }}</td>
                            <td>{{ $requestor->phone }}</td>
                            <td>{{ $requestor->email }}</td>
                            <td>{{ $requestor->address }}</td>
                            <td>{{ $requestor->required_blood_type }}</td>
                            <td>{{ $requestor->quantity }}</td>
                            <td>{{ $requestor->urgency }}</td>
                            <td>{{ $requestor->created_at }}</td>
                        </tr>
                    @endforeach --}}
                </tbody>
            </table>

            <h3>Blood Units:</h3>
            <table>
                <thead>
                    <tr>
                        <th>Blood Unit ID</th>
                        <th>Donor ID</th>
                        <th>Blood Type</th>
                        <th>Requestor ID</th>
                        <th>Expiry Date</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach ($bloodUnits as $unit)
                        <tr>
                            <td>{{ $unit->id }}</td>
                            <td>{{ $unit->donor_id }}</td>
                            <td>{{ $unit->blood_type }}</td>
                            <td>{{ $unit->requestor_id }}</td>
                            <td>{{ $unit->expiry_date }}</td>
                        </tr>
                    @endforeach --}}
                </tbody>
            </table>
        </section>
    </div>

    <script>
// ===== DASHBOARD TAB SWITCHER =====
(function() {
    'use strict';

    // Get all sections and nav links
    const sections = document.querySelectorAll('.admin');
    const navLinks = document.querySelectorAll('.adminbar a[href="#"]');
    
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
        if (e.key === '1') showSection('adminaccount');
        if (e.key === '2') showSection('generalreport');
    });

    // Auto-show Account tab on load
    window.addEventListener('load', () => {
        showSection('adminaccount');
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