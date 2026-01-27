<?php
require_once("config.php");
$db = new Database();
$conn = $db->get();

// OLDALCÍM BEÁLLÍTÁSA URL ALAPJÁN
function get_page_title() {
    $request_uri = $_SERVER['REQUEST_URI'];
    $base_path = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
    $relative_path = str_replace($base_path, '', $request_uri);
    $relative_path = explode('?', $relative_path)[0];
    
    $page_titles = [
        '' => 'Főoldal - Villám Meló',
        '/' => 'Főoldal - Villám Meló',
        'home' => 'Főoldal - Villám Meló',
        'profile' => 'Profil - Villám Meló',
        'about' => 'Rólunk - Villám Meló',
        'contact' => 'Elérhetőség - Villám Meló',
        'jobs' => 'Munkák - Villám Meló',
        'login' => 'Bejelentkezés - Villám Meló',
        'register' => 'Regisztráció - Villám Meló',
        'jelszo_visszaallitas' => 'Jelszó visszaállítás - Villám Meló',
        'workUpload' => 'Munka feltöltés - Villám Meló',
        'myJobs' => 'Munkáim - Villám Meló',
        'applications' => 'Jelentkezéseim - Villám Meló',
        'logout' => 'Kijelentkezés - Villám Meló'
    ];
    
    $relative_path = trim($relative_path, '/');
    return $page_titles[$relative_path] ?? 'Villám Meló';
}

// JAVÍTOTT REGISZTRÁCIÓ FUNKCIÓ - statusz = 'Fuggoben'
function register() {
    global $conn;
    $hibak = [];
    $uzenet = '';
    $uzenet_tipus = ''; // 'siker', 'hiba', 'figy'

    if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['reg_button'])) {
        $email = trim($_POST['email']);
        $fnev = trim($_POST['fnev']);
        $jelszo = $_POST['jelszo'];
        $jelszo_ujra = $_POST['jelszo_ujra'];

        if(empty($_POST['g-recaptcha-response'])) {
            $hibak[] = "Hibás recaptcha!";
        }

        if(!empty($_POST['firstName']) || !empty($_POST['lastName'])){
            $hibak[] = "Gyanús tevékenység!";
        }

        // Validációk
        if (empty($email)) {
            $hibak[] = "Email cím megadása kötelező!";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $hibak[] = "Érvényes email címet adj meg!";
        }

        if (empty($fnev)) {
            $hibak[] = "Felhasználónév megadása kötelező!";
        } elseif (strlen($fnev) < 4) {
            $hibak[] = "A felhasználónév minimum 4 karakter hosszú legyen!";
        }

        if (empty($jelszo)) {
            $hibak[] = "Jelszó megadása kötelező!";
        } elseif (strlen($jelszo) < 6) {
            $hibak[] = "A jelszó minimum 6 karakter hosszú legyen!";
        }

        if ($jelszo !== $jelszo_ujra) {
            $hibak[] = "A jelszavak nem egyeznek!";
        }

        if (empty($hibak)) {
            try {
                // Ellenőrizzük, hogy van-e már ilyen felhasználó
                $ellenorzes_sql = "SELECT f.fid, f.fnev, f.email, f.statusz, f.email_megerositve
                                  FROM felhasznalok f 
                                  WHERE f.email = ? OR f.fnev = ?";
                $ellenorzes_stmt = $conn->prepare($ellenorzes_sql);
                $ellenorzes_stmt->execute([$email, $fnev]);
                $letezo_felhasznalok = $ellenorzes_stmt->fetchAll();

                $regisztralt_de_nem_megerositett = false;
                $regisztralt_fid = null;
                $regisztralt_email = null;

                if ($letezo_felhasznalok) {
                    foreach ($letezo_felhasznalok as $felhasznalo) {
                        if ($felhasznalo['email'] === $email && $felhasznalo['fnev'] === $fnev) {
                            if ($felhasznalo['email_megerositve'] == 0) {
                                $regisztralt_de_nem_megerositett = true;
                                $regisztralt_fid = $felhasznalo['fid'];
                                $regisztralt_email = $felhasznalo['email'];
                                $uzenet_tipus = 'figy';
                                $uzenet = "Már regisztráltál ezzel az email címmel és felhasználónévvel, de még nem erősítetted meg az emailedet!";
                                break;
                            } else {
                                $hibak[] = "Ez az email cím és felhasználónév már foglalt!";
                                break;
                            }
                        } elseif ($felhasznalo['email'] === $email) {
                            if ($felhasznalo['email_megerositve'] == 0) {
                                $regisztralt_de_nem_megerositett = true;
                                $regisztralt_fid = $felhasznalo['fid'];
                                $regisztralt_email = $felhasznalo['email'];
                                $uzenet_tipus = 'figy';
                                $uzenet = "Már regisztráltál ezzel az email címmel, de még nem erősítetted meg az emailedet!";
                                break;
                            } else {
                                $hibak[] = "Ez az email cím már foglalt!";
                                break;
                            }
                        } elseif ($felhasznalo['fnev'] === $fnev) {
                            $hibak[] = "Ez a felhasználónév már foglalt!";
                            break;
                        }
                    }
                }

                if ($regisztralt_de_nem_megerositett) {
                    // Mentsük el a session-be az új email küldéshez
                    $_SESSION['regisztracio_info'] = [
                        'tipus' => 'megerosites_hiany',
                        'uzenet' => $uzenet,
                        'email' => $regisztralt_email,
                        'fid' => $regisztralt_fid
                    ];
                    
                } elseif (empty($hibak)) {
                    // Új regisztráció - JAVÍTVA: statusz = 'Fuggoben', email_megerositve = 0
                    $jelszo_hash = password_hash($jelszo, PASSWORD_DEFAULT);
                    $sql = "INSERT INTO felhasznalok (email, fnev, jelszo, profilkep, statusz, email_megerositve) VALUES (?, ?, ?, 'default.png', 'Fuggoben', 0)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$email, $fnev, $jelszo_hash]);
                    
                    $utolso_id = $conn->lastInsertId();
                    
                    // Aktivációs token generálása és email küldése
                    $token = general_aktivacios_token();
                    mentes_aktivacios_token($utolso_id, $token);
                    $email_kuldve = kuldes_aktivacios_email($email, $fnev, $token);
                    
                    // Naplózás
                    naplozz_tevekenyseg($utolso_id, 'regisztracio', 'sikeres');
                    
                    // Sikeres regisztráció üzenet
                    $uzenet_tipus = 'siker';
                    if ($email_kuldve) {
                        $uzenet = "Sikeres regisztráció! Küldtünk egy aktivációs emailt a(z) <strong>$email</strong> címre.";
                    } else {
                        $uzenet = "Sikeres regisztráció, de az aktivációs email küldése sikertelen. Kérjük, használd az 'Új email küldése' gombot.";
                    }
                    
                    $_SESSION['regisztracio_siker'] = [
                        'uzenet' => $uzenet,
                        'email' => $email,
                        'fid' => $utolso_id
                    ];
                }
            } catch (PDOException $e) {
                $hibak[] = "Adatbázis hiba: " . $e->getMessage();
            }
        }
    }
    
    return [
        'hibak' => $hibak,
        'uzenet' => $uzenet,
        'uzenet_tipus' => $uzenet_tipus
    ];
}

