<?php
require_once("includes/config.php");
require_once("includes/functions.php");
require_once("includes/jobs_functions.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['fid'])) {
    header("Location: " . base_url('login'));
    exit;
}

$db = new Database();
$conn = $db->get();

$hibak = [];
$siker = '';


if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: " . base_url('myJobs'));
    exit;
}

$munka_id = intval($_GET['id']);



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_job'])) {
    
    $munka_nev = trim($_POST['munka_nev']);
    $munka_leiras = trim($_POST['munka_leiras']);
    $telefonszam = trim($_POST['telefonszam']);
    $email = trim($_POST['email']);
    $munka_datum = $_POST['munka_datum'];
    $munka_ido = $_POST['munka_ido'];
    
    $ar_raw = $_POST['ar'];
    $ar = intval($ar_raw); 

    if (empty($munka_nev)) $hibak[] = "A munka megnevezése kötelező!";
    if (empty($munka_leiras)) $hibak[] = "A leírás kötelező!";
    if (strlen($munka_leiras) < 20) $hibak[] = "A leírás minimum 20 karakter legyen!";
    if ($ar <= 0) $hibak[] = "Az árnak nagyobbnak kell lennie 0-nál!";
    
    if ((string)$ar !== (string)$ar_raw && floor((float)$ar_raw) != (float)$ar_raw) {
         $hibak[] = "Az árat csak kerek egész számként lehet megadni!";
    }

    if (empty($hibak)) {
        try {
            $conn->beginTransaction();

            $datum_ido = $munka_datum . ' ' . $munka_ido . ':00';

            $update_sql = "UPDATE munkak SET 
                munka_nev = ?, 
                munka_leiras = ?, 
                ar = ?, 
                telefonszam = ?, 
                email = ?,
                datum_ido = ?
                WHERE id = ? AND felhasznalo_id = ?";
            
            $stmt = $conn->prepare($update_sql);
            $stmt->execute([
                $munka_nev, $munka_leiras, $ar, $telefonszam, $email, $datum_ido,
                $munka_id, $_SESSION['fid']
            ]);



            if (isset($_POST['delete_image_ids']) && !empty($_POST['delete_image_ids'])) {
                $ids_to_delete = explode(',', $_POST['delete_image_ids']);
                
                foreach ($ids_to_delete as $kep_id) {
                    $kep_id = intval($kep_id);

                    $stmt_img = $conn->prepare("SELECT kep_url FROM referencia_kepek WHERE id = ? AND munka_id = ?");
                    $stmt_img->execute([$kep_id, $munka_id]);
                    $img_data = $stmt_img->fetch();
                    
                    if ($img_data) {
                        $file_path = 'assets/images/referencia_kepek/' . $img_data['kep_url'];
                        if (file_exists($file_path)) {
                            unlink($file_path);
                        }
                        $del_stmt = $conn->prepare("DELETE FROM referencia_kepek WHERE id = ?");
                        $del_stmt->execute([$kep_id]);
                    }
                }
            }

            if (isset($_FILES['referencia_kepek']) && !empty($_FILES['referencia_kepek']['name'][0])) {
                $total_files = count($_FILES['referencia_kepek']['name']);
                $upload_dir = 'assets/images/referencia_kepek/'; 
                
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

                $stmt_count = $conn->prepare("SELECT MAX(sorrend) as max_sorrend FROM referencia_kepek WHERE munka_id = ?");
                $stmt_count->execute([$munka_id]);
                $row = $stmt_count->fetch();
                $start_order = ($row['max_sorrend'] !== null) ? $row['max_sorrend'] + 1 : 0;

                for ($i = 0; $i < $total_files; $i++) {
                    if ($_FILES['referencia_kepek']['error'][$i] === UPLOAD_ERR_OK) {
                        $tmp_name = $_FILES['referencia_kepek']['tmp_name'][$i];
                        $file_name = $_FILES['referencia_kepek']['name'][$i];
                        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                        
                        if (in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                            $biztonsagos_fnev = preg_replace('/[^a-zA-Z0-9]/', '_', $_SESSION['fnev'] ?? 'user');
                            $new_name = $biztonsagos_fnev . '.' . date('Y-m-d_H-i-s') . '_' . uniqid() . '.' . $file_ext;
                            $target_path = $upload_dir . $new_name;

                            if (move_uploaded_file($tmp_name, $target_path)) {
                                $ins_img = $conn->prepare("INSERT INTO referencia_kepek (munka_id, kep_url, sorrend) VALUES (?, ?, ?)");
                                $ins_img->execute([$munka_id, $new_name, $start_order + $i]);
                            }
                        }
                    }
                }
            }

            $conn->commit();
            $siker = "✅ A munka adatai sikeresen frissítve!";
            
        } catch (PDOException $e) {
            $conn->rollBack();
            $hibak[] = "Adatbázis hiba: " . $e->getMessage();
        }
    }
}


