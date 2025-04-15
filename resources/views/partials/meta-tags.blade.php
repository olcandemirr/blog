{{-- Temel Meta Etiketleri --}}
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- SEO Meta Etiketleri --}}
<title>{{ isset($title) ? str_replace(['%title%', '%site_title%'], [$title, $siteTitle], $ogTitle) : $siteTitle }}</title>
<meta name="description" content="{{ $siteDescription }}">
@if($siteKeywords)
<meta name="keywords" content="{{ $siteKeywords }}">
@endif
@if(isset($canonical))
<link rel="canonical" href="{{ $canonical }}">
@endif

{{-- Favicon --}}
@if(!empty($siteFavicon))
<link rel="icon" href="{{ asset('storage/' . $siteFavicon) }}">
@endif

{{-- Open Graph / Facebook --}}
<meta property="og:type" content="{{ isset($post) ? 'article' : 'website' }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ isset($title) ? str_replace(['%title%', '%site_title%'], [$title, $siteTitle], $ogTitle) : $siteTitle }}">
<meta property="og:description" content="{{ isset($description) ? $description : $ogDescription }}">
@if(!empty($ogImage))
<meta property="og:image" content="{{ asset('storage/' . $ogImage) }}">
@elseif(isset($imageUrl))
<meta property="og:image" content="{{ $imageUrl }}">
@endif
<meta property="og:site_name" content="{{ $siteTitle }}">

{{-- Twitter --}}
<meta name="twitter:card" content="{{ $twitterCard }}">
@if($twitterSite)
<meta name="twitter:site" content="{{ $twitterSite }}">
@endif
<meta name="twitter:title" content="{{ isset($title) ? str_replace(['%title%', '%site_title%'], [$title, $siteTitle], $ogTitle) : $siteTitle }}">
<meta name="twitter:description" content="{{ isset($description) ? $description : $ogDescription }}">
@if(!empty($ogImage))
<meta name="twitter:image" content="{{ asset('storage/' . $ogImage) }}">
@elseif(isset($imageUrl))
<meta name="twitter:image" content="{{ $imageUrl }}">
@endif