// JAVÍTOTT LOGIN FUNKCIÓ - statusz = 'Fuggoben' ellenőrzés
// JAVÍTOTT LOGIN FUNKCIÓ - Kitiltott státusz ellenőrzése
function login() {
    global $conn;
    $hibak = [];
    $uzenet = '';
    $uzenet_tipus = '';

    if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['log_button'])) {
        $fnev = trim($_POST['fnev']);
        $jelszo = $_POST['jelszo'];

        if(empty($_POST['g-recaptcha-response'])) {
            $hibak[] = "Hibás recaptcha!";
        }

        if (empty($fnev)) {
            $hibak[] = "Felhasználónév vagy email megadása kötelező!";
        }

        if (empty($jelszo)) {
            $hibak[] = "Jelszó megadása kötelező!";
        }

        if (empty($hibak)) {
            try {
                // Először ellenőrizzük a Kitiltott státuszt
                $sql = "SELECT * FROM felhasznalok WHERE (email = ? OR fnev = ?) AND statusz = 'Kitiltott'";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$fnev, $fnev]);
                $kitiltott_felhasznalo = $stmt->fetch();

                if ($kitiltott_felhasznalo) {
                    // Kitiltott felhasználó - részletes információ megjelenítése
                    $tiltsag_ok = $kitiltott_felhasznalo['statusz_ok'] ?? 'Ismeretlen ok';
                    $tiltsag_meddig = $kitiltott_felhasznalo['statusz_meddig'] ?? null;
                    
                    $tiltsag_uzenet = "A fiókod ki van tiltva!<br><strong>Ok:</strong> " . htmlspecialchars($tiltsag_ok);
                    
                    if ($tiltsag_meddig && $tiltsag_meddig != '0000-00-00 00:00:00') {
                        $tiltsag_uzenet .= "<br><strong>Meddig:</strong> " . date('Y-m-d H:i', strtotime($tiltsag_meddig));
                    } else {
                        $tiltsag_uzenet .= "<br><strong>Meddig:</strong> Határozatlan ideig";
                    }
                    
                    $hibak[] = $tiltsag_uzenet;
                    naplozz_tevekenyseg($kitiltott_felhasznalo['fid'], 'bejelentkezes', 'sikertelen', null, null, null, 'Kitiltott fiók');
                } else {
                    // Folytatjuk a normál bejelentkezést, ha nincs tiltás
                    // JAVÍTVA: Először a 'Fuggoben' státuszt keresük
                    $sql = "SELECT * FROM felhasznalok WHERE (email = ? OR fnev = ?) AND statusz = 'Fuggoben'";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$fnev, $fnev]);
                    $felhasznalo = $stmt->fetch();

                    if ($felhasznalo) {
                        // Email megerősítés ellenőrzése
                        if ($felhasznalo['email_megerositve'] == 0) {
                            // Nincs megerősítve az email
                            $uzenet_tipus = 'figy';
                            $uzenet = "Kérjük, erősítsd meg email címedet a bejelentkezés előtt!";
                            $_SESSION['uj_aktivacios_email'] = $felhasznalo['email'];
                            $_SESSION['uj_aktivacios_fid'] = $felhasznalo['fid'];
                            
                            // Naplózás: bejelentkezési kísérlet nem megerősített emaillel
                            naplozz_tevekenyseg($felhasznalo['fid'], 'bejelentkezes', 'sikertelen', null, null, null, 'Nem megerősített email');
                            
                        } elseif (password_verify($jelszo, $felhasznalo['jelszo'])) {
                            // Sikeres bejelentkezés - JAVÍTVA: Ha megerősített emaillel rendelkező 'Fuggoben' felhasználó
                            $_SESSION['fid'] = $felhasznalo['fid'];
                            $_SESSION['fnev'] = $felhasznalo['fnev'];
                            $_SESSION['email'] = $felhasznalo['email'];
                            $_SESSION['szerep'] = $felhasznalo['szerep'];
                            $_SESSION['profilkep'] = $felhasznalo['profilkep'] ?? 'default.png';
                            $_SESSION['nem'] = $felhasznalo['nem'] ?? 'nem_publikus';
                            $_SESSION['knev'] = $felhasznalo['knev'] ?? '';
                            $_SESSION['vnev'] = $felhasznalo['vnev'] ?? '';
                            $_SESSION['szuletett'] = $felhasznalo['szuletett'] ?? '';
                            $_SESSION['telefon'] = $felhasznalo['telefon'] ?? '';
                            $_SESSION['varmegye'] = $felhasznalo['varmegye'] ?? '';
                            $_SESSION['statusz'] = $felhasznalo['statusz'] ?? 'Fuggoben';
                            $_SESSION['regisztralt'] = $felhasznalo['regisztralt'] ?? '';
                            $_SESSION['modositott'] = $felhasznalo['modositott'] ?? '';
                            $_SESSION['belepett'] = $felhasznalo['belepett'] ?? '';
                            // ÚJ: reszletek hozzáadása a session-hez
                            $_SESSION['reszletek'] = $felhasznalo['reszletek'] ?? '';

                            $belepett_sql = "UPDATE felhasznalok SET belepett = CURRENT_TIMESTAMP WHERE fid = ?";
                            $belepett_stmt = $conn->prepare($belepett_sql);
                            $belepett_stmt->execute([$felhasznalo['fid']]);

                            // Naplózás: sikeres bejelentkezés
                            naplozz_tevekenyseg($felhasznalo['fid'], 'bejelentkezes', 'sikeres');
                            
                            $uzenet_tipus = 'siker';
                            $uzenet = "Sikeres bejelentkezés!";
                        } else {
                            $hibak[] = "Hibás felhasználónév / email vagy jelszó!";
                            naplozz_tevekenyseg($felhasznalo['fid'], 'bejelentkezes', 'sikertelen');
                        }
                    } else {
                        // Ha nem található 'Fuggoben' státusszal, próbáljuk meg 'Aktiv' státusszal
                        $sql = "SELECT * FROM felhasznalok WHERE (email = ? OR fnev = ?) AND statusz = 'Aktiv'";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute([$fnev, $fnev]);
                        $felhasznalo = $stmt->fetch();
                        
                        if ($felhasznalo && password_verify($jelszo, $felhasznalo['jelszo'])) {
                            // Sikeres bejelentkezés aktiv felhasználóval
                            $_SESSION['fid'] = $felhasznalo['fid'];
                            $_SESSION['fnev'] = $felhasznalo['fnev'];
                            $_SESSION['email'] = $felhasznalo['email'];
                            $_SESSION['szerep'] = $felhasznalo['szerep'];
                            $_SESSION['profilkep'] = $felhasznalo['profilkep'] ?? 'default.png';
                            $_SESSION['nem'] = $felhasznalo['nem'] ?? 'nem_publikus';
                            $_SESSION['knev'] = $felhasznalo['knev'] ?? '';
                            $_SESSION['vnev'] = $felhasznalo['vnev'] ?? '';
                            $_SESSION['szuletett'] = $felhasznalo['szuletett'] ?? '';
                            $_SESSION['telefon'] = $felhasznalo['telefon'] ?? '';
                            $_SESSION['varmegye'] = $felhasznalo['varmegye'] ?? '';
                            $_SESSION['statusz'] = $felhasznalo['statusz'] ?? 'Aktiv';
                            $_SESSION['regisztralt'] = $felhasznalo['regisztralt'] ?? '';
                            $_SESSION['modositott'] = $felhasznalo['modositott'] ?? '';
                            $_SESSION['belepett'] = $felhasznalo['belepett'] ?? '';
                            // ÚJ: reszletek hozzáadása a session-hez
                            $_SESSION['reszletek'] = $felhasznalo['reszletek'] ?? '';

                            $belepett_sql = "UPDATE felhasznalok SET belepett = CURRENT_TIMESTAMP WHERE fid = ?";
                            $belepett_stmt = $conn->prepare($belepett_sql);
                            $belepett_stmt->execute([$felhasznalo['fid']]);

                            // Naplózás: sikeres bejelentkezés
                            naplozz_tevekenyseg($felhasznalo['fid'], 'bejelentkezes', 'sikeres');
                            
                            $uzenet_tipus = 'siker';
                            $uzenet = "Sikeres bejelentkezés!";
                        } else {
                            $hibak[] = "Hibás felhasználónév / email vagy jelszó!";
                        }
                    }
                }
            } catch (PDOException $e) {
                $hibak[] = "Adatbázis hiba: " . $e->getMessage();
            }
        }
    }
    
    return [
        'hibak' => $hibak,
        'uzenet' => $uzenet,
        'uzenet_tipus' => $uzenet_tipus
    ];
}

