<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Profil</title>
</head>
<body>
      <!--Menü-->
<div class="menu">
    <a href="index.php">Kezdőlap</a>
    <a href="munkak.php">Munkák</a>
    <div class="dropdown">
      <a href="#">Kategóriák</a>
      <ul class="dropdown-content">
        <li><a href="kulteri.php">Kültéri munkák</a></li>
        <li><a href="belteri.php">Beltéri munkák</a></li>
      </ul>
    </div>
    <a href="profil.php">Profil</a>
  </div>

  
    <!--Profil, ha be van bejelentkezve-->
    <?php if (isset($_SESSION['user_id'])): ?>
    <h1>Üdv, <?= htmlspecialchars($_SESSION['reg_nev']) ?>!</h1>
    <div class ="infok">
      
    </div>
<?php else: ?>
    <!-- Profil, ha nincs bejelentkezve -->
    <h1>Úgy tűnik, nem vagy bejelentkezve. Jelentkezz be, vagy hozz létre fiókot, ha nincsen!</h1>
    <div class="profil">
      <li><a href="login.php">Bejelentkezés</a></li>
      <li><a href="register.php">Regisztráció</a></li>
    </div>
<?php endif; ?>
</body>
</html>