<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>András locsoló</title>
</head>
<body>
<div class='menu'>
        <!-- BAL OLDAL: Logó és brand -->
        <div class='menu_left'>
            <div class='logo'>
                <img src="logo.png" alt="Villám Meló logó" height="40">
            </div>
        </div>

        <!-- KÖZÉP: Navigációs elemek (MINDENKI láthatja) -->
        <div class='menu_center'>
            <a href='index.php'>Kezdőlap</a>

            <!-- Kategória menü konténer -->
            <div class='menu_category'>
                <button class='category-btn'>Kategória</button>
                <div id='menu_category_DropDown' class='menu_category_DropDown'>
                    <a href='outside.php?'>Kültéri</a>
                    <a href='inside.php?'>Beltéri</a>
                </div>
            </div>

            <a href='featured.php'>Kiemeltek</a>
        </div>

        <!-- JOBB OLDAL: Felhasználó specifikus tartalom -->
        <div class='menu_right'>
            <?php if(isset($_SESSION['user_id'])):?>
                <!-- Bejelentkezett felhasználók: Profil menü -->
                <div class='user_welcome'>
                    <span>Üdv, <?php echo htmlspecialchars($_SESSION['user_name']);?>!</span>
                </div>
                <div class='menu_p'>
                    <button class='profile-btn'>
                        <img src="<?php echo !empty($_SESSION['profil_kep']) ? htmlspecialchars($_SESSION['profil_kep']) : 'default.png'; ?>" alt="Profilkép">
                    </button>
                    <div id='menu_p_DropDown' class='menu_p_DropDown'>
                        <a href="profile.php">
                            <img src="<?php echo !empty($_SESSION['profil_kep']) ? htmlspecialchars($_SESSION['profil_kep']) : 'default.png'; ?>" alt="Profilkép"><?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </a>
                        <a href="settings.php">Beállítások</a>
                        <a href="logout.php">Kijelentkezés</a>
                    </div>
                </div>
            <?php else:?>
                <!-- Nem bejelentkezett felhasználók: Bejelentkezés/Regisztráció -->
                <div class='auth_links'>
                    <a href="login.php" class='login-btn'>Bejelentkezés</a>
                    <a href="register.php" class='register-btn'>Regisztráció</a>
                </div>
            <?php endif;?>
        </div>
    </div>
        <h1>Sziasztok!<h1>
        <p>András vagyok, 23 éves és locsolással foglalkozok. Bármilyen kertet meglocsolok akár 3000 forintért. Vasárnap is hívható vagyok :)</p>
        <p>+36 20 556 2498</p>
        <img src="Job_image/kulteri/locsolok/elso.jpg" alt="Eva">
 
</body>
</html>