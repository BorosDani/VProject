<?php
    session_start();
    include("con.php");

    //Regisztráció
    $reg_email = '';
    $reg_name = '';
    $errors = [];

    if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['reg']))
    {
        //Adatok bekérés (email, név, jelszó)
        $reg_email = trim($_POST['reg_email']);
        $reg_name = trim($_POST['reg_name']);
        $reg_pass = $_POST['reg_pass'];

        //Ellenőrzés, hogy jók-e az adatok
        $errors = [];

        if(empty($reg_email) || !filter_var($reg_email, FILTER_VALIDATE_EMAIL))
        {
            $errors[] = "Érvényes email cím megadása kötelező!";
        }

        if(empty($reg_name) || strlen($reg_name) < 3)
        {
            $errors[] = "A névnek legalább 3 karakter hosszú kell legyen!";
        }

        if(empty($reg_pass) || strlen($reg_pass) < 5)
        {
            $errors[] = "A jelszónak legalább 5 karakter hosszú kell legyen!";
        }

        //Email ellenőrzése (létezik-e már)
        if(empty($errors)) 
        {
            $email = mysqli_real_escape_string($con, $reg_email);
            $check_email = "SELECT * FROM felhasznalok WHERE email = '$email'";
            $result = mysqli_query($con, $check_email);

            if(mysqli_num_rows($result) > 0)
            {
                $errors[] = "Ez az email cím már regisztrálva van!";
            }
        }

        //Ha nincs hiba akkor mentjük az adatokat
        if(empty($errors))
        {
            $name = mysqli_real_escape_string($con, $reg_name);
            $jelszo_hash = password_hash($reg_pass, PASSWORD_DEFAULT);
            $szerep = "munkavállaló";

            //Adatok feltöltése
            $query = "INSERT INTO felhasznalok (szerep, nev, email, jelszo_hash, letrehozva, modositva)
                      VALUES (?, ?, ?, ?, NOW(), NOW())";
            
            if ($stmt = mysqli_prepare($con, $query)) {
                mysqli_stmt_bind_param($stmt, "ssss", $szerep, $name, $email, $jelszo_hash);
                
                if(mysqli_stmt_execute($stmt))
                {
                    //IP cím és session info lekérése
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $session_id = session_id();
                    
                    //Naplózás
                    $felhasznalo_id = mysqli_insert_id($con);
                    $naplo_query = "INSERT INTO felhasznalo_naplo (felhasznalo_id, ip_cim, session_id, tevekenyseg) 
                                    VALUES ('$felhasznalo_id', '$ip', '$session_id', 'Regisztráció')";
                    mysqli_query($con, $naplo_query);
                    
                    //Sikeres regisztrációs üzi
                    $_SESSION['success_msg'] = "Sikeres regisztráció! Most már bejelentkezhetsz.";
                    header("Location: login.php");
                    exit();
                }
                else
                {
                    $errors[] = "Adatbázis hiba: " . mysqli_error($con);
                }
                
                mysqli_stmt_close($stmt);
            } 

            else 
            {
                $errors[] = "Adatbázis hiba: " . mysqli_error($con);
            }
        }

        if(!empty($errors))
        {
            $_SESSION['errors'] = $errors;
        }
    }
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Villám meló - Regisztráció</title>
</head>
<body>
    <div class='container'>
        <div class='reg_box'>
            <form method="POST" action="register.php">
                <p>Regisztráció</p>


                <!--Hibák megjelenítése-->
                <?php
                    if(isset($_SESSION['errors']))
                    {
                        echo"<div class='error-box'>";

                        foreach($_SESSION['errors'] as $error)
                        {
                            echo"<p class='error'>$error</p>";
                        }

                        echo"</div>";
                        unset($_SESSION['errors']);
                    }
                ?>


                <div class='reg_data'>
                    <input type="text" name='reg_email' placeholder="Email" required value="<?php echo htmlspecialchars($reg_email); ?>"><br>
                    <input type="text" name='reg_name' placeholder="Teljes név" required value="<?php echo htmlspecialchars($reg_name); ?>"><br>
                    <input type="password" name='reg_pass' placeholder="Jelszó (minimum 5 karakter)" required><br>
                </div>

                <input type="submit" name='reg' value="Regisztráció">

                <div class="link_options">
                    <p>Van már fiókod? <a href="login.php">Jelentkezz be</a></p>
                    <p>Vissza a <a href="index.php">főoldalra</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>