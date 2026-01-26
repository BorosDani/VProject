<?php
// pages/work.php - CSAK AKTÍV MUNKÁK MEGJELENÍTÉSE (KIVÉVE TULAJDONOS/ADMIN)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session indítás
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Functions betöltés
$functions_file = "includes/functions.php";
if (file_exists($functions_file)) {
    require_once($functions_file);
} else {
    die("Functions.php nem található");
}

// Ellenőrizzük a kapcsolatot
if (!isset($conn)) {
    $use_test_data = true;
} else {
    $use_test_data = false;
}

// JOB ID lekérése a GET paraméterből
$job_id = isset($_GET['job_id']) ? (int)$_GET['job_id'] : 0;

if ($job_id === 0) {
    die("Nincs munka ID megadva! Használd: /work?job_id=123");
}

// POST kezelés - JELENTKEZÉS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$use_test_data && isset($_POST['apply_btn'])) {
    if (!isset($_SESSION['fid'])) {
        $_SESSION['error'] = "A jelentkezéshez be kell jelentkezni!";
    } else {
        $uid = $_SESSION['fid'];
        $jid = isset($_POST['munka_id']) ? (int)$_POST['munka_id'] : $job_id;

        try {
            // 1. Ellenőrzés: jelentkezett-e már
            $check_sql = "SELECT id FROM jelentkezesek WHERE munka_id = ? AND felhasznalo_id = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->execute([$jid, $uid]);

            if ($check_stmt->rowCount() > 0) {
                 $_SESSION['error'] = "Már korábban jelentkeztél erre a munkára!";
            } else {
                // 2. Beszúrás az adatbázisba
                try {
                    $test_sql = "INSERT INTO jelentkezesek (munka_id, felhasznalo_id, idopont) VALUES (?, ?, NOW())";
                    $insert_stmt = $conn->prepare($test_sql);
                    $insert_stmt->execute([$jid, $uid]);
                    $success = true;
                } catch (Exception $e) {
                    try {
                        $test_sql = "INSERT INTO jelentkezesek (munka_id, felhasznalo_id, datum) VALUES (?, ?, NOW())";
                        $insert_stmt = $conn->prepare($test_sql);
                        $insert_stmt->execute([$jid, $uid]);
                        $success = true;
                    } catch (Exception $ex) {
                        $success = false;
                        $_SESSION['error'] = "Adatbázis hiba jelentkezéskor: " . $ex->getMessage();
                    }
                }

                if (isset($success) && $success) {
                    // --- STÁTUSZ MÓDOSÍTÁS: FOLYAMATBAN ---
                    try {
                        $update_sql = "UPDATE munkak SET statusz = 'folyamatban' WHERE id = ?";
                        $update_stmt = $conn->prepare($update_sql);
                        $update_stmt->execute([$jid]);
                    } catch (Exception $e) {
                        error_log("Statusz frissitesi hiba: " . $e->getMessage());
                    }

                    // --- EMAIL KÜLDÉS A MUNKÁLTATÓNAK ---
                    try {
                        // 1. Lekérjük a munkáltató adatait és a munka nevét
                        $sql_owner = "SELECT f.email, f.fnev, m.munka_nev 
                                      FROM munkak m 
                                      JOIN felhasznalok f ON m.felhasznalo_id = f.fid 
                                      WHERE m.id = ?";
                        $stmt_owner = $conn->prepare($sql_owner);
                        $stmt_owner->execute([$jid]);
                        $owner_data = $stmt_owner->fetch(PDO::FETCH_ASSOC);
                    
                        // 2. Lekérjük a jelentkező adatait
                        $sql_applicant = "SELECT fnev, email, telefon FROM felhasznalok WHERE fid = ?";
                        $stmt_applicant = $conn->prepare($sql_applicant);
                        $stmt_applicant->execute([$uid]);
                        $applicant_data = $stmt_applicant->fetch(PDO::FETCH_ASSOC);
                    
                        if ($owner_data && $applicant_data && !empty($owner_data['email'])) {
                            $to = $owner_data['email'];
                            $subject = "Új jelentkezés a munkádra: " . $owner_data['munka_nev'];
                            
                            // HTML email törzs
                            $message = '<!DOCTYPE html>
                            <html lang="hu">
                            <head>
                                <meta charset="UTF-8">
                                <title>Új jelentkezés értesítés</title>
                            </head>
                            <body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
                                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; color: white; text-align: center;">
                                    <h1>Villám Meló</h1>
                                    <h2>Új jelentkezés érkezett!</h2>
                                </div>
                                
                                <div style="padding: 20px; background-color: #f9f9f9;">
                                    <p>Kedves <strong>' . htmlspecialchars($owner_data['fnev']) . '</strong>!</p>
                                    
                                    <p>Örömmel értesítünk, hogy sikeres jelentkezés érkezett a következő hirdetésedre:</p>
                                    
                                    <div style="background-color: white; padding: 15px; border-radius: 8px; margin: 20px 0; border: 1px solid #e0e0e0;">
                                        <h3 style="color: #667eea; margin-top: 0;">' . htmlspecialchars($owner_data['munka_nev']) . '</h3>
                                        <p>A munka státusza mostantól <strong>Folyamatban</strong> állapotra váltott.</p>
                                    </div>
                                    
                                    <h3 style="color: #333;">Jelentkező adatai:</h3>
                                    <div style="background-color: white; padding: 15px; border-radius: 8px; border: 1px solid #e0e0e0;">
                                        <p><strong>Név:</strong> ' . htmlspecialchars($applicant_data['fnev']) . '</p>
                                        <p><strong>Email:</strong> ' . htmlspecialchars($applicant_data['email']) . '</p>';
                            
                            if (!empty($applicant_data['telefon'])) {
                                $message .= '<p><strong>Telefon:</strong> ' . htmlspecialchars($applicant_data['telefon']) . '</p>';
                            }
                            
                            $message .= '</div>
                                    
                                    <div style="text-align: center; margin: 30px 0;">
                                        <a href="https://villammelo.hu/work?job_id=' . $jid . '" 
                                           style="background-color: #667eea; color: white; padding: 12px 24px; 
                                                  text-decoration: none; border-radius: 5px; display: inline-block;
                                                  font-weight: bold; font-size: 14px;">
                                           Munka megtekintése
                                        </a>
                                    </div>
                                    
                                    <p><strong>Fontos:</strong> Kérjük, vedd fel a kapcsolatot a jelentkezővel a lehető leghamarabb!</p>
                                    
                                    <p style="color: #666; font-size: 13px; border-left: 4px solid #667eea; padding-left: 10px;">
                                        <strong>Jelentkezés időpontja:</strong> ' . date('Y.m.d. H:i:s') . '
                                    </p>
                                </div>
                                
                                <div style="background-color: #333; color: white; padding: 15px; text-align: center; font-size: 12px;">
                                    <p>Ez egy automatikus értesítő email, kérjük ne válaszolj rá!</p>
                                    <p>&copy; ' . date('Y') . ' Villám Meló - Minden jog fenntartva</p>
                                    <p style="font-size: 11px; color: #aaa; margin-top: 5px;">
                                        <a href="https://villammelo.hu" style="color: #aaa; text-decoration: none;">villammelo.hu</a> | 
                                        <a href="https://villammelo.hu/adatvedelem" style="color: #aaa; text-decoration: none;">Adatvédelmi irányelvek</a>
                                    </p>
                                </div>
                            </body>
                            </html>';
                    
                            // Fejlécek HTML emailhez
                            $headers = "MIME-Version: 1.0" . "\r\n";
                            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                            $headers .= "From: Villám Meló <ertesites@villammelo.hu>" . "\r\n";
                            $headers .= "Reply-To: " . $applicant_data['email'] . "\r\n";
                            $headers .= "X-Priority: 1 (High)\r\n";
                            $headers .= "X-Mailer: PHP/" . phpversion();
                    
                            // Levél küldése
                            mail($to, $subject, $message, $headers);
                        }
                    } catch (Exception $mailError) {
                        error_log("Email küldési hiba: " . $mailError->getMessage());
                    }
                    // --- EMAIL KÜLDÉS VÉGE ---

                    $_SESSION['success'] = "Sikeresen jelentkeztél! A hirdetőt emailben értesítettük.";
                    header("Location: work?job_id=" . $jid);
                    exit;
                }
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Hiba történt: " . $e->getMessage();
        }
    }
}

