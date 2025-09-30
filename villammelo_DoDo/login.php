<?php
session_start();


// Bejelentkezés
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['login'])) 
{
    $log_email = trim($_POST['log_email']);
    $log_passw = $_POST['log_passw'];

    $errors = [];

    // Adatok ellenőrzése
    if (empty($log_email) || !filter_var($log_email, FILTER_VALIDATE_EMAIL)) 
    {
        $errors[] = "Érvényes email cím megadása kötelező!";
    }

    if (empty($log_passw)) 
    {
        $errors[] = "Jelszó megadása kötelező!";
    }

    // Ha nincs hiba → próbáljuk bejelentkeztetni
    if (empty($errors)) 
    {
        $stmt = $pdo->prepare("SELECT * FROM felhasznalok WHERE email = ?");
        $stmt->execute([$log_email]);
        $user = $stmt->fetch();

        if ($user && password_verify($log_passw, $user['jelszo_hash'])) 
        {
            // Session változók - PROFILKÉP HOZZÁADVA
            $_SESSION['user_id'] = $user['felhasznalo_id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['nev'];
            $_SESSION['user_role'] = $user['szerep'];
            $_SESSION['profil_kep'] = $user['profil_kep']; // PROFILKÉP BETÖLTÉSE

            // Utolsó bejelentkezés frissítése
            $stmt = $pdo->prepare("UPDATE felhasznalok SET utolso_belepes = NOW() WHERE felhasznalo_id = ?");
            $stmt->execute([$user['felhasznalo_id']]);

            // Naplózás
            $stmt = $pdo->prepare("
                INSERT INTO felhasznalo_naplo (felhasznalo_id, ip_cim, session_id, tevekenyseg)
                VALUES (?, ?, ?, 'Bejelentkezés')
            ");
            $stmt->execute([$user['felhasznalo_id'], $_SERVER['REMOTE_ADDR'], session_id()]);

            // Átirányítás főoldalra
            header("Location: index.php");
            exit();
        } 

        else 
        {
            $errors[] = "Hibás email vagy jelszó!";
        }
    }

    if (!empty($errors)) 
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
            // Hibák megjelenítése
            if (isset($_SESSION['login_errors'])) 
            {
                echo "<div class='error_box'>";
                foreach ($_SESSION['login_errors'] as $error) 
                {
                    echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
                }
                echo "</div>";
                unset($_SESSION['login_errors']);
            }

            // Regisztráció utáni sikerüzenet
            if (isset($_SESSION['success_msg'])) 
            {
                echo "<div class='success-box'><p class='success'>" . htmlspecialchars($_SESSION['success_msg']) . "</p></div>";
                unset($_SESSION['success_msg']); // csak egyszer jelenjen meg
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