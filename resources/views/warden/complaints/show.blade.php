@extends('warden.layout')

@section('title', 'Complaint Details')
@section('page-title', '')

@section('content')
<style>
    /* Page Container & Header */
    .page-container { max-width: 1100px; margin: auto; }
    .page-actions { margin-bottom: 25px; }

    /* Layout Grid */
    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        align-items: start;
    }
    @media (max-width: 992px) {
        .details-grid { grid-template-columns: 1fr; }
    }

    /* Modern Fieldset Style */
    .fieldset-modern {
        border: 1px solid #e3e6f0;
        padding: 1.5rem 2rem;
        border-radius: 8px;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .legend-modern {
        font-weight: 600;
        font-size: 1.2rem;
        color: #4e73df;
        padding: 0 10px;
    }

    /* Complaint Details Styling */
    .detail-item { margin-bottom: 1rem; }
    .detail-item strong { color: #5a5c69; }
    .detail-item p { margin-top: 0.25rem; }
    hr { border: 0; border-top: 1px solid #e3e6f0; margin: 1rem 0; }
    
    /* NEW STYLES FOR THE FORM */
    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #5a5c69;
    }
    .form-group select, .form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border-radius: 5px;
        border: 1px solid #d1d3e2;
        box-sizing: border-box;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-group select:focus, .form-group textarea:focus {
        outline: none;
        border-color: #4e73df;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.25);
    }
    
    /* Button Styles */
    .btn-secondary, .btn-submit {
        display: inline-block;
        padding: 0.6rem 1.2rem;
        font-weight: 600;
        font-size: 0.9rem;
        text-align: center;
        text-decoration: none;
        color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }
    .btn-secondary { background-color: #858796; }
    .btn-secondary:hover { background-color: #717384; transform: translateY(-2px); }
    .btn-submit { background-color: #1cc88a; }
    .btn-submit:hover { background-color: #17a673; transform: translateY(-2px); }
</style>

<div class="page-container">
    <div class="page-actions">
        <a href="{{ route('warden.complaints.index') }}" class="btn-secondary">&larr; Back to Complaints List</a>
    </div>

    <div class="details-grid">
        <fieldset class="fieldset-modern">
            <legend class="legend-modern">Complaint Details</legend>
            <div class="detail-item"><strong>Student:</strong> <p>{{ $complaint->student->full_name }}</p></div>
            <div class="detail-item"><strong>Reg No:</strong> <p>{{ $complaint->student->reg_no }}</p></div>
            <div class="detail-item"><strong>Submitted:</strong> <p>{{ $complaint->created_at->diffForHumans() }}</p></div>
            <div class="detail-item"><strong>Type:</strong> <p>{{ $complaint->type }}</p></div>
            <hr>
            <div class="detail-item">
                <strong>Message:</strong>
                <p style="white-space: pre-wrap;">{{ $complaint->message }}</p>
            </div>
            @if($complaint->image_path)
                <div class="detail-item" style="margin-top: 1rem;">
                    <strong>Attachment:</strong><br>
                    <a href="{{ asset('storage/' . $complaint->image_path) }}" target="_blank">
                        <img src="{{ asset('storage/' . $complaint->image_path) }}" alt="Complaint Attachment" style="max-width: 100%; border-radius: 8px; margin-top: 10px; cursor: pointer;">
                    </a>
                </div>
            @endif
        </fieldset>

        <fieldset class="fieldset-modern">
            <legend class="legend-modern">Update Status & Reply</legend>
            <form action="{{ route('warden.complaints.update', $complaint->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="status">Update Status</label>
                    <select name="status" id="status" required>
                        <option value="pending" {{ $complaint->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ $complaint->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ $complaint->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="form-group" style="margin-top: 1.5rem;">
                    <label for="admin_reply">Your Reply (visible to student)</label>
                    <textarea name="admin_reply" id="admin_reply" rows="8">{{ old('admin_reply', $complaint->admin_reply) }}</textarea>
                </div>
                <button type="submit" class="btn-submit" style="width: 100%; margin-top: 1.5rem;">Save Changes</button>
            </form>
        </fieldset>
    </div>
</div>
@endsection