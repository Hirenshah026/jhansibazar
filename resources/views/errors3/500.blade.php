<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>500 Internal Server Error</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fff0f0;
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
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">500</div>
        <h3>Something went wrong!</h3>
        <p>There was an internal server error. Please try again later.</p>
        <a href="{{ url('/') }}/" class="btn btn-danger mt-3">Return to Home</a>
    </div>
</body>
</html>
