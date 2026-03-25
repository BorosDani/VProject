<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$functions_path = __DIR__ . "/../includes/functions.php";
if (!file_exists($functions_path)) {
    die("HIBA: Nem található a functions.php ezen az útvonalon: " . $functions_path);
}
require_once($functions_path);

if (!isset($_SESSION['fid'])) {
    header("Location: " . base_url('login'));
    exit;
}

global $conn;
if (!$conn) {
    die("HIBA: Nincs adatbázis kapcsolat. Ellenőrizd a db_connect.php-t!");
}

$tiltas_info = ellenoriz_tiltast();
if ($tiltas_info) {
    echo '<div class="error-message">';
    echo '<h3>🚫 Fiókod ki van tiltva!</h3>';
    echo '<p><strong>Ok:</strong> ' . htmlspecialchars($tiltas_info['ok']) . '</p>';
    exit;
}

$hibak = [];
$siker = '';

try {
    $user_data = null;
    try {
        $email_sql = "SELECT * FROM felhasznalok WHERE id = ?"; 
        $email_stmt = $conn->prepare($email_sql);
        $email_stmt->execute([$_SESSION['fid']]);
        $user_data = $email_stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Adatbázis hiba (felhasználó lekérése): " . $e->getMessage());
    }

    $lejart_sql = "SELECT id, munka_nev, datum_ido FROM munkak 
                   WHERE felhasznalo_id = ? 
                   AND statusz = 'aktiv' 
                   AND datum_ido < NOW()";
    $lejart_stmt = $conn->prepare($lejart_sql);
    $lejart_stmt->execute([$_SESSION['fid']]);
    $lejart_munkak = $lejart_stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($lejart_munkak as $lm) {

        $archive_sql = "UPDATE munkak SET statusz = 'archivalt' WHERE id = ?";
        $conn->prepare($archive_sql)->execute([$lm['id']]);

        if ($user_data && !empty($user_data['email'])) {
            $to = $user_data['email'];
            $nev = !empty($user_data['keresztnev']) ? $user_data['keresztnev'] : 'Felhasználó';
            $subject = "A munka határideje lejárt: " . $lm['munka_nev'];
            $message = "Kedves " . $nev . "!\n\n";
            $message .= "A(z) '" . $lm['munka_nev'] . "' című munkád határideje lejárt (" . $lm['datum_ido'] . ").\n";
            $message .= "A rendszer automatikusan archiválta a hirdetést.\n\n";
            $message .= "Üdvözlettel,\nA Csapat";
            $headers = "From: noreply@villammelo.hu\r\nContent-Type: text/plain; charset=UTF-8\r\n";
            @mail($to, $subject, $message, $headers);
        }
    }

    $reactivate_sql = "UPDATE munkak 
                       SET statusz = 'aktiv' 
                       WHERE felhasznalo_id = ? 
                       AND statusz = 'archivalt' 
                       AND datum_ido > NOW()";
    $conn->prepare($reactivate_sql)->execute([$_SESSION['fid']]);

    $korrekcio_sql = "UPDATE munkak m 
                      SET m.statusz = 'aktiv' 
                      WHERE m.felhasznalo_id = ? 
                      AND m.statusz = 'folyamatban' 
                      AND m.datum_ido > NOW() 
                      AND (SELECT COUNT(*) FROM jelentkezesek j WHERE j.munka_id = m.id) = 0";
    $conn->prepare($korrekcio_sql)->execute([$_SESSION['fid']]);

} catch (PDOException $e) {
    error_log("Hiba az automatikus karbantartás során: " . $e->getMessage());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['update_status'])) {
            $munka_id = intval($_POST['munka_id']);
            $uj_statusz = $_POST['statusz'];
            
            $chk = $conn->prepare("SELECT id, statusz, (SELECT COUNT(*) FROM jelentkezesek WHERE munka_id = munkak.id) as j_count FROM munkak WHERE id = ? AND felhasznalo_id = ?");
            $chk->execute([$munka_id, $_SESSION['fid']]);
            $m = $chk->fetch();

            if ($m) {
                if ($m['j_count'] > 0 && $uj_statusz !== 'befejezett') {
                    $hibak[] = "Van jelentkező, csak 'Befejezett' státuszra válthatsz!";
                } elseif ($m['statusz'] === 'archivalt' && $uj_statusz === 'aktiv') {
                    $hibak[] = "Archivált munka nem lehet újra aktív (csak ha szerkeszted a dátumot).";
                } elseif ($uj_statusz === 'archivalt') {
                    $hibak[] = "Kézzel nem archiválhatsz (az automatikus lejáratkor).";
                } else {
                    $conn->prepare("UPDATE munkak SET statusz = ? WHERE id = ?")->execute([$uj_statusz, $munka_id]);
                    $siker = "Státusz frissítve!";
                    header("Refresh:0");
                }
            }
        }

        if (isset($_POST['delete_job'])) {
            $mid = intval($_POST['munka_id']);
            $chk = $conn->prepare("SELECT id FROM munkak WHERE id = ? AND felhasznalo_id = ?");
            $chk->execute([$mid, $_SESSION['fid']]);
            
            if ($chk->fetch()) {
                $kepek = $conn->prepare("SELECT kep_url FROM referencia_kepek WHERE munka_id = ?");
                $kepek->execute([$mid]);
                foreach ($kepek->fetchAll() as $k) {
                    $f = __DIR__ . "/../assets/images/referencia_kepek/" . $k['kep_url'];
                    if (file_exists($f)) unlink($f);
                }
                
                $conn->prepare("DELETE FROM referencia_kepek WHERE munka_id = ?")->execute([$mid]);
                $conn->prepare("DELETE FROM jelentkezesek WHERE munka_id = ?")->execute([$mid]);
                $conn->prepare("DELETE FROM munkak WHERE id = ?")->execute([$mid]);
                
                $siker = "Munka törölve!";
                header("Refresh:0");
            }
        }

        if (isset($_POST['edit_job'])) {
            $mid = intval($_POST['munka_id']);
            $chk = $conn->prepare("SELECT COUNT(*) FROM jelentkezesek WHERE munka_id = ?");
            $chk->execute([$mid]);
            if ($chk->fetchColumn() > 0) {
                $hibak[] = "Már jelentkeztek rá, nem szerkeszthető!";
            } else {
                header("Location: " . base_url("editJob?id=" . $mid));
                exit;
            }
        }

    } catch (PDOException $e) {
        $hibak[] = "Adatbázis műveleti hiba: " . $e->getMessage();
    }
}

