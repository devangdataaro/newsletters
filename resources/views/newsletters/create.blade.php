@extends('layouts.app')

@section('title', 'Create Newsletter')
@section('page-title', 'Create Newsletter')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('newsletters.index') }}">Newsletters</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-8">
        <div class="form-card">
            <div class="d-flex align-items-center gap-2 mb-4">
                <div style="width:38px;height:38px;background:#ede9fe;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#6366f1;font-size:18px">
                    <i class="bi bi-envelope-plus"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold" style="color:#0f172a">New Newsletter</h5>
                    <p class="mb-0 text-muted" style="font-size:12px">Fill in the details below to create a newsletter.</p>
                </div>
            </div>

            <form action="{{ route('newsletters.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="newsletter_title" class="form-label">
                        Newsletter Title <span class="text-danger">*</span>
                    </label>
                    <input type="text"
                           class="form-control @error('newsletter_title') is-invalid @enderror"
                           id="newsletter_title"
                           name="newsletter_title"
                           value="{{ old('newsletter_title') }}"
                           placeholder="e.g. May 2026 Monthly Update"
                           required>
                    @error('newsletter_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="newsletter_content" class="form-label">
                        Newsletter Content <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control @error('newsletter_content') is-invalid @enderror"
                              id="newsletter_content"
                              name="newsletter_content"
                              rows="10"
                              placeholder="Write your newsletter content here…"
                              required>{{ old('newsletter_content') }}</textarea>
                    @error('newsletter_content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text mt-1" style="font-size:12px;color:#9ca3af">
                        <i class="bi bi-info-circle me-1"></i>
                        HTML is supported. The content will be sent as the email body.
                    </div>
                </div>

                <div class="mb-4">
                    <label for="scheduled_at" class="form-label">
                        Scheduled At
                        <span class="text-muted fw-normal">(optional)</span>
                    </label>
                    <input type="datetime-local"
                           class="form-control @error('scheduled_at') is-invalid @enderror"
                           id="scheduled_at"
                           name="scheduled_at"
                           value="{{ old('scheduled_at') }}">
                    @error('scheduled_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text mt-1" style="font-size:12px;color:#9ca3af">
                        <i class="bi bi-calendar-event me-1"></i>
                        Leave blank to send immediately when you click "Start".
                    </div>
                </div>

                <!-- Status Info -->
                <div class="alert alert-info d-flex align-items-start gap-2 mb-4" style="font-size:13px">
                    <i class="bi bi-lightbulb-fill mt-1"></i>
                    <div>
                        <strong>How it works:</strong> Your newsletter will be saved with <strong>Pending</strong> status.
                        Once you're ready, go to the newsletter list and click
                        <span class="badge-pill badge-success" style="font-size:11px"><i class="bi bi-play-fill"></i> Start</span>
                        to begin dispatching emails with a 4–5 minute delay between each recipient.
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Save Newsletter
                    </button>
                    <a href="{{ route('newsletters.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