// TILTÁS ELLENŐRZÉSE - új funkció
function ellenoriz_tiltast($fid = null) {
    global $conn;
    
    if (!$fid && isset($_SESSION['fid'])) {
        $fid = $_SESSION['fid'];
    }
    
    if (!$fid) {
        return null;
    }
    
    try {
        $sql = "SELECT statusz, statusz_ok, statusz_meddig FROM felhasznalok WHERE fid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$fid]);
        $felhasznalo = $stmt->fetch();
        
        if ($felhasznalo && $felhasznalo['statusz'] === 'Kitiltott') {
            return [
                'ok' => $felhasznalo['statusz_ok'] ?? 'Ismeretlen ok',
                'meddig' => $felhasznalo['statusz_meddig'] ?? null
            ];
        }
    } catch (PDOException $e) {
        error_log("Tiltás ellenőrzési hiba: " . $e->getMessage());
    }
    
    return null;
}

// TILTÁS INFORMÁCIÓ MEGJELENÍTÉSE - új funkció
function megjelenit_tiltas_infot() {
    $tiltas_info = ellenoriz_tiltast();
    
    if ($tiltas_info) {
        $tiltsag_uzenet = "<div class='uzenet uzenet-hiba'>";
        $tiltsag_uzenet .= "<h3>🚫 Fiókod ki van tiltva!</h3>";
        $tiltsag_uzenet .= "<p><strong>Ok:</strong> " . htmlspecialchars($tiltas_info['ok']) . "</p>";
        
        if ($tiltas_info['meddig'] && $tiltas_info['meddig'] != '0000-00-00 00:00:00') {
            $tiltsag_uzenet .= "<p><strong>Meddig:</strong> " . date('Y-m-d H:i', strtotime($tiltas_info['meddig'])) . "</p>";
        } else {
            $tiltsag_uzenet .= "<p><strong>Meddig:</strong> Határozatlan ideig</p>";
        }
        
        $tiltsag_uzenet .= "<p><small>Ha úgy gondolod, hogy ez tévedés, kérjük vedd fel a kapcsolatot az adminisztrátorokkal.</small></p>";
        $tiltsag_uzenet .= "</div>";
        
        return $tiltsag_uzenet;
    }
    
    return '';
}

// LOGOUT FUNKCIÓ
function logout() {
    if (isset($_SESSION['fid'])) {
        naplozz_tevekenyseg($_SESSION['fid'], 'kijelentkezes', 'sikeres');
    }

    $_SESSION = [];
    session_destroy();
    
    header("Location: " . base_url('login'));
    exit;
}

// JAVÍTOTT NAPLÓZASI FUNKCIÓ
function naplozz_tevekenyseg($fid, $tevekenyseg, $sikeresseg = 'sikeres', $modositott_mezo = null, $regi_ertek = null, $uj_ertek = null, $reszletek = null) {
    global $conn;
    
    // Kategória meghatározása
    $kategoria = 'egyeb';
    if (in_array($tevekenyseg, ['bejelentkezes', 'kijelentkezes', 'sikertelen_bejelentkezes'])) {
        $kategoria = 'bejelentkezes';
    } elseif (in_array($tevekenyseg, ['profil_modositas', 'email_valtoztatas'])) {
        $kategoria = 'profil';
    } elseif (in_array($tevekenyseg, ['regisztracio', 'jelszo_valtoztatas', 'jelszo_visszaallitas', 'elfelejtett_jelszo', 'email_megerosites', 'uj_aktivacios_email'])) {
        $kategoria = 'biztonsag';
    }
    
    // Prioritás meghatározása
    $prioritas = 'normal';
    if (in_array($tevekenyseg, ['sikertelen_bejelentkezes', 'jelszo_valtoztatas'])) {
        $prioritas = 'magas';
    }
    
    $sql = "INSERT INTO felhasznalo_tevekenyseg (fid, ip, session_id, bongeszo, tevekenyseg, sikeresseg, modositott_mezo, regi_ertek, uj_ertek, kategoria, prioritas) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        $fid,
        $_SERVER['REMOTE_ADDR'] ?? 'ismeretlen',
        session_id(),
        bongeszo(),
        $tevekenyseg,
        $sikeresseg,
        $modositott_mezo,
        $regi_ertek,
        $uj_ertek,
        $kategoria,
        $prioritas
    ]);
}

