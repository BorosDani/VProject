<?php
    session_start();
    include("database.php");
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Villám meló - főoldal</title>
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

    <!-- KÉP SZAKASZ - Referencia munkák -->
    <div class='hero_section'>
        <div class='image_slider'>
            <img src="job_image/first_image.jpg" alt="Munka kép 1" class="slider-image active">
            <img src="job_image/second_image.jpg" alt="Munka kép 2" class="slider-image">
            <img src="job_image/third_image.jpg" alt="Munka kép 3" class="slider-image">
            <img src="job_image/fourth_image.jpg" alt="Munka kép 4" class="slider-image">
            
            <!-- Slider vezérlők -->
            <button class='slider-btn prev-btn'>‹</button>
            <button class='slider-btn next-btn'>›</button>
            
            <!-- Pont indikátorok -->
            <div class='slider-dots'>
                <span class='dot active' data-slide='0'></span>
                <span class='dot' data-slide='1'></span>
                <span class='dot' data-slide='2'></span>
                <span class='dot' data-slide='3'></span>
            </div>
        </div>
    </div>

    <!-- TARTALOM RÉSZ -->
    <div class="content">
        <?php if(isset($_SESSION['user_id'])):?>
            <!-- Bejelentkezett felhasználók -->
            <div class='welcome_user'>
                <h1>Üdvözöllek újra, <?php echo htmlspecialchars($_SESSION['user_name']);?>!</h1>
                <p>Itt találhatsz gyors munkalehetőségeket, vagy adhatsz fel saját munkákat.</p>
                <div class='action_buttons'>
                    <a href='jobs.php' class='btn primary'>Munkák böngészése</a>
                    <a href='post_job.php' class='btn secondary'>Munka feladása</a>
                </div>
            </div>
        <?php else:?>
            <!-- Nem bejelentkezett felhasználók -->
            <div class='welcome_guest'>
                <h1>Üdvözöljük a Villám Meló oldalán!</h1>
                <p>Csatlakozz több ezer elégedett felhasználónkhoz, akik már megtalálták álmaik munkáját!</p>
                <div class='features'>
                    <div class='feature'>
                        <h3>Gyors találat</h3>
                        <p>Percek alatt találj munkát vagy alkalmazottat</p>
                    </div>
                    <div class='feature'>
                        <h3>Biztonságos</h3>
                        <p>Ellenőrzött munkáltatók és munkavállalók</p>
                    </div>
                    <div class='feature'>
                        <h3>Egyszerű</h3>
                        <p>Felhasználóbarát felület, könnyű kezelés</p>
                    </div>
                </div>
                <div class='action_buttons'>
                    <a href='register.php' class='btn primary'>Regisztrálj most!</a>
                    <a href='about.php' class='btn secondary'>Tudj meg többet</a>
                </div>
            </div>
        <?php endif;?>
    </div>

<script src="scripts\profile-menu.js"></script>
<script src="scripts\category-menu.js"></script>
<script src="scripts\image-slider.js"></script>
</body>
</html>