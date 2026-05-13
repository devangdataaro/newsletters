@extends('layouts.app')

@section('title', $newsletter->newsletter_title)
@section('page-title', 'Newsletter Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('newsletters.index') }}">Newsletters</a></li>
    <li class="breadcrumb-item active">Details</li>
@endsection

@section('content')

<!-- ── Header Card ─────────────────────────── -->
<div class="table-card mb-4">
    <div class="p-4">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge-pill {{ $newsletter->status_badge_class }}">
                        @if($newsletter->process_status === 'pending') <i class="bi bi-clock me-1"></i>
                        @elseif($newsletter->process_status === 'start') <i class="bi bi-play-circle me-1"></i>
                        @elseif($newsletter->process_status === 'started') <i class="bi bi-arrow-repeat me-1"></i>
                        @else <i class="bi bi-check-circle me-1"></i>
                        @endif
                        {{ ucfirst($newsletter->process_status) }}
                    </span>
                    @if(in_array($newsletter->process_status, ['started','start']))
                        <span style="font-size:12px;color:#6366f1;animation: spin 1.5s linear infinite">
                            <i class="bi bi-arrow-repeat"></i>
                        </span>
                    @endif
                </div>
                <h3 class="fw-bold mb-1" style="color:#0f172a">{{ $newsletter->newsletter_title }}</h3>
                <div style="font-size:13px;color:#64748b">
                    Created {{ $newsletter->created_at->diffForHumans() }}
                    @if($newsletter->scheduled_at)
                        · Scheduled for {{ $newsletter->scheduled_at->format('M d, Y h:i A') }}
                    @endif
                    @if($newsletter->send_at)
                        · Completed {{ $newsletter->send_at->diffForHumans() }}
                    @endif
                </div>
            </div>

            <div class="d-flex gap-2 flex-shrink-0">
                @if($newsletter->process_status === 'pending')
                <form action="{{ route('newsletters.start', $newsletter) }}" method="POST"
                      onsubmit="return confirm('Start sending this newsletter now?')">
                    @csrf
                    <input type="hidden" name="process_status" value="start">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-play-fill me-1"></i> Start Sending
                    </button>
                </form>
                <a href="{{ route('newsletters.edit', $newsletter) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
                @endif
                <a href="{{ route('newsletters.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        <!-- Progress Bar -->
        @if(in_array($newsletter->process_status, ['started','completed']))
        <div class="mt-4">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span style="font-size:13px;font-weight:600;color:#374151">Email Dispatch Progress</span>
                <span style="font-size:13px;color:#6366f1;font-weight:600">
                    {{ $newsletter->sent_count }} / {{ $newsletter->total_recipients }} sent
                    ({{ $newsletter->progress_percentage }}%)
                </span>
            </div>
            <div class="progress" style="height:10px">
                <div class="progress-bar" style="width:{{ $newsletter->progress_percentage }}%"
                     role="progressbar"></div>
            </div>
            <div class="d-flex gap-4 mt-2" style="font-size:12px;color:#6b7280">
                <span><i class="bi bi-check-circle-fill text-success me-1"></i>Sent: {{ $newsletter->sent_count }}</span>
                <span><i class="bi bi-hourglass-split text-warning me-1"></i>Pending: {{ $newsletter->total_recipients - $newsletter->sent_count }}</span>
                <span><i class="bi bi-people text-primary me-1"></i>Total: {{ $newsletter->total_recipients }}</span>
            </div>
        </div>
        @endif
    </div>
</div>

<div class="row g-4">
    <!-- ── Content Preview ── -->
    <div class="col-12 col-xl-5">
        <div class="table-card h-100">
            <div class="table-header">
                <h5><i class="bi bi-file-text me-2"></i>Email Content Preview</h5>
            </div>
            <div class="p-4" style="max-height:400px;overflow-y:auto">
                <div style="font-size:14px;line-height:1.7;color:#374151;white-space:pre-wrap">{{ $newsletter->newsletter_content }}</div>
            </div>
        </div>
    </div>

    <!-- ── Recipients List ── -->
    <div class="col-12 col-xl-7">
        <div class="table-card">
            <div class="table-header">
                <h5><i class="bi bi-people me-2"></i>Recipients</h5>
                <div style="font-size:12px;color:#9ca3af">
                    Showing {{ $recipients->count() }} of {{ $recipients->total() }}
                </div>
            </div>

            @if($recipients->count())
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Sent At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recipients as $recipient)
                        <tr>
                            <td class="text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:28px;height:28px;border-radius:50%;background:#ede9fe;color:#6366f1;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700">
                                        {{ strtoupper(substr($recipient->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <span>{{ $recipient->user->name ?? '—' }}</span>
                                </div>
                            </td>
                            <td style="font-size:13px">{{ $recipient->user->email ?? '—' }}</td>
                            <td>
                                @if($recipient->status === 'sent')
                                    <span class="badge-pill badge-success"><i class="bi bi-check-circle me-1"></i>Sent</span>
                                @elseif($recipient->status === 'failed')
                                    <span class="badge-pill badge-danger"><i class="bi bi-x-circle me-1"></i>Failed</span>
                                @else
                                    <span class="badge-pill badge-warning"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                                @endif
                            </td>
                            <td style="font-size:12px;color:#6b7280">
                                {{ $recipient->sent_at ? $recipient->sent_at->format('M d, h:i A') : '—' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center py-3">
                {{ $recipients->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <div style="font-size:40px;opacity:.2"><i class="bi bi-people"></i></div>
                <p class="text-muted mt-2" style="font-size:13px">No recipients tracked yet. Start the newsletter to begin.</p>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('scripts')
<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>
@if(in_array($newsletter->process_status, ['started','start']))
<script>
    // Auto-refresh every 30 seconds while newsletter is sending
    setTimeout(() => location.reload(), 30000);
</script>
@endif
@endsection