// EMAIL MEGERŐSÍTÉS FUNKCIÓK
function general_aktivacios_token() {
    return bin2hex(random_bytes(32));
}

function kuldes_aktivacios_email($email, $fnev, $token) {
    $aktivacios_link = base_url() . "emailsend/aktivalas.php?token=" . $token;
    
    $template_path = dirname(__DIR__) . "/emailsend/template/email_megerosites.html";
    
    if (!file_exists($template_path)) {
        error_log("Email template nem található: " . $template_path);
        return false;
    }
    
    $template_sablon = file_get_contents($template_path);
    
    $email_uzenet = str_replace(
        ['{{FELHASZNALONEV}}', '{{AKTIVACIOS_LINK}}'],
        [htmlspecialchars($fnev), $aktivacios_link],
        $template_sablon
    );
    
    $targy = "Email megerősítés - Villám Meló";
    
    $fejleck = "MIME-Version: 1.0" . "\r\n";
    $fejleck .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $fejleck .= "From: Villám Meló <noreply@villammelo.hu>" . "\r\n";
    $fejleck .= "Reply-To: info@villammelo.hu" . "\r\n";
    
    return mail($email, $targy, $email_uzenet, $fejleck);
}

function mentes_aktivacios_token($fid, $token) {
    global $conn;
    
    $lejarati_ido = date('Y-m-d H:i:s', strtotime('+24 hours'));
    
    // Előző tokenek törlése
    $torles_sql = "DELETE FROM email_ell WHERE fid = ?";
    $torles_stmt = $conn->prepare($torles_sql);
    $torles_stmt->execute([$fid]);
    
    // Új token mentése
    $sql = "INSERT INTO email_ell (fid, token, lejarati_ido) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([$fid, $token, $lejarati_ido]);
}

// JAVÍTOTT EMAIL MEGERŐSÍTÉS - státusz módosítása 'Aktiv'-ra
function email_megerosites($token) {
    global $conn;
    
    // Token ellenőrzése
    $sql = "SELECT ea.*, f.fnev, f.email, f.fid, f.email_megerositve
            FROM email_ell ea 
            JOIN felhasznalok f ON ea.fid = f.fid 
            WHERE ea.token = ? AND ea.ellenorzve IS NULL";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute([$token]);
    $aktivacio = $stmt->fetch();
    
    if (!$aktivacio) {
        return ['sikeres' => false, 'uzenet' => 'Érvénytelen vagy már felhasznált token!'];
    }
    
    // Lejárat ellenőrzése
    if (strtotime($aktivacio['lejarati_ido']) < time()) {
        // Új token küldése
        $uj_token = general_aktivacios_token();
        mentes_aktivacios_token($aktivacio['fid'], $uj_token);
        kuldes_aktivacios_email($aktivacio['email'], $aktivacio['fnev'], $uj_token);
        
        return ['sikeres' => false, 'uzenet' => 'A token lejárt. Új aktivációs emailt küldtünk!'];
    }
    
    // Email megerősítése - JAVÍTVA: státusz módosítása 'Aktiv'-ra
    $ellenorzve = date('Y-m-d H:i:s');
    
    try {
        // 1. email_ell tábla frissítése
        $update_sql = "UPDATE email_ell SET ellenorzve = ? WHERE token = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->execute([$ellenorzve, $token]);
        
        // 2. felhasznalok tábla frissítése - JAVÍTVA: email_megerositve = 1 és statusz = 'Aktiv'
        $update_felhasznalo_sql = "UPDATE felhasznalok SET email_megerositve = 1, statusz = 'Aktiv' WHERE fid = ?";
        $update_felhasznalo_stmt = $conn->prepare($update_felhasznalo_sql);
        $update_felhasznalo_stmt->execute([$aktivacio['fid']]);
        
        // Naplózás
        naplozz_tevekenyseg($aktivacio['fid'], 'email_megerosites', 'sikeres');
        
        return ['sikeres' => true, 'uzenet' => 'Sikeres email megerősítés! Most már bejelentkezhetsz.'];
        
    } catch (PDOException $e) {
        // Naplózás: hiba esetén
        naplozz_tevekenyseg($aktivacio['fid'], 'email_megerosites', 'sikertelen');
        return ['sikeres' => false, 'uzenet' => 'Hiba történt a megerősítés során: ' . $e->getMessage()];
    }
}

function uj_aktivacios_email_kuldes($email, $fid = null) {
    global $conn;
    
    if (!$fid) {
        $sql = "SELECT fid, fnev, email FROM felhasznalok WHERE email = ? AND statusz = 'Fuggoben'";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $felhasznalo = $stmt->fetch();
        
        if (!$felhasznalo) {
            return ['sikeres' => false, 'uzenet' => 'Nem található függőben lévő regisztráció ezzel az email címmel.'];
        }
        
        $fid = $felhasznalo['fid'];
        $fnev = $felhasznalo['fnev'];
        $email = $felhasznalo['email'];
    } else {
        $sql = "SELECT fnev, email FROM felhasznalok WHERE fid = ? AND statusz = 'Fuggoben'";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$fid]);
        $felhasznalo = $stmt->fetch();
        
        if (!$felhasznalo) {
            return ['sikeres' => false, 'uzenet' => 'Nem található függőben lévő felhasználó.'];
        }
        
        $fnev = $felhasznalo['fnev'];
        $email = $felhasznalo['email'];
    }
    
    $token_sql = "SELECT token FROM email_ell WHERE fid = ? AND lejarati_ido > NOW() AND ellenorzve IS NULL";
    $token_stmt = $conn->prepare($token_sql);
    $token_stmt->execute([$fid]);
    $letezo_token = $token_stmt->fetch();
    
    if ($letezo_token) {
        $token = $letezo_token['token'];
    } else {
        $token = general_aktivacios_token();
        mentes_aktivacios_token($fid, $token);
    }
    
    $kuldve = kuldes_aktivacios_email($email, $fnev, $token);
    
    if ($kuldve) {
        naplozz_tevekenyseg($fid, 'uj_aktivacios_email', 'sikeres');
        return ['sikeres' => true, 'uzenet' => 'Új aktivációs emailt küldtünk a megadott címre!'];
    } else {
        naplozz_tevekenyseg($fid, 'uj_aktivacios_email', 'sikertelen');
        return ['sikeres' => false, 'uzenet' => 'Hiba történt az email küldése során. Kérjük, próbáld újra később.'];
    }
}

