<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villám meló - Regisztráció</title>
</head>
<body>
    <div class='reg_box'>
        <form action="register.php">
            <p>Regisztráció</p>

            <div class='reg_data'>
                <input type="text" name='reg_email' placeholder="Email"><br>
                <input type="text" name='reg_nev' placeholder="Teljes név"><br>
                <input type="password" name='reg_pass' placeholder="Jelszó"><br>
            </div>

            <input type="submit" name='reg' value="Regisztráció">

            <div class="link_options">
                <p>Van már fiókod? <a href="login.php">Jelentkezz be</a></p>
                <p>Vissza a <a href="index.php">főoldalra</a></p>
            </div>
        </form>
    </div>
</body>
</html>