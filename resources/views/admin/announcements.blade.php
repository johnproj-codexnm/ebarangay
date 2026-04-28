@extends('admin.layouts.admin-layout')

@section('content')

<h2>Announcements</h2>

@if(session('success'))
<div style="color:#16a34a; background:rgba(22, 163, 74, 0.1); padding:16px 20px; border-radius:12px; border:1px solid rgba(22, 163, 74, 0.2); margin-bottom:24px; font-size:15px; display:flex; align-items:center; gap:8px;">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
    {{ session('success') }}
</div>
@endif

<div class="card" style="margin-bottom: 40px;">
    <div style="border-bottom: 1px solid var(--glass-border); padding-bottom: 20px; margin-bottom: 24px;">
        <h3 style="margin:0; font-size: 20px; font-weight: 700; color: #1e293b;">Create New Announcement</h3>
        <p style="margin: 8px 0 0 0; color: var(--text-light); font-size: 14px;">Broadcast important information and updates to all residents.</p>
    </div>

    <form method="POST" action="/admin/announcement">
        @csrf
        
        <div style="display: flex; gap: 32px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 280px;">
                <div style="margin-bottom: 24px;">
                    <label>Announcement Title</label>
                    <input type="text" name="title" placeholder="e.g., Community Cleanup Drive" required>
                </div>
            </div>
            
            <div style="flex: 2; min-width: 300px;">
                <div style="margin-bottom: 24px;">
                    <label>Detailed Content</label>
                    <textarea name="content" placeholder="Provide all the necessary details here..." required style="height:200px; resize:vertical;"></textarea>
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; border-top: 1px solid var(--glass-border); padding-top: 24px; margin-top: 16px;">
            <button type="submit" style="display: flex; align-items: center; gap: 8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                Post Announcement
            </button>
        </div>
    </form>
</div>


<div class="card" style="width: 100%;">
    <h3 style="margin-top:0; margin-bottom:24px; color:#1e293b; font-size: 20px;">Announcement List</h3>

    <table>
        <tr>
            <th>Title</th>
            <th>Content</th>
            <th>Date</th>
            <th width="100">Action</th>
        </tr>

        @foreach($announcements as $ann)
        <tr>
            <td style="font-weight:600; color:#1e293b;">{{ $ann['title'] }}</td>
            <td style="color:#475569; line-height:1.5;">{{ $ann['content'] }}</td>
            <td style="color:var(--text-light); font-size:13px; white-space:nowrap;">{{ \Carbon\Carbon::parse($ann['created_at'])->format('M d, Y h:i A') }}</td>
            <td>
                <form method="POST" action="/admin/announcement/delete" onsubmit="return confirm('Are you sure you want to delete this announcement?')">
                    @csrf
                    <input type="hidden" name="id" value="{{ $ann['$id'] }}">
                    <button style="background:rgba(239, 68, 68, 0.1); color:#dc2626; padding:8px 16px; border-radius:8px; font-size:13px; font-weight:600; border:1px solid rgba(239, 68, 68, 0.2); width:100%; transition:all 0.2s; box-shadow:none;">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>

@endsection