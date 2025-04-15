@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Settings</h1>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Settings Groups</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($groups as $group)
                        <a href="{{ route('admin.settings.index', ['group' => $group]) }}" class="list-group-item list-group-item-action {{ $activeGroup === $group ? 'active' : '' }}">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <h6 class="mb-0">{{ ucfirst($group) }}</h6>
                                <i class="bi bi-chevron-right"></i>
                            </div>
                            <small>
                                @if($group === 'general')
                                    Site title, logo, favicon, etc.
                                @elseif($group === 'seo')
                                    Meta tags, robots.txt, sitemap
                                @elseif($group === 'social')
                                    Social media links and sharing
                                @elseif($group === 'analytics')
                                    Google Analytics, Facebook Pixel, etc.
                                @endif
                            </small>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ ucfirst($activeGroup) }} Settings</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="group" value="{{ $activeGroup }}" />
                        
                        @foreach($settings as $setting)
                        <div class="mb-3">
                            <label for="{{ $setting->key }}" class="form-label">
                                {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                            </label>
                            
                            @if($setting->type === 'text')
                                <input type="text" 
                                       class="form-control @error($setting->key) is-invalid @enderror" 
                                       id="{{ $setting->key }}" 
                                       name="{{ $setting->key }}" 
                                       value="{{ old($setting->key, $setting->value) }}">
                            
                            @elseif($setting->type === 'textarea')
                                <textarea class="form-control @error($setting->key) is-invalid @enderror" 
                                          id="{{ $setting->key }}" 
                                          name="{{ $setting->key }}" 
                                          rows="4">{{ old($setting->key, $setting->value) }}</textarea>
                            
                            @elseif($setting->type === 'boolean')
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="{{ $setting->key }}" 
                                           name="{{ $setting->key }}" 
                                           value="1" 
                                           {{ old($setting->key, $setting->value) == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="{{ $setting->key }}">Enable</label>
                                </div>
                            
                            @elseif($setting->type === 'number')
                                <input type="number" 
                                       class="form-control @error($setting->key) is-invalid @enderror" 
                                       id="{{ $setting->key }}" 
                                       name="{{ $setting->key }}" 
                                       value="{{ old($setting->key, $setting->value) }}">
                            
                            @elseif($setting->type === 'file')
                                <input type="file" 
                                       class="form-control @error($setting->key) is-invalid @enderror" 
                                       id="{{ $setting->key }}" 
                                       name="{{ $setting->key }}">
                                
                                @if($setting->value)
                                    <div class="mt-2">
                                        @if(in_array(pathinfo($setting->value, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                            <img src="{{ asset('storage/' . $setting->value) }}" alt="{{ $setting->key }}" class="img-thumbnail" style="max-height: 100px;">
                                        @else
                                            <a href="{{ asset('storage/' . $setting->value) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                <i class="bi bi-file-earmark"></i> View File
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            
                            @elseif($setting->type === 'select' && !empty($setting->options))
                                <select class="form-select @error($setting->key) is-invalid @enderror" 
                                        id="{{ $setting->key }}" 
                                        name="{{ $setting->key }}">
                                    @foreach(json_decode($setting->options, true) as $value => $label)
                                        <option value="{{ $value }}" {{ old($setting->key, $setting->value) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif

                            @error($setting->key)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            @if($setting->key === 'robots_txt')
                                <small class="form-text text-muted">This will be written to your robots.txt file.</small>
                            @elseif($setting->key === 'generate_sitemap')
                                <small class="form-text text-muted">If enabled, a sitemap.xml file will be generated.</small>
                            @elseif($setting->key === 'meta_title_format')
                                <small class="form-text text-muted">Use %title% for the page title and %site_title% for the site title.</small>
                            @endif
                        </div>
                        @endforeach
                        
                        <button type="submit" class="btn btn-primary">Save {{ ucfirst($activeGroup) }} Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($activeGroup === 'seo')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">SEO Tools</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Generate Sitemap</h5>
                                    <p class="card-text">Create an XML sitemap to help search engines understand the structure of your site.</p>
                                    <form action="{{ route('admin.settings.generate.sitemap') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">Generate Sitemap</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Update Robots.txt</h5>
                                    <p class="card-text">Update your robots.txt file to control search engine access.</p>
                                    <a href="#" class="btn btn-primary">Learn More</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Update Meta Tags</h5>
                                    <p class="card-text">Update your meta tags to optimize your site for search engines.</p>
                                    <a href="#" class="btn btn-primary">Learn More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection 