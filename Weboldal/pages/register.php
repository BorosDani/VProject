<?php
$regisztracio_eredmeny = register();
$hibak = $regisztracio_eredmeny['hibak'] ?? [];
$uzenet = $regisztracio_eredmeny['uzenet'] ?? '';
$uzenet_tipus = $regisztracio_eredmeny['uzenet_tipus'] ?? '';
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <title>Regisztráció - Villám Meló</title>
</head>
<body>
    <div class='reg_box'>
        <h1>Regisztráció</h1>
        
        <?php if($uzenet_tipus === 'siker'): ?>
            <div class='uzenet uzenet-siker'><?= $uzenet ?></div>
            
            <div class="email-kuldés-container siker">
                <p><strong>Nem kaptad meg az emailt?</strong></p>
                <form method="POST" action="<?= base_url('emailsend/uj_email.php') ?>">
                    <input type="hidden" name="email" value="<?= htmlspecialchars($_SESSION['regisztracio_siker']['email'] ?? '') ?>">
                    <input type="hidden" name="fid" value="<?= $_SESSION['regisztracio_siker']['fid'] ?? '' ?>">
                    <button type="submit" class="reg_button" style="background: #2196f3;">
                        Új aktivációs email küldése
                    </button>
                </form>
            </div>
        <?php endif; ?>
        
        <?php if($uzenet_tipus === 'figy'): ?>
            <div class='uzenet uzenet-figy'><?= $uzenet ?></div>
            
            <div class="email-kuldés-container figy">
                <p><strong>Új aktivációs emailt küldünk a(z) <span style="color: #d63031;"><?= htmlspecialchars($_SESSION['regisztracio_info']['email'] ?? '') ?></span> címre:</strong></p>
                <form method="POST" action="<?= base_url('emailsend/uj_email.php') ?>">
                    <input type="hidden" name="email" value="<?= htmlspecialchars($_SESSION['regisztracio_info']['email'] ?? '') ?>">
                    <input type="hidden" name="fid" value="<?= $_SESSION['regisztracio_info']['fid'] ?? '' ?>">
                    <button type="submit" class="reg_button" style="background: #e17055;">
                        📧 Új aktivációs email küldése
                    </button>
                </form>
                
                <div style="margin-top: 10px; font-size: 14px; color: #666;">
                    <p><strong>Megjegyzés:</strong> Az aktivációs link 24 óráig érvényes.</p>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if(!empty($hibak)):?>
            <div class='uzenet uzenet-hiba'>
                <?php foreach($hibak as $hiba):?>
                    <p><?= $hiba ?></p>
                <?php endforeach;?>
            </div>
        <?php endif; ?>
        
        <form method="post">
            <div class='reg_input'>
                <input type="email" id='email' name='email' placeholder="Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                <input type="text" id='fnev' name='fnev' placeholder="Felhasználónév" value="<?= htmlspecialchars($_POST['fnev'] ?? '') ?>" required>
                <input type="password" id='jelszo' name='jelszo' placeholder="Jelszó" required>
                <input type="password" id='jelszo_ujra' name='jelszo_ujra' placeholder="Jelszó ismét" required>
                <input type="hidden" name="firstName">
                <input type="text" name="lastName" style="display:none;">
            </div>
            <div class="g-recaptcha" data-sitekey="6LcpQwosAAAAAIFPktMIrCVcomaPjVKugcZ4M6J9"></div>
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            <button type="submit" name='reg_button' class='reg_button'>Regisztráció</button>
        </form>
        
        <div class='reg_links'>
            <p>Már van fiókod? <a href="<?= base_url('login') ?>">Jelentkezz be!</a></p>
            <p>Vissza a <a href="<?= base_url() ?>">főoldalra</a></p>
        </div>
    </div>
    <script src="<?= base_url('assets/js/tema_valtoztato.js') ?>"></script>
</body>
</html>