<?php
require_once dirname(__DIR__) . '/includes/functions.php';

$eredmeny = jelszo_visszaallitas_kezeles();

$hibak = $eredmeny['hibak'];
$siker = $eredmeny['siker'];
$token_ervenyes = $eredmeny['token_ervenyes'];
$fnev = $eredmeny['fnev'];
$token = $eredmeny['token'];
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <title>Jelszó visszaállítás - Villám Meló</title>
    <style>
        .jelszo_container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .info_box {
            background: #e8f4fd;
            border: 1px solid #b8daff;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .token_info {
            background: #e8f4fd;
            border: 1px solid #b8daff;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .hiba_uzenet p {
            color: red; 
            background: #ffeaea; 
            padding: 10px; 
            border-radius: 5px;
            margin: 5px 0;
        }
        
        .siker_uzenet {
            color: green; 
            background: #e8f5e8; 
            padding: 15px; 
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        input[type="email"],
        input[type="password"] {
            width: 100%; 
            padding: 12px; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            font-size: 16px;
            box-sizing: border-box;
            margin-bottom: 15px;
        }
        
        button {
            width: 100%; 
            padding: 12px; 
            border: none; 
            border-radius: 5px; 
            font-size: 16px; 
            cursor: pointer;
            margin: 10px 0;
            transition: background 0.3s;
        }
        
        .btn-email {
            background: #e74c3c; 
            color: white;
        }
        
        .btn-reset {
            background: #27ae60; 
            color: white;
        }
        
        .btn-email:hover {
            background: #c0392b;
        }
        
        .btn-reset:hover {
            background: #229954;
        }
        
        .vissza_link {
            text-align: center; 
            margin-top: 20px; 
            padding-top: 20px; 
            border-top: 1px solid #eee;
        }
        
        .vissza_link a {
            color: #666; 
            text-decoration: none;
        }
        
        .vissza_link a:hover {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="jelszo_container">
        <h1 style="text-align: center; color: #333; margin-bottom: 30px;">Jelszó visszaállítás</h1>
        
        <?php if (!empty($hibak)): ?>
            <div class="hiba_uzenet">
                <?php foreach ($hibak as $hiba): ?>
                    <p><?= htmlspecialchars($hiba) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($siker): ?>
            <div class="siker_uzenet">
                <h3 style="margin-top: 0;">✓ Sikeres művelet!</h3>
                <p><?= htmlspecialchars($siker) ?></p>
                <p style="margin-top: 15px;">
                    <a href="<?= base_url('login') ?>" style="color: #2196f3; font-weight: bold; text-decoration: none;">
                        Bejelentkezés
                    </a>
                </p>
            </div>
        
        <?php elseif ($token_ervenyes): ?>
            <div class="token_info">
                <p style="margin: 0;">Jelszó visszaállítás: <strong><?= htmlspecialchars($fnev) ?></strong></p>
                <p style="font-size: 14px; color: #666; margin: 10px 0 0 0;">Add meg az új jelszavad:</p>
            </div>
            
            <form method="POST">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                
                <div>
                    <label for="uj_jelszo" style="display: block; margin-bottom: 5px; font-weight: bold;">Új jelszó:</label>
                    <input type="password" id="uj_jelszo" name="uj_jelszo" required 
                           minlength="6" placeholder="Minimum 6 karakter">
                </div>
                
                <div>
                    <label for="uj_jelszo_ujra" style="display: block; margin-bottom: 5px; font-weight: bold;">Új jelszó megerősítése:</label>
                    <input type="password" id="uj_jelszo_ujra" name="uj_jelszo_ujra" required 
                           minlength="6" placeholder="Ismételd meg a jelszót">
                </div>
                
                <button type="submit" name="visszaallitas_button" class="btn-reset">
                    Jelszó visszaállítása
                </button>
            </form>
        
        <?php else: ?>
            <div class="info_box">
                <p style="margin: 0;"><strong>Add meg az email címed</strong>, és küldünk egy linket a jelszó visszaállításához.</p>
            </div>
            
            <form method="POST">
                <div>
                    <label for="email" style="display: block; margin-bottom: 5px; font-weight: bold;">Email cím:</label>
                    <input type="email" id="email" name="email" required 
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           placeholder="Add meg a regisztrált email címed">
                </div>
                
                <button type="submit" name="elkuld_button" class="btn-email">
                    Jelszó visszaállítási link küldése
                </button>
            </form>
        <?php endif; ?>
        
        <div class="vissza_link">
            <a href="<?= base_url('login') ?>">
                ← Vissza a bejelentkezéshez
            </a>
        </div>
    </div>
</body>
</html>