@extends('layouts.app')

@section('header_title', Auth::user()->isSuperAdmin() ? 'Global Multi-Org Management' : 'Organization Staff Management')

@section('content')
<div style="display:grid;grid-template-columns: 2fr 1fr; gap: 24px; align-items: start;">

    {{-- Left Side: Users List --}}
    <div class="card" style="padding:0; overflow:hidden;">
        <div style="padding:16px 20px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between; background:#fafbff;">
            <div>
                <h3 style="font-size:14px; font-weight:700; color:#1e293b;">
                    <i class="fa-solid fa-users-gear" style="color:#6366f1; margin-right:6px;"></i> Active Users & Staff
                </h3>
                <p style="font-size:11px; color:#94a3b8; margin-top:2px;">
                    @if(Auth::user()->isSuperAdmin())
                        Overview of all registered users across all organizations.
                    @else
                        Manage staff assigned to <b>{{ Auth::user()->organization->name }}</b>.
                    @endif
                </p>
            </div>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Organization</th>
                    <th>Role / Title</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td>
                        <span style="font-weight:700; color:#1e293b; display:block;">{{ $u->name }}</span>
                        @if($u->isSuperAdmin())
                            <span style="font-size:9px; color:#ef4444; font-weight:700; text-transform:uppercase;">Global SuperAdmin</span>
                        @endif
                    </td>
                    <td><span style="font-family:monospace; color:#475569;">{{ $u->email }}</span></td>
                    <td>
                        @if($u->organization)
                            <span style="font-weight:600; color:#4f46e5;">{{ $u->organization->name }}</span>
                        @else
                            <span style="color:#94a3b8; font-style:italic;">No Org (SuperAdmin)</span>
                        @endif
                    </td>
                    <td>
                        @if($u->isSuperAdmin())
                            <span class="badge b-high">SuperAdmin</span>
                        @elseif($u->isAdmin())
                            <span class="badge b-medium">Org Admin</span>
                        @else
                            <span class="badge b-low">Staff ({{ $u->staff_role ?: 'Sales' }})</span>
                        @endif
                    </td>
                    <td style="text-align:right;">
                        <div style="display:inline-flex; gap:6px;">
                            {{-- Edit Button (triggers inline form or basic popup modal toggle via simple javascript) --}}
                            <button onclick="openEditModal({{ json_encode($u) }})" class="btn btn-sm btn-light btn-icon" title="Edit User">
                                <i class="fa-solid fa-pen" style="font-size:10px; color:#475569;"></i>
                            </button>

                            <form action="{{ route('users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')" style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light btn-icon" title="Delete User">
                                    <i class="fa-solid fa-trash" style="font-size:10px; color:#be123c;"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; color:#94a3b8; padding:32px;">No users or staff managed yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Right Side: Create User Form / Create Org Form --}}
    <div style="display:flex; flex-direction:column; gap:24px;">

        {{-- Add User Card --}}
        <div class="card" style="padding:20px;">
            <h3 style="font-size:13px; font-weight:700; color:#1e293b; margin-bottom:16px; display:flex; align-items:center; gap:6px;">
                <i class="fa-solid fa-user-plus" style="color:#059669;"></i> Add New User / Staff
            </h3>

            <form action="{{ route('users.store') }}" method="POST" style="display:flex; flex-direction:column; gap:12px; margin:0;">
                @csrf
                <div>
                    <label style="display:block; font-size:10px; font-weight:700; text-transform:uppercase; color:#64748b; margin-bottom:4px;">Full Name</label>
                    <input type="text" name="name" required placeholder="e.g. David Miller" style="width:100%; padding:8px 10px; border-radius:8px; border:1px solid #cbd5e1; font-size:12px; outline:none;">
                </div>

                <div>
                    <label style="display:block; font-size:10px; font-weight:700; text-transform:uppercase; color:#64748b; margin-bottom:4px;">Email Address</label>
                    <input type="email" name="email" required placeholder="e.g. david@corp.com" style="width:100%; padding:8px 10px; border-radius:8px; border:1px solid #cbd5e1; font-size:12px; outline:none;">
                </div>

                <div>
                    <label style="display:block; font-size:10px; font-weight:700; text-transform:uppercase; color:#64748b; margin-bottom:4px;">Password</label>
                    <input type="password" name="password" required placeholder="••••••••" style="width:100%; padding:8px 10px; border-radius:8px; border:1px solid #cbd5e1; font-size:12px; outline:none;">
                </div>

                @if(Auth::user()->isSuperAdmin())
                    <div>
                        <label style="display:block; font-size:10px; font-weight:700; text-transform:uppercase; color:#64748b; margin-bottom:4px;">System Role</label>
                        <select name="role" required style="width:100%; padding:8px 10px; border-radius:8px; border:1px solid #cbd5e1; font-size:12px; outline:none; background:#fff;">
                            <option value="staff">Staff</option>
                            <option value="admin">Org Admin</option>
                            <option value="superadmin">Global SuperAdmin</option>
                        </select>
                    </div>

                    <div>
                        <label style="display:block; font-size:10px; font-weight:700; text-transform:uppercase; color:#64748b; margin-bottom:4px;">Organization Assignment</label>
                        <select name="organization_id" style="width:100%; padding:8px 10px; border-radius:8px; border:1px solid #cbd5e1; font-size:12px; outline:none; background:#fff;">
                            <option value="">No Organization (Global)</option>
                            @foreach($organizations as $org)
                                <option value="{{ $org->id }}">{{ $org->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display:block; font-size:10px; font-weight:700; text-transform:uppercase; color:#64748b; margin-bottom:4px;">Staff Specific Role / Title (Optional)</label>
                        <input type="text" name="staff_role" placeholder="e.g. Sales Executive, Lead Manager" style="width:100%; padding:8px 10px; border-radius:8px; border:1px solid #cbd5e1; font-size:12px; outline:none;">
                    </div>
                @else
                    {{-- Standard Org Admin only creates Staff under their organization --}}
                    <div>
                        <label style="display:block; font-size:10px; font-weight:700; text-transform:uppercase; color:#64748b; margin-bottom:4px;">Staff Specific Role / Title</label>
                        <input type="text" name="staff_role" required placeholder="e.g. Sales Executive, Technical Specialist" style="width:100%; padding:8px 10px; border-radius:8px; border:1px solid #cbd5e1; font-size:12px; outline:none;">
                    </div>
                @endif

                <button type="submit" class="btn btn-primary" style="margin-top:8px; font-size:12px; padding:10px;">
                    <i class="fa-solid fa-plus" style="margin-right:4px;"></i> Create User
                </button>
            </form>
        </div>

        {{-- Add Org Card (SuperAdmin only) --}}
        @if(Auth::user()->isSuperAdmin())
            <div class="card" style="padding:20px;">
                <h3 style="font-size:13px; font-weight:700; color:#1e293b; margin-bottom:16px; display:flex; align-items:center; gap:6px;">
                    <i class="fa-solid fa-building" style="color:#2563eb;"></i> Register New Organization
                </h3>

                <form action="{{ route('organizations.store') }}" method="POST" style="display:flex; flex-direction:column; gap:12px; margin:0;">
                    @csrf
                    <div>
                        <label style="display:block; font-size:10px; font-weight:700; text-transform:uppercase; color:#64748b; margin-bottom:4px;">Organization Name</label>
                        <input type="text" name="name" required placeholder="e.g. Microsoft India" style="width:100%; padding:8px 10px; border-radius:8px; border:1px solid #cbd5e1; font-size:12px; outline:none;">
                    </div>

                    <button type="submit" class="btn btn-violet" style="margin-top:8px; font-size:12px; padding:10px;">
                        <i class="fa-solid fa-plus" style="margin-right:4px;"></i> Register Organization
                    </button>
                </form>
            </div>
        @endif

    </div>
</div>

{{-- Edit User Modal (Simple elegant overlay) --}}
<div id="editUserModal" style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.4); z-index:9999; align-items:center; justify-content:center;">
    <div class="card" style="width:100%; max-width:480px; padding:24px; position:relative; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <button onclick="closeEditModal()" style="position:absolute; top:16px; right:16px; background:none; border:none; font-size:16px; color:#64748b; cursor:pointer;"><i class="fa-solid fa-xmark"></i></button>

        <h3 style="font-size:15px; font-weight:700; color:#1e293b; margin-bottom:18px;">
            <i class="fa-solid fa-user-pen" style="color:#4f46e5; margin-right:6px;"></i> Edit User Details
        </h3>

        <form id="editUserForm" method="POST" style="display:flex; flex-direction:column; gap:12px; margin:0;">
            @csrf
            @method('PUT')

            <div>
                <label style="display:block; font-size:10px; font-weight:700; text-transform:uppercase; color:#64748b; margin-bottom:4px;">Full Name</label>
                <input type="text" id="edit_name" name="name" required style="width:100%; padding:8px 10px; border-radius:8px; border:1px solid #cbd5e1; font-size:12px; outline:none;">
            </div>

            <div>
                <label style="display:block; font-size:10px; font-weight:700; text-transform:uppercase; color:#64748b; margin-bottom:4px;">Email Address</label>
                <input type="email" id="edit_email" name="email" required style="width:100%; padding:8px 10px; border-radius:8px; border:1px solid #cbd5e1; font-size:12px; outline:none;">
            </div>

            <div>
                <label style="display:block; font-size:10px; font-weight:700; text-transform:uppercase; color:#64748b; margin-bottom:4px;">New Password (leave blank to keep current)</label>
                <input type="password" name="password" placeholder="••••••••" style="width:100%; padding:8px 10px; border-radius:8px; border:1px solid #cbd5e1; font-size:12px; outline:none;">
            </div>

            @if(Auth::user()->isSuperAdmin())
                <div>
                    <label style="display:block; font-size:10px; font-weight:700; text-transform:uppercase; color:#64748b; margin-bottom:4px;">System Role</label>
                    <select id="edit_role" name="role" required style="width:100%; padding:8px 10px; border-radius:8px; border:1px solid #cbd5e1; font-size:12px; outline:none; background:#fff;">
                        <option value="staff">Staff</option>
                        <option value="admin">Org Admin</option>
                        <option value="superadmin">Global SuperAdmin</option>
                    </select>
                </div>

                <div>
                    <label style="display:block; font-size:10px; font-weight:700; text-transform:uppercase; color:#64748b; margin-bottom:4px;">Organization Assignment</label>
                    <select id="edit_org" name="organization_id" style="width:100%; padding:8px 10px; border-radius:8px; border:1px solid #cbd5e1; font-size:12px; outline:none; background:#fff;">
                        <option value="">No Organization (Global)</option>
                        @foreach($organizations as $org)
                            <option value="{{ $org->id }}">{{ $org->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display:block; font-size:10px; font-weight:700; text-transform:uppercase; color:#64748b; margin-bottom:4px;">Staff Specific Role / Title (Optional)</label>
                    <input type="text" id="edit_staff_role" name="staff_role" style="width:100%; padding:8px 10px; border-radius:8px; border:1px solid #cbd5e1; font-size:12px; outline:none;">
                </div>
            @else
                <div>
                    <label style="display:block; font-size:10px; font-weight:700; text-transform:uppercase; color:#64748b; margin-bottom:4px;">Staff Specific Role / Title</label>
                    <input type="text" id="edit_staff_role_admin" name="staff_role" required style="width:100%; padding:8px 10px; border-radius:8px; border:1px solid #cbd5e1; font-size:12px; outline:none;">
                </div>
            @endif

            <button type="submit" class="btn btn-primary" style="margin-top:10px; font-size:12px; padding:10px;">
                <i class="fa-solid fa-check" style="margin-right:4px;"></i> Update User Details
            </button>
        </form>
    </div>
</div>

<script>
function openEditModal(user) {
    document.getElementById('editUserForm').action = "/users/" + user.id;
    document.getElementById('edit_name').value = user.name;
    document.getElementById('edit_email').value = user.email;

    const roleSelect = document.getElementById('edit_role');
    if (roleSelect) roleSelect.value = user.role;

    const orgSelect = document.getElementById('edit_org');
    if (orgSelect) orgSelect.value = user.organization_id || "";

    const staffRoleInput = document.getElementById('edit_staff_role');
    if (staffRoleInput) staffRoleInput.value = user.staff_role || "";

    const staffRoleAdminInput = document.getElementById('edit_staff_role_admin');
    if (staffRoleAdminInput) staffRoleAdminInput.value = user.staff_role || "";

    const modal = document.getElementById('editUserModal');
    modal.style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editUserModal').style.display = 'none';
}
</script>
@endsection
