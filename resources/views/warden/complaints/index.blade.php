@extends('warden.layout')

@section('title', 'Manage Complaints')
@section('page-title', 'Student Complaints')

@section('content')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Get the new elements for date and time text.
    const dateText = document.getElementById('date-text');
    const timeText = document.getElementById('time-text');

    // Check if the elements exist on the page.
    if (dateText && timeText) {
        
        function updateClock() {
            const now = new Date();
            
            // Create a more beautiful, readable date format.
            // Example: Saturday, October 4, 2025
            const formattedDate = now.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            // Format the time. Example: 3:06:06 PM
            const formattedTime = now.toLocaleTimeString('en-US');

            // Update the text for both elements separately.
            dateText.textContent = formattedDate;
            timeText.textContent = formattedTime;
        }

        // Run once to show the time immediately.
        updateClock();
        
        // Update every second.
        setInterval(updateClock, 1000);
    }
});
</script>
@endpush

<style>
    /* You can copy the same beautiful styles from your admin/complaints/index.blade.php file */
    .filter-tabs { margin-bottom: 20px; background-color: #fff; padding: 10px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); display: inline-block; }
    .filter-tabs .btn { margin-right: 5px; background-color: #f8f9fc; border: 1px solid #d1d3e2; color: #5a5c69; font-weight: 600; padding: 0.5rem 1rem; border-radius: 5px; text-decoration: none; transition: all 0.2s ease; }
    .filter-tabs .btn:hover { background-color: #e3e6f0; }
    .filter-tabs .btn.active { background-color: #4e73df; color: white; border-color: #4e73df; box-shadow: 0 2px 5px rgba(78, 115, 223, 0.3); }
    .table-container { background-color: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 0.9rem; border-bottom: 1px solid #e3e6f0; text-align: left; vertical-align: middle; }
    thead th { background-color: #f8f9fc; font-weight: 600; color: #5a5c69; border-top: 1px solid #e3e6f0; }
    tbody tr:hover { background-color: #f8f9fc; }
    .status-badge { padding: 0.3em 0.7em; font-size: 0.75em; font-weight: 700; border-radius: 10rem; color: #fff; text-transform: uppercase; letter-spacing: 0.5px; }
    .status-pending { background-color: #f6c23e; }
    .status-in_progress { background-color: #36b9cc; }
    .status-completed { background-color: #1cc88a; }
    .actions .btn { padding: 0.4rem 0.8rem; font-size: 0.8rem; border-radius: 5px; text-decoration: none; color: white; border: none; cursor: pointer; transition: all 0.2s; }
    .actions .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
    .btn-primary { background-color: #4e73df; }
</style>

<!-- Status Filter Tabs -->
<div class="filter-tabs">
    <a href="{{ route('warden.complaints.index') }}" class="btn {{ !request('status') ? 'active' : '' }}">All</a>
    <a href="{{ route('warden.complaints.index', ['status' => 'pending']) }}" class="btn {{ request('status') == 'pending' ? 'active' : '' }}">Pending</a>
    <a href="{{ route('warden.complaints.index', ['status' => 'in_progress']) }}" class="btn {{ request('status') == 'in_progress' ? 'active' : '' }}">In Progress</a>
    <a href="{{ route('warden.complaints.index', ['status' => 'completed']) }}" class="btn {{ request('status') == 'completed' ? 'active' : '' }}">Completed</a>
</div>

<!-- Complaints Table -->
<div class="table-container">
    <table>
        <thead>
            <tr>
                <!-- <th>Student Name</th>
                <th>Reg No</th> -->
                <th>Complaint Type</th>
                <th>Submitted On</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($complaints as $complaint)
            <tr>
                <!-- <td>{{ $complaint->student->full_name ?? 'N/A' }}</td>
                <td><strong>{{ $complaint->student->reg_no ?? 'N/A' }}</strong></td> -->
                <td>{{ $complaint->type }}</td>
                <td>{{ $complaint->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    <span class="status-badge status-{{ str_replace(' ', '_', strtolower($complaint->status)) }}">
                        {{ ucwords(str_replace('_', ' ', $complaint->status)) }}
                    </span>
                </td>
                <td class="actions">
                    <a href="{{ route('warden.complaints.show', $complaint->id) }}" class="btn btn-primary">View / Reply</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 2rem;">No complaints found for this status.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 20px;">
    {{ $complaints->appends(request()->query())->links() }}
</div>
@endsection