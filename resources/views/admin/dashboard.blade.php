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

<div class="card chart-container" style="margin-bottom: 24px; display: flex; flex-direction: column; min-height: 380px;">
    <div class="chart-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0; color:#1e293b; font-size: 16px;">Complaints Overview</h3>
        <select id="chartDateRange" style="width: auto; padding: 8px 12px; border-radius: var(--radius-md); border: 1px solid var(--glass-border); background: rgba(255,255,255,0.7); font-size: 13px; font-weight: 500; color: #475569; cursor: pointer; outline: none;">
            <option value="7">Last 7 Days</option>
            <option value="30" selected>Last 30 Days</option>
            <option value="90">Last 3 Months</option>
            <option value="all">All Time</option>
        </select>
    </div>
    <div style="flex-grow: 1; position: relative; width: 100%;">
        <canvas id="complaintsChart"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rawData = @json($complaintList);
    const complaints = Array.isArray(rawData) ? rawData : [];

    // Format date string to YYYY-MM-DD for grouping
    const formatDate = (dateString) => {
        const d = new Date(dateString);
        return isNaN(d.getTime()) ? null : d.toISOString().split('T')[0];
    };

    // Helper to format date for display (e.g. "May 12")
    const formatLabel = (dateString) => {
        const d = new Date(dateString);
        const options = { month: 'short', day: 'numeric' };
        return d.toLocaleDateString('en-US', options);
    };

    let chartInstance = null;

    function renderChart(daysFilter) {
        const ctx = document.getElementById('complaintsChart').getContext('2d');
        
        let filteredComplaints = complaints;
        const now = new Date();
        
        if (daysFilter !== 'all') {
            const days = parseInt(daysFilter);
            const cutoffDate = new Date();
            cutoffDate.setDate(now.getDate() - days);
            // reset time for accurate day comparison
            cutoffDate.setHours(0,0,0,0);
            
            filteredComplaints = complaints.filter(c => {
                const cDate = new Date(c['$createdAt']);
                return cDate >= cutoffDate;
            });
        }

        // Group by date string YYYY-MM-DD
        const grouped = {};
        filteredComplaints.forEach(c => {
            const d = formatDate(c['$createdAt']);
            if (d) {
                grouped[d] = (grouped[d] || 0) + 1;
            }
        });

        let labels = [];
        let dataPoints = [];

        if (daysFilter === 'all') {
            labels = Object.keys(grouped).sort();
            if (labels.length === 0) {
                // If no data at all, provide a default empty chart state
                const today = new Date().toISOString().split('T')[0];
                labels = [today];
                dataPoints = [0];
            } else {
                dataPoints = labels.map(l => grouped[l]);
            }
        } else {
            const days = parseInt(daysFilter);
            for (let i = days - 1; i >= 0; i--) {
                const d = new Date();
                d.setDate(now.getDate() - i);
                const dateStr = d.toISOString().split('T')[0];
                labels.push(dateStr);
                dataPoints.push(grouped[dateStr] || 0);
            }
        }

        // Convert YYYY-MM-DD labels to "Mon DD"
        const displayLabels = labels.map(l => formatLabel(l));

        const gradient = ctx.createLinearGradient(0, 0, 0, 350);
        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.35)');
        gradient.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

        if (chartInstance) {
            chartInstance.destroy();
        }

        chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: displayLabels,
                datasets: [{
                    label: 'Complaints',
                    data: dataPoints,
                    borderColor: '#2563eb',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#2563eb',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(30, 41, 59, 0.95)',
                        titleFont: { size: 13, family: 'Inter', weight: 'normal' },
                        bodyFont: { size: 14, weight: 'bold', family: 'Inter' },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' Complaints';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: { family: 'Inter', size: 11 },
                            color: '#64748b',
                            maxRotation: 45,
                            minRotation: 0,
                            autoSkip: true,
                            maxTicksLimit: 10
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(226, 232, 240, 0.6)',
                            drawBorder: false,
                            borderDash: [4, 4]
                        },
                        ticks: {
                            font: { family: 'Inter', size: 11 },
                            color: '#64748b',
                            stepSize: 1,
                            beginAtZero: true,
                            precision: 0
                        },
                        min: 0
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
            }
        });
    }

    renderChart(document.getElementById('chartDateRange').value);

    document.getElementById('chartDateRange').addEventListener('change', function(e) {
        renderChart(e.target.value);
    });
});
</script>


<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h3 style="margin:0; color:#1e293b;">Recent Complaints</h3>
        <select id="tableDateRange" style="width: auto; padding: 8px 12px; border-radius: var(--radius-md); border: 1px solid var(--glass-border); background: rgba(255,255,255,0.7); font-size: 13px; font-weight: 500; color: #475569; cursor: pointer; outline: none;">
            <option value="7">Last 7 Days</option>
            <option value="30">Last 30 Days</option>
            <option value="90">Last 3 Months</option>
            <option value="all" selected>All Time</option>
        </select>
    </div>
    
    <table>
        <tr>
            <th>Date</th>
            <th>Title</th>
            <th>Category</th>
            <th>Status</th>
            <th>Location</th>
        </tr>

        @foreach(array_reverse($complaintList) as $complaint)
        <tr class="complaint-row" data-date="{{ $complaint['$createdAt'] ?? '' }}">
            <td style="color:#64748b; font-size:13px;">
                @if(isset($complaint['$createdAt']))
                    {{ \Carbon\Carbon::parse($complaint['$createdAt'])->format('M d, Y') }}
                @else
                    N/A
                @endif
            </td>
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
                @if($complaint['location'] == 'Location not available')
                    <span style="color:#64748b; font-size:14px; font-weight:500; font-style:italic;">Check Description</span>
                @else
                    <a href="{{ $complaint['location'] }}" target="_blank" style="color:#2563eb; text-decoration:none; font-size:14px; font-weight:600; display:inline-flex; align-items:center; gap:6px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> View
                    </a>
                @endif
            </td>
        </tr>
        @endforeach

    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableSelect = document.getElementById('tableDateRange');
    const rows = document.querySelectorAll('.complaint-row');

    function filterTable(daysFilter) {
        const now = new Date();
        let cutoffDate = null;

        if (daysFilter !== 'all') {
            const days = parseInt(daysFilter);
            cutoffDate = new Date();
            cutoffDate.setDate(now.getDate() - days);
            cutoffDate.setHours(0,0,0,0);
        }

        rows.forEach(row => {
            if (daysFilter === 'all') {
                row.style.display = '';
                return;
            }

            const dateStr = row.getAttribute('data-date');
            if (!dateStr) {
                row.style.display = 'none';
                return;
            }

            const cDate = new Date(dateStr);
            if (cDate >= cutoffDate) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    tableSelect.addEventListener('change', function(e) {
        filterTable(e.target.value);
    });
});
</script>

@endsection