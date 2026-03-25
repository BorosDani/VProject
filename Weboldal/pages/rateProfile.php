<link
      rel="stylesheet"
      href="https://site-assets.fontawesome.com/releases/v7.0.0/css/all.css"
    />

    <link
      rel="stylesheet"
      href="https://site-assets.fontawesome.com/releases/v7.0.0/css/sharp-solid.css"
    />

    <link
      rel="stylesheet"
      href="https://site-assets.fontawesome.com/releases/v7.0.0/css/sharp-regular.css"
    />

    <link
      rel="stylesheet"
      href="https://site-assets.fontawesome.com/releases/v7.0.0/css/sharp-light.css"
    />

    <link
      rel="stylesheet"
      href="https://site-assets.fontawesome.com/releases/v7.0.0/css/duotone.css"
    />

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$functions_file = "includes/functions.php";
if (file_exists($functions_file)) {
    require_once($functions_file);
} else {
    die("Functions.php nem található");
}

if (!isset($conn)) {
    $use_test_data = true;
} else {
    $use_test_data = false;
}

$profile_fid = isset($_GET['fid']) ? (int)$_GET['fid'] : 0;

if ($profile_fid === 0) {
    die("<div style='padding: 20px; background: var(--error-bg); color: var(--error-text); border: 1px solid var(--error-text);'>
        <h3>Hiba: Nincs felhasználó ID megadva!</h3>
        <p>Használd: /rateProfile?fid=ID</p>
    </div>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$use_test_data) {
    
    if (isset($_POST['ertekeles'])) {
        if (!isset($_SESSION['fid'])) {
            $_SESSION['error'] = "Értékeléshez be kell jelentkezni!";
        } else {
            $ertekeles = (int)$_POST['rating'];
            $megjegyzes = trim($_POST['comment'] ?? '');
            $fid = $_SESSION['fid'];
            $dolgozo_id = $profile_fid;
            
            if ($fid == $dolgozo_id) {
                $_SESSION['error'] = "Nem értékelheted saját magadat!";
            } else {
                try {
                    $sql_check = "SELECT meid FROM megjegyzesek WHERE fid = ? AND dolgozo_id = ?";
                    $stmt_check = $conn->prepare($sql_check);
                    $stmt_check->execute([$fid, $dolgozo_id]);
                    $existing_ertekeles = $stmt_check->fetch(PDO::FETCH_ASSOC);
                    
                    if ($existing_ertekeles) {
                        $sql_update = "UPDATE megjegyzesek SET megjegyzes = ?, ertekeles = ?, idopont = NOW() WHERE meid = ?";
                        $stmt_update = $conn->prepare($sql_update);
                        if ($stmt_update->execute([$megjegyzes, $ertekeles, $existing_ertekeles['meid']])) {
                            $_SESSION['success'] = "Értékelésed frissítettük!";
                        }
                    } else {
                        $sql_insert = "INSERT INTO megjegyzesek (fid, dolgozo_id, megjegyzes, ertekeles, idopont) VALUES (?, ?, ?, ?, NOW())";
                        $stmt_insert = $conn->prepare($sql_insert);
                        if ($stmt_insert->execute([$fid, $dolgozo_id, $megjegyzes, $ertekeles])) {
                            $_SESSION['success'] = "Értékelésedet elküldtük!";
                        }
                    }
                } catch (Exception $e) {
                    $_SESSION['error'] = "Hiba: " . $e->getMessage();
                }
            }
        }
    }
    
    if (isset($_POST['torles'])) {
        if (!isset($_SESSION['fid'])) {
            $_SESSION['error'] = "Nincs jogosultságod ehhez a művelethez!";
        } else {
            $komment_id = (int)$_POST['komment_id'];
            $user_id = $_SESSION['fid'];
            $user_szerep = $_SESSION['szerep'] ?? 'user';
            $torles_tipusa = ($user_szerep === 'admin') ? 'admin_torles' : 'user_torles';
            
            try {
                $sql_check = "SELECT meid, fid, dolgozo_id, megjegyzes, ertekeles, idopont FROM megjegyzesek WHERE meid = ?";
                $stmt_check = $conn->prepare($sql_check);
                $stmt_check->execute([$komment_id]);
                $comment_data = $stmt_check->fetch(PDO::FETCH_ASSOC);
                
                if ($comment_data) {
                    $can_delete = ($user_szerep === 'admin' || $comment_data['fid'] == $user_id);
                    
                    if ($can_delete) {
                        $sql_check_table = "SHOW TABLES LIKE 'torolt_megjegyzesek'";
                        $check_table = $conn->query($sql_check_table);
                        
                        if ($check_table->rowCount() > 0) {
                            $sql_insert_torolt = "INSERT INTO torolt_megjegyzesek (meid, fid, dolgozo_id, megjegyzes, ertekeles, idopont, torles_tipusa, torleste_fid, torles_datuma) 
                                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                            $stmt_insert_torolt = $conn->prepare($sql_insert_torolt);
                            
                            $params = [
                                $comment_data['meid'],
                                $comment_data['fid'],
                                $comment_data['dolgozo_id'] ?? 0,
                                $comment_data['megjegyzes'],
                                $comment_data['ertekeles'],
                                $comment_data['idopont'],
                                $torles_tipusa,
                                $user_id
                            ];
                            
                            if ($stmt_insert_torolt->execute($params)) {
                                $sql_delete = "DELETE FROM megjegyzesek WHERE meid = ?";
                                $stmt_delete = $conn->prepare($sql_delete);
                                
                                if ($stmt_delete->execute([$komment_id])) {
                                    $_SESSION['success'] = "Komment " . ($user_szerep === 'admin' ? 'adminként' : 'sikeresen') . " törölve!";
                                } else {
                                    $_SESSION['error'] = "Hiba történt a komment törlésekor!";
                                }
                            } else {
                                $_SESSION['error'] = "Hiba történt az archiválás során!";
                            }
                        } else {
                            $sql_delete = "DELETE FROM megjegyzesek WHERE meid = ?";
                            $stmt_delete = $conn->prepare($sql_delete);
                            
                            if ($stmt_delete->execute([$komment_id])) {
                                $_SESSION['success'] = "Komment " . ($user_szerep === 'admin' ? 'adminként' : 'sikeresen') . " törölve!";
                            } else {
                                $_SESSION['error'] = "Hiba történt a komment törlésekor!";
                            }
                        }
                    } else {
                        $_SESSION['error'] = "Csak a saját kommentedet törölheted!";
                    }
                } else {
                    $_SESSION['error'] = "Komment nem található!";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Hiba: " . $e->getMessage();
            }
        }
    }
}

if ($use_test_data) {
    $profile_fid = isset($_GET['fid']) ? (int)$_GET['fid'] : 1;
    
    $test_users = [
        1 => [
            'fid' => 1,
            'fnev' => 'kovacsj',
            'knev' => 'János',
            'vnev' => 'Kovács',
            'profilkep' => 'default-profile.png',
            'email' => 'kovacs.janos@example.com',
            'telefon' => '+36 30 123 4567',
            'reszletek' => 'Tapasztalt festő, 10+ év gyakorlat, precíz munka, magas minőségű anyagok.',
            'varmegye' => 'Budapest'
        ],
        2 => [
            'fid' => 2,
            'fnev' => 'nagyp',
            'knev' => 'Péter',
            'vnev' => 'Nagy',
            'profilkep' => 'profile2.jpg',
            'email' => 'nagy.peter@example.com',
            'telefon' => '+36 20 555 6677',
            'reszletek' => 'Villanyszerelő, 15 év tapasztalat, hivatalos engedélyekkel.',
            'varmegye' => 'Pest'
        ],
        3 => [
            'fid' => 3,
            'fnev' => 'szaboe',
            'knev' => 'Éva',
            'vnev' => 'Szabó',
            'profilkep' => 'profile3.jpg',
            'email' => 'szabo.eva@example.com',
            'telefon' => '+36 70 333 4444',
            'reszletek' => 'Kerttervező és karbantartó, növényismeret, komplex megoldások.',
            'varmegye' => 'Fejér'
        ],
        7 => [
            'fid' => 7,
            'fnev' => 'tesztuser',
            'knev' => 'Teszt',
            'vnev' => 'Felhasználó',
            'profilkep' => 'default-profile.png',
            'email' => 'teszt@example.com',
            'telefon' => '+36 30 987 6543',
            'reszletek' => 'Megbízható szakember, hosszú évek tapasztalatával. Minden munkámat garanciával vállalom.',
            'varmegye' => 'Budapest'
        ]
    ];
    
    $felhasznalo = $test_users[$profile_fid] ?? $test_users[1];
    
    $teljes_nev = !empty($felhasznalo['vnev']) && !empty($felhasznalo['knev']) 
        ? $felhasznalo['vnev'] . ' ' . $felhasznalo['knev'] 
        : $felhasznalo['fnev'];
    
    $kommentek = [
        [
            'meid' => 1,
            'fid' => 2,
            'fnev' => 'nagyp',
            'knev' => 'Péter',
            'vnev' => 'Nagy',
            'profilkep' => 'profile2.jpg',
            'megjegyzes' => 'Nagyon precíz munka, időben elkészült, minden igényemet figyelembe vette!',
            'ertekeles' => 5,
            'idopont' => '2024-01-15 10:30:00',
            'dolgozo_id' => 1
        ],
        [
            'meid' => 2,
            'fid' => 3,
            'fnev' => 'szaboe',
            'knev' => 'Éva',
            'vnev' => 'Szabó',
            'profilkep' => 'profile3.jpg',
            'megjegyzes' => 'Kiváló minőség, korrekt áron dolgozik. Ajánlom mindenkinek!',
            'ertekeles' => 4,
            'idopont' => '2024-01-10 14:20:00',
            'dolgozo_id' => 1
        ]
    ];
    
    $csak_ertekeleseim = [];
    
    $atlag_ertekeles = 4.5;
    $ertekelesek_szama = 2;
    
} else {
    try {
        $sql_felhasznalo = "SELECT fid, fnev, knev, vnev, profilkep, email, telefon, reszletek, varmegye FROM felhasznalok WHERE fid = ?";
        
        $stmt_felhasznalo = $conn->prepare($sql_felhasznalo);
        $stmt_felhasznalo->execute([$profile_fid]);
        $felhasznalo = $stmt_felhasznalo->fetch(PDO::FETCH_ASSOC);
        
        if (!$felhasznalo) {
            die("<div style='padding: 20px; background: var(--error-bg); color: var(--error-text); border: 1px solid var(--error-text);'>
                <h3>Hiba: A felhasználó nem található!</h3>
                <p>A(z) $profile_fid ID-val rendelkező felhasználó nem létezik az adatbázisban.</p>
            </div>");
        }
        
        $teljes_nev = !empty($felhasznalo['vnev']) && !empty($felhasznalo['knev']) 
            ? $felhasznalo['vnev'] . ' ' . $felhasznalo['knev'] 
            : $felhasznalo['fnev'];
        
        $atlag_ertekeles = 0;
        $ertekelesek_szama = 0;
        
        $sql_atlag = "SELECT AVG(ertekeles) as atlag_ertekeles, COUNT(meid) as ertekelesek_szama 
                      FROM megjegyzesek 
                      WHERE dolgozo_id = ?";
        $stmt_atlag = $conn->prepare($sql_atlag);
        $stmt_atlag->execute([$profile_fid]);
        $atlag_result = $stmt_atlag->fetch(PDO::FETCH_ASSOC);
        
        if ($atlag_result) {
            $atlag_ertekeles = $atlag_result['ertekelesek_szama'] > 0 ? 
                              round($atlag_result['atlag_ertekeles'], 1) : 0;
            $ertekelesek_szama = $atlag_result['ertekelesek_szama'] ?? 0;
        }
        
        $kommentek = [];
        
        $sql_kommentek = "SELECT m.*, f.fnev, f.knev, f.vnev, f.profilkep, f.fid as komment_fid
                          FROM megjegyzesek m 
                          INNER JOIN felhasznalok f ON m.fid = f.fid 
                          WHERE m.dolgozo_id = ? 
                          AND TRIM(m.megjegyzes) != '' 
                          AND m.megjegyzes IS NOT NULL 
                          ORDER BY m.idopont DESC";
        $stmt_kommentek = $conn->prepare($sql_kommentek);
        $stmt_kommentek->execute([$profile_fid]);
        $kommentek = $stmt_kommentek->fetchAll(PDO::FETCH_ASSOC);
        
        $csak_ertekeleseim = [];
        $aktualis_felhasznalo_id = $_SESSION['fid'] ?? 0;
        
        if ($aktualis_felhasznalo_id > 0) {
            $sql_csak_ertekeleseim = "SELECT m.*, f.fnev, f.knev, f.vnev, f.profilkep, f.fid as komment_fid
                                      FROM megjegyzesek m 
                                      INNER JOIN felhasznalok f ON m.fid = f.fid 
                                      WHERE m.fid = ? 
                                      AND m.dolgozo_id = ? 
                                      AND (m.megjegyzes IS NULL OR TRIM(m.megjegyzes) = '') 
                                      ORDER BY m.idopont DESC";
            $stmt_csak_ertekeleseim = $conn->prepare($sql_csak_ertekeleseim);
            $stmt_csak_ertekeleseim->execute([$aktualis_felhasznalo_id, $profile_fid]);
            $csak_ertekeleseim = $stmt_csak_ertekeleseim->fetchAll(PDO::FETCH_ASSOC);
        }
        
    } catch (Exception $e) {
        die("<div style='padding: 20px; background: var(--error-bg); color: var(--error-text); border: 1px solid var(--error-text);'>
            <h3>Adatbázis hiba!</h3>
            <p><strong>Hibaüzenet:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
        </div>");
    }
}

$profilkep_mappa = 'assets/images/profile/';
$profil_kep = (empty($felhasznalo['profilkep']) || $felhasznalo['profilkep'] === 'default.png') 
    ? $profilkep_mappa . 'default.png' 
    : $profilkep_mappa . $felhasznalo['profilkep'];

$bejelentkezve = isset($_SESSION['fid']);
$aktualis_felhasznalo_id = $_SESSION['fid'] ?? 0;
$aktualis_felhasznalo_szerep = $_SESSION['szerep'] ?? 'user';
$is_admin = ($aktualis_felhasznalo_szerep === 'admin');
$sajat_oldal = ($bejelentkezve && $aktualis_felhasznalo_id == $profile_fid);
?>

<style>

.rateprofile-container {
    max-width: 1000px;
    margin: 30px auto;
    padding: 30px;
    background: var(--card-bg);
    border-radius: 15px;
    border: 1px solid var(--border-color);
    box-shadow: 0 10px 30px var(--shadow-color);
    animation: fadeInUp 0.5s ease-out;
    color: var(--primary-text);
}

.profil-header {
    display: flex;
    align-items: center;
    gap: 30px;
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 2px solid var(--border-color);
}

@media (max-width: 768px) {
    .profil-header {
        flex-direction: column;
        text-align: center;
        gap: 20px;
    }
}

.profil-kep {
    width: 180px;
    height: 180px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid var(--accent-color);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    transition: transform 0.3s ease;
}

.profil-kep:hover {
    transform: scale(1.05);
}

.profil-info {
    flex: 1;
}

.profil-felhasznalonev {
    font-size: 2.2rem;
    margin: 0 0 5px 0;
    color: var(--primary-text);
    font-weight: 700;
}

.profil-teljes-nev {
    font-size: 1.1rem;
    margin: 0 0 15px 0;
    color: var(--secondary-text);
    font-weight: 400;
    font-style: italic;
}

.szerkesztes-gomb {
    background: var(--accent-gradient);
    color: var(--button-text);
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    border: 1px solid var(--accent-color);
    box-shadow: 0 4px 12px var(--shadow-color);
    margin-left: 15px;
}

.szerkesztes-gomb:hover {
    background: var(--button-hover-bg);
    transform: translateY(-3px);
    box-shadow: 0 6px 18px var(--shadow-color);
    text-decoration: none;
    color: var(--button-text);
}

.szerkesztes-gomb:active {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px var(--shadow-color);
}

.szerkesztes-gomb i {
    font-size: 1rem;
}

.ertekeles-cim {
    font-size: 1.1rem;
    margin-bottom: 15px;
    color: var(--secondary-text);
}

.csillagok-nagy {
    display: flex;
    gap: 5px;
    margin: 10px 0;
}

.csillagok-nagy .csillag {
    font-size: 2.2rem;
    color: var(--border-color);
    transition: color 0.3s ease;
}

.csillagok-nagy .csillag.active {
    color: #ffc107;
}

.ertekeles-szam {
    font-size: 1rem;
    color: var(--secondary-text);
    display: block;
    margin-top: 5px;
}

.profil-tartalom {
    margin-bottom: 40px;
}

.reszlet-cim {
    font-size: 1.5rem;
    color: var(--primary-text);
    margin: 30px 0 20px 0;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--accent-color);
    font-weight: 600;
}

.bemutatkozas {
    background: var(--tertiary-bg);
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 30px;
    border-left: 4px solid var(--accent-color);
    color: var(--primary-text);
    line-height: 1.7;
}

.elérhetőségek {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.elérhetőség-item {
    background: var(--secondary-bg);
    padding: 20px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid var(--border-color);
}

.elérhetőség-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px var(--shadow-color);
}

.elérhetőség-item i {
    color: var(--accent-color);
    font-size: 1.4rem;
    width: 30px;
    text-align: center;
}

.elérhetőség-item span {
    color: var(--primary-text);
    font-weight: 500;
    flex: 1;
}

.kommentek-section {
    margin-top: 40px;
    padding-top: 30px;
    border-top: 2px solid var(--border-color);
}

.komment-cim {
    font-size: 1.5rem;
    color: var(--primary-text);
    margin-bottom: 25px;
    font-weight: 600;
}

.kommentek-lista {
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-bottom: 30px;
}

.komment-item {
    background: var(--secondary-bg);
    border-radius: 12px;
    padding: 25px;
    border: 1px solid var(--border-color);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.komment-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px var(--shadow-color);
}

.komment-fejlec {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    margin-bottom: 15px;
}

.komment-profilkep {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--accent-color);
    transition: transform 0.3s ease;
    cursor: pointer;
    flex-shrink: 0;
}

.komment-profilkep:hover {
    transform: scale(1.1);
}

.komment-profilkep-link {
    display: block;
    text-decoration: none;
}

.komment-szerzo-info {
    flex: 1;
    min-width: 0;
}

.komment-szerzo-nev-container {
    display: flex;
    align-items: baseline;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 5px;
}

.komment-szerzo-felhasznalonev {
    font-weight: 600;
    color: var(--primary-text);
    font-size: 1.1rem;
    text-decoration: none;
    transition: color 0.3s ease;
}

.komment-szerzo-felhasznalonev:hover {
    color: var(--accent-color);
    text-decoration: none;
}

.komment-szerzo-teljesnev {
    font-size: 0.95rem;
    color: var(--secondary-text);
    font-style: italic;
    font-weight: 400;
}

.komment-datum {
    font-size: 0.85rem;
    color: var(--secondary-text);
    margin-bottom: 8px;
}

.komment-ertekeles {
    margin: 8px 0;
}

.ertekeles-csillagok-komment {
    display: flex;
    gap: 3px;
    align-items: center;
    flex-wrap: wrap;
}

.csillag-komment {
    font-size: 1.1rem;
    color: var(--border-color);
}

.csillag-komment.active {
    color: #ffc107;
}

.komment-szoveg {
    background: var(--tertiary-bg);
    padding: 20px;
    border-radius: 10px;
    color: var(--primary-text);
    line-height: 1.6;
    margin-top: 15px;
    border-left: 3px solid var(--accent-color);
}

.torles-form {
    margin-left: auto;
    flex-shrink: 0;
}

.btn-danger {
    background: var(--error-bg);
    color: var(--error-text);
    border: 1px solid var(--error-text);
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-danger:hover {
    background: color-mix(in srgb, var(--error-bg) 80%, #000000 20%);
    transform: translateY(-2px);
}

.admin-delete {
    background: linear-gradient(135deg, var(--error-bg) 0%, color-mix(in srgb, var(--error-bg) 70%, black) 100%);
}

.admin-megjegyzes {
    display: block;
    font-size: 0.7rem;
    color: var(--error-text);
    font-weight: bold;
    margin-top: 5px;
    text-align: center;
}

.csak-ertekeleseim-lista {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px dashed var(--border-color);
}

.csak-ertekeleseim-lista h4 {
    font-size: 1.2rem;
    color: var(--secondary-text);
    margin-bottom: 15px;
}

.ertekeles-item {
    background: var(--tertiary-bg);
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 10px;
    border-left: 3px solid #ffc107;
}

.ertekeles-fejlec {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 8px;
}

.ertekeles-profilkep {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ffc107;
    cursor: pointer;
    transition: transform 0.3s ease;
    flex-shrink: 0;
}

.ertekeles-profilkep:hover {
    transform: scale(1.1);
}

.ertekeles-profilkep-link {
    display: block;
    text-decoration: none;
}

.ertekeles-info {
    flex: 1;
    min-width: 0;
}

.ertekeles-szerzo-nev-container {
    display: flex;
    align-items: baseline;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 3px;
}

.ertekeles-szerzo-felhasznalonev {
    font-weight: 600;
    color: var(--primary-text);
    font-size: 1rem;
    text-decoration: none;
    transition: color 0.3s ease;
}

.ertekeles-szerzo-felhasznalonev:hover {
    color: var(--accent-color);
    text-decoration: none;
}

.ertekeles-szerzo-teljesnev {
    font-size: 0.85rem;
    color: var(--secondary-text);
    font-style: italic;
    font-weight: 400;
}

.ertekeles-datum {
    font-size: 0.8rem;
    color: var(--secondary-text);
    margin-bottom: 5px;
}

.ertekeles-csillagok {
    display: flex;
    gap: 2px;
    align-items: center;
    flex-wrap: wrap;
    margin-top: 5px;
}

.csillag-ertekeles-item {
    font-size: 1rem;
    color: var(--border-color);
}

.csillag-ertekeles-item.active {
    color: #ffc107;
}

.ertekeles-megjegyzes {
    font-style: italic;
    color: var(--secondary-text);
    font-size: 0.9rem;
    margin-top: 5px;
    padding-left: 10px;
}

.ertekeles-urlap {
    background: var(--secondary-bg);
    padding: 30px;
    border-radius: 12px;
    margin-top: 40px;
    border: 2px solid var(--accent-color);
}

.ertekeles-urlap h3 {
    color: var(--primary-text);
    margin-bottom: 25px;
    font-size: 1.4rem;
    font-weight: 600;
}

.ertekeles-form {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.form-group label {
    font-weight: 600;
    margin-bottom: 10px;
    color: var(--primary-text);
    display: block;
    font-size: 1rem;
}

.csillag-ertekeles {
    display: flex;
    gap: 10px;
    margin: 10px 0;
    flex-wrap: wrap;
}

.csillag-label {
    cursor: pointer;
    position: relative;
    display: inline-block;
}

.csillag-bemenet {
    display: none;
}

.csillag {
    font-size: 2.5rem;
    color: var(--border-color);
    transition: all 0.3s ease;
    cursor: pointer;
    display: block;
}

.csillag-label:hover .csillag {
    color: #ffdb70;
    transform: scale(1.1);
}

.csillag-label:hover ~ .csillag-label .csillag {
    color: var(--border-color) !important;
    transform: none !important;
}

.csillag-bemenet:checked ~ .csillag-label .csillag {
    color: var(--border-color);
}

.csillag-bemenet:checked + .csillag,
.csillag-bemenet:checked ~ .csillag-label .csillag {
    color: #ffc107;
}

.csillag-label:hover .csillag {
    color: #ffc107;
}

.csillag-label:hover ~ .csillag-label .csillag {
    color: var(--border-color);
}

.megjegyzes-input {
    width: 100%;
    padding: 15px;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    background: var(--primary-bg);
    color: var(--primary-text);
    font-family: inherit;
    font-size: 1rem;
    resize: vertical;
    min-height: 120px;
    transition: all 0.3s ease;
}

.megjegyzes-input:focus {
    outline: none;
    border-color: var(--accent-color);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.kuldes-gomb {
    background: var(--accent-gradient);
    color: var(--button-text);
    border: none;
    padding: 15px 35px;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    width: auto;
}

.kuldes-gomb:hover {
    background: var(--button-hover-bg);
    transform: translateY(-3px);
    box-shadow: 0 5px 15px var(--shadow-color);
}

.bejelentkezes-figyelmeztetes {
    text-align: center;
    padding: 30px;
    background: var(--tertiary-bg);
    border-radius: 10px;
    border: 1px solid var(--border-color);
}

.bejelentkezes-figyelmeztetes h3 {
    color: var(--primary-text);
    margin-bottom: 15px;
}

.bejelentkezes-figyelmeztetes p {
    margin-bottom: 20px;
    color: var(--primary-text);
    font-size: 1.1rem;
}

.sajat-oldal-info {
    background: var(--tertiary-bg);
    padding: 30px;
    border-radius: 12px;
    margin-top: 30px;
    border-left: 4px solid var(--accent-color);
}

.sajat-oldal-info h3 {
    color: var(--primary-text);
    margin-bottom: 15px;
    font-size: 1.3rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

.sajat-oldal-info h3 i {
    color: var(--accent-color);
}

.sajat-oldal-info p {
    color: var(--primary-text);
    line-height: 1.6;
    margin-bottom: 10px;
}

.sajat-oldal-info a {
    color: var(--accent-color);
    text-decoration: none;
    font-weight: 600;
}

.sajat-oldal-info a:hover {
    text-decoration: underline;
}

.nincs-komment {
    text-align: center;
    padding: 50px 30px;
    color: var(--secondary-text);
    font-style: italic;
    background: var(--tertiary-bg);
    border-radius: 10px;
    border: 2px dashed var(--border-color);
    font-size: 1.1rem;
}

.hiba-uzenet {
    background: var(--error-bg);
    color: var(--error-text);
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 25px;
    border-left: 4px solid var(--error-text);
    font-weight: 600;
}

.siker-uzenet {
    background: var(--success-bg);
    color: var(--success-text);
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 25px;
    border-left: 4px solid var(--success-text);
    font-weight: 600;
}

@media (max-width: 768px) {
    .rateprofile-container {
        margin: 15px;
        padding: 20px;
    }
    
    .profil-header {
        flex-direction: column;
        text-align: center;
        gap: 20px;
    }
    
    .profil-kep {
        width: 150px;
        height: 150px;
    }
    
    .profil-felhasznalonev {
        font-size: 1.8rem;
    }
    
    .profil-teljes-nev {
        font-size: 1rem;
    }
    
    .szerkesztes-gomb {
        margin: 10px 0 0 0;
        width: 100%;
        justify-content: center;
        padding: 12px 20px;
        font-size: 1rem;
    }
    
    .elérhetőségek {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .komment-item {
        padding: 20px;
    }
    
    .komment-fejlec {
        flex-wrap: wrap;
    }
    
    .torles-form {
        margin-left: 0;
        margin-top: 10px;
        width: 100%;
        text-align: center;
        order: 3;
    }
    
    .csillag-ertekeles {
        gap: 8px;
        justify-content: center;
    }
    
    .csillag {
        font-size: 2rem;
    }
    
    .ertekeles-urlap {
        padding: 20px;
    }
    
    .komment-szerzo-nev-container {
        flex-direction: column;
        align-items: flex-start;
        gap: 2px;
    }
    
    .ertekeles-szerzo-nev-container {
        flex-direction: column;
        align-items: flex-start;
        gap: 2px;
    }
}

@media (max-width: 576px) {
    .rateprofile-container {
        margin: 10px;
        padding: 15px;
    }
    
    .profil-kep {
        width: 120px;
        height: 120px;
    }
    
    .profil-felhasznalonev {
        font-size: 1.5rem;
    }
    
    .profil-teljes-nev {
        font-size: 0.9rem;
    }
    
    .szerkesztes-gomb {
        padding: 10px 15px;
        font-size: 0.9rem;
    }
    
    .szerkesztes-gomb i {
        font-size: 0.9rem;
    }
    
    .reszlet-cim {
        font-size: 1.3rem;
    }
    
    .bemutatkozas {
        padding: 20px;
    }
    
    .elérhetőség-item {
        padding: 15px;
    }
    
    .komment-item {
        padding: 15px;
    }
    
    .komment-szoveg {
        padding: 15px;
    }
    
    .csillag {
        font-size: 1.8rem;
    }
    
    .megjegyzes-input {
        padding: 12px;
        min-height: 100px;
    }
    
    .kuldes-gomb {
        padding: 12px 25px;
        font-size: 1rem;
        width: 100%;
    }
}

@media (hover: none) and (pointer: coarse) {
    .komment-item:hover,
    .elérhetőség-item:hover,
    .kuldes-gomb:hover,
    .szerkesztes-gomb:hover {
        transform: none;
    }
    
    .csillag-label:hover .csillag {
        transform: none;
        color: var(--border-color);
    }
    
    .komment-profilkep:hover {
        transform: none;
    }
    
    .ertekeles-profilkep:hover {
        transform: none;
    }
    
    .komment-item:active,
    .elérhetőség-item:active,
    .kuldes-gomb:active,
    .szerkesztes-gomb:active {
        transform: scale(0.98);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
<div class="rateprofile-container">
    <a href="jobs" style="color: gray; font-style: italic;"><p style=" text-align: right;">Vissza a munkákhoz</p></a>
    <?php if(isset($_SESSION['error'])): ?>
        <div class="hiba-uzenet">
            <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="siker-uzenet">
            <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <div class="profil-header">
        <img src="<?php echo htmlspecialchars($profil_kep); ?>" 
             alt="Profilkép" 
             class="profil-kep"
             onerror="this.src='assets/images/profile/default-profile.png'">
        
        <div class="profil-info">
            <div style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                <div>
                    <h1 class="profil-felhasznalonev"><?php echo htmlspecialchars($felhasznalo['fnev']); ?></h1>
                    <?php if(!empty($felhasznalo['vnev']) || !empty($felhasznalo['knev'])): ?>
                        <p class="profil-teljes-nev"><?php echo htmlspecialchars($teljes_nev); ?></p>
                    <?php endif; ?>
                </div>
                
                <?php if($sajat_oldal): ?>
                    <a href="/profile" class="szerkesztes-gomb">
                        <i class="fas fa-edit"></i> Profil szerkesztése
                    </a>
                <?php endif; ?>
            </div>

            
            <div class="ertekeles-cim">
                <?php if($ertekelesek_szama > 0): ?>
                    <div class="csillagok-nagy">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <span class="csillag <?php echo $i <= $atlag_ertekeles ? 'active' : ''; ?>">★</span>
                        <?php endfor; ?>
                    </div>
                    <span class="ertekeles-szam">
                        <?php echo $atlag_ertekeles; ?>/5 - <?php echo $ertekelesek_szama; ?> értékelés alapján
                    </span>
                <?php else: ?>
                    <div class="ertekeles-szam">Még nincs értékelés</div>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <div class="profil-tartalom">
        <?php if(!empty($felhasznalo['reszletek'])): ?>
            <h3 class="reszlet-cim">Bemutatkozás</h3>
            <div class="bemutatkozas">
                <?php echo nl2br(htmlspecialchars($felhasznalo['reszletek'])); ?>
            </div>
        <?php endif; ?>

        <h3 class="reszlet-cim">Elérhetőségek</h3>
        <div class="elérhetőségek">
            <?php if(!empty($felhasznalo['telefon'])): ?>
                <div class="elérhetőség-item">
                    <i class="fas fa-phone"></i>
                    <span><?php echo htmlspecialchars($felhasznalo['telefon']); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if(!empty($felhasznalo['email'])): ?>
                <div class="elérhetőség-item">
                    <i class="fas fa-envelope"></i>
                    <span><?php echo htmlspecialchars($felhasznalo['email']); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if(!empty($felhasznalo['varmegye'])): ?>
                <div class="elérhetőség-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span><?php echo htmlspecialchars($felhasznalo['varmegye']); ?></span>
                </div>
            <?php endif; ?>
        </div>

        <div class="kommentek-section">
            <h3 class="komment-cim">Értékelések és megjegyzések</h3>
            
            <?php if(!empty($kommentek)): ?>
                <div class="kommentek-lista">
                    <?php foreach($kommentek as $komment): ?>
                        <?php 
                        $komment_user_fid = $komment['komment_fid'] ?? $komment['fid'];
                        $komment_user_fname = htmlspecialchars($komment['fnev']);
                        $komment_user_teljesnev = !empty($komment['vnev']) && !empty($komment['knev']) 
                            ? htmlspecialchars($komment['vnev'] . ' ' . $komment['knev']) 
                            : '';
                        ?>
                        <div class="komment-item">
                            <div class="komment-fejlec">
                                <a href="/rateProfile?fid=<?php echo $komment_user_fid; ?>" 
                                   class="komment-profilkep-link"
                                   title="<?php echo $komment_user_fname; ?> profilja">
                                    <img src="<?php echo htmlspecialchars($profilkep_mappa . ($komment['profilkep'] ?: 'default-profile.png')); ?>" 
                                         alt="Profilkép" 
                                         class="komment-profilkep"
                                         onerror="this.src='assets/images/profile/default-profile.png'">
                                </a>
                                
                                <div class="komment-szerzo-info">
                                    <div class="komment-szerzo-nev-container">
                                        <a href="/rateProfile?fid=<?php echo $komment_user_fid; ?>" 
                                           class="komment-szerzo-felhasznalonev"
                                           title="<?php echo $komment_user_fname; ?> profilja">
                                            <?php echo $komment_user_fname; ?>
                                        </a>
                                        <?php if(!empty($komment_user_teljesnev)): ?>
                                            <span class="komment-szerzo-teljesnev">(<?php echo $komment_user_teljesnev; ?>)</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="komment-datum">
                                        <?php echo date('Y.m.d. H:i', strtotime($komment['idopont'])); ?>
                                    </div>
                                    <div class="komment-ertekeles">
                                        <span class="ertekeles-csillagok-komment">
                                            <?php 
                                            if (isset($komment['ertekeles'])) {
                                                for ($i = 1; $i <= 5; $i++): 
                                                    $csillagClass = $i <= $komment['ertekeles'] ? 'active' : '';
                                            ?>
                                                <span class="csillag-komment <?php echo $csillagClass; ?>">★</span>
                                            <?php 
                                                endfor; 
                                                echo '<span class="ertekeles-szam">(' . $komment['ertekeles'] . '/5)</span>';
                                            }
                                            ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <?php if ($bejelentkezve && $aktualis_felhasznalo_id == $komment['fid']): ?>
                                    <form method="POST" class="torles-form">
                                        <input type="hidden" name="komment_id" value="<?php echo $komment['meid']; ?>">
                                        <button type="submit" name="torles" class="btn btn-danger" 
                                                onclick="return confirm('Biztosan törölni szeretnéd ezt a kommentet?')">
                                            Törlés
                                        </button>
                                    </form>
                                <?php elseif ($is_admin): ?>
                                    <form method="POST" class="torles-form">
                                        <input type="hidden" name="komment_id" value="<?php echo $komment['meid']; ?>">
                                        <button type="submit" name="torles" class="btn btn-danger admin-delete" 
                                                onclick="return confirm('Adminként törlöd ezt a kommentet?')">
                                            Admin törlés
                                        </button>
                                        <small class="admin-megjegyzes">(Admin)</small>
                                    </form>
                                <?php endif; ?>
                            </div>
                            <div class="komment-szoveg">
                                <?php echo nl2br(htmlspecialchars($komment['megjegyzes'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="nincs-komment">
                    Még nem érkeztek értékelések ehhez a felhasználóhoz.
                </div>
            <?php endif; ?>
            
            <?php if(!empty($csak_ertekeleseim)): ?>
                <div class="csak-ertekeleseim-lista">
                    <h4>Saját értékeléseim (komment nélkül):</h4>
                    <?php foreach($csak_ertekeleseim as $ertekeles): ?>
                        <?php 
                        $ertekeles_user_fid = $ertekeles['komment_fid'] ?? $ertekeles['fid'];
                        $ertekeles_user_fname = htmlspecialchars($ertekeles['fnev']);
                        $ertekeles_user_teljesnev = !empty($ertekeles['vnev']) && !empty($ertekeles['knev']) 
                            ? htmlspecialchars($ertekeles['vnev'] . ' ' . $ertekeles['knev']) 
                            : '';
                        ?>
                        <div class="ertekeles-item">
                            <div class="ertekeles-fejlec">
                                <a href="/rateProfile?fid=<?php echo $ertekeles_user_fid; ?>" 
                                   class="ertekeles-profilkep-link"
                                   title="<?php echo $ertekeles_user_fname; ?> profilja">
                                    <img src="<?php echo htmlspecialchars($profilkep_mappa . ($ertekeles['profilkep'] ?: 'default-profile.png')); ?>" 
                                         alt="Profilkép" 
                                         class="ertekeles-profilkep"
                                         onerror="this.src='assets/images/profile/default-profile.png'">
                                </a>
                                
                                <div class="ertekeles-info">
                                    <div class="ertekeles-szerzo-nev-container">
                                        <a href="/rateProfile?fid=<?php echo $ertekeles_user_fid; ?>" 
                                           class="ertekeles-szerzo-felhasznalonev"
                                           title="<?php echo $ertekeles_user_fname; ?> profilja">
                                            <?php echo $ertekeles_user_fname; ?>
                                        </a>
                                        <?php if(!empty($ertekeles_user_teljesnev)): ?>
                                            <span class="ertekeles-szerzo-teljesnev">(<?php echo $ertekeles_user_teljesnev; ?>)</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="ertekeles-datum">
                                        <?php echo date('Y.m.d. H:i', strtotime($ertekeles['idopont'])); ?>
                                    </div>
                                    <div class="ertekeles-csillagok">
                                        <?php 
                                        if (isset($ertekeles['ertekeles'])) {
                                            for ($i = 1; $i <= 5; $i++): 
                                                $csillagClass = $i <= $ertekeles['ertekeles'] ? 'active' : '';
                                        ?>
                                            <span class="csillag-ertekeles-item <?php echo $csillagClass; ?>">★</span>
                                        <?php 
                                            endfor; 
                                            echo '<span class="ertekeles-szam">(' . $ertekeles['ertekeles'] . '/5)</span>';
                                        }
                                        ?>
                                    </div>
                                </div>
                                
                                <?php if ($bejelentkezve && $aktualis_felhasznalo_id == $ertekeles['fid']): ?>
                                    <form method="POST" class="torles-form">
                                        <input type="hidden" name="komment_id" value="<?php echo $ertekeles['meid']; ?>">
                                        <button type="submit" name="torles" class="btn btn-danger" 
                                                onclick="return confirm('Biztosan törölni szeretnéd ezt az értékelést?')">
                                            Törlés
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                            <p class="ertekeles-megjegyzes"><em>Csak értékelés, megjegyzés nélkül</em></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if(!$sajat_oldal): ?>
        <div class="ertekeles-urlap">
    <?php if($bejelentkezve): ?>
        <h3>Értékelés küldése</h3>
        <form method="POST" class="ertekeles-form">
            <input type="hidden" name="dolgozo_id" value="<?php echo $profile_fid; ?>">
            
            <div class="form-group">
                <label>Értékelés (1-5 csillag):</label>
                <div class="csillag-ertekeles">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <label class="csillag-label">
                            <input type="radio" name="rating" value="<?php echo $i; ?>" 
                                   class="csillag-bemenet" required>
                            <span class="csillag">★</span>
                        </label>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="comment">Megjegyzés (opcionális):</label>
                <textarea name="comment" id="comment" 
                          class="megjegyzes-input" 
                          placeholder="Írj megjegyzést az értékelésed mellé..."></textarea>
            </div>
            
            <button type="submit" name="ertekeles" class="kuldes-gomb">
                <i class="fas fa-paper-plane"></i> Értékelés küldése
            </button>
        </form>
    <?php else: ?>
        <div class="bejelentkezes-figyelmeztetes">
            <h3>Értékelés küldése</h3>
            <p>Az értékeléshez be kell jelentkezned.</p>
            <a href="/login" class="kuldes-gomb">
                <i class="fas fa-sign-in-alt"></i> Bejelentkezés
            </a>
        </div>
    <?php endif; ?>
</div>
    <?php else: ?>
        <div class="sajat-oldal-info">
            <h3><i class="fas fa-info-circle"></i> Saját profilod</h3>
            <p>Saját magadat nem értékelheted. Ez az űrlap csak más felhasználók számára jelenik meg.</p>
            <p>Ha szeretnéd szerkeszteni a profilodat, látogass el a <a href="/profile">profil szerkesztése</a> oldalra.</p>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const starGroups = document.querySelectorAll('.csillag-ertekeles');
    
    starGroups.forEach(group => {
        const stars = Array.from(group.querySelectorAll('.csillag-bemenet'));
        const starLabels = Array.from(group.querySelectorAll('.csillag-label'));
        const starIcons = Array.from(group.querySelectorAll('.csillag-label .csillag'));
        
        const checkedStar = group.querySelector('.csillag-bemenet:checked');
        if (checkedStar) {
            const checkedIndex = stars.indexOf(checkedStar);
            updateStars(starIcons, checkedIndex);
        }
        
        stars.forEach((star, index) => {
            star.addEventListener('change', function() {
                updateStars(starIcons, index);
            });
        });
        
        starLabels.forEach((label, index) => {
            label.addEventListener('mouseover', function() {
                resetStars(starIcons);
                for(let i = 0; i <= index; i++) {
                    if(starIcons[i]) {
                        starIcons[i].style.color = '#ffdb70';
                        starIcons[i].classList.add('active');
                    }
                }
            });
            
            label.addEventListener('mouseout', function() {
                const checkedStar = group.querySelector('.csillag-bemenet:checked');
                if (checkedStar) {
                    const checkedIndex = stars.indexOf(checkedStar);
                    updateStars(starIcons, checkedIndex);
                } else {
                    resetStars(starIcons);
                }
            });
        });
    });
    
    function updateStars(starIcons, index) {
        resetStars(starIcons);
        for(let i = 0; i <= index; i++) {
            if(starIcons[i]) {
                starIcons[i].style.color = '#ffc107';
                starIcons[i].classList.add('active');
            }
        }
    }
    
    function resetStars(starIcons) {
        starIcons.forEach(icon => {
            icon.style.color = '';
            icon.classList.remove('active');
        });
    }
    
    const ertekelesForm = document.querySelector('.ertekeles-form');
    if (ertekelesForm) {
        ertekelesForm.addEventListener('submit', function(e) {
            const rating = this.querySelector('input[name="rating"]:checked');
            if (!rating) {
                e.preventDefault();
                alert('Kérjük, válassz értékelést (1-5 csillag)!');
                return false;
            }
            
            const comment = this.querySelector('textarea[name="comment"]');
            if (comment.value.trim().length > 1000) {
                e.preventDefault();
                alert('A megjegyzés maximum 1000 karakter hosszú lehet!');
                comment.focus();
                return false;
            }
        });
    }
    
    document.querySelectorAll('.komment-profilkep-link, .komment-szerzo-felhasznalonev, .ertekeles-profilkep-link, .ertekeles-szerzo-felhasznalonev').forEach(link => {
        link.addEventListener('click', function(e) {
            console.log('Felhasználó profiljára navigálva:', this.href);
        });
    });
});
</script>