$munkak = [];
try {
    $sql = "SELECT m.*, 
                (SELECT COUNT(*) FROM referencia_kepek rk WHERE rk.munka_id = m.id) as kep_szam,
                (SELECT COUNT(*) FROM jelentkezesek j WHERE j.munka_id = m.id) as jelentkezes_szam
            FROM munkak m 
            WHERE m.felhasznalo_id = ? AND m.aktiv = 1
            ORDER BY m.letrehozas_datuma DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_SESSION['fid']]);
    $munkak = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($munkak as &$munka) {
        $kep_stmt = $conn->prepare("SELECT kep_url FROM referencia_kepek WHERE munka_id = ? ORDER BY sorrend LIMIT 1");
        $kep_stmt->execute([$munka['id']]);
        $munka['kepek'] = $kep_stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    unset($munka);
} catch (PDOException $e) {
    $hibak[] = "Nem sikerült betölteni a munkákat: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="hu" data-theme="<?= isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Munkáim</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <style>
         :root 
        {   
            --bg-body: #f7fafc; 
            --card-bg: #ffffff; 
            --primary-text: #1a202c; 
            --secondary-text: #718096; 
            --border-color: #e2e8f0; 
            --secondary-bg: #edf2f7; 
            --tertiary-bg: #f8f9fa; 
            --accent-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            --accent-color: #667eea; 
            --modal-overlay: rgba(0, 0, 0, 0.5); 
        }

        [data-theme="dark"] 
        { 
            --bg-body: #1a202c; 
            --card-bg: #2d3748; 
            --primary-text: #f7fafc; 
            --secondary-text: #a0aec0; 
            --border-color: #4a5568; 
            --secondary-bg: #4a5568; 
            --tertiary-bg: #283141; 
            --modal-overlay: rgba(0, 0, 0, 0.7); 
        }

        body 
        { 
            background-color: var(--bg-body); 
            color: var(--primary-text); 
            transition: background-color 0.3s, color 0.3s; 
        }
        
        .my-jobs-page 
        { 
            max-width: 1400px; 
            margin: 30px auto; 
            padding: 0 20px; 
        }

        .page-header 
        { 
            text-align: center; 
            margin-bottom: 40px; 
        }

        .page-header h1 
        { 
            font-size: 2rem; 
            margin-bottom: 10px; 
        }
        
        .jobs-grid 
        { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); 
            gap: 30px; padding-bottom: 50px; 
        }

        .job-card 
        { 
            background: var(--card-bg); 
            border: 1px solid var(--border-color); 
            border-radius: 16px; 
            overflow: hidden; 
            display: flex; 
            flex-direction: column; 
            height: 100%; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); 
            transition: transform 0.2s; 
        }

        .job-card:hover 
        { 
            transform: translateY(-5px); 
        }
        
        .job-images 
        { 
            height: 240px; 
            width: 100%; 
            background: var(--secondary-bg); 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            overflow: hidden; 
        }

        .job-image 
        { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
        }

        .no-images 
        { 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            gap: 10px; 
            color: var(--secondary-text); 
        }

        .job-info 
        { 
            padding: 20px; 
            flex-grow: 1; 
            display: flex; 
            flex-direction: column; 
        }

        .job-header 
        { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 12px; 
        }

        .job-title 
        { 
            font-size: 1.1rem; 
            font-weight: 700; 
            margin: 0; 
        }

        .job-price 
        { 
            background: var(--accent-gradient); 
            color: white; 
            padding: 4px 10px; 
            border-radius: 6px; 
            font-weight: 600; 
            font-size: 0.9rem; 
        }

        .job-description 
        { 
            color: var(--secondary-text);
            font-size: 0.95rem; 
            margin-bottom: 20px; 
            display: -webkit-box; 
            -webkit-line-clamp: 3; 
            -webkit-box-orient: vertical; 
            overflow: hidden; }
        .job-details 
        { 
            margin-top: auto; 
            display: flex; 
            gap: 15px; 
            font-size: 0.85rem; 
            color: var(--secondary-text); 
            padding-top: 15px; 
            border-top: 1px solid var(--border-color); 
        }

        .job-status 
        { 
            background: var(--tertiary-bg); 
            padding: 15px 20px; 
            border-top: 1px solid var(--border-color); 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
        }

        .status-badge
        { 
            font-size: 0.75rem; 
            font-weight: 700; 
            text-transform: uppercase; 
            padding: 4px 8px; 
            border-radius: 4px; 
        }
        
        .status-aktiv 
        { 
            color: #22543d; 
            background: #c6f6d5; 
        }
        
        .status-folyamatban 
        { 
            color: #744210; 
            background: #fefcbf; 
        }
        
        .status-befejezett 
        { 
            color: #2a4365; 
            background: #bee3f8; 
        }
        
        .status-archivalt 
        { 
            color: #4a5568; 
            background: #e2e8f0; 
        }

        .job-actions 
        { 
            display: flex; 
            gap: 8px; 
        }
        
        .action-btn 
        { 
            width: 36px; 
            height: 36px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            border-radius: 8px; 
            border: 1px solid var(--border-color); 
            cursor: pointer; 
            background: var(--card-bg); 
            font-size: 1.1rem; 
        }
        
        .action-btn.disabled 
        { 
            opacity: 0.6; 
            cursor: not-allowed; 
            background: var(--secondary-bg); 
        }
        
        .status-modal 
        { 
            display: none; 
            position: fixed; 
            top: 0; left: 0; 
            width: 100%; 
            height: 100%; 
            background: var(--modal-overlay); 
            z-index: 9999; 
            align-items: center; 
            justify-content: center; 
            backdrop-filter: blur(4px); 
        }
        
        .modal-content 
        { 
            background: var(--card-bg); 
            padding: 25px; 
            border-radius: 16px; 
            width: 90%; 
            max-width: 400px; 
            border: 1px solid var(--border-color); 
        }
        
        .status-option 
        { 
            padding: 12px 15px; 
            border: 2px solid var(--border-color); 
            border-radius: 10px; 
            cursor: pointer; 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 10px; 
            align-items: center; 
        }

        .status-option.active 
        { 
            border-color: #3182ce; 
            background: rgba(49, 130, 206, 0.1); 
        }
        
        .status-option.disabled 
        { 
            opacity: 0.5; 
            pointer-events: none; 
            background: var(--secondary-bg); 
        }
        
        .modal-actions 
        { 
            display: grid; 
            grid-template-columns: 1fr 1fr; 
            gap: 15px; 
            margin-top: 25px; 
        }
        
        .btn-modal 
        { 
            padding: 12px; 
            border-radius: 10px; 
            font-weight: 600; 
            cursor: pointer; 
            border: none; 
        }
        
        .btn-update 
        { 
            background: var(--accent-gradient); 
            color: white; 
        }
    </style>