// JELSZÓ VISSZAÁLLÍTÁS EMAIL KÜLDÉSE
function kuldes_jelszo_visszaallitas_email($email, $fnev, $token) {
    $visszaallitas_link = base_url() . "jelszo_visszaallitas?token=" . $token;
    
    $template_path = dirname(__DIR__) . "/emailsend/template/jelszo_visszaallitas.html";
    
    if (!file_exists($template_path)) {
        error_log("Jelszó template nem található: " . $template_path);
        return false;
    }
    
    $template_sablon = file_get_contents($template_path);
    
    $email_uzenet = str_replace(
        ['{{FELHASZNALONEV}}', '{{VISSZAALLITAS_LINK}}'],
        [htmlspecialchars($fnev), $visszaallitas_link],
        $template_sablon
    );
    
    $targy = "Jelszó visszaállítás - Villám Meló";
    
    $fejleck = "MIME-Version: 1.0" . "\r\n";
    $fejleck .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $fejleck .= "From: Villám Meló <noreply@villammelo.hu>" . "\r\n";
    $fejleck .= "Reply-To: info@villammelo.hu" . "\r\n";
    
    return mail($email, $targy, $email_uzenet, $fejleck);
}

// JELSZÓ VISSZAÁLLÍTÁS FUNKCIÓK
function jelszo_visszaallitas_kezeles() {
    global $conn;
    
    $hibak = [];
    $siker = '';
    $token_ervenyes = false;
    $fnev = '';
    $token = '';

    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        
        $sql = "SELECT jv.jvid, jv.fid, jv.token, jv.lejarati_ido, jv.felhasznalva, f.fnev 
                FROM jelszo_visszaallitasok jv 
                JOIN felhasznalok f ON jv.fid = f.fid 
                WHERE jv.token = ? AND jv.felhasznalva = 0 AND jv.lejarati_ido > NOW()";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([$token]);
        $token_adat = $stmt->fetch();
        
        if ($token_adat) {
            $token_ervenyes = true;
            $fnev = $token_adat['fnev'];
            $token = $token_adat['token'];
        } else {
            $hibak[] = "Érvénytelen vagy lejárt token!";
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['elkuld_button'])) {
        $email = trim($_POST['email']);
        
        if (empty($email)) {
            $hibak[] = "Email cím megadása kötelező!";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $hibak[] = "Érvényes email címet adj meg!";
        }
        
        if (empty($hibak)) {
            $sql = "SELECT fid, fnev, email FROM felhasznalok WHERE email = ? AND statusz = 'Aktiv'";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$email]);
            $felhasznalo = $stmt->fetch();
            
            if (!$felhasznalo) {
                $hibak[] = 'Nem található aktív felhasználó ezzel az email címmel.';
            } else {
                $token = bin2hex(random_bytes(32));
                $lejarati_ido = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                $torles_sql = "DELETE FROM jelszo_visszaallitasok WHERE fid = ?";
                $torles_stmt = $conn->prepare($torles_sql);
                $torles_stmt->execute([$felhasznalo['fid']]);
                
                $sql = "INSERT INTO jelszo_visszaallitasok (fid, token, lejarati_ido, felhasznalva) VALUES (?, ?, ?, 0)";
                $stmt = $conn->prepare($sql);
                $mentes_sikeres = $stmt->execute([$felhasznalo['fid'], $token, $lejarati_ido]);
                
                if ($mentes_sikeres) {
                    $email_kuldve = kuldes_jelszo_visszaallitas_email($felhasznalo['email'], $felhasznalo['fnev'], $token);
                    
                    if ($email_kuldve) {
                        $siker = 'Küldtünk egy jelszó visszaállítási linket a megadott email címre!';
                        naplozz_tevekenyseg($felhasznalo['fid'], 'elfelejtett_jelszo', 'sikeres');
                    } else {
                        $hibak[] = 'Hiba történt az email küldése során. Kérjük, próbáld újra később.';
                        naplozz_tevekenyseg($felhasznalo['fid'], 'elfelejtett_jelszo', 'sikertelen');
                    }
                } else {
                    $hibak[] = 'Hiba történt a token mentése során.';
                }
            }
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['visszaallitas_button'])) {
        $token = $_POST['token'];
        $uj_jelszo = $_POST['uj_jelszo'];
        $uj_jelszo_ujra = $_POST['uj_jelszo_ujra'];
        
        if (empty($uj_jelszo)) {
            $hibak[] = "Új jelszó megadása kötelező!";
        } elseif (strlen($uj_jelszo) < 6) {
            $hibak[] = "Az új jelszó minimum 6 karakter hosszú legyen!";
        }
        
        if ($uj_jelszo !== $uj_jelszo_ujra) {
            $hibak[] = "A jelszavak nem egyeznek!";
        }
        
        if (empty($hibak)) {
            $sql = "SELECT jv.jvid, jv.fid, jv.token, f.fnev 
                    FROM jelszo_visszaallitasok jv 
                    JOIN felhasznalok f ON jv.fid = f.fid 
                    WHERE jv.token = ? AND jv.felhasznalva = 0 AND jv.lejarati_ido > NOW()";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([$token]);
            $visszaallitas = $stmt->fetch();
            
            if (!$visszaallitas) {
                $hibak[] = 'Érvénytelen vagy lejárt token!';
            } else {
                $jelszo_hash = password_hash($uj_jelszo, PASSWORD_DEFAULT);
                
                $update_sql = "UPDATE felhasznalok SET jelszo = ? WHERE fid = ?";
                $update_stmt = $conn->prepare($update_sql);
                $jelszo_frissitve = $update_stmt->execute([$jelszo_hash, $visszaallitas['fid']]);
                
                if ($jelszo_frissitve) {
                    $token_sql = "UPDATE jelszo_visszaallitasok SET felhasznalva = 1 WHERE token = ?";
                    $token_stmt = $conn->prepare($token_sql);
                    $token_stmt->execute([$token]);
                    
                    naplozz_tevekenyseg($visszaallitas['fid'], 'jelszo_visszaallitas', 'sikeres');
                    
                    $siker = 'Sikeres jelszó visszaállítás! Most már bejelentkezhetsz az új jelszavaddal.';
                    $token_ervenyes = false;
                } else {
                    $hibak[] = 'Hiba történt a jelszó frissítése során.';
                }
            }
        }
    }

    return [
        'hibak' => $hibak,
        'siker' => $siker,
        'token_ervenyes' => $token_ervenyes,
        'fnev' => $fnev,
        'token' => $token
    ];
}

