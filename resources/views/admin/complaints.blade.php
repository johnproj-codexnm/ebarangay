@extends('admin.layouts.admin-layout')

@section('content')

<h2>Complaints Management</h2>

<style>
    .layout-wrapper {
        display: flex;
        gap: 24px;
        align-items: flex-start;
    }
    .table-container {
        flex: 1;
        min-width: 0; 
        overflow-x: auto;
    }
    #complaintDetails {
        width: 450px;
        flex-shrink: 0;
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: var(--radius-xl);
        box-shadow: var(--glass-shadow);
        border: 1px solid var(--glass-border);
        padding: 32px;
        position: sticky;
        top: 24px;
        max-height: calc(100vh - 48px);
        overflow-y: auto;
        box-sizing: border-box;
    }
    #complaintDetails::-webkit-scrollbar { width: 6px; }
    #complaintDetails::-webkit-scrollbar-track { background: transparent; }
    #complaintDetails::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 10px; }
    .clickable-row {
        cursor: pointer;
        transition: background 0.2s;
    }
    .clickable-row:hover {
        background-color: rgba(255,255,255,0.05) !important;
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
    .detail-section h4 {
        margin-top: 0;
        color: #1e293b;
        font-size: 18px;
        border-bottom: 1px solid var(--glass-border);
        padding-bottom: 12px;
        margin-bottom: 20px;
    }
    .detail-row {
        display: flex;
        margin-bottom: 16px;
        font-size: 15px;
    }
    .detail-row b {
        color: var(--text-light);
        width: 110px;
        flex-shrink: 0;
        font-weight: 600;
    }
    .detail-row span {
        color: #1e293b;
        font-weight: 500;
        line-height: 1.5;
    }
    .action-btn {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        border: 1px solid rgba(239, 68, 68, 0.2);
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
        box-shadow: none;
    }
    .action-btn:hover {
        background: rgba(239, 68, 68, 0.2);
        color: #b91c1c;
        transform: translateY(-2px);
    }
    #messageBox {
        border: 1px solid var(--glass-border);
        border-radius: 12px;
        padding: 20px;
        height: 300px;
        overflow-y: auto;
        background: rgba(255, 255, 255, 0.5);
        margin-bottom: 16px;
    }
    #messageBox::-webkit-scrollbar { width: 4px; }
    #messageBox::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 4px; }
    .msg-admin b { color: #2563eb; }
    .msg-resident b { color: #16a34a; }
    .msg-bubble-admin {
        background: rgba(59, 130, 246, 0.1);
        color: #1e3a8a;
        border: 1px solid rgba(59, 130, 246, 0.2);
        display: inline-block;
        padding: 10px 16px;
        border-radius: 16px 16px 0 16px;
        margin-top: 6px;
        font-size: 14px;
        text-align: left;
        line-height: 1.4;
    }
    .msg-bubble-resident {
        background: rgba(16, 185, 129, 0.1);
        color: #064e3b;
        border: 1px solid rgba(16, 185, 129, 0.2);
        display: inline-block;
        padding: 10px 16px;
        border-radius: 16px 16px 16px 0;
        margin-top: 6px;
        font-size: 14px;
        line-height: 1.4;
    }
</style>

<div class="layout-wrapper">
    <!-- TABLE SECTION -->
    <div class="table-container card" style="margin-bottom: 0;">
        <div style="margin-bottom: 16px; display: flex; align-items: center; justify-content: space-between;">
            <h3 style="margin: 0; color: #1e293b; font-size: 20px;">Complaints List</h3>
            <select id="categoryFilter" onchange="filterComplaints()" style="width: auto; padding: 10px 16px; border-radius: 8px; font-size: 14px;">
                <option value="All">All Categories</option>
                <option value="Others">Others</option>
                <option value="Health and Safety">Health and Safety</option>
                <option value="Business and Commercial Concerns">Business and Commercial Concerns</option>
                <option value="Public Infrastructue and Hazards">Public Infrastructue and Hazards</option>
                <option value="Neighborhood Disputes (Katarungang Pambarangay)">Neighborhood Disputes (Katarungang Pambarangay)</option>
                <option value="Environment and Sanitation">Environment and Sanitation</option>
                <option value="Peace and Order (Public Disturbances)">Peace and Order (Public Disturbances)</option>
            </select>
        </div>
        <table width="100%" id="complaintsTable">
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Location</th>
                <th>Status</th>
                <th>Date</th>
                <th>Resident</th>
                <th>Contact</th>
                <th>Action</th>
            </tr>

            @foreach($complaints as $complaint)
            <tr class="clickable-row complaint-row" data-category="{{ $complaint['category'] }}" onclick="showComplaint(
                '{{ $complaint['$id'] }}',
                '{{ addslashes($complaint['title']) }}',
                '{{ addslashes($complaint['description']) }}',
                '{{ addslashes($complaint['category']) }}',
                '{{ addslashes($complaint['location']) }}',
                '{{ addslashes($complaint['status']) }}',
                '{{ addslashes($complaint['resident_name']) }}',
                '{{ addslashes($complaint['resident_contact']) }}',
                '{{ addslashes($complaint['resident_address']) }}'
            )">
                <td style="font-weight:600; color:#1e293b;">{{ $complaint['title'] }}</td>
                <td style="color:var(--text-light);">{{ $complaint['category'] }}</td>
                <td>
                    <a href="{{ $complaint['location'] }}" target="_blank" onclick="event.stopPropagation();" style="color:#2563eb; text-decoration:none; font-weight:600; font-size:14px; display:inline-flex; align-items:center; gap:6px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg> View
                    </a>
                </td>
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
                <td style="color:var(--text-light); font-size:13px; white-space:nowrap;">{{ \Carbon\Carbon::parse($complaint['date_submitted'])->format('M d, Y h:i A') }}</td>
                <td style="color:#1e293b;">{{ $complaint['resident_name'] }}</td>
                <td style="color:var(--text-light); font-size:13px;">{{ $complaint['resident_contact'] }}</td>

                <td>
                    <form method="POST" action="/admin/delete-complaint"
                        onsubmit="return confirm('Are you sure you want to delete this complaint?')"
                        onclick="event.stopPropagation();">
                        @csrf
                        <input type="hidden" name="complaint_id" value="{{ $complaint['$id'] }}">
                        <button class="action-btn">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>

    <!-- DETAILS SECTION -->
    <div id="complaintDetails" style="display:none;">
        
        <div class="detail-section">
            <h4>Complaint Details</h4>
            <div class="detail-row"><b>Title:</b> <span id="c_title"></span></div>
            <div class="detail-row"><b>Description:</b> <span id="c_description"></span></div>
            <div class="detail-row"><b>Category:</b> <span id="c_category"></span></div>
            <div class="detail-row"><b>Location:</b> <span id="c_location"></span></div>
            <div class="detail-row"><b>Status:</b> <span id="c_status" style="font-weight:600;"></span></div>
        </div>

        <div class="detail-section" style="margin-top: 24px;">
            <h4>Resident Information</h4>
            <div class="detail-row"><b>Name:</b> <span id="c_name"></span></div>
            <div class="detail-row"><b>Contact:</b> <span id="c_contact"></span></div>
            <div class="detail-row"><b>Address:</b> <span id="c_address"></span></div>
        </div>

        <div class="detail-section" style="margin-top: 24px;">
            <h4>Evidence</h4>
            <div id="evidenceSection">
                @foreach($evidence as $ev)
                <div class="evidenceItem" data-complaint="{{ $ev['complaint_id'] }}" style="display:none; text-align:center;">
                    <img src="https://sgp.cloud.appwrite.io/v1/storage/buckets/{{ env('APPWRITE_BUCKET_ID') }}/files/{{ $ev['image_id'] }}/view?project={{ env('APPWRITE_PROJECT_ID') }}&mode=admin"
                         style="max-width:100%; border-radius:8px; border:1px solid #e2e8f0; margin-top:10px;">
                </div>
                @endforeach
                <p id="noEvidence" style="color:#64748b; font-size:14px; font-style:italic;">No evidence uploaded.</p>
            </div>
        </div>

        <div class="detail-section" style="margin-top: 24px;">
            <h4>Update Status</h4>
            <form method="POST" action="/admin/update-status" style="display:flex; gap:10px;">
                @csrf
                <input type="hidden" name="complaint_id" id="status_complaint_id">
                <select name="status" style="flex:1;">
                    <option>Pending</option>
                    <option>Processing</option>
                    <option>Resolved</option>
                </select>
                <button type="submit" style="padding: 10px 16px;">Update</button>
            </form>
        </div>

        <div class="detail-section" style="margin-top: 32px;">
            <h4>Conversation</h4>
            <div id="messageBox"></div>

            <form id="adminMessageForm" style="margin-top: 10px;">
                @csrf
                <input type="hidden" id="message_complaint_id">
                <textarea id="adminMessageInput" required placeholder="Type a reply..." style="height:80px; resize:none; margin-bottom:10px;"></textarea>
                <button type="submit" style="width:100%;">Send Message</button>
            </form>
        </div>

    </div>
</div>

<script>
let currentComplaint = null;

function filterComplaints() {
    let filter = document.getElementById('categoryFilter').value;
    let rows = document.querySelectorAll('.complaint-row');
    
    rows.forEach(row => {
        let category = row.getAttribute('data-category');
        if (filter === 'All' || category === filter) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function showComplaint(id,title,description,category,location,status,name,contact,address){
    currentComplaint = id;
    
    let detailsPanel = document.getElementById("complaintDetails");
    detailsPanel.style.display="block";
    detailsPanel.scrollTop = 0;
    
    document.getElementById("c_title").innerText = title;
    document.getElementById("c_description").innerText = description;
    document.getElementById("c_category").innerText = category;
    document.getElementById("c_location").innerText = location;
    document.getElementById("c_status").innerText = status;
    
    document.getElementById("c_name").innerText = name;
    document.getElementById("c_contact").innerText = contact;
    document.getElementById("c_address").innerText = address;
    
    document.getElementById("message_complaint_id").value = id;
    document.getElementById("status_complaint_id").value = id;

    let evidenceItems = document.querySelectorAll(".evidenceItem");
    let foundEvidence = false;
    
    evidenceItems.forEach(item => {
        if(item.dataset.complaint === id){
            item.style.display="block";
            foundEvidence = true;
        } else {
            item.style.display="none";
        }
    });
    
    document.getElementById("noEvidence").style.display = foundEvidence ? "none" : "block";
    
    loadMessages();
}

function loadMessages(){
    if(!currentComplaint) return;
    
    fetch("/admin/messages/" + currentComplaint)
    .then(res => res.json())
    .then(data => {
        let box = document.getElementById("messageBox");
        box.innerHTML = "";
        
        if(data.length === 0){
            box.innerHTML = "<p style='color:#64748b; font-size:14px; text-align:center; padding-top:20px;'>No messages yet.</p>";
            return;
        }
        
        data.forEach(msg => {
            let div = document.createElement("div");
            div.style.marginBottom = "12px";
            
            if(msg.sender_role === "admin"){
                div.innerHTML = `
                <div class="msg-admin" style="text-align:right;">
                    <b style="font-size:12px;">Admin</b><br>
                    <div class="msg-bubble-admin">${msg.message}</div>
                </div>
                `;
            } else {
                div.innerHTML = `
                <div class="msg-resident" style="text-align:left;">
                    <b style="font-size:12px;">Resident</b><br>
                    <div class="msg-bubble-resident">${msg.message}</div>
                </div>
                `;
            }
            box.appendChild(div);
        });
        
        box.scrollTop = box.scrollHeight;
    });
}

document.getElementById("adminMessageForm").addEventListener("submit", function(e){
    e.preventDefault();
    let message = document.getElementById("adminMessageInput").value;
    
    fetch("/admin/message", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            complaint_id: currentComplaint,
            message: message
        })
    }).then(() => {
        document.getElementById("adminMessageInput").value = "";
        loadMessages();
    });
});

setInterval(loadMessages, 3000);
</script>

@endsection