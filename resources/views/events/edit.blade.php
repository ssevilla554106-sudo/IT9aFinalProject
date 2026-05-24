{{-- FILE: resources/views/events/edit.blade.php --}}
@extends('layouts.app')
@section('title', 'Edit Event')

@push('styles')
<style>
.form-card { background:white; border-radius:20px; padding:2.5rem; box-shadow:0 2px 20px rgba(0,0,0,.06); margin-bottom:1.5rem; }
.form-section-title { font-size:.95rem; font-weight:700; color:#1e293b; margin:1.5rem 0 1rem; padding-bottom:.5rem; border-bottom:2px solid #f1f5f9; display:flex; align-items:center; gap:.5rem; }
.fgrid { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; }
@media(max-width:600px){ .fgrid{ grid-template-columns:1fr; } }
.preview-drop { border:2px dashed #e2e8f0; border-radius:12px; padding:2rem; text-align:center; color:#94a3b8; cursor:pointer; transition:all .2s; }
.preview-drop:hover { border-color:#e94560; color:#e94560; }
.current-image { margin-top:1rem; }
.current-image img { max-height:200px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
.current-image .label { font-size:.85rem; color:#64748b; margin-top:.75rem; }
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="container">
        <div style="display:flex;align-items:center;gap:1rem;">
            <a href="{{ route('events.show', $event) }}" style="color:#94a3b8;text-decoration:none;"><i class="fas fa-arrow-left"></i></a>
            <div>
                <h1><i class="fas fa-edit" style="color:#e94560;"></i> Edit Event</h1>
                <p>Update the details for your event</p>
            </div>
        </div>
    </div>
</div>

<div class="container" style="padding-top:2.5rem;max-width:900px;">

    {{-- Flash messages --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> Please fix the errors below:
            <ul style="margin-top:0.5rem;margin-bottom:0;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('events.update', $event) }}" enctype="multipart/form-data" id="eventForm">
        @csrf
        @method('PUT')

        {{-- Hidden action field — set by buttons at the bottom --}}
        <input type="hidden" name="action" id="actionField" value="update">

        <div class="form-card">
            <div class="form-section-title"><i class="fas fa-info-circle" style="color:#e94560;"></i> Basic Information</div>

            <div class="form-group">
                <label class="form-label">Event Title *</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $event->title) }}"
                       placeholder="e.g. Tech Summit 2025" required maxlength="255">
                @error('title')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Description *</label>
                <textarea name="description" class="form-control" rows="6"
                          placeholder="Describe your event in detail..." required id="descInput">{{ old('description', $event->description) }}</textarea>
                <div style="text-align:right;font-size:.75rem;color:#94a3b8;margin-top:.2rem;"><span id="descCount">0</span> characters</div>
                @error('description')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="fgrid">
                <div class="form-group">
                    <label class="form-label">Category *</label>
                    <select name="category" class="form-control" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category', $event->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                @if(Auth::user()->is_admin)
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="published" {{ old('status', $event->status) == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ old('status', $event->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="pending" {{ old('status', $event->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="cancelled" {{ old('status', $event->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                @else
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <div style="padding:0.65rem 0.9rem;background:#f1f5f9;border-radius:8px;color:#64748b;font-weight:500;">
                        {{ ucfirst($event->status) }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="form-card">
            <div class="form-section-title"><i class="fas fa-map-marker-alt" style="color:#e94560;"></i> Location</div>
            <div class="fgrid">
                <div class="form-group">
                    <label class="form-label">City / Location *</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $event->location) }}"
                           placeholder="e.g. Manila, Philippines" required>
                    @error('location')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Venue Name</label>
                    <input type="text" name="venue" class="form-control" value="{{ old('venue', $event->venue) }}"
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
                           value="{{ old('start_date', $event->start_date->format('Y-m-d\TH:i')) }}" required>
                    @error('start_date')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">End Date & Time *</label>
                    <input type="datetime-local" name="end_date" class="form-control"
                           value="{{ old('end_date', $event->end_date->format('Y-m-d\TH:i')) }}" required>
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
                           value="{{ old('max_attendees', $event->max_attendees) }}" min="1" placeholder="Unlimited">
                    @error('max_attendees')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Ticket Price ($) <span style="color:#94a3b8;font-weight:400;">(0 = free)</span></label>
                    <input type="number" name="ticket_price" class="form-control"
                           value="{{ old('ticket_price', $event->ticket_price) }}" min="0" step="0.01">
                    @error('ticket_price')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-section-title"><i class="fas fa-image" style="color:#e94560;"></i> Event Image</div>
            <div class="preview-drop" onclick="document.getElementById('imgInput').click()">
                <i class="fas fa-cloud-upload-alt" style="font-size:2rem;display:block;margin-bottom:.5rem;"></i>
                <div>Click to upload new event banner (JPEG/PNG, max 2MB)</div>
                <img id="imgPreview" style="display:none;max-height:180px;border-radius:10px;margin-top:1rem;">
            </div>
            <input type="file" id="imgInput" name="image" accept="image/*" style="display:none;"
                   onchange="previewImg(this)">
            @error('image')<div class="form-error">{{ $message }}</div>@enderror

            {{-- Current image display --}}
            @if($event->image)
            <div class="current-image">
                <img src="{{ Storage::disk('public')->url('events/' . $event->image) }}" alt="{{ $event->title }}">
                <div class="label">Current event banner</div>
            </div>
            @endif
        </div>

        @if(Auth::user()->is_admin)
        <div class="form-card">
            <div style="display:flex;align-items:center;gap:.75rem;">
                <input type="checkbox" name="is_featured" id="is_featured" value="1"
                       {{ old('is_featured', $event->is_featured) ? 'checked' : '' }}
                       style="width:18px;height:18px;accent-color:#e94560;">
                <label for="is_featured" style="cursor:pointer;font-weight:600;color:#374151;margin:0;">
                    <i class="fas fa-star" style="color:#f5a623;"></i> Mark as Featured Event
                </label>
            </div>
        </div>
        @endif

        {{-- ACTION BUTTONS --}}
        <div style="display:flex;gap:1rem;justify-content:flex-end;flex-wrap:wrap;margin-bottom:3rem;">
            <a href="{{ route('events.show', $event) }}" class="btn btn-secondary btn-lg">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="button" class="btn btn-primary btn-lg" onclick="submitForm('update')">
                <i class="fas fa-save"></i> Save Changes
            </button>
            @if(!Auth::user()->is_admin && $event->status === 'draft')
            <button type="button" class="btn btn-success btn-lg" onclick="submitForm('submit')">
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
