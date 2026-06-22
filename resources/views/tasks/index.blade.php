@extends('layouts.app')

@section('header_title', 'Tasks Console')

@section('content')
<div x-data="{ openAddTaskModal: false }">

    {{-- ── Filters & Actions ── --}}
    <div class="glass-card rounded-2xl p-5 mb-6" style="display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;">
        <form action="{{ route('tasks.index') }}" method="GET" style="display:flex;gap:10px;flex-wrap:wrap;">
            <select name="status" onchange="this.form.submit()" class="form-input" style="width:150px;">
                <option value="">All Tasks</option>
                <option value="Pending"   {{ request('status') === 'Pending'   ? 'selected' : '' }}>Pending</option>
                <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
            </select>
            <select name="type" onchange="this.form.submit()" class="form-input" style="width:150px;">
                <option value="">All Types</option>
                @foreach(['Follow-up','Proposal','Call','Meeting'] as $t)
                    <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </form>

        <div style="display:flex;gap:10px;align-items:center;">
            <form action="{{ route('tasks.generate-priority') }}" method="POST">
                @csrf
                <button type="submit" class="btn-ai">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span>Generate AI Tasks</span>
                </button>
            </form>
            <button @click="openAddTaskModal = true" class="btn-primary">
                <i class="fa-solid fa-plus"></i> Add Task
            </button>
        </div>
    </div>

    {{-- ── Tasks Table ── --}}
    <div class="glass-card rounded-2xl" style="overflow:hidden;">
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="text-align:center;width:60px;">Done</th>
                        <th>Task Description</th>
                        <th>Lead Associate</th>
                        <th>Assigned To</th>
                        <th>Due Date</th>
                        <th>Priority</th>
                        <th>Source</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                        <tr>
                            <td style="text-align:center;">
                                <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit"
                                        style="width:22px;height:22px;border-radius:6px;border:2px solid {{ $task->status === 'Completed' ? '#16a34a' : '#cbd5e1' }};background:{{ $task->status === 'Completed' ? '#dcfce7' : '#ffffff' }};display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.15s;margin:0 auto;">
                                        @if($task->status === 'Completed')
                                            <i class="fa-solid fa-check" style="color:#16a34a;font-size:10px;"></i>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td>
                                <span style="font-weight:700;color:#1e293b;display:block;{{ $task->status === 'Completed' ? 'text-decoration:line-through;color:#94a3b8;' : '' }}">
                                    {{ $task->title }}
                                </span>
                                @if($task->notes)
                                    <span style="font-size:10px;color:#94a3b8;display:block;margin-top:2px;max-width:260px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                        {{ $task->notes }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($task->lead)
                                    <a href="{{ route('leads.show', $task->lead_id) }}" style="color:#6366f1;font-weight:600;font-size:12px;text-decoration:none;display:block;">{{ $task->lead->full_name }}</a>
                                    <span style="font-size:10px;color:#94a3b8;display:block;margin-top:1px;">{{ $task->lead->company_name ?: 'No Company' }}</span>
                                @else
                                    <span style="font-size:12px;color:#94a3b8;">General Task</span>
                                @endif
                            </td>
                            <td style="color:#64748b;font-size:12px;">{{ $task->user->name ?? 'Unassigned' }}</td>
                            <td style="font-size:12px;font-weight:600;color:#475569;">{{ date('d M, Y', strtotime($task->due_date)) }}</td>
                            <td>
                                @php
                                    $priorityClass = match($task->priority) {
                                        'High'   => 'badge-high',
                                        'Medium' => 'badge-medium',
                                        default  => 'badge-low'
                                    };
                                @endphp
                                <span class="badge {{ $priorityClass }}">{{ $task->priority }}</span>
                            </td>
                            <td>
                                @if($task->ai_suggested)
                                    <span class="badge badge-ai">
                                        <i class="fa-solid fa-wand-magic-sparkles" style="font-size:8px;margin-right:3px;"></i> AI Auto
                                    </span>
                                @else
                                    <span style="font-size:11px;color:#94a3b8;">Manual</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;padding:48px;color:#94a3b8;">
                                <i class="fa-solid fa-clipboard-list" style="font-size:28px;display:block;margin-bottom:10px;opacity:0.5;"></i>
                                No tasks yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tasks->hasPages())
            <div style="padding:14px 20px;border-top:1px solid #f1f5f9;">{{ $tasks->links() }}</div>
        @endif
    </div>

    {{-- ══ Add Task Slide-over ══ --}}
    <div x-show="openAddTaskModal" class="fixed inset-0 z-50" style="overflow:hidden;display:none;">
        <div style="position:absolute;inset:0;overflow:hidden;">
            <div x-show="openAddTaskModal"
                 x-transition:enter="ease-in-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in-out duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 style="position:absolute;inset:0;background:rgba(15,23,42,0.4);backdrop-filter:blur(4px);"
                 @click="openAddTaskModal = false"></div>

            <div style="pointer-events:none;position:fixed;top:0;bottom:0;right:0;display:flex;max-width:100%;padding-left:40px;">
                <div x-show="openAddTaskModal"
                     x-transition:enter="transform transition ease-in-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                     x-transition:leave="transform transition ease-in-out duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                     style="pointer-events:auto;width:100%;max-width:460px;">
                    <div class="drawer-panel" style="display:flex;flex-direction:column;height:100%;overflow:hidden;">

                        <div class="drawer-header">
                            <h2><i class="fa-solid fa-list-check" style="color:#6366f1;"></i> Add Task</h2>
                            <button @click="openAddTaskModal = false" style="background:none;border:none;color:#64748b;cursor:pointer;font-size:18px;padding:4px;">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>

                        <div class="drawer-body">
                            <form action="{{ route('tasks.store') }}" method="POST">
                                @csrf
                                <div style="display:flex;flex-direction:column;gap:14px;">

                                    <div>
                                        <label class="form-label">Link to Lead (Optional)</label>
                                        <select name="lead_id" class="form-input">
                                            <option value="">Choose a Lead…</option>
                                            @foreach($leads as $l)
                                                <option value="{{ $l->id }}">{{ $l->full_name }} ({{ $l->company_name ?: 'No Company' }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label">Task Title *</label>
                                        <input type="text" name="title" required placeholder="Call back to confirm ERP features" class="form-input">
                                    </div>
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                                        <div>
                                            <label class="form-label">Task Category *</label>
                                            <select name="type" required class="form-input">
                                                @foreach(['Follow-up','Proposal','Call','Meeting'] as $t)
                                                    <option value="{{ $t }}">{{ $t }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="form-label">Priority *</label>
                                            <select name="priority" required class="form-input">
                                                <option value="Low">Low</option>
                                                <option value="Medium" selected>Medium</option>
                                                <option value="High">High</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                                        <div>
                                            <label class="form-label">Due Date *</label>
                                            <input type="date" name="due_date" required value="{{ date('Y-m-d') }}" class="form-input">
                                        </div>
                                        <div>
                                            <label class="form-label">Assigned Agent *</label>
                                            <select name="user_id" required class="form-input">
                                                @foreach($agents as $agent)
                                                    <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="form-label">Notes & Reminders</label>
                                        <textarea name="notes" rows="3" placeholder="Confirm price discounts and billing terms." class="form-input"></textarea>
                                    </div>

                                    <div style="display:flex;justify-content:flex-end;gap:10px;padding-top:10px;border-top:1px solid #f1f5f9;margin-top:4px;">
                                        <button type="button" @click="openAddTaskModal = false" class="btn-secondary">Cancel</button>
                                        <button type="submit" class="btn-primary">
                                            <i class="fa-solid fa-floppy-disk"></i> Save Task
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
