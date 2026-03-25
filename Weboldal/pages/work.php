<?php
require_once("includes/jobs_functions.php");
$job_id = isset($_GET['job_id']) ? (int)$_GET['job_id'] : 0;
$work_data = getWorkData($job_id);
$account_data = getAccountData($work_data["felhasznalo_id"]);
$bejelentkezve = checkLoginStatus();
$mar_jelentkezett = checkApplication($job_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply_btn'])) {
    if (!isset($_SESSION['fid'])) {
        $_SESSION['error'] = "A jelentkezéshez be kell jelentkezni!";
    } else {
        $uid = $_SESSION['fid'];
        $jid = isset($_POST['munka_id']) ? (int)$_POST['munka_id'] : $job_id;
        applyForJob($jid, $uid);
        header("Location: work?job_id=" . $jid);
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
    <link rel="stylesheet" href="/assets/css/work.css">
</head>
<body>
<div class="work-container">
    <a href="jobs" class="vissza-link">
        <i class="fas fa-arrow-left"></i> Vissza a munkákhoz
    </a>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="uzenet uzenet-hiba">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="uzenet uzenet-siker">
            <i class="fas fa-check-circle"></i>
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($work_data)): ?>
            <div class="work-header">
                <h1 class="work-title"><?php echo htmlspecialchars($work_data['munka_nev']); ?></h1>
                
                <div class="statusz-container">
                    <?php 
                        $statusz_szoveg = 'Ismeretlen';
                        $statusz_class = 'status-default';
                        
                        switch($work_data['statusz']) {
                            case 'aktiv':
                                $statusz_szoveg = 'Aktív';
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
                                $statusz_szoveg = 'Archivált';
                                $statusz_class = 'status-archived';
                                break;
                        }
                    ?>
                    <span class="status-badge <?php echo $statusz_class; ?>">
                        <i class="fas fa-circle"></i> <?php echo $statusz_szoveg; ?>
                    </span>
                </div>
            </div>

            <div class="info-card">
                <div class="info-grid">
                    <?php if (!empty($work_data['kategoria'])): ?>
                        <div class="info-item">
                            <i class="fas fa-tag"></i>
                            <div class="info-content">
                                <span class="info-label">Kategória</span>
                                <span class="info-value"><?php echo htmlspecialchars($work_data['kategoria']); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($work_data['tapasztalati_szint'])): ?>
                        <div class="info-item">
                            <i class="fas fa-chart-line"></i>
                            <div class="info-content">
                                <span class="info-label">Tapasztalati szint</span>
                                <span class="info-value"><?php echo htmlspecialchars($work_data['tapasztalati_szint']); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($work_data['munkavegzes_helye'])): ?>
                        <div class="info-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="info-content">
                                <span class="info-label">Munkavégzés helye</span>
                                <span class="info-value"><?php echo htmlspecialchars($work_data['munkavegzes_helye']); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($work_data['datum_ido'])): ?>
                        <div class="info-item">
                            <i class="fas fa-calendar-alt"></i>
                            <div class="info-content">
                                <span class="info-label">Időpont</span>
                                <span class="info-value"><?php echo date('Y.m.d. H:i', strtotime($work_data['datum_ido'])); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="price-apply-card">
                <div class="price-section">
                    <span class="price-label">Várható díjazás</span>
                    <span class="price-value"><?php echo number_format($work_data['ar'], 0, ',', ' '); ?> Ft</span>
                </div>
                
                <div class="apply-section">
                    <?php if (!$bejelentkezve): ?>
                        <a href="login" class="btn btn-primary btn-large">
                            <i class="fas fa-sign-in-alt"></i> Jelentkezéshez lépj be!
                        </a>

                    <?php elseif ($account_data['fid'] == $_SESSION["fid"]): ?>
                        <div class="info-message">
                            <i class="fas fa-user"></i>
                            <span>Ez a saját munkád</span>
                        </div>
                        <a href="<?= base_url('editJob?id=' . $job_id) ?>" class="btn btn-secondary">
                            <i class="fas fa-edit"></i> Munka szerkesztése
                        </a>

                    <?php elseif ($mar_jelentkezett): ?>
                        <div class="applied-message">
                            <i class="fas fa-check-circle"></i>
                            <span>Már jelentkeztél erre a munkára</span>
                        </div>
                        <a href="Jelentkezeseim" class="btn btn-secondary">
                            <i class="fas fa-list"></i> Jelentkezéseim
                        </a>
                    
                    <?php elseif ($work_data['statusz'] !== 'aktiv'): ?>
                        <div class="info-message warning">
                            <i class="fas fa-lock"></i>
                            <span>A munka már nem fogad jelentkezést</span>
                        </div>

                    <?php else: ?>
                        <form method="POST" class="apply-form">
                            <input type="hidden" name="munka_id" value="<?php echo $job_id; ?>">
                            <button type="submit" name="apply_btn" class="btn btn-apply btn-large">
                                <i class="fas fa-paper-plane"></i> Jelentkezem a munkára
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <div class="description-card">
                <h2 class="card-title">
                    <i class="fas fa-file-alt"></i>
                    Részletes leírás
                </h2>
                <div class="description-content">
                    <?php echo nl2br(htmlspecialchars($work_data['munka_leiras'])); ?>
                </div>
            </div>
            <?php
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
            ?>
            <?php if (!empty($referencia_kepek)): ?>
                <div class="reference-section">
                    <h2 class="card-title">
                        <i class="fas fa-images"></i>
                        Referencia képek
                    </h2>
                    <div class="reference-grid">
                        <?php foreach ($referencia_kepek as $index => $kep): ?>
                            <div class="reference-item">
                                <img src="<?php echo htmlspecialchars($kep['kep_url']); ?>" 
                                    alt="Referencia kép <?php echo $index + 1; ?>"
                                    onclick="openImageModal(this.src)">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="employer-card">
                <h2 class="card-title">
                    <i class="fas fa-user-tie"></i>
                    Munkaadó
                </h2>
                
                <div class="employer-info">
                    <a href="<?= base_url('rateProfile?fid=' . $account_data['fid']) ?>" class="employer-profile">
                        <img src="<?php echo htmlspecialchars($profil_kep); ?>" 
                            alt="Profilkép"
                            class="employer-avatar"
                            onerror="this.src='assets/images/profile/default.png'">
                        <div class="employer-details">
                            <span class="employer-name"><?php echo htmlspecialchars($account_data['fnev']); ?></span>
                            <?php if (!empty($account_data['varmegye'])): ?>
                                <span class="employer-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo htmlspecialchars($account_data['varmegye']); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </a>
                    
                    <a href="<?= base_url('rateProfile?fid=' . $account_data['fid']) ?>" class="btn btn-outline">
                        <i class="fas fa-user"></i> Profil megtekintése
                    </a>
                </div>

                <?php if ($bejelentkezve): ?>
                    <div class="contact-info">
                        <h3 class="contact-title">Elérhetőségek</h3>
                        <div class="contact-grid">
                            <?php if (!empty($work_data['telefonszam'])): ?>
                                <div class="contact-item">
                                    <i class="fas fa-phone"></i>
                                    <span><?php echo htmlspecialchars($work_data['telefonszam']); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($work_data['email'])): ?>
                                <div class="contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <span><?php echo htmlspecialchars($work_data['email']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

    <?php else: ?>
        <div class="nincs-munka">
            <i class="fas fa-exclamation-circle"></i>
            <p>Nincs megjeleníthető munka.</p>
        </div>
    <?php endif; ?>
</div>

<div id="imageModal" class="modal" onclick="closeImageModal()">
    <span class="modal-close">&times;</span>
    <img class="modal-content" id="modalImage">
</div>


<script>
function openImageModal(src) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    modal.style.display = 'flex';
    modalImg.src = src;
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('imageModal');
        if (modal.style.display === 'flex') {
            modal.style.display = 'none';
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const applyForm = document.querySelector('.apply-form');
    if (applyForm) {
        applyForm.addEventListener('submit', function(e) {
            if (!confirm('Biztosan jelentkezni szeretnél erre a munkára?')) {
                e.preventDefault();
            }
        });
    }
    
    const uzenetek = document.querySelectorAll('.uzenet');
    uzenetek.forEach(uzenet => {
        setTimeout(() => {
            uzenet.style.opacity = '0';
            uzenet.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                uzenet.style.display = 'none';
            }, 300);
        }, 5000);
    });
});
</script>
</body>
</html>