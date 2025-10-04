@extends('admin.layout')
@section('title', 'Feedback Analytics')
@section('page-title', 'Feedback Report')

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
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .page-header h2 {
        margin: 0;
        font-size: 1.75rem;
        color: #333;
    }

    .page-header .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px; 
        background: linear-gradient(45deg, #4e73df, #224abe);
        color: #fff;
        border: none;
        padding: 0.7rem 1.3rem;
        font-weight: 600;
        font-size: 0.9rem;
        border-radius: 6px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .page-header .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        background: linear-gradient(45deg, #5a7fef, #2f56d0);
    }

    .chart-card {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .report-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
</style>

<div class="page-header">
    <h2>Feedback Report</h2>
    <a href="{{ route('admin.feedback.questions') }}" class="btn btn-primary">
        <i class="fas fa-cog"></i>
        Manage Questions
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="report-grid">
    @forelse($chartData as $index => $chart)
        <div class="chart-card">
            <h4>{{ $loop->iteration }}. {{ $chart['question'] }}</h4>
            <canvas id="feedbackChart{{ $index }}"></canvas>
        </div>
    @empty
        <div class="chart-card" style="grid-column: 1 / -1;">
            <p>No feedback has been submitted yet. <a href="{{ route('admin.feedback.questions') }}">Manage Questions</a></p>
        </div>
    @endforelse
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartData = @json($chartData);

    // A more modern and visually appealing color palette
    const backgroundColors = [
        'rgba(75, 192, 192, 0.6)','rgba(54, 162, 235, 0.6)','rgba(255, 206, 86, 0.6)',
        'rgba(255, 159, 64, 0.6)','rgba(255, 99, 132, 0.6)'
    ];
    const borderColors = [
        'rgb(75, 192, 192)','rgb(54, 162, 235)','rgb(255, 206, 86)',
        'rgb(255, 159, 64)','rgb(255, 99, 132)'
    ];
    const hoverBackgroundColors = [
        'rgba(75, 192, 192, 0.85)','rgba(54, 162, 235, 0.85)','rgba(255, 206, 86, 0.85)',
        'rgba(255, 159, 64, 0.85)','rgba(255, 99, 132, 0.85)'
    ];

    chartData.forEach((chart, index) => {
        const ctx = document.getElementById('feedbackChart' + index).getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chart.labels,
                datasets: [{
                    label: 'Responses',
                    data: chart.data,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    hoverBackgroundColor: hoverBackgroundColors,
                    borderWidth: 1.5,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#555', font: { family: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif" } },
                        grid: { color: 'rgba(0, 0, 0, 0.05)' }
                    },
                    x: {
                        ticks: { color: '#555', font: { family: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif" } },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: true,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 12 },
                        padding: 12,
                        cornerRadius: 4,
                        displayColors: false,
                        callbacks: {
                            label: function(context) { return `Responses: ${context.parsed.y}`; }
                        }
                    }
                },
                onHover: (event, chartElement) => {
                    const target = event.native.target;
                    target.style.cursor = chartElement[0] ? 'pointer' : 'default';
                },
                animation: { duration: 800, easing: 'easeInOutQuart' }
            }
        });
    });
});
</script>
@endsection