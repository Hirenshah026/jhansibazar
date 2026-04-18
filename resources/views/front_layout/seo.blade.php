{{-- Basic --}}
<title>{{ $seo['title'] ?? 'Jhansi Bazaar — Apna Sheher, Apna Platform' }}</title>
<meta name="description" content="{{ $seo['description'] ?? '' }}">
<meta name="keywords" content="{{ $seo['keywords'] ?? '' }}">
<link rel="icon" type="image/x-icon"        href="{{ $seo['favicon'] ?? asset('logo/logo3.jpeg') }}">
<meta name="author" content="{{ $seo['author'] ?? 'Jhansi Bazaar' }}">
<meta name="robots" content="{{ $seo['robots'] ?? 'index, follow' }}">
<link rel="canonical" href="{{ $seo['canonical'] ?? url()->current() }}">
<meta property="og:title"       content="{{ $seo['og_title'] ?? $seo['title'] ?? 'Jhansi Bazaar — Apna Sheher, Apna Platform' }}">
<meta property="og:description" content="{{ $seo['og_description'] ?? $seo['description'] ?? '' }}">
<meta property="og:image"       content="{{ $seo['og_image'] ?? asset('logo/logo_listee.png') }}">
<meta property="og:image:width"  content="1200">
<meta property="og:image:height" content="630">
<meta property="og:url"         content="{{ $seo['og_url'] ?? url()->current() }}">
<meta property="og:type"        content="{{ $seo['og_type'] ?? 'website' }}">
<meta property="og:site_name"   content="{{ $seo['og_site_name'] ?? 'Jhansi Bazaar — Apna Sheher, Apna Platform' }}">
<meta property="og:locale"      content="{{ $seo['og_locale'] ?? 'hi_IN' }}">