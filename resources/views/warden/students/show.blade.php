@extends('warden.layout')

@section('title', 'Student Details')
@section('page-title', '')

@section('content')
<style>
    .details-container {
        max-width: 900px;
        margin: auto;
    }
    .details-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e3e6f0;
    }
    .details-header h2 {
        margin: 0;
        font-size: 1.75rem;
        color: #333;
    }
    .details-header .actions {
        display: flex;
        gap: 10px;
    }
    .details-card {
        background-color: #fff;
        padding: 1.5rem 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }
    .details-card h5 {
        font-weight: 600;
        font-size: 1.2rem;
        color: #4e73df;
        margin-top: 0;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid #e3e6f0;
        padding-bottom: 0.75rem;
    }
    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem 2rem;
    }
    .detail-item {
        margin-bottom: 0.5rem;
    }
    .detail-item strong {
        display: block;
        color: #858796;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 2px;
    }
    .detail-item span {
        font-size: 1rem;
        color: #5a5c69;
    }

    .btn {
        padding: 0.6rem 1.2rem;
        font-weight: 600;
        font-size: 0.9rem;
        text-align: center;
        text-decoration: none;
        color: white;
        border: none;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .btn-secondary { background-color: #858796; }
    .btn-warning { background-color: #f6c23e; }
</style>

<div class="details-container">
    <div class="details-header">
        <h2>{{ $student->full_name }}</h2>
        <div class="actions">
            <a href="{{ route('warden.students.index') }}" class="btn btn-secondary">&larr; Back to List</a>
            <a href="{{ route('warden.students.edit', $student->id) }}" class="btn btn-warning">Edit Student</a>
        </div>
    </div>

    <div class="details-card">
        <h5>Personal Information</h5>
        <div class="details-grid">
            <div class="detail-item"><strong>Full Name</strong><span>{{ $student->full_name }}</span></div>
            <div class="detail-item"><strong>Name with Initials</strong><span>{{ $student->initial_name }}</span></div>
            <div class="detail-item"><strong>NIC</strong><span>{{ $student->nic }}</span></div>
            <div class="detail-item"><strong>Date of Birth</strong><span>{{ $student->dob }}</span></div>
            <div class="detail-item"><strong>Gender</strong><span>{{ ucfirst($student->gender) }}</span></div>
            <div class="detail-item"><strong>Email</strong><span>{{ $student->user->email }}</span></div>
            <div class="detail-item"><strong>Telephone</strong><span>{{ $student->telephone_number }}</span></div>
            <div class="detail-item"><strong>Address</strong><span>{{ $student->address }}</span></div>
        </div>
    </div>

    <div class="details-card">
        <h5>Educational Information</h5>
        <div class="details-grid">
            <div class="detail-item"><strong>Registration No</strong><span>{{ $student->reg_no }}</span></div>
            <div class="detail-item"><strong>Faculty</strong><span>{{ $student->faculty }}</span></div>
            <div class="detail-item"><strong>Batch</strong><span>{{ $student->batch }}</span></div>
            <div class="detail-item"><strong>Department</strong><span>{{ $student->department }}</span></div>
            <div class="detail-item"><strong>Year</strong><span>{{ $student->year }}</span></div>
            <div class="detail-item"><strong>Course</strong><span>{{ $student->course ?? 'N/A' }}</span></div>
        </div>
    </div>

    <div class="details-card">
        <h5>Guardian Information</h5>
        <div class="details-grid">
            <div class="detail-item"><strong>Guardian Name</strong><span>{{ $student->guardian_name }}</span></div>
            <div class="detail-item"><strong>Relationship</strong><span>{{ ucfirst($student->guardian_relationship) }}</span></div>
            <div class="detail-item"><strong>Guardian Mobile</strong><span>{{ $student->guardian_mobile }}</span></div>
            <div class="detail-item"><strong>Emergency Contact</strong><span>{{ $student->emergency_contact_name }} ({{ $student->emergency_contact_number }})</span></div>
        </div>
    </div>

    <div class="details-card">
        <h5>Medical Information</h5>
        <div class="details-grid">
            <div class="detail-item"><strong>Long-term Medical Treatments</strong><span>{{ $student->medical_info }}</span></div>
        </div>
    </div>
</div>

@endsection