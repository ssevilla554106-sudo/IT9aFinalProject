{{-- FILE: resources/views/events/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Create Event')

@push('styles')
<style>
.form-card { background:white; border-radius:20px; padding:2.5rem; box-shadow:0 2px 20px rgba(0,0,0,.06); margin-bottom:1.5rem; }
.form-section-title { font-size:.95rem; font-weight:700; color:#1e293b; margin:1.5rem 0 1rem; padding-bottom:.5rem; border-bottom:2px solid #f1f5f9; display:flex; align-items:center; gap:.5rem; }
.fgrid { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; }
@media(max-width:600px){ .fgrid{ grid-template-columns:1fr; } }
.preview-drop { border:2px dashed #e2e8f0; border-radius:12px; padding:2rem; text-align:center; color:#94a3b8; cursor:pointer; transition:all .2s; }
.preview-drop:hover { border-color:#e94560; color:#e94560; }
.approval-notice { background:linear-gradient(135deg,#fef3c7,#fffbeb); border:1.5px solid #f59e0b; border-radius:14px; padding:1.25rem 1.5rem; display:flex; gap:1rem; align-items:flex-start; margin-bottom:1.5rem; }
.approval-notice i { color:#f59e0b; font-size:1.2rem; flex-shrink:0; margin-top:.1rem; }
.approval-notice h4 { margin:0 0 .25rem; color:#92400e; font-size:.95rem; }
.approval-notice p  { margin:0; font-size:.82rem; color:#78350f; line-height:1.5; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="container">
        <div style="display:flex;align-items:center;gap:1rem;">
            <a href="{{ route('events.index') }}" style="color:#94a3b8;text-decoration:none;"><i class="fas fa-arrow-left"></i></a>
            <div>
                <h1><i class="fas fa-plus-circle" style="color:#e94560;"></i> Create New Event</h1>
                <p>Fill in the details below to create your event</p>
            </div>
        </div>
    </div>
</div>

<div class="container" style="padding-top:2.5rem;max-width:900px;">

    {{-- Approval notice for non-admins --}}
    @if(!Auth::user()->is_admin)
    <div class="approval-notice">
        <i class="fas fa-info-circle"></i>
        <div>
            <h4>Event Approval Required</h4>
            <p>Events submitted by members require admin approval before going live. You can save as a draft first, then submit when ready. Admins can publish events directly.</p>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('events.store') }}" enctype="multipart/form-data" id="eventForm">
        @csrf

        {{-- Hidden action field — set by buttons at the bottom --}}
        <input type="hidden" name="action" id="actionField" value="draft">

        <div class="form-card">
            <div class="form-section-title"><i class="fas fa-info-circle" style="color:#e94560;"></i> Basic Information</div>

            <div class="form-group">
                <label class="form-label">Event Title *</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}"
                       placeholder="e.g. Tech Summit 2025" required maxlength="255">
                @error('title')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Description *</label>
                <textarea name="description" class="form-control" rows="6"
                          placeholder="Describe your event in detail..." required id="descInput">{{ old('description') }}</textarea>
                <div style="text-align:right;font-size:.75rem;color:#94a3b8;margin-top:.2rem;"><span id="descCount">0</span> characters</div>
                @error('description')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="fgrid">
                <div class="form-group">
                    <label class="form-label">Category *</label>
                    <select name="category" class="form-control" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                @if(Auth::user()->is_admin)
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="published" selected>Published</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
                @endif
            </div>
        </div>

        <div class="form-card">
            <div class="form-section-title"><i class="fas fa-map-marker-alt" style="color:#e94560;"></i> Location</div>
            <div class="fgrid">
                <div class="form-group">
                    <label class="form-label">City / Location *</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location') }}"
                           placeholder="e.g. Manila, Philippines" required>
                    @error('location')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Venue Name</label>
                    <input type="text" name="venue" class="form-control" value="{{ old('venue') }}"
                           placeholder="e.g. SMX Convention Center">
                    @error('venue')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-section-title"><i class="fas fa-clock" style="color:#e94560;"></i> Date & Time</div>
            <div class="fgrid">
                <div class="form-group">
                    <label class="form-label">Start Date & Time *</label>
                    <input type="datetime-local" name="start_date" class="form-control"
                           value="{{ old('start_date') }}" required>
                    @error('start_date')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">End Date & Time *</label>
                    <input type="datetime-local" name="end_date" class="form-control"
                           value="{{ old('end_date') }}" required>
                    @error('end_date')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-section-title"><i class="fas fa-ticket-alt" style="color:#e94560;"></i> Capacity & Pricing</div>
            <div class="fgrid">
                <div class="form-group">
                    <label class="form-label">Max Attendees <span style="color:#94a3b8;font-weight:400;">(optional — blank = unlimited)</span></label>
                    <input type="number" name="max_attendees" class="form-control"
                           value="{{ old('max_attendees') }}" min="1" placeholder="Unlimited">
                    @error('max_attendees')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Ticket Price ($) <span style="color:#94a3b8;font-weight:400;">(0 = free)</span></label>
                    <input type="number" name="ticket_price" class="form-control"
                           value="{{ old('ticket_price', 0) }}" min="0" step="0.01">
                    @error('ticket_price')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-section-title"><i class="fas fa-image" style="color:#e94560;"></i> Event Image</div>
            <div class="preview-drop" onclick="document.getElementById('imgInput').click()">
                <i class="fas fa-cloud-upload-alt" style="font-size:2rem;display:block;margin-bottom:.5rem;"></i>
                <div>Click to upload event banner (JPEG/PNG, max 2MB)</div>
                <img id="imgPreview" style="display:none;max-height:180px;border-radius:10px;margin-top:1rem;">
            </div>
            <input type="file" id="imgInput" name="image" accept="image/*" style="display:none;"
                   onchange="previewImg(this)">
            @error('image')<div class="form-error">{{ $message }}</div>@enderror

            @if(Auth::user()->is_admin)
            <div style="display:flex;align-items:center;gap:.75rem;padding:1rem;background:#f8fafc;border-radius:10px;margin-top:1.25rem;">
                <input type="checkbox" name="is_featured" id="is_featured" value="1"
                       {{ old('is_featured') ? 'checked' : '' }}
                       style="width:18px;height:18px;accent-color:#e94560;">
                <label for="is_featured" style="cursor:pointer;font-weight:600;color:#374151;">
                    <i class="fas fa-star" style="color:#f5a623;"></i> Mark as Featured Event
                </label>
            </div>
            @endif
        </div>

        {{-- ACTION BUTTONS --}}
        <div style="display:flex;gap:1rem;justify-content:flex-end;flex-wrap:wrap;margin-bottom:3rem;">
            <a href="{{ route('events.index') }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="button" class="btn btn-secondary btn-lg" onclick="submitForm('draft')">
                <i class="fas fa-save"></i> Save as Draft
            </button>
            @if(Auth::user()->is_admin)
                <button type="button" class="btn btn-primary btn-lg" onclick="submitForm('submit')">
                    <i class="fas fa-rocket"></i> Publish Now
                </button>
            @else
                <button type="button" class="btn btn-primary btn-lg" onclick="submitForm('submit')">
                    <i class="fas fa-paper-plane"></i> Submit for Approval
                </button>
            @endif
        </div>
    </form>
</div>

@push('scripts')
<script>
function submitForm(action) {
    document.getElementById('actionField').value = action;
    document.getElementById('eventForm').submit();
}
function previewImg(input) {
    const p = document.getElementById('imgPreview');
    if (input.files && input.files[0]) {
        const r = new FileReader();
        r.onload = e => { p.src = e.target.result; p.style.display = 'block'; };
        r.readAsDataURL(input.files[0]);
    }
}
const d = document.getElementById('descInput');
const c = document.getElementById('descCount');
d.addEventListener('input', () => c.textContent = d.value.length);
c.textContent = d.value.length;
</script>
@endpush
@endsection
