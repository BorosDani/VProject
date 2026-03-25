<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require_once __DIR__ . '/functions.php';

if (!function_exists('base_url')) {
    die('HIBA: A base_url() függvény nem található.');
}

if (!isset($_SESSION['fid'])) {
    header("Location: " . base_url('login'));
    exit;
}


if (function_exists('ellenoriz_tiltast')) {
    $tiltas_info = ellenoriz_tiltast();
    if ($tiltas_info) {
        echo megjelenit_tiltas_infot();
        exit;
    }
}

$hibak = [];
$siker = '';

$job_id = isset($_GET['job_id']) ? (int)$_GET['job_id'] : 0;

if ($job_id <= 0) {
    die('Érvénytelen munka azonosító.');
}

global $conn;
if (!isset($conn) || !$conn) {
    die('HIBA: Adatbázis kapcsolat nem elérhető.');
}


$stmt = $conn->prepare("SELECT * FROM munkak WHERE id = ? AND felhasznalo_id = ?");
$stmt->execute([$job_id, $_SESSION['fid']]);
$munka = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$munka) {
    die('A munka nem található vagy nem a tiéd.');
}

if ($munka['kiemelt'] != 1) {
    die('Ez a munka nem kiemelt, így nem kell fizetni.');
}
if ($munka['fizetve'] == 1) {
    die('Ezt a munkát már kifizetted.');
}

$fizetendo_osszeg = 500;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fizetes'])) {
    try {
        $conn->beginTransaction();
        $update = $conn->prepare("UPDATE munkak SET fizetve = 1, aktiv = 1 WHERE id = ?");
        $update->execute([$job_id]);
        $conn->commit();
        $siker = "Sikeres fizetés! A munkád mostantól aktív és kiemeltként jelenik meg.";
    } catch (PDOException $e) {
        $conn->rollBack();
        $hibak[] = "Hiba a fizetés feldolgozása közben: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Fizetés - Kiemelt munka</title>
    <style>

    </style>
</head>
<body>
    <div class="container">
        <h1>💳 Kiemelt munka fizetés</h1>
        <?php if (!empty($siker)): ?>
            <div class="uzenet uzenet-siker"><?= htmlspecialchars($siker) ?></div>
            <a href="<?= base_url('myJobs') ?>" class="btn btn-secondary">Vissza a munkáimhoz</a>
        <?php else: ?>
            <?php if (!empty($hibak)): ?>
                <div class="uzenet uzenet-hiba">
                    <?php foreach ($hibak as $hiba): ?>
                        <p><?= htmlspecialchars($hiba) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="munka-adatok">
                <h3>📋 Munka adatai</h3>
                <p><strong>Kategória:</strong> <?= htmlspecialchars($munka['munka_nev']) ?></p>
                <p><strong>Leírás:</strong> <?= nl2br(htmlspecialchars(substr($munka['munka_leiras'], 0, 200))) ?>...</p>
                <p><strong>Ár:</strong> <?= number_format($munka['ar'], 0, ',', ' ') ?> Ft</p>
                <p><strong>Időpont:</strong> <?= date('Y-m-d H:i', strtotime($munka['datum_ido'])) ?></p>
            </div>
            <div class="fizetendo">Fizetendő összeg: <?= number_format($fizetendo_osszeg, 0, ',', ' ') ?> Ft</div>
            <form method="POST">
                <button type="submit" name="fizetes" class="btn">💸 Fizetés szimulálása</button>
            </form>
            <p class="info">Szimulált fizetés – a gomb megnyomásával a munka azonnal aktiválódik.</p>
        <?php endif; ?>
    </div>
</body>
</html>