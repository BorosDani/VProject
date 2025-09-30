<!DOCTYPE html>
<html lang="hu">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <script src="slideshow.js" defer></script>
  <title>Főoldal</title>
</head>
<body>

  <!--Menü (meglévő rész, itt csak példa)-->
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

  <!--Főoldal-->
  <h1>Üdvözlünk a Villámmelókon!</h1>
  <div class="slider-wrapper">
  <div class="slider">

    <!-- 1. kép -->
    <div class="slide" id="slide-1">
      <img src="kertesz.jpg" alt="kertészkedő ember"/>
      <div class="caption">
        <h2>KERTÉSZT KERESEL?</h2>
        <p>Nézz be a <a href = "#">kültéri munkák<a> közé!</p>
      </div>
    </div>

    <!-- 2. kép -->
    <div class="slide" id="slide-2">
      <img src="szobafesto.jpg" alt="szobafestő ember"/>
      <div class="caption">
        <h2>FESTŐT KERESEL?</h2>
        <p>Nézz be a <a href = "#">beltéri munkák<a> közé!</p>
      </div>
    </div>

  </div>

  <!-- navigáció -->
  <div class="slider-nav">
    <a href="#slide-1"></a>
    <a href="#slide-2"></a>
  </div>
</div>

  </section>
  

  <!--Lábléc-->
    <footer>
  <div class="footer-container">
    <div>
      <h4>Elérhetőségek</h4>
      <p>Email: <a href ="info@villammelo.hu">info@villammelo.hu</a></p>
      <p><a href="#">Facebook</a></p>
      <p><a href="#">Instagram</a></p>

    </div>


    <div>
      <h4>Fontos oldalak:</h4>
      <p><a href="#">Rólunk</a></p>
      <p><a href="#">Közösségi irányelvek</a></p>
    </div>
  </div>
  </footer>
</body>
</html>
