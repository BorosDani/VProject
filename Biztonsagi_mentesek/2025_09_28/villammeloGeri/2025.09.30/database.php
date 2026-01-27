<?php
$host = "localhost";
$db_name = "villamme_villammelo";
$db_user = "root";
$db_pass = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db_name;charset=utf8",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Hibák dobása kivétellel
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Asszociatív tömbök
            PDO::ATTR_EMULATE_PREPARES => false, // Natív prepared statement
        ]
    );
} catch (PDOException $e) {
    die("Adatbázis hiba: " . $e->getMessage());
}
