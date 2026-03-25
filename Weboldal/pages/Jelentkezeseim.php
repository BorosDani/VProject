<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . "/../includes/functions.php");
require_once(__DIR__ . "/../includes/config.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['fid'])) {
    $_SESSION['error'] = "A jelentkezéseid megtekintéséhez be kell jelentkezni!";
    header("Location: login.php");
    exit;
}

$functions_file = "includes/functions.php";
if (file_exists($functions_file)) {
    require_once($functions_file);
} else {
    if (!isset($conn)) {
        die("Hiba: Adatbázis kapcsolat nem található!");
    }
}

$user_id = $_SESSION['fid'];

if (isset($_POST['withdraw_btn'])) {
    $torlendo_id = (int)$_POST['jel_id'];
    try {
        $check_sql = "SELECT 
                        j.munka_id, 
                        m.statusz, 
                        m.munka_nev,
                        h.email as hirdeto_email, 
                        h.fnev as hirdeto_nev,
                        j_user.fnev as jelentkezo_nev
                      FROM jelentkezesek j 
                      LEFT JOIN munkak m ON j.munka_id = m.id
                      LEFT JOIN felhasznalok h ON m.felhasznalo_id = h.fid
                      LEFT JOIN felhasznalok j_user ON j.felhasznalo_id = j_user.fid
                      WHERE j.id = ? AND j.felhasznalo_id = ?";
                      
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->execute([$torlendo_id, $user_id]);
        $result = $check_stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $munka_id = $result['munka_id'];
            $munka_statusz = $result['statusz']; 
            
            $hirdeto_email = $result['hirdeto_email'];
            $hirdeto_nev = $result['hirdeto_nev'];
            $munka_nev = $result['munka_nev'];
            $jelentkezo_nev = $result['jelentkezo_nev'];
            $mai_datum = date("Y.m.d. H:i");

            $del_sql = "DELETE FROM jelentkezesek WHERE id = ?";
            $del_stmt = $conn->prepare($del_sql);
            
            if ($del_stmt->execute([$torlendo_id])) {
                if ($munka_id && $munka_statusz !== 'torolt') {
                    $update_sql = "UPDATE munkak SET statusz = 'aktiv' WHERE id = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->execute([$munka_id]);
                }

                if (!empty($hirdeto_email)) {
                    $to = $hirdeto_email;
                    $subject = "Jelentkezés visszavonása - " . $munka_nev;
                    
                    
                    $message = '<!DOCTYPE html>
                    <html lang="hu">
                    <head>
                        <meta charset="UTF-8">
                        <title>Jelentkezés visszavonása</title>
                    </head>
                    <body style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
                        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; color: white; text-align: center;">
                            <h1>Villám Meló</h1>
                            <h2>Jelentkezés visszavonva</h2>
                        </div>
                        
                        <div style="padding: 20px; background-color: #f9f9f9;">
                            <p>Kedves <strong>' . htmlspecialchars($hirdeto_nev) . '</strong>!</p>
                            
                            <p>Tájékoztatjuk, hogy egy felhasználó visszavonta a jelentkezését az alábbi munkára:</p>
                            
                            <div style="background-color: white; padding: 15px; border-radius: 8px; margin: 20px 0; border: 1px solid #e0e0e0;">
                                <h3 style="color: #667eea; margin-top: 0;">' . htmlspecialchars($munka_nev) . '</h3>
                                <p><strong>Jelentkező neve:</strong> ' . htmlspecialchars($jelentkezo_nev) . '</p>
                                <p><strong>Visszavonás ideje:</strong> ' . $mai_datum . '</p>
                            </div>
                            
                            <div style="text-align: center; margin: 30px 0;">
                                <a href="https://villammelo.hu/work?job_id=' . $munka_id . '" 
                                   style="background-color: #667eea; color: white; padding: 12px 24px; 
                                          text-decoration: none; border-radius: 5px; display: inline-block;
                                          font-weight: bold; font-size: 14px;">
                                   Munka megtekintése
                                </a>
                            </div>
                            
                            <p style="color: #666; font-size: 13px; border-left: 4px solid #667eea; padding-left: 10px;">
                                <strong>A munka státusza:</strong> A munka visszakerült <strong>Aktív</strong> státuszba, így újra fogadhat jelentkezéseket.
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

                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                    
                    $headers .= "From: Villám Meló <ertesites@villammelo.hu>" . "\r\n"; 

                    @mail($to, $subject, $message, $headers);
                }

                $_SESSION['success'] = "A jelentkezést sikeresen törölted, és a hirdetőt értesítettük.";
            } else {
                $_SESSION['error'] = "Hiba történt a törlés során.";
            }
        } else {
            $_SESSION['error'] = "A jelentkezés nem található, vagy nincs jogosultságod a visszavonáshoz.";
        }

    } catch (Exception $e) {
        $_SESSION['error'] = "Adatbázis hiba: " . $e->getMessage();
    }
    header("Location: Jelentkezeseim");
    exit;
}

