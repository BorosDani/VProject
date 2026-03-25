<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="error-page-container">
        <div class="error-content">
            <h1 class="error-code">404</h1>
            <h2 class="error-title">Sajnos nem találtuk a kérelmedet!</h2>
            <p class="error-message">Úgy tűnik, ez az oldal már nem létezik, vagy elírtad a webcímet. Ne aggódj, a főoldalról mindent megtalálsz!</p>
            <a href="<?= base_url() ?>" class="action-btn">Vissza a főoldalra</a>
        </div>
    </div>
</body>
</html>