// ADATOK LEKÉRÉSE
if ($use_test_data) {
    // TESZT ADATOK
    $job_id = isset($_GET['job_id']) ? (int)$_GET['job_id'] : 1;
    
    $test_jobs = [
        1 => [
            'felhasznalo' => [
                'fid' => 7,
                'fnev' => 'Kovács János',
                'knev' => '',
                'profilkep' => 'default-profile.png',
                'email' => 'kovacs.janos@example.com',
                'telefon' => '+36 30 123 4567',
                'reszletek' => 'Tapasztalt festő, 10+ év gyakorlat, precíz munka, magas minőségű anyagok.',
                'varmegye' => 'Budapest'
            ],
            'munka' => [
                'id' => 1,
                'munka_nev' => 'Festési munka',
                'munka_leiras' => 'Lakás teljes festése, magas minőségű anyagokkal.',
                'ar' => 150000,
                'datum_ido' => '2024-01-20 14:30:00',
                'statusz' => 'aktiv'
            ]
        ],
    ];
    
    $current_job = $test_jobs[$job_id] ?? $test_jobs[1];
    
    // Teszt adatok szűrése
    if (isset($current_job['munka']['statusz']) && $current_job['munka']['statusz'] !== 'aktiv') {
        $_SESSION['error'] = "Csak az aktív munkák tekinthetők meg.";
        header("Location: index.php");
        exit;
    }

    $felhasznalo = $current_job['felhasznalo'];
    $munka_adatok = $current_job['munka'];
    $felhasznalo_id = $felhasznalo['fid'];
    
    $munkak_adatokkal = [[
        'id' => $job_id,
        'munka_nev' => $munka_adatok['munka_nev'],
        'munka_leiras' => $munka_adatok['munka_leiras'],
        'ar' => $munka_adatok['ar'],
        'statusz' => $munka_adatok['statusz']
    ]];
    
    $referencia_kepek = [];
    
} else {
    // VALÓS ADATOK AZ ADATBÁZISBÓL
    try {
        // 1. Munka és felhasználó LEKÉRÉSE
        $sql_munka = "SELECT m.*, f.fid, f.fnev, f.knev, f.profilkep, f.email, f.telefon, f.reszletek, f.varmegye FROM munkak m LEFT JOIN felhasznalok f ON m.felhasznalo_id = f.fid WHERE m.id = ?";
        
        $stmt_munka = $conn->prepare($sql_munka);
        $stmt_munka->execute([$job_id]);
        $munka_result = $stmt_munka->fetch(PDO::FETCH_ASSOC);
        
        if (!$munka_result) {
            die("A munka nem található! (ID: $job_id)");
        }

        // --- AUTOMATIKUS ARCHIVÁLÁS ---
        if ($munka_result['statusz'] === 'aktiv' && !empty($munka_result['datum_ido'])) {
            $munka_idopont = strtotime($munka_result['datum_ido']);
            $aktualis_ido = time();

            if ($aktualis_ido > $munka_idopont) {
                try {
                    $update_archived = "UPDATE munkak SET statusz = 'archivalt' WHERE id = ?";
                    $stmt_archived = $conn->prepare($update_archived);
                    $stmt_archived->execute([$job_id]);

                    $munka_result['statusz'] = 'archivalt';

                    // Email értesítés
                    if (!empty($munka_result['email'])) {
                        $to = $munka_result['email'];
                        $subject = "Munkád archiválva lett: " . $munka_result['munka_nev'];
                        
                        $message = '<!DOCTYPE html>
                        <html lang="hu">
                        <head><meta charset="UTF-8"><title>Munka archiválva</title></head>
                        <body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
                            <div style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%); padding: 20px; color: white; text-align: center;">
                                <h1>Villám Meló</h1>
                                <h2>A hirdetésed időpontja lejárt</h2>
                            </div>
                            <div style="padding: 20px; background-color: #f9f9f9;">
                                <p>Kedves <strong>' . htmlspecialchars($munka_result['fnev']) . '</strong>!</p>
                                <p>Tájékoztatunk, hogy az alábbi hirdetésed automatikusan archiválásra került, mivel a megadott időpontja lejárt:</p>
                                
                                <div style="background-color: white; padding: 15px; border-radius: 8px; margin: 20px 0; border: 1px solid #e0e0e0;">
                                    <h3 style="color: #666; margin-top: 0;">' . htmlspecialchars($munka_result['munka_nev']) . '</h3>
                                    <p><strong>Időpont:</strong> ' . date('Y.m.d. H:i', $munka_idopont) . '</p>
                                    <p>Új státusz: <strong>Archivált (Lejárt)</strong></p>
                                </div>

                                <p>A munka mostantól nem jelenik meg az aktív keresési listákban, és új jelentkezők sem jelentkezhetnek rá.</p>
                                
                                <div style="text-align: center; margin: 30px 0;">
                                    <a href="https://villammelo.hu/work?job_id=' . $job_id . '" 
                                       style="background-color: #6c757d; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                                       Hirdetés megtekintése
                                    </a>
                                </div>
                            </div>
                            <div style="background-color: #333; color: white; padding: 15px; text-align: center; font-size: 12px;">
                                <p>Ez egy automatikus rendszerüzenet.</p>
                                <p>&copy; ' . date('Y') . ' Villám Meló</p>
                            </div>
                        </body>
                        </html>';

                        $headers = "MIME-Version: 1.0" . "\r\n";
                        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                        $headers .= "From: Villám Meló <ertesites@villammelo.hu>" . "\r\n";
                        
                        @mail($to, $subject, $message, $headers);
                    }

                } catch (Exception $e) {
                    error_log("Automatikus archiválási hiba (ID: $job_id): " . $e->getMessage());
                }
            }
        }
        
        // --- SZIGORÚ SZŰRÉS: CSAK AKTÍV MUNKÁK ---
        if ($munka_result['statusz'] !== 'aktiv') {
            $current_user_id = $_SESSION['fid'] ?? 0;
            $is_admin_user = (isset($_SESSION['szerep']) && $_SESSION['szerep'] === 'admin');
            $is_owner_user = ($munka_result['felhasznalo_id'] == $current_user_id);
            
            if (!$is_owner_user && !$is_admin_user) {
                $_SESSION['error'] = "Ez a munka jelenleg nem aktív (státusz: " . $munka_result['statusz'] . "), ezért nem tekinthető meg.";
                header("Location: index.php");
                exit;
            }
        }
        
        $felhasznalo = [
            'fid' => $munka_result['fid'],
            'fnev' => $munka_result['fnev'],
            'knev' => $munka_result['knev'],
            'profilkep' => $munka_result['profilkep'],
            'email' => $munka_result['email'],
            'telefon' => $munka_result['telefon'],
            'reszletek' => $munka_result['reszletek'],
            'varmegye' => $munka_result['varmegye']
        ];
        
        $felhasznalo_id = $felhasznalo['fid'];
        
        $munka_adatok = [
            'id' => $munka_result['id'],
            'munka_nev' => $munka_result['munka_nev'],
            'munka_leiras' => $munka_result['munka_leiras'],
            'ar' => $munka_result['ar'],
            'datum_ido' => $munka_result['datum_ido'],
            'statusz' => $munka_result['statusz'] 
        ];
        
        // Referencia képek
        $referencia_kepek = [];
        try {
            $sql_referencia_kepek = "SELECT kep_url FROM referencia_kepek WHERE munka_id = :munka_id";
            $kep_stmt = $conn->prepare($sql_referencia_kepek);
            $kep_stmt->execute([':munka_id' => $job_id]);
            $referencia_kepek_db = $kep_stmt->fetchAll(PDO::FETCH_COLUMN, 0);
            
            foreach ($referencia_kepek_db as $kep) {
                if (!empty($kep)) {
                    if (filter_var($kep, FILTER_VALIDATE_URL)) {
                        $kep_url = $kep;
                    } else {
                        $kep_url = '/assets/images/referencia_kepek/' . $kep;
                    }
                    
                    $referencia_kepek[] = [
                        'kep_url' => $kep_url
                    ];
                }
            }
        } catch (Exception $e) {
            $referencia_kepek = [];
        }
        
        $munkak_adatokkal = [[
            'id' => $job_id,
            'munka_nev' => $munka_adatok['munka_nev'],
            'munka_leiras' => $munka_adatok['munka_leiras'],
            'ar' => $munka_adatok['ar'],
            'statusz' => $munka_adatok['statusz']
        ]];
        
    } catch (Exception $e) {
        die("Adatbázis hiba: " . $e->getMessage());
    }
}