$query = "SELECT 
            j.id as jelentkezes_id,
            j.idopont as jelentkezes_ideje,
            m.id as munka_id,
            m.munka_nev,
            m.ar,
            m.statusz,
            m.munka_leiras,
            f.fnev as hirdeto_nev,
            f.profilkep as hirdeto_kep,
            (SELECT GROUP_CONCAT(kep_nev SEPARATOR ',') FROM munka_kepek WHERE munka_id = m.id) as referenciak
          FROM jelentkezesek j
          LEFT JOIN munkak m ON j.munka_id = m.id
          LEFT JOIN felhasznalok f ON m.felhasznalo_id = f.fid
          WHERE j.felhasznalo_id = ?
          ORDER BY j.idopont DESC";

try {
    $stmt = $conn->prepare($query);
    $stmt->execute([$user_id]);
    $jelentkezesek = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    try {
        $query_fallback = "SELECT 
            j.id as jelentkezes_id,
            j.idopont as jelentkezes_ideje,
            m.id as munka_id,
            m.munka_nev,
            m.ar,
            m.statusz,
            m.munka_leiras,
            f.fnev as hirdeto_nev,
            f.profilkep as hirdeto_kep,
            '' as referenciak
          FROM jelentkezesek j
          LEFT JOIN munkak m ON j.munka_id = m.id
          LEFT JOIN felhasznalok f ON m.felhasznalo_id = f.fid
          WHERE j.felhasznalo_id = ?
          ORDER BY j.idopont DESC";
          
        $stmt = $conn->prepare($query_fallback);
        $stmt->execute([$user_id]);
        $jelentkezesek = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $ex) {
        $jelentkezesek = [];
        $db_error = "Adatbázis hiba: " . $ex->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Jelentkezéseim</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --bg-body: #f4f6f9;
            --bg-card: #ffffff;
            --text-main: #333333;
            --text-muted: #666666;
            --border-color: #e0e0e0;
            --header-bg: #f9f9f9;
            --shadow: 0 4px 6px rgba(0,0,0,0.05);
            --shadow-hover: 0 8px 15px rgba(0,0,0,0.1);
            --link-color: #007bff;
            --btn-bg: #007bff;
            --btn-text: #ffffff;
            --btn-cancel-border: #dc3545;
            --btn-cancel-text: #dc3545;
        }

        [data-theme="dark"] {
            --bg-body: #121212;
            --bg-card: #1e1e1e;
            --text-main: #e0e0e0;
            --text-muted: #a0a0a0;
            --border-color: #333333;
            --header-bg: #2d2d2d;
            --shadow: 0 4px 6px rgba(0,0,0,0.5);
            --shadow-hover: 0 8px 15px rgba(0,0,0,0.7);
            --link-color: #66b0ff;
            --btn-bg: #0d6efd;
            --btn-cancel-border: #ff6b6b;
            --btn-cancel-text: #ff6b6b;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 45px;
            transition: background-color 0.3s, color 0.3s;
        }

        .container-jel {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 15px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .page-header h1 {
            margin: 0;
            font-size: 1.8rem;
        }

        .cards-wrapper {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
        }
        
        @media (max-width: 400px) {
            .cards-wrapper {
                grid-template-columns: 1fr;
            }
        }

        .app-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            flex-direction: column;
        }

        .app-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        .app-card.deleted-job {
            opacity: 0.75;
            border-color: #e0e0e0;
            background-color: rgba(200, 200, 200, 0.1);
        }
        .app-card.deleted-job:hover {
            transform: none;
            box-shadow: var(--shadow);
        }

        .card-head {
            background: var(--header-bg);
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            color: var(--text-muted);
        }
        
        .status-active { color: #28a745; font-weight: bold; }
        .status-closed { color: #dc3545; font-weight: bold; }
        .status-inprogress { color: #fd7e14; font-weight: bold; }
        .status-deleted { color: #6c757d; font-weight: bold; text-decoration: line-through; }
        
        .card-body {
            padding: 20px 15px;
            flex-grow: 1;
        }
        
        .job-title {
            margin: 0 0 10px 0;
            font-size: 1.3rem;
            color: var(--text-main);
            line-height: 1.3;
        }

        .image-gallery-scroll {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 10px;
            margin-bottom: 15px;
            scroll-snap-type: x mandatory;
            scrollbar-width: thin; 
            scrollbar-color: var(--text-muted) transparent;
        }
        
        .image-gallery-scroll::-webkit-scrollbar {
            height: 6px;
        }
        .image-gallery-scroll::-webkit-scrollbar-thumb {
            background-color: var(--border-color);
            border-radius: 3px;
        }

        .gallery-item {
            scroll-snap-align: start;
            flex: 0 0 auto;
            width: 85%;
            max-width: 300px;
            height: 200px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--border-color);
            position: relative;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        
        .price-tag {
            font-size: 1.2rem;
            color: var(--link-color);
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .employer-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding-top: 10px;
            border-top: 1px solid var(--border-color);
            color: var(--text-muted);
            font-size: 0.95rem;
        }
        
        .emp-img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid var(--border-color);
        }

        .card-actions {
            padding: 15px;
            background: var(--bg-card);
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            border: none;
            font-weight: 600;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all 0.2s;
        }
        
        .btn-view {
            background-color: var(--btn-bg);
            color: var(--btn-text);
            flex: 1;
        }
        .btn-view:hover { opacity: 0.9; }
        
        .btn-view.disabled {
            background-color: #6c757d;
            cursor: not-allowed;
            opacity: 0.6;
            pointer-events: none;
        }
        
        .btn-withdraw {
            background: transparent;
            color: var(--btn-cancel-text);
            border: 1px solid var(--btn-cancel-border);
            width: 45px;
        }
        .btn-withdraw:hover {
            background-color: var(--btn-cancel-border);
            color: #fff;
        }

        .alert { padding: 15px; margin-bottom: 20px; border-radius: 8px; }
        .alert-success { background: #d1e7dd; color: #0f5132; }
        .alert-error { background: #f8d7da; color: #842029; }

    </style>
</head>
<body>

<div class="container-jel">
    <div class="page-header">
        <h1>Jelentkezéseim</h1>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($db_error)): ?>
        <div class="alert alert-error"><?php echo $db_error; ?></div>
    <?php endif; ?>

    <?php if (empty($jelentkezesek)): ?>
        <div style="text-align:center; padding:50px; color:var(--text-muted); background:var(--bg-card); border-radius:12px; border:1px solid var(--border-color);">
            <i class="far fa-folder-open" style="font-size:3rem; margin-bottom:15px; opacity:0.5;"></i>
            <h3>Még nem jelentkeztél egyetlen munkára sem.</h3>
            <p>Nézz szét a főoldalon a legfrissebb ajánlatok között!</p>
        </div>
    <?php else: ?>
        <div class="cards-wrapper">
            <?php foreach ($jelentkezesek as $jel): ?>
                <?php 
                    $is_deleted = empty($jel['munka_id']) || ($jel['statusz'] === 'torolt');

                    $status_class = 'status-closed';
                    $display_status = '';
                    
                    if ($is_deleted) {
                        $status_class = 'status-deleted';
                        $display_status = 'TÖRÖLT MUNKA';
                    } elseif ($jel['statusz'] == 'aktiv') {
                        $status_class = 'status-active';
                        $display_status = 'Aktív';
                    } elseif ($jel['statusz'] == 'folyamatban') {
                        $status_class = 'status-inprogress';
                        $display_status = 'Folyamatban';
                    } else {
                        $display_status = 'Befejezett';
                    }
                    
                    $job_name = !empty($jel['munka_nev']) ? htmlspecialchars($jel['munka_nev']) : 'Ismeretlen / Törölt munka';
                    $job_price = !empty($jel['ar']) ? number_format($jel['ar'], 0, ',', ' ') . ' Ft' : '-';
                ?>

                <div class="app-card <?php echo $is_deleted ? 'deleted-job' : ''; ?>">
                    <div class="card-head">
                        <span><i class="far fa-clock"></i> <?php echo date('Y.m.d. H:i', strtotime($jel['jelentkezes_ideje'])); ?></span>
                        <span class="<?php echo $status_class; ?>">
                            <?php echo $display_status; ?>
                        </span>
                    </div>

                    <div class="card-body">
                        <h3 class="job-title"><?php echo $job_name; ?></h3>
                        
                        <div class="price-tag">
                            <?php echo $job_price; ?>
                        </div>

                        <?php 
                        if (!$is_deleted && !empty($jel['referenciak'])) {
                            $kepek = explode(',', $jel['referenciak']);
                            echo '<div class="image-gallery-scroll">';
                            foreach ($kepek as $kep) {
                                $kep = trim($kep);
                                if (!empty($kep)) {
                                    $img_path = "assets/images/munkak/" . htmlspecialchars($kep);
                                    echo '<div class="gallery-item">';
                                        echo '<img src="' . $img_path . '" alt="Referencia kép" onclick="window.open(this.src, \'_blank\');">';
                                    echo '</div>';
                                }
                            }
                            echo '</div>';
                        }
                        ?>

                        <div class="employer-info">
                            <?php if (!$is_deleted && !empty($jel['hirdeto_nev'])): ?>
                                <img src="assets/images/profile/<?php echo !empty($jel['hirdeto_kep']) ? htmlspecialchars($jel['hirdeto_kep']) : 'default-profile.png'; ?>" 
                                     class="emp-img" 
                                     onerror="this.src='assets/images/profile/default-profile.png'">
                                <span><?php echo htmlspecialchars($jel['hirdeto_nev']); ?></span>
                            <?php else: ?>
                                <span style="font-style:italic;">A hirdető vagy a munka nem elérhető.</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card-actions">
                        <?php if (!$is_deleted): ?>
                            <a href="work?job_id=<?php echo $jel['munka_id']; ?>" class="btn btn-view">
                                Részletek
                            </a>
                        <?php else: ?>
                            <a href="#" class="btn btn-view disabled">
                                Nem elérhető
                            </a>
                        <?php endif; ?>
                        
                        <form method="POST" onsubmit="return confirm('Biztosan törlöd a listádból? Ezzel a hirdetőt is értesítjük.');" style="margin:0;">
                            <input type="hidden" name="jel_id" value="<?php echo $jel['jelentkezes_id']; ?>">
                            <button type="submit" name="withdraw_btn" class="btn btn-withdraw" title="Jelentkezés törlése a listáról">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>