<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'KTI Monitor' ?></title>
    <link rel="stylesheet" href="/resources/assets/css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="/" class="logo">KTI Monitor</a>
            <ul class="nav-links">
                <li><a href="/dashboard">Dashboard</a></li>
                <li><a href="/map">Peta</a></li>
            </ul>
        </div>
    </nav>
    <main class="container">
        <?= $content ?? '' ?>
    </main>
    <footer>
        <div class="container">
            <p>&copy; <?= date('Y') ?> KTI Monitor. Data dari BMKG.</p>
        </div>
    </footer>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="/resources/assets/js/app.js"></script>
</body>
</html>
