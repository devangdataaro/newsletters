@extends('layouts.app')

@section('title', 'Edit Newsletter')
@section('page-title', 'Edit Newsletter')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('newsletters.index') }}">Newsletters</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-8">
        <div class="form-card">
            <div class="d-flex align-items-center gap-2 mb-4">
                <div style="width:38px;height:38px;background:#dbeafe;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#3b82f6;font-size:18px">
                    <i class="bi bi-pencil-square"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold" style="color:#0f172a">Edit Newsletter</h5>
                    <p class="mb-0 text-muted" style="font-size:12px">Update newsletter details.</p>
                </div>
            </div>

            <form action="{{ route('newsletters.update', $newsletter) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="form-label">Newsletter Title <span class="text-danger">*</span></label>
                    <input type="text"
                           class="form-control @error('newsletter_title') is-invalid @enderror"
                           name="newsletter_title"
                           value="{{ old('newsletter_title', $newsletter->newsletter_title) }}"
                           required>
                    @error('newsletter_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Newsletter Content <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('newsletter_content') is-invalid @enderror"
                              name="newsletter_content"
                              rows="10"
                              required>{{ old('newsletter_content', $newsletter->newsletter_content) }}</textarea>
                    @error('newsletter_content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Scheduled At <span class="text-muted fw-normal">(optional)</span></label>
                    <input type="datetime-local"
                           class="form-control @error('scheduled_at') is-invalid @enderror"
                           name="scheduled_at"
                           value="{{ old('scheduled_at', $newsletter->scheduled_at ? $newsletter->scheduled_at->format('Y-m-d\TH:i') : '') }}">
                    @error('scheduled_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Start Option for Pending -->
                @if($newsletter->process_status === 'pending')
                <div class="mb-4 p-3" style="background:#f8fafc;border-radius:10px;border:1px solid #e2e8f0;">
                    <div class="form-check d-flex align-items-center gap-2">
                        <input class="form-check-input" type="checkbox" id="start_now"
                               name="process_status" value="start">
                        <label class="form-check-label" for="start_now" style="font-size:14px;font-weight:500;color:#0f172a;cursor:pointer">
                            <i class="bi bi-play-circle-fill text-success me-1"></i>
                            Start sending immediately after saving
                        </label>
                    </div>
                    <div class="text-muted mt-1" style="font-size:12px;padding-left:26px">
                        Check this to change status from <strong>Pending → Start</strong> and begin dispatching emails.
                    </div>
                </div>
                @endif

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Update Newsletter
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
