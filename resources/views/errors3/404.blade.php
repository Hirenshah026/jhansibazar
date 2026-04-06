<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 Not Found</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .error-container {
            text-align: center;
        }
        .error-code {
            font-size: 100px;
            font-weight: 800;
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <h3>Oops! Page not found.</h3>
        <p>The page you're looking for doesn't exist or has been moved.</p>
        <a href="{{ url('/') }}/" class="btn btn-primary mt-3">Go to Homepage</a>
    </div>
</body>
</html>
