@extends('admin.layouts.admin-layout')

@section('content')

<h2>Admin Dashboard</h2>

<style>
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
    }
    .stat-card {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        padding: 32px 24px;
        border-radius: var(--radius-xl);
        box-shadow: var(--glass-shadow);
        border: 1px solid var(--glass-border);
        display: flex;
        flex-direction: column;
        justify-content: center;
        transition: transform 0.2s, box-shadow 0.2s, background 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(31, 38, 135, 0.15);
        background: rgba(255, 255, 255, 0.85);
    }
    .stat-card h3 {
        margin: 0;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-light) !important;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .stat-card p {
        margin: 12px 0 0 0;
        font-size: 36px;
        font-weight: 800;
        color: #1e293b;
    }
    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 99px;
        font-size: 12px;
        font-weight: 600;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
</style>

<div class="dashboard-grid">
    <div class="stat-card">
        <h3>Total Complaints</h3>
        <p>{{ $totalComplaints }}</p>
    </div>

    <div class="stat-card">
        <h3>Total Residents</h3>
        <p>{{ $totalResidents }}</p>
    </div>

    <div class="stat-card">
        <h3>Pending</h3>
        <p style="color: #f59e0b;">{{ $pending }}</p>
    </div>

    <div class="stat-card">
        <h3>Processing</h3>
        <p style="color: #3b82f6;">{{ $processing }}</p>
    </div>

    <div class="stat-card">
        <h3>Resolved</h3>
        <p style="color: #10b981;">{{ $resolved }}</p>
    </div>
</div>

<div class="card">
    <h3 style="margin-top:0; margin-bottom:24px; color:#1e293b;">Recent Complaints</h3>
    
    <table>
        <tr>
            <th>Title</th>
            <th>Category</th>
            <th>Status</th>
            <th>Location</th>
        </tr>

        @foreach($complaintList as $complaint)
        <tr>
            <td style="font-weight:600; color:#1e293b;">{{ $complaint['title'] }}</td>
            <td style="color:#475569;">{{ $complaint['category'] }}</td>
            <td>
                @if(strtolower($complaint['status']) == 'pending')
                    <span class="status-badge" style="background:rgba(245, 158, 11, 0.1);color:#d97706;border-color:rgba(245,158,11,0.2);">{{ $complaint['status'] }}</span>
                @elseif(strtolower($complaint['status']) == 'processing')
                    <span class="status-badge" style="background:rgba(59, 130, 246, 0.1);color:#2563eb;border-color:rgba(59,130,246,0.2);">{{ $complaint['status'] }}</span>
                @elseif(strtolower($complaint['status']) == 'resolved')
                    <span class="status-badge" style="background:rgba(16, 185, 129, 0.1);color:#059669;border-color:rgba(16,185,129,0.2);">{{ $complaint['status'] }}</span>
                @else
                    {{ $complaint['status'] }}
                @endif
            </td>
            <td>
                <a href="{{ $complaint['location'] }}" target="_blank" style="color:#2563eb; text-decoration:none; font-size:14px; font-weight:600; display:inline-flex; align-items:center; gap:6px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> View
                </a>
            </td>
        </tr>
        @endforeach

    </table>
</div>

@endsection