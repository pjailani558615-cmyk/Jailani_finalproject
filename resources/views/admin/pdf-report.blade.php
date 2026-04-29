<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 13px; color: #222; padding: 30px; }

        .header { text-align: center; border-bottom: 3px solid #753B2F; padding-bottom: 16px; margin-bottom: 24px; }
        .header h1 { font-size: 22px; color: #753B2F; }
        .header p  { font-size: 12px; color: #666; margin-top: 4px; }

        .section { margin-bottom: 28px; }
        .section h2 { font-size: 15px; background: #753B2F; color: #FEFDF1;
                      padding: 6px 12px; border-radius: 4px; margin-bottom: 12px; }

        .stat-grid { display: table; width: 100%; border-collapse: collapse; }
        .stat-box  { display: table-cell; width: 25%; text-align: center;
                     border: 1px solid #ddd; padding: 14px 8px; background: #fafafa; }
        .stat-box .num   { font-size: 26px; font-weight: bold; color: #753B2F; }
        .stat-box .label { font-size: 11px; color: #666; margin-top: 4px; }

        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th { background: #753B2F; color: #FEFDF1; padding: 7px 10px; text-align: left; }
        td { padding: 6px 10px; border-bottom: 1px solid #eee; }
        tr:nth-child(even) td { background: #f9f5f4; }

        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 11px; }
        .badge-available { background: #d4edda; color: #155724; }
        .badge-expired   { background: #f8d7da; color: #721c24; }
        .badge-used      { background: #d1ecf1; color: #0c5460; }

        .footer { margin-top: 30px; border-top: 1px solid #ccc; padding-top: 10px;
                  font-size: 11px; color: #888; text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Management On Reports of blood Offers — General Report</h1>
        <p>Generated: {{ $generated_at }}</p>
    </div>

    <!-- Summary stats -->
    <div class="section">
        <h2>System Overview</h2>
        <div class="stat-grid">
            <div class="stat-box"><div class="num">{{ $total_admins }}</div><div class="label">Total Admins</div></div>
            <div class="stat-box"><div class="num">{{ $total_users }}</div><div class="label">Total Users</div></div>
            <div class="stat-box"><div class="num">{{ $total_staff }}</div><div class="label">Staff Members</div></div>
            <div class="stat-box"><div class="num">{{ $total_donations }}</div><div class="label">Total Donations</div></div>
            <div class="stat-box"><div class="num">{{ $total_requests }}</div><div class="label">Blood Requests</div></div>
            <div class="stat-box"><div class="num">{{ $total_units }}</div><div class="label">Blood Units</div></div>
        </div>
    </div>

    <!-- Recent blood units -->
    <div class="section">
        <h2>Recent Blood Units (Last 10)</h2>
        <table>
            <tr><th>Donor ID</th><th>Blood Type</th><th>Request ID</th><th>Volume</th><th>Expiry</th></tr>
            @foreach($recent_units as $unit)
                <tr>
                    <td>{{ $unit->donation->id ?? 'N/A' }}</td>
                    <td>{{ $unit->blood_type }}</td>
                    <td>{{ $unit->request->id ?? 'N/A' }}</td>
                    <td>{{ $unit->volume }} ml</td>
                    <td>{{ \Carbon\Carbon::parse($unit->expiry_date)->format('M d, Y') }}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <!-- Recent donors -->
    <div class="section">
        <h2>Recent Donors (Last 10)</h2>
        <table>
            <tr><th>Name</th><th>Email</th><th>Age</th><th>Sex</th><th>Joined</th></tr>
            @foreach($recent_donors as $donor)
                <tr>
                    <td>{{ $donor->name }}</td>
                    <td>{{ $donor->email }}</td>
                    <td>{{ $donor->age }}</td>
                    <td>{{ ucfirst($donor->sex) }}</td>
                    <td>{{ $donor->created_at->format('M d, Y') }}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="footer">
        Management On Records of Blood Offers &bull; Confidential &bull; {{ $generated_at }}
    </div>

</body>
</html>