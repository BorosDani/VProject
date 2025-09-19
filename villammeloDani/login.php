<?php
    session_start();
    include("con.php");

    //Bejelentkezés
    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['login']))
    {
        $log_email = trim($_POST['log_email']);
        $log_passw = $_POST['log_passw'];

        $errors = [];

        //Ellenőrzés, hogy jók-e az adatok
        if(empty($log_email) || !filter_var($log_email, FILTER_VALIDATE_EMAIL))
        {
            $errors[] = "Érvényes email cím megadása kötelező!";
        }

        if(empty($log_passw))
        {
            $errors[] = "Jelszó megadása kötelező!";
        }

        //No hiba = adat ellenőrzés
        if(empty($errors))
        {
            $email = mysqli_real_escape_string($con, $log_email);
            $query = "SELECT * FROM felhasznalok WHERE email = '$email'";
            $result = mysqli_query($con, $query);

            if($result && mysqli_num_rows($result) == 1)
            {
                $user = mysqli_fetch_assoc($result);

                //Jelszó ellenőrzés, adatok ellenőrzése
                if(password_verify($log_passw, $user['jelszo_hash']))
                {
                    $_SESSION['user_id'] = $user['felhasznalo_id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_name'] = $user['nev'];
                    $_SESSION['user_role'] = $user['szerep'];

                    //Utolsó bejelentkezés frissítése
                    $update_query = "UPDATE felhasznalok SET utolso_belepes = NOW() WHERE felhasznalo_id = " . $user['felhasznalo_id'];
                    mysqli_query($con, $update_query);

                    //Bejegyzés a bejelentkezésről
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $session_id = session_id();
                    $naplo_query = "INSERT INTO felhasznalo_naplo (felhasznalo_id, ip_cim, session_id, tevekenyseg)
                                    VALUES('". $user['felhasznalo_id'] ."', '$ip', '$session_id', 'Bejelentkezés')";
                    
                    mysqli_query($con, $naplo_query);

                    //Átírányítás a főoldalra
                    header("Location: index.php");
                    exit();
                }

                else
                {
                    $errors[] = "Hibás email vagy jelszó!";
                }
            }
            else
            {
                $errors[] = "Hibás email vagy jelszó!";
            }
        }
        //Hibák mentése session-be
        if(!empty($errors))
        {
            $_SESSION['login_errors'] = $errors;
        }
    }
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Villám meló - Bejelentkezés</title>
</head>
<body>
    <div class='login_box'>
        <form method="POST" action="login.php">
            <p>Bejelentkezés</p>


            <?php
                //Hibák megjelenítése
                if(isset($_SESSION['login_errors']))
                {
                    echo"<div class='error_box'>";
                    foreach($_SESSION['login_errors'] as $error)
                    {
                        echo"<p class='error'>$error</p>";
                    }
                    echo"</div>";
                    unset($_SESSION['login_errors']);
                }

                //Biztonság kedvéért jelezzük a felhasználónak, hogy sikeresen regisztrált
                if(isset($_SESSION['success_msg']))
                {
                    echo"<div class='success-box'><p class='success'>". $_SESSION['success_msg'] ."</p></div>";
                    unset($_SESSION['success_msg']); //Csak 1x jelenik meg
                }
            ?>


            <div class='login_data'>
                <input type="text" name='log_email' placeholder="Email" required value="<?php echo isset($_POST['log_email']) ? htmlspecialchars($_POST['log_email']) : ''; ?>"><br>
                <input type="password" name='log_passw' placeholder="Jelszó" required><br>
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