// JAVÍTOTT PROFIL MÓDOSÍTÁS FUNKCIÓ - email változás esetén státusz 'Fuggoben'
function update_profile() {
    global $conn;
    $hibak = [];
    $siker = '';

    if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['profil_modositasa'])) {
        $fid = $_SESSION['fid'];
        
        try {
            // MODOSÍTVA: reszletek mező hozzáadva a SELECT lekérdezéshez
            $sql = "SELECT nem, email, fnev, knev, vnev, profilkep, szuletett, telefon, varmegye, reszletek FROM felhasznalok WHERE fid = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$fid]);
            $jelenlegi_adatok = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$jelenlegi_adatok) {
                $hibak[] = "Felhasználó nem található!";
                return ['hibak' => $hibak];
            }
        } catch (PDOException $e) {
            $hibak[] = "Adatbázis hiba: " . $e->getMessage();
            return ['hibak' => $hibak];
        }
        
        $uj_adatok = [
            'nem' => $_POST['nem'] ?? 'nem_publikus',
            'email' => trim($_POST['email']),
            'fnev' => trim($_POST['fnev']),
            'knev' => trim($_POST['knev'] ?? ''),
            'vnev' => trim($_POST['vnev'] ?? ''),
            'szuletett' => $_POST['szuletett'] ?? null,
            'telefon' => trim($_POST['telefon'] ?? ''),
            'varmegye' => trim($_POST['varmegye'] ?? ''),
            'reszletek' => trim($_POST['reszletek'] ?? '')
        ];
        
        $jelszo = $_POST['jelszo'] ?? '';
        $jelszo_ujra = $_POST['jelszo_ujra'] ?? '';
        
        $profilkep_eleresi_ut = $jelenlegi_adatok['profilkep'];
        $profilkep_feltoltve = false;
        $valtozasok = [];

        // JAVÍTVA: Email változás észlelése
        $email_valtozott = ($uj_adatok['email'] !== $jelenlegi_adatok['email']);

        // Validációk
        if (empty($uj_adatok['email'])) {
            $hibak[] = "Email cím megadása kötelező!";
        } elseif (!filter_var($uj_adatok['email'], FILTER_VALIDATE_EMAIL)) {
            $hibak[] = "Érvényes email címet adj meg!";
        }

        if (empty($uj_adatok['fnev'])) {
            $hibak[] = "Felhasználónév megadása kötelező!";
        } elseif (strlen($uj_adatok['fnev']) < 4) {
            $hibak[] = "A felhasználónév minimum 4 karakter hosszú legyen!";
        }

        if (!empty($jelszo)) {
            if (strlen($jelszo) < 6) {
                $hibak[] = "Az új jelszó minimum 6 karakter hosszú legyen!";
            } elseif ($jelszo !== $jelszo_ujra) {
                $hibak[] = "A jelszavak nem egyeznek!";
            } else {
                $valtozasok[] = [
                    'mezo' => 'jelszo',
                    'regi' => '***',
                    'uj' => '***'
                ];
            }
        }

        // Email és felhasználónév egyediség ellenőrzése
        if ($uj_adatok['email'] !== $jelenlegi_adatok['email'] || $uj_adatok['fnev'] !== $jelenlegi_adatok['fnev']) {
            try {
                $ellenorzes_sql = "SELECT fid, email, fnev FROM felhasznalok WHERE (email = ? OR fnev = ?) AND fid != ?";
                $ellenorzes_stmt = $conn->prepare($ellenorzes_sql);
                $ellenorzes_stmt->execute([$uj_adatok['email'], $uj_adatok['fnev'], $fid]);
                $letezo_felhasznalok = $ellenorzes_stmt->fetchAll();

                foreach ($letezo_felhasznalok as $felhasznalo) {
                    if ($felhasznalo['email'] === $uj_adatok['email'] && $uj_adatok['email'] !== $jelenlegi_adatok['email']) {
                        $hibak[] = "Ez az email cím már foglalt!";
                    }
                    if ($felhasznalo['fnev'] === $uj_adatok['fnev'] && $uj_adatok['fnev'] !== $jelenlegi_adatok['fnev']) {
                        $hibak[] = "Ez a felhasználónév már foglalt!";
                    }
                }
            } catch (PDOException $e) {
                $hibak[] = "Adatbázis hiba az ellenőrzés során: " . $e->getMessage();
            }
        }

        // Profilkép feltöltés
        if (isset($_FILES['profilkep']) && $_FILES['profilkep']['error'] === UPLOAD_ERR_OK) {
            $fajl = $_FILES['profilkep'];
            $megengedett_tipusok = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $max_meret = 5 * 1024 * 1024;

            if (!in_array($fajl['type'], $megengedett_tipusok)) {
                $hibak[] = "Csak JPG, PNG, GIF és WebP formátumú képeket tölthetsz fel!";
            } elseif ($fajl['size'] > $max_meret) {
                $hibak[] = "A feltöltött kép mérete maximum 5MB lehet!";
            } elseif (!getimagesize($fajl['tmp_name'])) {
                $hibak[] = "A feltöltött fájl nem érvényes kép!";
            } else {
                $kiterjesztes = pathinfo($fajl['name'], PATHINFO_EXTENSION);
                $uj_nev = $uj_adatok['fnev'] . '_' . date('Y-m-d_H-i-s') . '.' . strtolower($kiterjesztes);
                $cel_ut = dirname(__DIR__) . '/assets/images/profile/' . $uj_nev;

                if ($profilkep_eleresi_ut !== 'default.png') {
                    $regi_kep_ut = dirname(__DIR__) . '/assets/images/profile/' . $profilkep_eleresi_ut;
                    if (file_exists($regi_kep_ut)) {
                        unlink($regi_kep_ut);
                    }
                }

                if (move_uploaded_file($fajl['tmp_name'], $cel_ut)) {
                    $valtozasok[] = [
                        'mezo' => 'profilkep',
                        'regi' => $profilkep_eleresi_ut,
                        'uj' => $uj_nev
                    ];
                    
                    $profilkep_eleresi_ut = $uj_nev;
                    $profilkep_feltoltve = true;
                } else {
                    $hibak[] = "Hiba történt a kép feltöltése során!";
                }
            }
        }

        // Változások összegyűjtése
        foreach ($uj_adatok as $mezo => $uj_ertek) {
            $regi_ertek = $jelenlegi_adatok[$mezo] ?? '';
            
            if ($mezo === 'szuletett') {
                $regi_ertek = $regi_ertek ? date('Y-m-d', strtotime($regi_ertek)) : '';
                $uj_ertek = $uj_ertek ?: '';
            }
            
            $regi_ertek = $regi_ertek === '' ? null : $regi_ertek;
            $uj_ertek = $uj_ertek === '' ? null : $uj_ertek;
            
            if ($regi_ertek != $uj_ertek) {
                $valtozasok[] = [
                    'mezo' => $mezo,
                    'regi' => $regi_ertek ?? 'üres',
                    'uj' => $uj_ertek ?? 'üres'
                ];
            }
        }

        if (empty($hibak)) {
            try {
                // MODOSÍTVA: reszletek mező hozzáadva az UPDATE parancshoz
                $sql = "UPDATE felhasznalok SET nem = ?, email = ?, fnev = ?, knev = ?, vnev = ?, szuletett = ?, telefon = ?, varmegye = ?, reszletek = ?, modositott = CURRENT_TIMESTAMP";
                $params = [
                    $uj_adatok['nem'], 
                    $uj_adatok['email'], 
                    $uj_adatok['fnev'], 
                    $uj_adatok['knev'], 
                    $uj_adatok['vnev'], 
                    $uj_adatok['szuletett'], 
                    $uj_adatok['telefon'], 
                    $uj_adatok['varmegye'],
                    $uj_adatok['reszletek']
                ];

                // JAVÍTVA: Ha email változott, állítsuk vissza "Fuggoben" státuszra
                if ($email_valtozott) {
                    $sql .= ", statusz = 'Fuggoben', email_megerositve = 0";
                }

                if (!empty($jelszo)) {
                    $sql .= ", jelszo = ?";
                    $params[] = password_hash($jelszo, PASSWORD_DEFAULT);
                }

                if ($profilkep_feltoltve) {
                    $sql .= ", profilkep = ?";
                    $params[] = $profilkep_eleresi_ut;
                }

                $sql .= " WHERE fid = ?";
                $params[] = $fid;

                $stmt = $conn->prepare($sql);
                $sikeres_frissites = $stmt->execute($params);

                if ($sikeres_frissites) {
                    // Session frissítése AZONNAL
                    $_SESSION['nem'] = $uj_adatok['nem'];
                    $_SESSION['email'] = $uj_adatok['email'];
                    $_SESSION['fnev'] = $uj_adatok['fnev'];
                    $_SESSION['knev'] = $uj_adatok['knev'];
                    $_SESSION['vnev'] = $uj_adatok['vnev'];
                    $_SESSION['szuletett'] = $uj_adatok['szuletett'];
                    $_SESSION['telefon'] = $uj_adatok['telefon'];
                    $_SESSION['varmegye'] = $uj_adatok['varmegye'];
                    // MODOSÍTVA: reszletek hozzáadása a session-hez
                    $_SESSION['reszletek'] = $uj_adatok['reszletek'];
                    
                    // JAVÍTVA: Ha email változott, frissítsük a státuszt a session-ben is
                    if ($email_valtozott) {
                        $_SESSION['statusz'] = 'Fuggoben';
                    }
                    
                    if ($profilkep_feltoltve) {
                        $_SESSION['profilkep'] = $profilkep_eleresi_ut;
                    }

                    // Naplózás
                    if (!empty($valtozasok)) {
                        foreach ($valtozasok as $valtozas) {
                            naplozz_tevekenyseg(
                                $fid, 
                                'profil_modositas', 
                                'sikeres',
                                $valtozas['mezo'],
                                $valtozas['regi'],
                                $valtozas['uj']
                            );
                        }
                        
                        $siker = "Profiladatok sikeresen frissítve! (" . count($valtozasok) . " módosítás)";
                    } else {
                        $siker = "Nincs módosítandó adat.";
                    }
                    
                    // Ha email változott, új email megerősítés
                    if ($email_valtozott) {
                        $token = general_aktivacios_token();
                        mentes_aktivacios_token($fid, $token);
                        kuldes_aktivacios_email($uj_adatok['email'], $uj_adatok['fnev'], $token);
                        
                        $siker .= " Mivel megváltoztattad az email címed, küldtünk egy megerősítő emailt az új címre! Az új email megerősítéséig a fiókod 'Függőben' státuszú lesz.";
                    }

                    // AZONNALI FRISSÍTÉS - újra lekérjük a felhasználó adatait
                    // MODOSÍTVA: reszletek mező hozzáadva a refresh lekérdezéshez
                    $refresh_sql = "SELECT fid, szerep, statusz, nem, email, fnev, knev, vnev, profilkep, szuletett, telefon, varmegye, reszletek, regisztralt, modositott, belepett, email_megerositve 
                                   FROM felhasznalok 
                                   WHERE fid = ?";
                    $refresh_stmt = $conn->prepare($refresh_sql);
                    $refresh_stmt->execute([$fid]);
                    $friss_adatok = $refresh_stmt->fetch(PDO::FETCH_ASSOC);
                    
                    return [
                        'hibak' => $hibak,
                        'siker' => $siker,
                        'friss_adatok' => $friss_adatok
                    ];
                } else {
                    $hibak[] = "Hiba történt a profil frissítése során!";
                }

            } catch (PDOException $e) {
                $hibak[] = "Adatbázis hiba: " . $e->getMessage();
            }
        }
    }

    return [
        'hibak' => $hibak,
        'siker' => $siker
    ];
}