$work_data = getWorkData($munka_id);
$reference_urls = getReferenceImageUrls($munka_id);

if (!$work_data || $work_data['felhasznalo_id'] != $_SESSION["fid"]) {
    echo "<div style='padding:20px; text-align:center;'>Hiba: A munka nem található, vagy nincs jogosultságod szerkeszteni. <br> <a href='myJobs'>Vissza</a></div>";
    exit;
}


$db_datum = date('Y-m-d', strtotime($work_data['datum_ido']));
$db_ido = date('H:i', strtotime($work_data['datum_ido']));



$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
?>

<!DOCTYPE html>
<html lang="hu" data-theme="<?= $theme ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Szerkesztés - <?= htmlspecialchars($munka['munka_nev']) ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <style>

        :root {
            --primary-bg: #f8f9fa;
            --card-bg: #ffffff;
            --primary-text: #2d3748;
            --secondary-text: #718096;
            --border-color: #e2e8f0;
            --input-bg: #ffffff;
            --accent-color: #3182ce;
            --accent-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --shadow-color: rgba(0,0,0,0.05);
            --secondary-bg: #f7fafc;
            --tertiary-bg: #edf2f7;
            --button-text: #ffffff;
            --secondary-button-bg: #e2e8f0;
            --secondary-button-hover-bg: #cbd5e0;
            --danger-color: #e53e3e;
        }

        [data-theme="dark"] {
            --primary-bg: #1a202c;
            --card-bg: #2d3748;
            --primary-text: #f7fafc;
            --secondary-text: #a0aec0;
            --border-color: #4a5568;
            --input-bg: #1a202c;
            --secondary-bg: #2d3748;
            --tertiary-bg: #4a5568;
            --shadow-color: rgba(0,0,0,0.2);
            --secondary-button-bg: #4a5568;
            --secondary-button-hover-bg: #718096;
            --danger-color: #fc8181;
        }

        body {
            background-color: var(--primary-bg);
            color: var(--primary-text);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            transition: background-color 0.3s ease;
        }

        .edit-page {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
            animation: fadeInUp 0.5s ease-out;
        }

        .edit-container {
            background: var(--card-bg);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 40px var(--shadow-color);
            border: 1px solid var(--border-color);
            margin-top: 80px; 
        }

        .edit-title {
            text-align: center;
            font-size: 2.2rem;
            margin-bottom: 30px;
            color: var(--primary-text);
            position: relative;
            padding-bottom: 15px;
        }

        .edit-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--accent-gradient);
            border-radius: 2px;
        }

        /* 2 Oszlopos Grid */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .form-column {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--primary-text);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .form-group label::before {
            content: '•';
            color: var(--accent-color);
            font-size: 18px;
        }

        .form-control {
            padding: 14px 16px;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: var(--input-bg);
            color: var(--primary-text);
            width: 100%;
            box-sizing: border-box;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-section {
            background: var(--tertiary-bg);
            padding: 25px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            margin-top: 10px;
        }

        .section-title {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: var(--primary-text);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-title::before {
            content: '';
            display: block;
            width: 4px;
            height: 20px;
            background: var(--accent-gradient);
            border-radius: 2px;
        }
        
        .section-desc {
            font-size: 0.9rem;
            color: var(--secondary-text);
            margin-bottom: 20px;
        }

        /* Képkezelés */
        .upload-box {
            border: 3px dashed var(--border-color);
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: var(--primary-bg);
            position: relative;
        }
        .upload-box:hover {
            border-color: var(--accent-color);
            background: rgba(102, 126, 234, 0.05);
        }
        
        .image-preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .preview-item {
            position: relative;
            height: 100px;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid var(--border-color);
        }
        
        .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .delete-btn {
            position: absolute;
            top: 5px; right: 5px;
            background: rgba(229, 62, 62, 0.9);
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px; height: 24px;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
        }
        .delete-btn:hover { background: #e53e3e; transform: scale(1.1); }
        
        .preview-item.marked-delete {
            opacity: 0.4;
            border-color: var(--danger-color);
        }
        .preview-item.marked-delete::after {
            content: 'TÖRLÉS';
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            background: var(--danger-color);
            color: white;
            padding: 2px 5px;
            font-size: 10px;
            border-radius: 3px;
        }

        /* Gombok */
        .form-actions {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid var(--border-color);
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex; align-items: center; justify-content: center;
        }
        
        .btn-primary {
            background: var(--accent-gradient);
            color: #fff;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 20px var(--shadow-color); }
        
        .btn-secondary {
            background: var(--secondary-button-bg);
            color: var(--primary-text);
        }
        .btn-secondary:hover { background: var(--secondary-button-hover-bg); transform: translateY(-2px); }


        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-weight: 600;
            text-align: center;
        }
        .alert-success { background-color: rgba(72, 187, 120, 0.2); color: #2f855a; border: 1px solid #48bb78; }
        .alert-danger { background-color: rgba(245, 101, 101, 0.2); color: #c53030; border: 1px solid #f56565; }

        [data-theme="dark"] .alert-success { color: #68d391; }
        [data-theme="dark"] .alert-danger { color: #fc8181; }

        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
            .edit-container { padding: 25px; }
            .form-actions { flex-direction: column; }
            .btn { width: 100%; }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="edit-page">
    <div class="edit-container" id="top-anchor">
        <a href="myJobs" style="float: right; text-decoration: none; color: var(--secondary-text); font-size: 1.8rem;">&times;</a>
        
        <h1 class="edit-title">Munka Szerkesztése</h1>

        <?php if (!empty($siker)): ?>
            <div class="alert alert-success" id="msg-box">
                <?= htmlspecialchars($siker) ?>
            </div>
            <script>document.getElementById('msg-box').scrollIntoView({ behavior: 'smooth', block: 'center' });</script>
        <?php endif; ?>

        <?php if (!empty($hibak)): ?>
            <div class="alert alert-danger" id="msg-box">
                <?php foreach($hibak as $hiba): ?>
                    <div>⚠️ <?= htmlspecialchars($hiba) ?></div>
                <?php endforeach; ?>
            </div>
            <script>document.getElementById('msg-box').scrollIntoView({ behavior: 'smooth', block: 'center' });</script>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="delete_image_ids" id="delete_image_ids" value="">

            <div class="form-grid">
                <div class="form-column">
                    <div class="form-group">
                        <label for="munka_nev">Megnevezés</label>
                        <input type="text" id="munka_nev" name="munka_nev" class="form-control" 
                               value="<?= htmlspecialchars($work_data['munka_nev']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="ar">Ár (Ft)</label>
                        <input type="number" id="ar" name="ar" class="form-control" 
                               value="<?= intval($work_data['ar']) ?>" 
                               required min="1" step="1">
                    </div>

                    <div class="form-group">
                        <label for="munka_datum">Dátum</label>
                        <input type="date" id="munka_datum" name="munka_datum" class="form-control" 
                               value="<?= $db_datum ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="munka_ido">Időpont</label>
                        <input type="time" id="munka_ido" name="munka_ido" class="form-control" 
                               value="<?= $db_ido ?>" required>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">Elérhetőségek</h3>
                        <div class="form-group" style="margin-bottom:15px;">
                            <label for="telefonszam">Telefonszám</label>
                            <input type="tel" id="telefonszam" name="telefonszam" class="form-control" 
                                   value="<?= htmlspecialchars($work_data['telefonszam']) ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email cím</label>
                            <input type="email" id="email" name="email" class="form-control" 
                                   value="<?= htmlspecialchars($work_data['email']) ?>">
                        </div>
                    </div>
                </div>

                <div class="form-column">
                    <div class="form-group">
                        <label for="munka_leiras">Részletes leírás</label>
                        <textarea id="munka_leiras" name="munka_leiras" class="form-control" rows="10" required><?= htmlspecialchars($work_data['munka_leiras']) ?></textarea>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">Képek kezelése</h3>
                        <p class="section-desc">Kezeld a meglévő képeket vagy tölts fel újakat.</p>
                        
                        <?php if(count($reference_urls) > 0): ?>
                            <label style="font-size:0.9rem; font-weight:600;">Meglévő képek (Kattints a kukára a törléshez):</label>
                            <div class="image-preview-grid">
                                <?php foreach($reference_urls as $url): ?>
                                    <div class="preview-item" id="img-card-<?= $kep['id'] ?>">
                                        <img src="<?=$url ?>" alt="Kép">
                                        <button type="button" class="delete-btn" onclick="toggleDelete(<?= $kep['id'] ?>)" title="Törlésre jelölés">🗑️</button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p style="font-style:italic; color:var(--secondary-text);">Nincsenek feltöltött képek.</p>
                        <?php endif; ?>
                        
                        <div style="margin: 20px 0; border-top:1px solid var(--border-color);"></div>

                        <div class="upload-box" id="uploadBox">
                            <div style="font-size:2rem; margin-bottom:10px;">☁️</div>
                            <strong>Húzd ide a képeket vagy kattints</strong>
                            <div style="font-size:0.8rem; color:var(--secondary-text); margin-top:5px;">JPG, PNG, WEBP (Max 5db)</div>
                            <input type="file" id="imageInput" name="referencia_kepek[]" multiple accept="image/*" 
                                   style="position:absolute; top:0; left:0; width:100%; height:100%; opacity:0; cursor:pointer;">
                        </div>
                        
                        <div class="image-preview-grid" id="newImagePreview"></div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" name="save_job" class="btn btn-primary">Mentés</button>
                <a href="myJobs" class="btn btn-secondary">Mégse</a>
            </div>
        </form>
    </div>
</div>

<script>
    let deletedIds = [];
    const deleteInput = document.getElementById('delete_image_ids');

    function toggleDelete(id) {
        const card = document.getElementById('img-card-' + id);
        
        if (deletedIds.includes(id)) {
            deletedIds = deletedIds.filter(item => item !== id);
            card.classList.remove('marked-delete');
        } else {
            deletedIds.push(id);
            card.classList.add('marked-delete');
        }
        deleteInput.value = deletedIds.join(',');
    }

    const uploadBox = document.getElementById('uploadBox');
    const imageInput = document.getElementById('imageInput');
    const newPreview = document.getElementById('newImagePreview');

    uploadBox.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadBox.style.borderColor = 'var(--accent-color)';
        uploadBox.style.backgroundColor = 'rgba(102, 126, 234, 0.1)';
    });
    uploadBox.addEventListener('dragleave', (e) => {
        e.preventDefault();
        uploadBox.style.borderColor = 'var(--border-color)';
        uploadBox.style.backgroundColor = 'var(--primary-bg)';
    });
    uploadBox.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadBox.style.borderColor = 'var(--border-color)';
        uploadBox.style.backgroundColor = 'var(--primary-bg)';

    });

    imageInput.addEventListener('change', function() {
        newPreview.innerHTML = '';
        Array.from(this.files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'preview-item';
                    div.innerHTML = `<img src="${e.target.result}">`;
                    newPreview.appendChild(div);
                }
                reader.readAsDataURL(file);
            }
        });
    });


    function getCookie(name) {
        const v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
        return v ? v[2] : null;
    }
    const theme = getCookie('theme');
    if (theme) document.documentElement.setAttribute('data-theme', theme);
</script>

</body>
</html>