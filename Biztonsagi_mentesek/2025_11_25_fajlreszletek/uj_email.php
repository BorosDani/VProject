<?php
require_once "../includes/functions.php";

$eredmeny = ['sikeres' => false, 'uzenet' => ''];

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['email'])) {
    $email = trim($_POST['email']);
    $fid = $_POST['fid'] ?? null;
    
    // Ha nincs fid, de van email, keressük meg az adatbázisban
    if (!$fid) {
        global $conn;
        $sql = "SELECT fid FROM felhasznalok WHERE email = ? AND statusz = 'aktiv'";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $felhasznalo = $stmt->fetch();
        $fid = $felhasznalo['fid'] ?? null;
    }
    
    if ($fid) {
        $eredmeny = uj_aktivacios_email_kuldes($email, $fid);
    } else {
        $eredmeny = ['sikeres' => false, 'uzenet' => 'Nem található felhasználó ezzel az email címmel.'];
    }
}

// Visszairányítás
$_SESSION['uj_email_eredmeny'] = $eredmeny;
header("Location: " . base_url("login"));
exit;
?>