function bongeszo() {
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    // Böngésző detektálás pontosabb logikával
    if (strpos($user_agent, 'MSIE') !== false || strpos($user_agent, 'Trident') !== false) {
        return 'Internet Explorer';
    } elseif (strpos($user_agent, 'Edg') !== false) {
        return 'Microsoft Edge';
    } elseif (strpos($user_agent, 'Chrome') !== false && strpos($user_agent, 'Edg') === false) {
        return 'Chrome';
    } elseif (strpos($user_agent, 'Firefox') !== false) {
        return 'Firefox';
    } elseif (strpos($user_agent, 'Safari') !== false && strpos($user_agent, 'Chrome') === false) {
        return 'Safari';
    } elseif (strpos($user_agent, 'Opera') !== false || strpos($user_agent, 'OPR/') !== false) {
        return 'Opera';
    } elseif (strpos($user_agent, 'Brave') !== false) {
        return 'Brave';
    } elseif (strpos($user_agent, 'Vivaldi') !== false) {
        return 'Vivaldi';
    } else {
        return 'Ismeretlen';
    }
}


// ============================================================================
// KAPCSOLATI ŰRLAP FUNKCIÓK
// ============================================================================

function process_contact_form() 
{
    global $conn;
    
    $hibak = [];
    $siker = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contacts-submit'])) {
        // reCAPTCHA ellenőrzés
        if (empty($_POST['g-recaptcha-response'])) {
            $hibak[] = "Kérjük, erősítsd meg, hogy nem vagy robot!";
        }

        // Adatok ellenőrzése
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $message = trim($_POST['message'] ?? '');
        
        // Validációk
        if (empty($name)) {
            $hibak[] = "Név megadása kötelező!";
        } elseif (strlen($name) < 2) {
            $hibak[] = "A névnek legalább 2 karakter hosszúnak kell lennie!";
        }
        
        if (empty($email)) {
            $hibak[] = "Email cím megadása kötelező!";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $hibak[] = "Érvénytelen email cím!";
        }
        
        if (empty($category)) {
            $hibak[] = "Kategória kiválasztása kötelező!";
        }
        
        if (empty($message)) {
            $hibak[] = "Üzenet megadása kötelező!";
        } elseif (strlen($message) < 10) {
            $hibak[] = "Az üzenetnek legalább 10 karakter hosszúnak kell lennie!";
        }
        
        // Ha nincs hiba, mentjük az adatbázisba
        if (empty($hibak)) {
            try {
                // Ellenőrizzük, hogy létezik-e a kapcsolat_uzenet tábla
                $check_table = $conn->query("SHOW TABLES LIKE 'kapcsolat_uzenet'");
                if ($check_table->rowCount() == 0) {
                    // Ha nem létezik, létrehozzuk
                    $create_table_sql = "CREATE TABLE IF NOT EXISTS kapcsolat_uzenet (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        nev VARCHAR(100) NOT NULL,
                        email VARCHAR(100) NOT NULL,
                        kategoria VARCHAR(50) NOT NULL,
                        uzenet TEXT NOT NULL,
                        datum TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        statusz ENUM('uj', 'olvasott', 'valaszolt') DEFAULT 'uj'
                    )";
                    $conn->exec($create_table_sql);
                }
                
                // Ellenőrizzük, hogy van-e kategoria oszlop
                $check_column = $conn->query("SHOW COLUMNS FROM kapcsolat_uzenet LIKE 'kategoria'");
                if ($check_column->rowCount() == 0) {
                    // Ha nincs kategoria oszlop, hozzáadjuk
                    $add_column_sql = "ALTER TABLE kapcsolat_uzenet ADD COLUMN kategoria VARCHAR(50) NOT NULL DEFAULT 'altalanos' AFTER email";
                    $conn->exec($add_column_sql);
                }
                
                // Adatok mentése az adatbázisba
                $sql = "INSERT INTO kapcsolat_uzenet (nev, email, kategoria, uzenet) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$name, $email, $category, $message]);
                
                $siker = "Köszönjük üzenetedet! Hamarosan válaszolunk, általában 24 órán belül.";
                
                // Ha van bejelentkezett felhasználó, naplózzuk
                if (isset($_SESSION['fid'])) {
                    naplozz_tevekenyseg($_SESSION['fid'], 'kapcsolati_uzi', 'sikeres', null, null, $category);
                }
                
            } catch (PDOException $e) {
                $hibak[] = "Hiba történt az üzenet mentése során: " . $e->getMessage();
                
                if (isset($_SESSION['fid'])) {
                    naplozz_tevekenyseg($_SESSION['fid'], 'kapcsolati_uzi', 'sikertelen', null, null, $category);
                }
            }
        }
    }
    
    return ['hibak' => $hibak, 'siker' => $siker];
}