// VÁLTOZÓK BEÁLLÍTÁSA
$teljes_nev = trim($felhasznalo['fnev'] ?? '');
$megjelenitendo_nev = !empty($teljes_nev) ? $teljes_nev : $felhasznalo['fnev'];

$profilkep_mappa = 'assets/images/profile/';
$profil_kep = (empty($felhasznalo['profilkep']) || $felhasznalo['profilkep'] === 'default.png') 
    ? $profilkep_mappa . 'default-profile.png' 
    : $profilkep_mappa . $felhasznalo['profilkep'];

$bejelentkezve = isset($_SESSION['fid']);
$aktualis_felhasznalo_id = $_SESSION['fid'] ?? 0;
$aktualis_felhasznalo_szerep = $_SESSION['szerep'] ?? 'user';
$is_admin = ($aktualis_felhasznalo_szerep === 'admin');
$sajat_oldal = ($bejelentkezve && $aktualis_felhasznalo_id == $felhasznalo_id);

// --- JELENTKEZÉS STÁTUSZ ELLENŐRZÉSE ---
$mar_jelentkezett = false;
if ($bejelentkezve && !$use_test_data) {
    try {
        $check_table = $conn->query("SHOW TABLES LIKE 'jelentkezesek'");
        if ($check_table->rowCount() > 0) {
            $sql_check_app = "SELECT id FROM jelentkezesek WHERE munka_id = ? AND felhasznalo_id = ?";
            $stmt_check_app = $conn->prepare($sql_check_app);
            $stmt_check_app->execute([$job_id, $aktualis_felhasznalo_id]);
            
            if ($stmt_check_app->rowCount() > 0) {
                $mar_jelentkezett = true;
            }
        }
    } catch (Exception $e) {
        $mar_jelentkezett = false; 
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Munka részletei - Villám Meló</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="word_profile_box">
    <div class="profil">
        <a href="jobs" style="color: gray; font-style: italic;"><p style=" text-align: right;">Vissza a munkákhoz</p></a>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="uzenet uzenet-hiba">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="uzenet uzenet-siker">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <div class="intro">
            <a href="<?= base_url('rateProfile?fid=' . $felhasznalo['fid']) ?>">
                <img src="<?php echo htmlspecialchars($profil_kep); ?>" 
                    alt="Profilkép"
                    onerror="this.src='assets/images/profile/default-profile.png'">
            </a>
            <div class="bio">
                <h1><?php echo htmlspecialchars($megjelenitendo_nev); ?></h1>
                
                <?php if (!empty($felhasznalo['reszletek'])): ?>
                    <p><strong>Bemutatkozás: </strong><?php echo nl2br(htmlspecialchars($felhasznalo['reszletek'])); ?></p>
                <?php endif; ?>
                
                <?php if (!empty($felhasznalo['varmegye'])): ?>
                    <p><strong>Vármegye:</strong> <?php echo htmlspecialchars($felhasznalo['varmegye']); ?></p>
                <?php endif; ?>
                
                <?php if ($sajat_oldal): ?>
                    <div class="profil-szerkesztes">
                        <a href="/profile" class="btn btn-primary szerkesztes-gomb">
                            <i class="fas fa-edit"></i> Profil szerkesztése
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($munkak_adatokkal)): ?>
            <h2>Munka részletei:</h2>
            <?php foreach ($munkak_adatokkal as $munka): ?>
                <div class="munka-blokk">
                    <h3><?php echo htmlspecialchars($munka['munka_nev']); ?></h3>
                    
                    <div class="statusz-container">
                        <?php 
                            $statusz_szoveg = 'Ismeretlen';
                            $statusz_class = 'status-default';
                            
                            switch($munka['statusz']) {
                                case 'aktiv':
                                    $statusz_szoveg = 'Aktív (Jelentkezhetsz)';
                                    $statusz_class = 'status-active';
                                    break;
                                case 'folyamatban':
                                    $statusz_szoveg = 'Folyamatban';
                                    $statusz_class = 'status-inprogress';
                                    break;
                                case 'befejezett':
                                    $statusz_szoveg = 'Befejezett';
                                    $statusz_class = 'status-closed';
                                    break;
                                case 'archivalt':
                                    $statusz_szoveg = 'Archivált (Lejárt)';
                                    $statusz_class = 'status-archived';
                                    break;
                            }
                        ?>
                        <span class="status-badge <?php echo $statusz_class; ?>">
                            <i class="fas fa-info-circle"></i> <?php echo $statusz_szoveg; ?>
                        </span>
                    </div>

                    <?php if (!empty($munka['munka_leiras'])): ?>
                        <p><?php echo nl2br(htmlspecialchars($munka['munka_leiras'])); ?></p>
                    <?php endif; ?>
                    
                    <div style="font-size: 1.5rem; font-weight: bold; color: var(--success-text); margin: 10px 0;">
                    <?php echo number_format($munka['ar'], 0, ',', ' '); ?> Ft
                    </div>

                    <div class="jelentkezes-container">
                        <?php if (!$bejelentkezve): ?>
                            <a href="login" class="btn btn-primary btn-block">
                                <i class="fas fa-sign-in-alt"></i> Jelentkezéshez lépj be!
                            </a>

                        <?php elseif ($sajat_oldal): ?>
                            <button class="btn btn-secondary btn-block" disabled style="opacity: 0.7; cursor: not-allowed;">
                                <i class="fas fa-user"></i> Ez a saját munkád
                            </button>

                        <?php elseif ($mar_jelentkezett): ?>
                            <button class="btn btn-applied btn-block" disabled>
                                <i class="fas fa-check-circle"></i> Már jelentkeztél erre a munkára
                            </button>
                            <div style="margin-top:10px; font-weight:bold; color:var(--primary-text);">
                                Jelenlegi státusz: <?php echo ucfirst($munka['statusz']); ?>
                            </div>
                            <a href="Jelentkezeseim" style="display:block; text-align:center; margin-top:5px; font-size:0.9rem; color: var(--link-color);">
                                Jelentkezés megtekintése
                            </a>
                        
                        <?php elseif ($munka['statusz'] !== 'aktiv'): ?>
                            <button class="btn btn-secondary btn-block" disabled style="background-color: #6c757d; cursor: not-allowed; opacity: 0.8;">
                                <i class="fas fa-lock"></i> A munka már nem fogad jelentkezést
                            </button>

                        <?php else: ?>
                            <form method="POST">
                                <input type="hidden" name="munka_id" value="<?php echo $job_id; ?>">
                                <button type="submit" name="apply_btn" class="btn btn-apply btn-block">
                                    <i class="fas fa-paper-plane"></i> Jelentkezem a munkára
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="nincs-munka">Nincs megjeleníthető munka.</p>
        <?php endif; ?>

        <?php if (!empty($referencia_kepek)): ?>
            <div class="referencia-kepek-section">
                <h2>Referencia képek</h2>
                <div class="referencia-kepek-grid">
                    <?php foreach ($referencia_kepek as $index => $kep): ?>
                        <div class="referencia-kep-item">
                            <img src="<?php echo htmlspecialchars($kep['kep_url']); ?>" 
                                alt="Referencia kép <?php echo $index + 1; ?>"
                                onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1581578731548-c64695cc6952?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80'">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <p class="nincs-referencia">Nincsenek referencia képek ehhez a munkához.</p>
        <?php endif; ?>

        <h2>Elérhetőségek:</h2>
        <?php if (!empty($felhasznalo['telefon'])): ?>
            <p><strong>Telefon:</strong> <?php echo htmlspecialchars($felhasznalo['telefon']); ?></p>
        <?php endif; ?>
        
        <?php if (!empty($felhasznalo['email'])): ?>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($felhasznalo['email']); ?></p>
        <?php endif; ?>
    </div>
</div>

<style>

.word_profile_box
{
    margin: 3rem 0;
}

/* STÁTUSZ JELVÉNYEK STÍLUSA */
.statusz-container {
    margin-bottom: 25px;
}
.status-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: bold;
    color: white;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.status-active {
    background-color: #28a745;
}
.status-inprogress {
    background-color: #fd7e14;
    color: white;
}
[data-theme="dark"] .status-inprogress {
    background-color: #d6630a;
}
.status-closed {
    background-color: #6c757d;
}
.status-archived {
    background-color: #343a40;
    color: #e2e6ea;
    border: 1px solid #495057;
}

/* Munka részletek kártya */
.munka-blokk {
    background: var(--card-bg);
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 25px;
    box-shadow: 0 4px 6px var(--shadow-color);
    border: 1px solid var(--border-color);
    color: var(--primary-text);
}

.munka-blokk h3 {
    color: var(--primary-text);
    margin-top: 0;
}

.munka-blokk p {
    color: var(--secondary-text);
    line-height: 1.6;
}

/* Profil szerkesztés gomb */
.profil-szerkesztes {
    margin-top: 15px;
    padding-top: 15px;
    border-top: 1px solid var(--border-color);
}

.szerkesztes-gomb {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background-color: var(--accent-color);
    color: white;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.szerkesztes-gomb:hover {
    background-color: var(--button-hover-bg);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px var(--shadow-color);
    color: white;
    text-decoration: none;
}

.szerkesztes-gomb i {
    font-size: 14px;
}

/* JELENTKEZÉS GOMB STÍLUSOK */
.jelentkezes-container {
    padding: 15px;
    background-color: var(--tertiary-bg);
    border-radius: 8px;
    border: 1px solid var(--border-color);
    text-align: center;
    margin: 20px 0;
}

.btn-block {
    display: block;
    width: 100%;
    padding: 12px;
    font-size: 1.1rem;
    font-weight: 600;
    text-align: center;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    box-sizing: border-box;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-primary:hover {
    background-color: #0056b3;
}

/* JELENTKEZÉS GOMB */
.btn-apply {
    background: linear-gradient(135deg, #2ea44f 0%, #218838 100%);
    color: white;
    border: 1px solid #1e7e34;
    box-shadow: 0 4px 6px rgba(46, 164, 79, 0.2);
}

.btn-apply:hover {
    background: linear-gradient(135deg, #34b359 0%, #28a745 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(46, 164, 79, 0.3);
}

/* MÁR JELENTKEZETT ÁLLAPOT */
.btn-applied {
    background-color: #fff3cd !important; 
    color: #856404 !important;
    border: 1px solid #ffeeba !important;
    cursor: default !important;
    opacity: 1 !important;
}

[data-theme="dark"] .btn-applied {
    background-color: #4a4a28 !important;
    color: #fceeb5 !important;
    border: 1px solid #6e6e3a !important;
}

/* REFERENCIA KÉPEK */
.referencia-kepek-section {
    margin: 40px 0;
    padding: 30px 0;
    border-top: 2px solid var(--border-color);
}

.referencia-kepek-section h2 {
    margin: 0 0 25px 0;
    color: var(--primary-text);
    font-size: 1.6rem;
    font-weight: 600;
}

.referencia-kepek-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.referencia-kep-item {
    background: var(--card-bg);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 3px 10px var(--shadow-color);
    transition: all 0.3s ease;
    height: 200px;
    border: 1px solid var(--border-color);
}

.referencia-kep-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px var(--shadow-color);
}

.referencia-kep-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
}

.referencia-kep-item:hover img {
    transform: scale(1.05);
}

.nincs-referencia {
    padding: 30px;
    background: var(--tertiary-bg);
    border-radius: 8px;
    border: 1px dashed var(--border-color);
    color: var(--secondary-text);
    text-align: center;
    font-size: 1rem;
    margin: 20px 0;
    font-style: italic;
}

.nincs-munka {
    padding: 20px;
    background: var(--tertiary-bg);
    border-radius: 8px;
    text-align: center;
    color: var(--secondary-text);
}

/* Üzenetek */
.uzenet {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 8px;
    font-weight: 500;
}

.uzenet-hiba {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.uzenet-siker {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

/* Intro rész */
.intro {
    display: flex;
    gap: 20px;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--border-color);
}

.intro img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid var(--accent-color);
    transition: 0.3s;
}

.intro img:hover
{
    transition: 0.3s;
    transform: translateY(-10px);
}

.bio {
    flex: 1;
}

.bio h1 {
    margin: 0 0 10px 0;
    color: var(--primary-text);
}

.bio h2 {
    margin: 0 0 15px 0;
    font-size: 1.2rem;
    color: var(--secondary-text);
}

/* Reszponzív design */
@media (max-width: 768px) {
    .intro {
        flex-direction: column;
        text-align: center;
    }
    
    .intro img {
        margin-bottom: 15px;
    }
    
    .szerkesztes-gomb {
        width: 100%;
        justify-content: center;
    }
    
    .referencia-kepek-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    .referencia-kep-item {
        height: 150px;
    }
    
    .referencia-kepek-section h2 {
        font-size: 1.4rem;
    }
}

@media (min-width: 769px) and (max-width: 1024px) {
    .referencia-kepek-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 18px;
    }
    
    .referencia-kep-item {
        height: 180px;
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.referencia-kep-item {
    animation: fadeIn 0.5s ease forwards;
    opacity: 0;
}

.referencia-kep-item:nth-child(1) { animation-delay: 0.1s; }
.referencia-kep-item:nth-child(2) { animation-delay: 0.2s; }
.referencia-kep-item:nth-child(3) { animation-delay: 0.3s; }
.referencia-kep-item:nth-child(4) { animation-delay: 0.4s; }
</style>
</body>
</html>