</head>
<body>

    <div class="my-jobs-page">
        <div class="page-header">
            <h1>Munkáim</h1>
            <p class="page-description">Kezeld a feltöltött hirdetéseidet egy helyen.</p>
        </div>

        <div class="message-container">
            <?php if (!empty($siker)): ?>
                <div style="text-align:center; padding:15px; background:#f0fff4; color:#22543d; border-radius:8px; margin-bottom:20px; border:1px solid #c6f6d5;"><?= $siker ?></div>
            <?php endif; ?>
            <?php if (!empty($hibak)): ?>
                <div style="text-align:center; padding:15px; background:#fff5f5; color:#742a2a; border-radius:8px; margin-bottom:20px; border:1px solid #fed7d7;">
                    <?= implode('<br>', $hibak) ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if (empty($munkak)): ?>
            <div class="empty-state" style="text-align:center; padding:50px 20px;">
                <h3>Nincs még feltöltött munkád</h3>
                <a href="<?= base_url('workUpload') ?>" style="display:inline-block; margin-top:15px; padding:10px 20px; background:linear-gradient(135deg, #667eea 0%, #764ba2 100%); color:white; text-decoration:none; border-radius:8px;">Új munka feltöltése</a>
            </div>
        <?php else: ?>
            <div class="jobs-grid">
                <?php foreach ($munkak as $munka): ?>
                    <div class="job-card">
                        <div class="job-images">
                            <?php if (!empty($munka['kepek'])): ?>
                                <img src="<?= base_url('assets/images/referencia_kepek/' . $munka['kepek'][0]['kep_url']) ?>" class="job-image" loading="lazy">
                            <?php else: ?>
                                <div class="no-images"><span style="font-size:2rem;">📷</span><span>Nincs kép</span></div>
                            <?php endif; ?>
                        </div>

                        <div class="job-info">
                            <div class="job-header">
                                <h3 class="job-title"><?= htmlspecialchars($munka['munka_nev']) ?></h3>
                                <span class="job-price"><?= number_format($munka['ar'], 0, ',', ' ') ?> Ft</span>
                            </div>
                            <p class="job-description"><?= nl2br(htmlspecialchars(mb_strimwidth($munka['munka_leiras'], 0, 120, '...'))) ?></p>
                            <div class="job-details">
                                <span>📅 <?= date('Y.m.d.', strtotime($munka['datum_ido'])) ?></span>
                                <span style="margin-left:auto; font-weight:600; color:var(--accent-color);">👥 <?= $munka['jelentkezes_szam'] ?> jelentkező</span>
                            </div>
                        </div>

                        <div class="job-status">
                            <?php 
                                $s_key = $munka['statusz'];
                                $s_text = ['aktiv'=>'Aktív', 'folyamatban'=>'Folyamatban', 'befejezett'=>'Befejezett', 'archivalt'=>'Archivált'][$s_key] ?? $s_key;
                                if ($munka['jelentkezes_szam'] > 0 && !in_array($s_key, ['befejezett', 'archivalt'])) { $s_key='folyamatban'; $s_text='FOLYAMATBAN'; }
                            ?>
                            <span class="status-badge status-<?= $s_key ?>"><?= $s_text ?></span>
                            
                            <div class="job-actions">
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="munka_id" value="<?= $munka['id'] ?>">
                                    <button type="<?= ($munka['jelentkezes_szam'] > 0) ? 'button' : 'submit' ?>" name="edit_job" class="action-btn <?= ($munka['jelentkezes_szam'] > 0) ? 'disabled' : '' ?>">✏️</button>
                                </form>
                                <button type="button" class="action-btn" onclick="openStatusModal(<?= $munka['id'] ?>, '<?= $munka['statusz'] ?>', <?= $munka['jelentkezes_szam'] ?>)">🔄</button>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Törlöd?')">
                                    <input type="hidden" name="munka_id" value="<?= $munka['id'] ?>">
                                    <button type="submit" name="delete_job" class="action-btn btn-delete">🗑️</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="status-modal" id="statusModal">
        <div class="modal-content">
            <h3 style="text-align:center;">Állapot módosítása</h3>
            <p id="modal-subtitle" style="text-align:center; color:#718096; margin-bottom:20px;">Válassz új állapotot</p>
            
            <form method="POST">
                <input type="hidden" name="munka_id" id="modalJobId">
                <input type="hidden" name="update_status" value="1">
                <div id="statusOptionsContainer">
                    <div class="status-option" id="opt-aktiv" onclick="selectOpt('aktiv')">
                        <div><input type="radio" name="statusz" value="aktiv" id="rad-aktiv"> Aktív</div>
                        <span class="lock-icon" style="display:none">🔒</span>
                    </div>
                    <div class="status-option" id="opt-befejezett" onclick="selectOpt('befejezett')">
                        <div><input type="radio" name="statusz" value="befejezett" id="rad-befejezett"> Befejezett</div>
                    </div>
                    <div class="status-option" id="opt-archivalt" onclick="selectOpt('archivalt')">
                        <div><input type="radio" name="statusz" value="archivalt" id="rad-archivalt"> Archivált</div>
                        <span class="lock-icon" style="display:none">🔒</span>
                    </div>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-modal" style="background:#edf2f7;" onclick="document.getElementById('statusModal').style.display='none'">Mégse</button>
                    <button type="submit" class="btn-modal btn-update">Mentés</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openStatusModal(id, status, apps) {
            document.getElementById('modalJobId').value = id;
            document.getElementById('statusModal').style.display = 'flex';
            
            const optAktiv = document.getElementById('opt-aktiv');
            const optArch = document.getElementById('opt-archivalt');
            const radAktiv = document.getElementById('rad-aktiv');
            const sub = document.getElementById('modal-subtitle');

            [optAktiv, optArch].forEach(el => { 
                el.classList.remove('disabled'); 
                el.style.display = 'flex'; 
                el.querySelector('.lock-icon').style.display = 'none';
            });
            radAktiv.disabled = false;
            sub.innerText = "Válassz új állapotot";

            if (status === 'archivalt') {
                sub.innerHTML = "<span style='color:#d69e2e'>⚠️ Archivált nem lehet újra aktív.</span>";
                optAktiv.classList.add('disabled');
                optAktiv.querySelector('.lock-icon').style.display = 'block';
                radAktiv.disabled = true;
            } else {
                optArch.style.display = 'none';
            }

            if (apps > 0) {
                sub.innerHTML = "<span style='color:#e53e3e'>⚠️ Van jelentkező! Csak 'Befejezett' lehet.</span>";
                optAktiv.classList.add('disabled');
                radAktiv.disabled = true;
                optAktiv.querySelector('.lock-icon').style.display = 'block';
                
                if(status === 'archivalt') {
                    optArch.classList.add('disabled');
                    document.getElementById('rad-archivalt').disabled = true;
                }
            }
            
            if(status !== 'folyamatban' && status !== 'archivalt') {
                if(document.getElementById('rad-'+status)) document.getElementById('rad-'+status).checked = true;
                selectOpt(status);
            }
        }

        function selectOpt(val) {
            const el = document.getElementById('opt-'+val);
            if(el.classList.contains('disabled') || el.style.display === 'none') return;
            document.getElementById('rad-'+val).checked = true;
            document.querySelectorAll('.status-option').forEach(x => x.classList.remove('active'));
            el.classList.add('active');
        }
    </script>
</body>
</html>