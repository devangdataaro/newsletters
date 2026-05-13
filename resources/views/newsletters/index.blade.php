@extends('layouts.app')

@section('title', 'Newsletters')
@section('page-title', 'Newsletter Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Newsletters</li>
@endsection

@section('content')

<!-- ── Stats Row ───────────────────────────── -->
<div class="row g-3 mb-4">
    @php
        $total    = $newsletters->total();
        $pending   = \App\Models\Newsletter::where('process_status','pending')->count();
        $running   = \App\Models\Newsletter::whereIn('process_status',['start','started'])->count();
        $completed = \App\Models\Newsletter::where('process_status','completed')->count();
    @endphp

    <div class="col-6 col-xl-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#ede9fe; color:#6366f1"><i class="bi bi-envelope-paper"></i></div>
            <div>
                <div class="stat-value">{{ $total }}</div>
                <div class="stat-label">Total Newsletters</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#fef3c7; color:#d97706"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="stat-value">{{ $pending }}</div>
                <div class="stat-label">Pending</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#dbeafe; color:#3b82f6"><i class="bi bi-send-arrow-up"></i></div>
            <div>
                <div class="stat-value">{{ $running }}</div>
                <div class="stat-label">In Progress</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#dcfce7; color:#16a34a"><i class="bi bi-check2-circle"></i></div>
            <div>
                <div class="stat-value">{{ $completed }}</div>
                <div class="stat-label">Completed</div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-3">
        <div class="stat-card d-flex align-items-center gap-3">
            <div class="stat-icon" style="background:#f0fdf4; color:#16a34a"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="stat-value">{{ $totalUsers }}</div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>
    </div>
</div>

<!-- ── Newsletters Table ──────────────────── -->
<div class="table-card">
    <div class="table-header">
        <h5><i class="bi bi-envelope-paper me-2 text-indigo-600"></i>All Newsletters</h5>
        <a href="{{ route('newsletters.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Create New
        </a>
    </div>

    @if($newsletters->count())
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>Scheduled At</th>
                    <th>Created At</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($newsletters as $newsletter)
                <tr>
                    <td class="text-muted" style="width:50px">{{ $loop->iteration }}</td>
                    <td>
                        <div class="fw-semibold" style="color:#0f172a">{{ Str::limit($newsletter->newsletter_title, 50) }}</div>
                        <div class="text-muted" style="font-size:12px">{{ Str::limit(strip_tags($newsletter->newsletter_content), 60) }}</div>
                    </td>
                    <td>
                        <span class="badge-pill {{ $newsletter->status_badge_class }}">
                            @if($newsletter->process_status === 'pending') <i class="bi bi-clock me-1"></i>
                            @elseif($newsletter->process_status === 'start') <i class="bi bi-play-circle me-1"></i>
                            @elseif($newsletter->process_status === 'started') <i class="bi bi-arrow-repeat me-1"></i>
                            @else <i class="bi bi-check-circle me-1"></i>
                            @endif
                            {{ ucfirst($newsletter->process_status) }}
                        </span>
                    </td>
                    <td style="min-width: 160px">
                        @if(in_array($newsletter->process_status, ['started','completed']))
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height:8px">
                                    <div class="progress-bar" style="width: {{ $newsletter->progress_percentage }}%"></div>
                                </div>
                                <span style="font-size:11px;color:#6b7280;white-space:nowrap">
                                    {{ $newsletter->sent_count }}/{{ $newsletter->total_recipients }}
                                </span>
                            </div>
                            <div style="font-size:11px;color:#6b7280;margin-top:3px">{{ $newsletter->progress_percentage }}% sent</div>
                        @elseif($newsletter->process_status === 'pending')
                            <span style="font-size:12px;color:#9ca3af"><i class="bi bi-dash-circle"></i> Not started</span>
                        @else
                            <span style="font-size:12px;color:#6366f1"><i class="bi bi-play-circle"></i> Queued…</span>
                        @endif
                    </td>
                    <td>
                        @if($newsletter->scheduled_at)
                            <div style="font-size:13px">{{ $newsletter->scheduled_at->format('M d, Y') }}</div>
                            <div style="font-size:11px;color:#9ca3af">{{ $newsletter->scheduled_at->format('h:i A') }}</div>
                        @else
                            <span style="font-size:12px;color:#d1d5db">—</span>
                        @endif
                    </td>
                    <td>
                        <div style="font-size:13px">{{ $newsletter->created_at->format('M d, Y') }}</div>
                        <div style="font-size:11px;color:#9ca3af">{{ $newsletter->created_at->format('h:i A') }}</div>
                    </td>
                    <td class="text-end">
                        <div class="d-flex align-items-center justify-content-end gap-1">
                            <!-- View -->
                            <a href="{{ route('newsletters.show', $newsletter) }}"
                               class="action-btn" style="background:#ede9fe;color:#6366f1"
                               title="View Details">
                                <i class="bi bi-eye"></i>
                            </a>

                            @if($newsletter->process_status === 'pending')
                            <!-- Start -->
                            <form action="{{ route('newsletters.start', $newsletter) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Start sending this newsletter to {{ $totalUsers }} subscribers?')">
                                @csrf
                                <input type="hidden" name="process_status" value="start">
                                <button type="submit" class="action-btn"
                                        style="background:#dcfce7;color:#16a34a" title="Start Sending">
                                    <i class="bi bi-play-fill"></i>
                                </button>
                            </form>

                            <!-- Edit -->
                            <a href="{{ route('newsletters.edit', $newsletter) }}"
                               class="action-btn" style="background:#dbeafe;color:#3b82f6"
                               title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <!-- Delete -->
                            <form action="{{ route('newsletters.destroy', $newsletter) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this newsletter?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn"
                                        style="background:#fee2e2;color:#ef4444" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif

                            @if(in_array($newsletter->process_status, ['started','start']))
                            <span class="badge-pill badge-primary" style="font-size:10px">
                                <i class="bi bi-arrow-repeat spin me-1"></i>Sending…
                            </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($newsletters->hasPages())
    <div class="d-flex justify-content-center py-3">
        {{ $newsletters->links() }}
    </div>
    @endif

    @else
    <div class="text-center py-5">
        <div style="font-size:60px; opacity:.2"><i class="bi bi-envelope-paper"></i></div>
        <h5 class="mt-3 text-muted">No newsletters yet</h5>
        <p class="text-muted mb-4" style="font-size:14px">Create your first newsletter and start engaging your subscribers.</p>
        <a href="{{ route('newsletters.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Create Newsletter
        </a>
    </div>
    @endif
</div>

@endsection

@section('scripts')
<style>
@keyframes spin { to { transform: rotate(360deg); } }
.spin { display: inline-block; animation: spin 1.5s linear infinite; }
</style>
