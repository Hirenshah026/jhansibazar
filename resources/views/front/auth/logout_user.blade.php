<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <title>Clearing Storage...</title>
</head>
<body>

    <script>
        // 1. LocalStorage ko puri tarah saaf karne ke liye
        localStorage.clear();

        // 2. Agar SessionStorage bhi clear karni ho (optional)
        sessionStorage.clear();

        // 3. Home page (/) par redirect karne ke liye
        window.location.href = "/";
    </script>
</body>
</html>