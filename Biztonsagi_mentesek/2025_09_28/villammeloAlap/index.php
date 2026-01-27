<?php
    session_start();
    include("con.php");
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
        <?php if(isset($_SESSION['user_id'])):?>
            <!--Csak bejelentkezett felhasználók látják-->
            <a href="profile.php">Profil</a>
            <a href="logout.php">Kijelentkezés</a>
            <span>Üdvözöllek, <?php echo htmlspecialchars($_SESSION['user_name']);?>!</span>

        <?php else:?>
            <!--Nem csak bejelentkezett felhasználók látják-->
            <a href="login.php">Bejelentkezés</a>
            <a href="register.php">Regisztráció</a>

        <?php endif;?>
    </div>

    <div class="content">
        <?php if(isset($_SESSION['user_id'])):?>
            <!--Csak bejelentkezett felhasználók látják-->
            <p>Itt találhatsz gyors munkalehetőségeket, vagy adhatsz fel saját munkákat.</p>
        
        <?php else:?>
            <!--Nem csak bejelentkezett felhasználók látják-->
            <h1>Üdvözöljük a Villám Meló oldalán!</h1>
            <p>Itt találhat gyors munkalehetőségeket, vagy adhat fel saját munkákat.<br>Ehhez viszont be kell jelentkeznie!</p>
        
        <?php endif;?>
    </div>
</body>
</html>