@extends('admin.layout')
@section('title', 'Settings')
@section('page-title', 'Account Settings')

@section('content')
<style>
    .settings-card { background-color: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); max-width: 600px; margin: auto; }
    .settings-section { border-bottom: 1px solid #e3e6f0; padding-bottom: 1.5rem; margin-bottom: 1.5rem; }
    .settings-section:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .settings-section h5 { font-size: 1.2rem; font-weight: 600; color: #5a5c69; margin-bottom: 1rem; }
    .account-email { font-size: 1rem; color: #333; background: #f8f9fc; padding: 0.75rem; border-radius: 5px; border: 1px solid #e3e6f0; }
    
    .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(5px); }
    .modal-content { background-color: #fefefe; margin: 10% auto; padding: 25px; border: 1px solid #888; width: 90%; max-width: 450px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); animation: fadeIn 0.3s; }
    @keyframes fadeIn { from {opacity: 0; transform: scale(0.95);} to {opacity: 1; transform: scale(1);} }
    .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e3e6f0; padding-bottom: 10px; margin-bottom: 20px; }
    .modal-header h3 { margin: 0; color: #333; }
    .close-button { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; }
    .modal-step { display: none; }
    .modal-step.active { display: block; }

    .modal .form-group label { font-weight: 600; margin-bottom: 0.5rem; color: #5a5c69; display: block; }
    .modal .form-group input {
        width: 100%;
        padding: 0.75rem;
        border-radius: 5px;
        border: 1px solid #d1d3e2;
        box-sizing: border-box;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .modal .form-group input:focus {
        outline: none;
        border-color: #4e73df;
        box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.25);
    }
    #modal-error { 
        padding: 0.75rem;
        margin-top: 1rem;
        border-radius: 5px;
        background-color: #fee2e2; 
        color: #991b1b; 
        border: 1px solid #fecaca;
        font-weight: 500;
        display: none; 
    }

    .details-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.8rem;
        margin-bottom: 1.5rem;
    }
    .detail-item { /* No changes here, just for context */
        display: flex; flex-direction: column; background: #f8f9fc; padding: 0.75rem;
        border-radius: 5px; border: 1px solid #e3e6f0;
    }
    .detail-label { /* No changes here */
        font-weight: 600; color: #5a5c69; font-size: 0.8rem; margin-bottom: 0.25rem;
    }

    /* --- NEW ANIMATION & LAYOUT STYLES --- */
    .more-details {
        max-height: 0; /* Initially collapsed */
        overflow: hidden; /* Hide the content when collapsed */
        transition: max-height 0.5s ease-in-out, margin-top 0.5s ease-in-out; /* Smooth transition */
    }
    /* This class will be added by JavaScript to expand the section */
    .more-details.show {
        max-height: 500px; /* Animate to a height large enough for the content */
        margin-top: 1.5rem; /* Add space when it appears */
    }
    /* UPDATED: Grid inside 'more-details' is now TWO columns */
    .more-details .details-grid {
        grid-template-columns: 1fr 1fr; /* Two equal columns */
        gap: 1rem; /* Space between items */
    }

    /* --- NEW BUTTON LAYOUT STYLES --- */
    .action-buttons {
        display: flex; /* Arrange buttons horizontally */
        gap: 1rem; /* Space between buttons */
        margin-top: 1rem;
    }
    /* Base button style adjustments */
    .btn {
        width: 100%; /* Make buttons fill the flex container space */
        padding: 0.75rem; font-size: 0.9rem; font-weight: 600; border-radius: 5px;
        border: none; cursor: pointer; text-align: center; text-decoration: none;
        transition: all 0.2s ease-in-out;
        /* REMOVED margin-left, as flexbox now handles layout */
    }
    .btn:hover {
        transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .btn-secondary {
        background-color: #f8f9fc; color: #5a5c69; border: 1px solid #d1d3e2;
    }
    .btn-secondary:hover { background-color: #e3e6f0; }
    .btn-warning { background-color: #f6c23e; color: #fff; }
    .btn-danger { background-color: #e74a3b; color: #fff; }
    .btn-primary { background-color: #4e73df; color: #fff; }

</style>

<div class="settings-card">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="settings-section">
        <h5>Account Details</h5>
        @if($admin)
            <div class="details-grid">
                <div class="detail-item">
                    <span class="detail-label">Full Name:</span>
                    <span>{{ $admin->full_name }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email:</span>
                    <span>{{ Auth::user()->email }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Telephone:</span>
                    <span>{{ $admin->telephone }}</span>
                </div>
            </div>

            <div id="more-details-content" class="more-details">
                <div class="details-grid">
                    <div class="detail-item">
                        <span class="detail-label">Name with Initials:</span>
                        <span>{{ $admin->initial_name }}</span>
                    </div>
                     <div class="detail-item">
                        <span class="detail-label">NIC:</span>
                        <span>{{ $admin->nic }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Address:</span>
                        <span>{{ $admin->address }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Date of Birth:</span>
                        <span>{{ \Carbon\Carbon::parse($admin->dob)->format('F j, Y') }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Nationality:</span>
                        <span>{{ $admin->nationality }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Civil Status:</span>
                        <span>{{ ucfirst($admin->civil_status) }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Province:</span>
                        <span>{{ $admin->province }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">District:</span>
                        <span>{{ $admin->district }}</span>
                    </div>
                </div>
            </div>

            <button id="toggle-details-btn" class="btn btn-secondary">More Details</button>

        @else
            <p>Your profile is not yet updated. Please update your profile.</p>
        @endif
    </div>

    <div class="settings-section">
        <h5>Actions</h5>
        <div class="action-buttons">
            <a href="{{ route('admin.settings.profile') }}" class="btn btn-primary">Update Profile</a>
            <button id="changePasswordBtn" class="btn btn-warning">Change Password</button>
            <form action="{{ route('logout') }}" method="POST" style="width: 100%;">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </div>
    </div>
</div>

<div id="passwordModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Change Password</h3>
            <span class="close-button">&times;</span>
        </div>

        <div id="step1" class="modal-step active">
            <p>Enter your account email to receive a verification OTP.</p>
            <form id="sendOtpForm">
                @csrf
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top: 1rem; width: 100%;">Send OTP</button>
            </form>
        </div>

        <div id="step2" class="modal-step">
            <p>An OTP has been sent. Please enter it below.</p>
            <form id="verifyOtpForm">
                @csrf
                <div class="form-group">
                    <label for="otp">6-Digit OTP</label>
                    <input type="text" id="otp" name="otp" required>
                </div>
                <button type="submit" class="btn btn-primary" style="margin-top: 1rem; width: 100%;">Confirm</button>
            </form>
        </div>

        <div id="step3" class="modal-step">
            <p>OTP verified! You can now set a new password.</p>
            <form id="changePasswordForm" action="{{ route('admin.settings.changePassword') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group" style="margin-top: 1rem;">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required>
                </div>
                <button type="submit" class="btn btn-submit" style="margin-top: 1rem; width: 100%;">Change Password</button>
            </form>
        </div>
        <div id="modal-error"></div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggle-details-btn');
    const moreDetailsContent = document.getElementById('more-details-content');

    if (toggleBtn && moreDetailsContent) {
        toggleBtn.addEventListener('click', function() {
            // Toggle the .show class on the content div
            moreDetailsContent.classList.toggle('show');

            // Check if the class is now present to update the button text
            if (moreDetailsContent.classList.contains('show')) {
                this.textContent = 'Hide'; // Change button text to "Hide"
            } else {
                this.textContent = 'More Details'; // Change button text back
            }
        });
    }
});
</script>
@endpush