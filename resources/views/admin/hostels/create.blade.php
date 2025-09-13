@extends('admin.layout')

@section('title', 'Add Hostel')
@section('page-title', 'Add New Hostel')

@section('content')
<style>
    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .page-header h2 {
        margin: 0;
        font-size: 1.75rem;
    }
    .btn-back {
        display: inline-block; padding: 0.6rem 1.2rem; font-weight: 600; font-size: 0.9rem;
        text-align: center; text-decoration: none; color: #fff; background-color: #858796;
        border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: all 0.2s;
    }
    .btn-back:hover { background-color: #717384; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.15); }

    /* Form Styles */
    .form-container {
        max-width: 700px; margin: auto; background-color: #fff; padding: 2rem;
        border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    label {
        display: block; font-weight: 600; margin-bottom: 0.5rem; color: #5a5c69;
    }
    input[type="text"], input[type="number"], select {
        width: 100%; padding: 0.75rem; border-radius: 5px; border: 1px solid #d1d3e2;
        box-sizing: border-box; transition: border-color 0.2s, box-shadow 0.2s;
    }
    input:focus, select:focus {
        outline: none; border-color: #4e73df; box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.25);
    }
    .note-text {
        margin-top: 1.5rem; color: #858796; background-color: #f8f9fc; padding: 1rem;
        border-radius: 5px; border-left: 4px solid #4e73df;
    }

    /* Button Styles */
    .form-buttons {
        display: flex; 
        gap: 1rem; 
        margin-top: 2rem;
        width: 50%;
        margin-left: 25%;
    }
    .btn {
        flex-grow: 1; 
        padding: 0.85rem; 
        font-size: 1rem; 
        font-weight: 600; 
        border-radius: 5px;
        cursor: pointer; 
        text-align: center; 
        text-decoration: none; 
        border: none; 
        transition: all 0.2s;
    }
    .btn-submit { background-color: #1cc88a; color: white; }
    .btn-submit:hover { background-color: #17a673; }
    .btn-secondary { background-color: #858796; color: white; }
    .btn-secondary:hover { background-color: #717384; }

    .floor-inputs-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e3e6f0;
    }
</style>

<div class="page-header">
    <a href="{{ route('admin.hostels.index') }}" class="btn-back">&larr; Back to All Hostels</a>
</div>

<div class="form-container">
    @if ($errors->any())
        <div style="list-style-type: none; padding: 10px; background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; border-radius: 5px; margin-bottom: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.hostels.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Hostel Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g., Villa 1 Boys Hostel" required>
        </div>
        <div class="form-group">
            <label for="warden_id">Assign a Warden</label>
            <select id="warden_id" name="warden_id" required>
                <option value="">Select a Warden</option>
                @foreach($wardens as $warden)
                    <option value="{{ $warden->id }}" {{ old('warden_id') == $warden->id ? 'selected' : '' }}>
                        {{ $warden->full_name }} ({{ $warden->nic }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="floor-inputs-grid">
            <div class="form-group">
                <label for="rooms_ground">Rooms on Ground Floor</label>
                <input type="number" id="rooms_ground" name="rooms_ground" value="{{ old('rooms_ground', 0) }}" required min="0">
            </div>
            <div class="form-group">
                <label for="rooms_first">Rooms on First Floor</label>
                <input type="number" id="rooms_first" name="rooms_first" value="{{ old('rooms_first', 0) }}" required min="0">
            </div>
            <div class="form-group">
                <label for="rooms_second">Rooms on Second Floor</label>
                <input type="number" id="rooms_second" name="rooms_second" value="{{ old('rooms_second', 0) }}" required min="0">
            </div>
            <div class="form-group">
                <label for="rooms_third">Rooms on Third Floor</label>
                <input type="number" id="rooms_third" name="rooms_third" value="{{ old('rooms_third', 0) }}" required min="0">
            </div>
        </div>
        <div class="note-text">
            <strong>Note:</strong> Rooms will be numbered sequentially starting from 1 based on the floor-wise counts provided.
        </div>
        
        <div class="form-buttons">
            <button type="submit" class="btn btn-submit">Add Hostel</button>
            <a href="{{ route('admin.hostels.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

{{-- This push ensures the alert styles are available --}}
@push('styles')
<style>
    .alert { padding: 1rem; margin-bottom: 1rem; border-radius: 5px; border: 1px solid transparent; }
    .alert-danger { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
</style>
@endpush
@endsection