<?php
require_once "../includes/functions.php";

$eredmeny = [];

if (isset($_GET['token'])) 
{
    $eredmeny = email_megerosites($_GET['token']);
} 
else 
{
    $eredmeny = ['sikeres' => false, 'uzenet' => 'Hiányzó token!'];
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <title>Email megerősítés - Villám Meló</title>
</head>
<body>
    <div class='log_box verification-box' style="text-align: center;">
        <?php if($eredmeny['sikeres']): ?>
            <h1 style="color: green;"> Sikeres megerősítés!</h1>
            <p><?= $eredmeny['uzenet'] ?></p>
            <a href="<?= base_url('../login') ?>" class='log_button'>Bejelentkezés</a>

        <?php else: ?>
            <h1 style="color: red;">Hiba</h1>
            <p><?= $eredmeny['uzenet'] ?></p>
            
            <?php if(strpos($eredmeny['uzenet'], 'lejárt') !== false): ?>
                <div style="margin: 20px 0;">
                    <p><strong>Új aktivációs emailt tudsz kérni a bejelentkezési oldalon!</strong></p>
                    <a href="<?= base_url('../login') ?>" class='log_button'>Új email kérése</a>
                </div>
            <?php endif; ?>
            
        <?php endif; ?>
        
        <div style="margin-top: 20px;">
            <a href="<?= base_url('../login') ?>">Bejelentkezés</a> | 
            <a href="<?= base_url() ?>">Főoldal</a>
        </div>
    </div>
</body>
</html>