@extends('admin.layout')
@section('title', 'Feedback Analytics')
@section('page-title', 'Feedback Report')

@section('content')

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
        padding: 0.6rem 1.2rem;
        font-weight: 600;
        font-size: 0.9rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: all 0.2s ease-in-out;
    }
    .page-header .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .chart-card {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .report-grid {
            display: grid;
            grid-template-columns: 1fr 1fr; /* This creates the two-column layout */
            gap: 20px; /* This adds space between the chart cards */
    }
</style>

<div class="page-header">
    <h2>Feedback Report</h2>
    <a href="{{ route('admin.feedback.questions') }}" class="btn btn-primary">Manage Questions</a>
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
    chartData.forEach((chart, index) => {
        const ctx = document.getElementById('feedbackChart' + index).getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chart.labels,
                datasets: [{
                    label: 'Number of Responses',
                    data: chart.data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)', 'rgba(255, 159, 64, 0.5)',
                        'rgba(255, 205, 86, 0.5)', 'rgba(75, 192, 192, 0.5)',
                        'rgba(54, 162, 235, 0.5)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)', 'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)', 'rgb(75, 192, 192)', 'rgb(54, 162, 235)'
                    ],
                    borderWidth: 1
                }]
            },
            options: { scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
        });
    });
});
</script>
@endsection