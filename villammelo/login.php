<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villám meló - Bejelentkezés</title>
</head>
<body>
    <div class='login_box'>
        <form action="login.php">
            <p>Bejelentkezés</p>

            <div class='login_data'>
                <input type="text" name='log_email' placeholder="Email"><br>
                <input type="text" name='log_passw' placeholder="Jelszó"><br>
            </div>

            <input type="submit" name='login' value="Bejelentkezés">

            <div class='link_options'>
                <p>Nincs fiókod? <a href="register.php">Regisztrálj</a></p>
                <p>Vissza a <a href="index.php">főoldalra</a></p>
            </div>
        </form>
    </div>
</body>
</html>