<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel SPA (No NPM)</title>

    <script src="https://unpkg.com/@hotwired/turbo@7.3.0/dist/turbo.es2017-umd.js"></script>
    <style>
        body { font-family: sans-serif; margin: 0; background: #f4f7f6; }
        nav { background: #1a202c; padding: 15px; text-align: center; }
        nav a { color: white; margin: 0 15px; text-decoration: none; font-weight: bold; }
        nav a:hover { color: #63b3ed; }
        .main-content { max-width: 900px; margin: 50px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        
        /* Turbo loading bar color */
        .turbo-progress-bar { background: #3182ce; height: 4px; }
    </style>
</head>
<body>

    <nav>
        <a href="{{ url('home') }}">Home</a>
        <a href="{{ url('about') }}">About</a>
        <a href="{{ url('services') }}">Services</a>
        <a href="{{ url('contact') }}">Contact</a>
    </nav>

    <div class="main-content">
        @yield('content')
    </div>

</body>
</html>