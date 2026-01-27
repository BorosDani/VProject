<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <script src = "Slideshow.js" defer></script>
    <title>Főoldal</title>
</head>
<body>


    <!--Menü-->

    <div class = 'menu'>
    <a href="index.php">Kezdőlap</a>
        <a href="munkak.php">Munkák</a>
        <label class="dropdown">
        <input type="checkbox">
        <span>Kategóriák </span>
        <ul class="content">
            <li><a href="kulteri.php">Kültéri munkák</a></li>
            <li><a href="belteri.php">Beltéri munkák</a></li>
        </ul>
    </label>
        <a href="profil.php">Profil</a>
    </div>
    <!--Főoldal-->

    <h1>Üdvözlünk a Villámmelókon! </h1>
    <div class = 'ajanlatok'>
      <h2>Kertészt keresel?</h2>
      <p>Less be a <a href = kulterimunkak.php>kültéri munkák<a> közé</p>
      <img class="mySlides" src="kertesz.jpg">
      <img class="mySlides" src="szobafesto.jpg">
      <button class="w3-button w3-display-left" onclick="plusDivs(-1)">&#10094;</button>
      <button class="w3-button w3-display-right" onclick="plusDivs(+1)">&#10095;</button>
    </div>




    <!--Lábléc-->
    <footer>
  <div class="footer-container">
    <div>
      <h4>Elérhetőségek</h4>
      <p>Email: <a href ="info@villammelo.hu">info@villammelo.hu</a></p>
      <p>Telefon: insert telefonszám</p>
      <p><a href="#">Facebook</a></p>
      <p><a href="#">Instagram</a></p>

    </div>


    <div>
      <h4>Fontos oldalak:</h4>
      <p><a href="#">Rólunk</a></p>
      <p><a href="#">Közösségi irányelvek</a></p>
    </div>
  </div>
</body>
</html>