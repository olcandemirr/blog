@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    // ... existing stats cards ...

    <!-- Charts Row -->
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Activity Overview (Last 30 Days)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 320px;">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        // ... existing popular posts and active users tables ...
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('activityChart').getContext('2d');
    const chartData = @json($chartData); // Controller'dan gelen veriyi al

    const activityChart = new Chart(ctx, {
        type: 'line', // Grafik tipi: Çizgi
        data: {
            labels: chartData.labels, // X ekseni etiketleri (tarihler)
            datasets: [{
                label: 'New Users',
                data: chartData.users, // Kullanıcı verisi
                borderColor: 'rgb(78, 115, 223)', // Mavi renk
                tension: 0.1,
                fill: false
            }, {
                label: 'New Posts',
                data: chartData.posts, // Gönderi verisi
                borderColor: 'rgb(28, 200, 138)', // Yeşil renk
                tension: 0.1,
                fill: false
            }, {
                label: 'New Comments',
                data: chartData.comments, // Yorum verisi
                borderColor: 'rgb(246, 194, 62)', // Sarı renk
                tension: 0.1,
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true, // Y ekseni 0'dan başlasın
                    ticks: {
                        precision: 0 // Tam sayıları göster
                    }
                }
            },
            plugins: {
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            }
        }
    });
});
</script>
@endpush 