// MUNKÁK FUNKCIÓK
function getMunkakByFelhasznalo($felhasznalo_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM munkak WHERE felhasznalo_id = ? AND aktiv = TRUE ORDER BY letrehozas_datuma DESC");
    $stmt->execute([$felhasznalo_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMunkaById($munka_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT m.*, f.fnev, f.knev, f.profil_kep FROM munkak m JOIN felhasznalok f ON m.felhasznalo_id = f.fid WHERE m.id = ?");
    $stmt->execute([$munka_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getOsszesMunka() {
    global $conn;
    $stmt = $conn->query("SELECT m.*, f.fnev, f.knev, f.profil_kep FROM munkak m JOIN felhasznalok f ON m.felhasznalo_id = f.fid WHERE m.aktiv = TRUE ORDER BY m.letrehozas_datuma DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getKategoriak() {
    global $conn;
    $stmt = $conn->query("SELECT * FROM kategoriak WHERE aktiv = TRUE ORDER BY kategoria_nev");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getKategoriakByMunka($munka_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT k.* FROM kategoriak k JOIN munka_kategoriak mk ON k.id = mk.kategoria_id WHERE mk.munka_id = ?");
    $stmt->execute([$munka_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getReferenciaKepekByMunka($munka_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM referencia_kepek WHERE munka_id = ? ORDER BY sorrend, feltoltes_datuma");
    $stmt->execute([$munka_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getKommentekByMunka($munka_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT k.*, f.fnev, f.knev, f.profil_kep FROM kommentek k JOIN felhasznalok f ON k.felhasznalo_id = f.fid WHERE k.munka_id = ? AND k.aktiv = TRUE ORDER BY k.letrehozas_datuma DESC");
    $stmt->execute([$munka_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAtlagErtekelesByFelhasznalo($felhasznalo_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT AVG(ertekeles) as atlag FROM munkak WHERE felhasznalo_id = ? AND ertekeles > 0");
    $stmt->execute([$felhasznalo_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['atlag'] ? round($result['atlag'], 1) : 0;
}

function createMunka($felhasznalo_id, $munka_nev, $munka_leiras, $ar, $kategoriak = []) {
    global $conn;
    
    try {
        $conn->beginTransaction();
        
        // Munka létrehozása
        $sql = "INSERT INTO munkak (felhasznalo_id, munka_nev, munka_leiras, ar) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$felhasznalo_id, $munka_nev, $munka_leiras, $ar]);
        
        $munka_id = $conn->lastInsertId();
        
        // Kategóriák hozzáadása
        if (!empty($kategoriak)) {
            foreach ($kategoriak as $kategoria_id) {
                $sql = "INSERT INTO munka_kategoriak (munka_id, kategoria_id) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$munka_id, $kategoria_id]);
            }
        }
        
        $conn->commit();
        naplozz_tevekenyseg($felhasznalo_id, 'munka_letrehozas', 'sikeres', null, null, $munka_id);
        return ['sikeres' => true, 'munka_id' => $munka_id];
        
    } catch (PDOException $e) {
        $conn->rollBack();
        naplozz_tevekenyseg($felhasznalo_id, 'munka_letrehozas', 'sikertelen');
        return ['sikeres' => false, 'hiba' => $e->getMessage()];
    }
}

// JOBS OLDALHOZ - LOCSOLÁS KATEGÓRIÁBAN DOLGOZÓ FELHASZNÁLÓK
function getLocsolasMunkasok() {
    global $conn;
    
    $sql = "
        SELECT 
            f.fid,
            f.fnev,
            f.knev,
            f.profilkep,
            f.varmegye,
            m.ar,
            COALESCE(rk.kep_url, CONCAT('assets/images/profile/', f.profilkep)) as megjelenito_kep,
            m.ertekeles
        FROM felhasznalok f
        JOIN munkak m ON f.fid = m.felhasznalo_id
        JOIN munka_kategoriak mk ON m.id = mk.munka_id
        JOIN kategoriak k ON mk.kategoria_id = k.id
        LEFT JOIN referencia_kepek rk ON m.id = rk.munka_id AND rk.fo_kep = TRUE
        WHERE k.kategoria_nev = 'Locsolás' 
        AND m.aktiv = TRUE
        AND f.statusz = 'Aktiv'
        ORDER BY m.ertekeles DESC, f.fnev
        LIMIT 12
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>