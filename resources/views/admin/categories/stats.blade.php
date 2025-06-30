@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Category Statistics</h1>
        <a href="{{ route('admin.categories.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Categories
        </a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Total Categories Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Categories</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $categories->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Posts Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Posts</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $categories->sum('posts_count') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Comments Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Comments</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $categories->sum('comments_count') ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Uncategorized Posts Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Uncategorized Posts</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $uncategorizedCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-question-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Most Active Categories -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Most Active Categories (Last 30 Days)</h6>
                </div>
                <div class="card-body">
                    @if($mostActiveCategories->count() > 0)
                        @foreach($mostActiveCategories as $category)
                            <h4 class="small font-weight-bold">{{ $category->name }} <span class="float-right">{{ $category->posts_count }} Posts</span></h4>
                            <div class="progress mb-4">
                                @php
                                    $maxPosts = $mostActiveCategories->max('posts_count');
                                    $percentage = $maxPosts > 0 ? ($category->posts_count / $maxPosts) * 100 : 0;
                                    
                                    if ($percentage > 75) {
                                        $color = 'bg-success';
                                    } elseif ($percentage > 50) {
                                        $color = 'bg-info';
                                    } elseif ($percentage > 25) {
                                        $color = 'bg-warning';
                                    } else {
                                        $color = 'bg-danger';
                                    }
                                @endphp
                                <div class="progress-bar {{ $color }}" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-center">No active categories in the last 30 days.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Category Engagement -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Category Engagement</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Posts</th>
                                    <th>Comments</th>
                                    <th>Likes</th>
                                    <th>Engagement Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categoryEngagement as $category)
                                    <tr>
                                        <td>{{ $category->name }}</td>
                                        <td>{{ $category->posts_count }}</td>
                                        <td>{{ $category->comments_count }}</td>
                                        <td>{{ $category->likes_count }}</td>
                                        <td>
                                            @if($category->engagement_rate > 0)
                                                {{ number_format($category->engagement_rate, 2) }}
                                            @else
                                                0
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Posts Chart -->
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Posts by Category (Last 12 Months)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="monthlyPostsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script>
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    // Monthly Posts Chart
    var ctx = document.getElementById("monthlyPostsChart");
    var labels = @json($chartLabels);
    var categoryNames = @json($categoryNames);
    var chartData = @json($chartData);
    
    // Prepare datasets
    var datasets = [];
    var chartColors = [
        '#4E73DF', '#1CC88A', '#36B9CC', '#F6C23E', '#E74A3B', 
        '#5A5C69', '#858796', '#6610F2', '#6F42C1', '#E83E8C'
    ];
    
    // Initialize datasets for each category
    for (let i = 0; i < categoryNames.length; i++) {
        datasets.push({
            label: categoryNames[i],
            data: [],
            backgroundColor: chartColors[i % chartColors.length],
            borderColor: chartColors[i % chartColors.length],
            borderWidth: 1
        });
    }
    
    // Fill in data for each month
    for (let i = 0; i < labels.length; i++) {
        let monthData = chartData[i];
        for (let j = 0; j < monthData.length; j++) {
            let categoryIndex = categoryNames.indexOf(monthData[j].label);
            if (categoryIndex !== -1) {
                datasets[categoryIndex].data[i] = monthData[j].data;
            }
        }
    }
    
    var myBarChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                xAxes: [{
                    time: {
                        unit: 'month'
                    },
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 12
                    },
                    stacked: true,
                }],
                yAxes: [{
                    ticks: {
                        min: 0,
                        maxTicksLimit: 5,
                        padding: 10,
                    },
                    gridLines: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    },
                    stacked: true,
                }],
            },
            legend: {
                display: true,
                position: 'bottom'
            },
            tooltips: {
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
        }
    });
</script>
@endpush 