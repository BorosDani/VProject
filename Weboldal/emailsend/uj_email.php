<?php
require_once "../includes/functions.php";

$eredmeny = ['sikeres' => false, 'uzenet' => ''];

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['email'])) {
    $email = trim($_POST['email']);
    $fid = $_POST['fid'] ?? null;
    
    if (!$fid) {
        global $conn;
        $sql = "SELECT fid FROM felhasznalok WHERE email = ? AND statusz = 'Fuggoben'";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $felhasznalo = $stmt->fetch();
        $fid = $felhasznalo['fid'] ?? null;
    }
    
    if ($fid) {
        $eredmeny = uj_aktivacios_email_kuldes($email, $fid);
    } else {
        $eredmeny = ['sikeres' => false, 'uzenet' => 'Nem található függőben lévő felhasználó ezzel az email címmel.'];
    }
}

$_SESSION['uj_email_eredmeny'] = $eredmeny;
header("Location: " . base_url("login"));
exit;
?>