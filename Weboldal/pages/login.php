<?php
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['log_button'])) {
    $login_eredmeny = login();
    $hibak = $login_eredmeny['hibak'] ?? [];
    $uzenet = $login_eredmeny['uzenet'] ?? '';
    $uzenet_tipus = $login_eredmeny['uzenet_tipus'] ?? '';
} else {
    $hibak = [];
    $uzenet = '';
    $uzenet_tipus = '';
}

if (isset($_SESSION['uj_email_eredmeny'])) {
    $email_eredmeny = $_SESSION['uj_email_eredmeny'];
    unset($_SESSION['uj_email_eredmeny']);
    
    if ($email_eredmeny['sikeres']) {
        $email_uzenet = $email_eredmeny['uzenet'];
        $email_uzenet_tipus = 'siker';
    } else {
        $email_uzenet = $email_eredmeny['uzenet'];
        $email_uzenet_tipus = 'hiba';
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <title>Bejelentkezés - Villám Meló</title>
</head>
<body>
    <div class='log_box'>
        <h1>Bejelentkezés</h1>

        <?php if (isset($email_uzenet_tipus) && $email_uzenet_tipus === 'siker'): ?>
            <div class='uzenet uzenet-siker'>
                <?= $email_uzenet ?>
            </div>
        <?php endif; ?>

        <?php if (isset($email_uzenet_tipus) && $email_uzenet_tipus === 'hiba'): ?>
            <div class='uzenet uzenet-hiba'>
                <?= $email_uzenet ?>
            </div>
        <?php endif; ?>

        <?php if($uzenet_tipus === 'siker'): ?>
            <div class='uzenet uzenet-siker'>
                <?= $uzenet ?>
                <p>Átirányítás a főoldalra...</p>
            </div>
            <script>
                setTimeout(function() {
                    window.location.href = "<?= base_url() ?>";
                }, 2000);
            </script>
        <?php endif; ?>

        <?php if($uzenet_tipus === 'figy'): ?>
            <div class='uzenet uzenet-figy'>
                <p><?= $uzenet ?></p>
            </div>
            
            <div class="email-kuldés-container figy">
                <p>Új aktivációs emailt küldünk a(z) <strong><?= htmlspecialchars($_SESSION['uj_aktivacios_email'] ?? '') ?></strong> címre:</p>
                <form method="POST" action="<?= base_url('emailsend/uj_email.php') ?>">
                    <input type="hidden" name="email" value="<?= htmlspecialchars($_SESSION['uj_aktivacios_email'] ?? '') ?>">
                    <input type="hidden" name="fid" value="<?= $_SESSION['uj_aktivacios_fid'] ?? '' ?>">
                    <button type="submit" class="log_button" style="background: #ff9800;">
                        Új email küldése
                    </button>
                </form>
            </div>
        <?php endif; ?>

        <?php if(!empty($hibak)):?>
            <div class='uzenet uzenet-hiba'>
                <?php foreach($hibak as $hiba):?>
                    <p><?= $hiba ?></p>
                <?php endforeach;?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['regisztracio_siker'])): 
            $reg_siker = $_SESSION['regisztracio_siker'];
        ?>
            <div class="uzenet uzenet-siker">
                <h3 style="margin-top: 0;">✓ Sikeres regisztráció</h3>
                <p><?= $reg_siker['uzenet'] ?></p>
            </div>
            <div class="email-kuldés-container siker">
                    <p><strong>Nem kaptad meg az emailt?</strong></p>
                    <form method="POST" action="<?= base_url('emailsend/uj_email.php') ?>">
                        <input type="hidden" name="email" value="<?= htmlspecialchars($reg_siker['email']) ?>">
                        <input type="hidden" name="fid" value="<?= $reg_siker['fid'] ?>">
                        <button type="submit" class="log_button" style="background: #2196f3;">
                        Új aktivációs email küldése
                        </button>
                </form>
            </div>
            <?php unset($_SESSION['regisztracio_siker']); ?>
        <?php endif; ?>

        <form method="post">
            <div class='log_input'>
                <input type="text" id='fnev' name='fnev' placeholder="Felhasználó vagy email" value="<?= htmlspecialchars($_POST['fnev'] ?? '') ?>" required>
                <input type="password" id='jelszo' name='jelszo' placeholder="Jelszó" required>
            </div>
            <div class="g-recaptcha" data-sitekey="6LcpQwosAAAAAIFPktMIrCVcomaPjVKugcZ4M6J9"></div>
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            <button type="submit" name='log_button' class='log_button'>Bejelentkezés</button>
            
            <div class='log_links'>
                <p>Nincs még fiókod? <a href="<?= base_url('register') ?>">Regisztrálj</a></p>
                <p>Vissza a <a href="<?= base_url() ?>">főoldalra</a></p>
                <p><a href="<?= base_url('jelszo_visszaallitas') ?>">Elfelejtetted a jelszavad?</a></p>
            </div>        
        </form>
    </div>
    <script src="<?= base_url('assets/js/tema_valtoztato.js') ?>"></script>
</body>
</html>