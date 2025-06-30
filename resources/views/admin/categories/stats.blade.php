@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Category Statistics</h1>
        <div>
            <a href="{{ route('admin.categories.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="bi bi-list"></i> Category List
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Total Categories -->
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
                            <i class="bi bi-folder2 fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Posts in Categories -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Categorized Posts</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $categories->sum('posts_count') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-file-text fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Uncategorized Posts -->
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
                            <i class="bi bi-question-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Comments on Categorized Posts -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Comments on Posts</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $categories->sum('comments_count') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-chat-dots fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Most Active Categories (Last 30 Days) -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Most Active Categories (Last 30 Days)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="activeCategories"></canvas>
                    </div>
                    <div class="mt-4">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>New Posts</th>
                                    <th>Total Posts</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($mostActiveCategories as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->posts_count }}</td>
                                    <td>{{ $categories->firstWhere('id', $category->id)->posts_count }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Engagement -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Category Engagement Metrics</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie mb-4">
                        <canvas id="categoryEngagement"></canvas>
                    </div>
                    <div class="mt-4">
                        <table class="table table-bordered table-sm">
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
                                    <td>{{ number_format($category->engagement_rate, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Category Overview -->
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Category Overview</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="categoriesTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Posts</th>
                                    <th>Comments</th>
                                    <th>Last Post</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->posts_count }}</td>
                                    <td>{{ $category->comments_count }}</td>
                                    <td>
                                        @php
                                            $lastPost = $category->posts()->latest()->first();
                                        @endphp
                                        @if($lastPost)
                                            {{ $lastPost->created_at->format('Y-m-d') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="{{ route('categories.show', $category) }}" class="btn btn-sm btn-primary" target="_blank">
                                            <i class="bi bi-eye"></i>
                                        </a>
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
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data from the backend
    const categoryNames = @json($categoryNames);
    const chartLabels = @json($chartLabels);
    const chartData = @json($chartData);
    const chartColors = [
        '#4E73DF', '#1CC88A', '#36B9CC', '#F6C23E', '#E74A3B', 
        '#5A5C69', '#858796', '#6610F2', '#6F42C1', '#E83E8C'
    ];
    
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.font.family = 'Nunito, -apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.color = '#858796';

    // Active Categories Chart
    const ctxActive = document.getElementById('activeCategories').getContext('2d');
    new Chart(ctxActive, {
        type: 'bar',
        data: {
            labels: {!! json_encode($mostActiveCategories->pluck('name')) !!},
            datasets: [{
                label: "Posts in last 30 days",
                backgroundColor: "#4e73df",
                hoverBackgroundColor: "#2e59d9",
                borderColor: "#4e73df",
                data: {!! json_encode($mostActiveCategories->pluck('posts_count')) !!},
            }],
        },
        options: {
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Category Engagement Chart
    const ctxEngagement = document.getElementById('categoryEngagement').getContext('2d');
    new Chart(ctxEngagement, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($categoryEngagement->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($categoryEngagement->pluck('engagement_rate')) !!},
                backgroundColor: chartColors,
                hoverBackgroundColor: chartColors.map(color => color + 'dd'),
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.raw.toFixed(2) + ' engagement per post';
                        }
                    }
                }
            },
            cutout: '70%',
        }
    });

    // Initialize DataTable
    $(document).ready(function() {
        $('#categoriesTable').DataTable({
            order: [[1, 'desc']]
        });
    });
</script>
@endsection 