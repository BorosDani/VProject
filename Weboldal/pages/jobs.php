<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db_connected = false;
if (isset($conn)) {
    $db_connected = true;
} else {
    $paths = ['includes/functions.php', 'includes/config.php', '../includes/functions.php', 'config.php'];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once($path);
            if (isset($conn)) {
                $db_connected = true;
                break;
            }
        }
    }
}

if (!$db_connected) {
    $host = 'localhost';
    $db_name = 'villamme_villammelo';
    $username = 'root';
    $password = '';
    try {
        $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db_connected = true;
    } catch(PDOException $e) {
    }
}


if ($db_connected) {
    try {
        $sql_expired = "
            SELECT m.id, m.munka_nev, m.felhasznalo_id, f.email, f.vezeteknev 
            FROM munkak m 
            LEFT JOIN felhasznalok f ON m.felhasznalo_id = f.id 
            WHERE m.statusz = 'Aktiv' AND m.datum_ido < NOW()
        ";
        
        $stmt_check = $conn->prepare($sql_expired);
        $stmt_check->execute();
        $lejart_munkak = $stmt_check->fetchAll(PDO::FETCH_ASSOC);

        if (count($lejart_munkak) > 0) {
            $update_stmt = $conn->prepare("UPDATE munkak SET statusz = 'Archivalt' WHERE id = ?");

            foreach ($lejart_munkak as $munka) {
                if (!empty($munka['email'])) {
                    $to = $munka['email'];
                    $subject = "Értesítés: A hirdetése lejárt (" . $munka['munka_nev'] . ")";
                    $nev = !empty($munka['vezeteknev']) ? $munka['vezeteknev'] : 'Felhasználó';
                    
                    $message = "Kedves " . $nev . "!\n\n";
                    $message .= "Ezúton tájékoztatjuk, hogy az alábbi hirdetése elérte a határidejét:\n";
                    $message .= "Hirdetés címe: " . $munka['munka_nev'] . "\n\n";
                    $message .= "A hirdetés státuszát automatikusan 'Archivált'-ra állítottuk, így az már nem jelenik meg az aktív keresések között.\n\n";
                    $message .= "Üdvözlettel,\nA Villámmeló Csapat";

                    $headers = "From: no-reply@villammelo.hu\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

                    @mail($to, $subject, $message, $headers);
                }
                $update_stmt->execute([$munka['id']]);
            }
        }
    } catch (PDOException $e) {
        error_log("Archiválási hiba: " . $e->getMessage());
    }
}

$kilteri_definicio = [
    'Kertrendezés', 'Fűnyírás', 'Ültetés', 'Festés (kültér)', 'Burkolás', 'Külső vízvezeték javítás',
    'Kerítés javítás', 'Tetőfedés', 'Csatornázás', 'Kerti medence karbantartás', 'Locsolórendszer telepítés',
    'Kerti világítás', 'Padlóburkolás (kültér)', 'Faanyag kezelés', 'Zöldhulladék elszállítás', 'Hóolvadás',
    'Járdasó hintés', 'Kerti pad festés', 'Gyepszegély nyírás', 'Kerti tó kialakítás', 'Virágágyás rendezés',
    'Sövénynyírás', 'Öntözőrendszer javítás', 'Kőműves munka (kültér)', 'Vakolás (kültér)', 'Festőmunka (kültér)',
    'Garázskapu javítás', 'Bejárati ajtó csere', 'Bejárati ajtó festés', 'Erkély üvegezés', 'Tetőcsatorna tisztítás',
    'Tetőfedő csere', 'Hóvédő szerelés', 'Kerti locsoló szivattyú javítás', 'Kutyaház építés', 'Macskaajtó beépítés',
    'Légicsúsztatós ajtó szerelés', 'Csúszdás hinta szerelés', 'Homokozó építés', 'Trambulin szerelés', 'Tábori tűzhely kialakítás',
    'Kerti hinta szerelés', 'Kerti tűzrakóhely építés', 'Szalmatető készítés', 'Nádas kerítés építés', 'Fa kerítés festése',
    'Sövénynyírás géppel', 'Kerti tó szűrőrendszer telepítése', 'Láncfűrész lánc cseréje', 'Kerti permetező javítása',
    'Kültéri szennyvízcsatorna tisztítása', 'Madáretető készítés', 'Kerti világítás telepítés', 'Okos locsolórendszer telepítés',
    'Egyéb (kültéri)'
];

$belteri_definicio = [
    'Padlócsiszolás', 'Villanyszerelés','Vízszerelés', 'Festés (beltér)', 'Bútor szerelés', 'Csaptelep csere', 'Ablaktisztítás', 'Padlóviaszozás',
    'Redőny szerelés', 'Klima telepítés', 'Kályha tisztítás', 'Mélygarázs építés', 'Terasz burkolás', 'Padlófűtés telepítés',
    'Mennyezeti fűtés telepítés', 'Okosotthon rendszer telepítés', 'Tapéta felragasztás', 'Járólap csere', 'Ablak csere',
    'Szellőztető rendszer telepítés', 'Pince átalakítás', 'Tetőtér beépítés', 'Konyhabútor szerelés', 'Fürdőszoba felújítás',
    'Tűzhely csere', 'Hűtőszekrény javítás', 'Mosógép javítás', 'Légkondicionáló tisztítás', 'Kéményseprés', 'Porszívó javítás',
    'Számítógép összeszerelés', 'Hálózati kábel húzás', 'Biztonsági rendszer telepítés', 'Kaputelefon javítás', 'Táblakeret készítés',
    'Redőny javítás', 'Tárolórendszer szerelés', 'Parketta ragasztás', 'Csempe javítás', 'Vízóra csere', 'Gázóra csere',
    'Szivargyújtó javítás', 'Páraelszívó szerelés', 'Konyhai elszívó javítás', 'Vízhűtő szerelés', 'Forgó ajtó beépítés',
    'Fürdőszobai szárító szerelése', 'Kád és wc csempe burkolás', 'Konyhapult kialakítás', 'Konyhai mosogató csere',
    'Bútorlábak cseréje', 'Redőny zsanérjának cseréje', 'Lámpa okossá tétele', 'Sötétedésre kapcsoló beállítása',
    'Megfigyelő kamera telepítése', 'Padlófűtés termosztát cseréje', 'Beltéri szennyvízcső javítása', 'Vízvezeték szigetelése',
    'Gázcső szigetelése', 'Hőszigetelés kialakítása', 'Hangszigetelés telepítése', 'Riasztó berendezés telepítése',
    'Gardrób beépítés', 'Fali polc szerelés', 'TV konzol szerelés', 'Hálószobai kandalló telepítés', 'Mélyhűtő javítás',
    'Sütőjavítás', 'Mikrohullámú sütő javítás', 'Kávéfőző javítás', 'Szobabicikli szerelése', 'Faltámasz szerelése',
    'Gardróbszekrény beépítés', 'Könyvespolc kialakítás', 'Egyéb (beltéri)'
];

function renderJobCards($conn, $search = '', $type = '', $category = '') {
    global $kilteri_definicio, $belteri_definicio;
    
    if (!$conn) return '<div class="error">Nincs adatbázis kapcsolat!</div>';

    $params = [];

    $sql = "SELECT m.*, 
            (SELECT GROUP_CONCAT(kep_url ORDER BY sorrend ASC SEPARATOR '|') 
             FROM referencia_kepek rk WHERE rk.munka_id = m.id) as kepek 
            FROM munkak m 
            WHERE m.statusz = 'Aktiv' 
            AND m.datum_ido > NOW()
            AND m.aktiv = 1
            AND (m.kiemelt = 0 OR (m.kiemelt = 1 AND m.fizetve = 1))";

    if (!empty($search)) {
        $sql .= " AND m.munka_nev LIKE ?";
        $params[] = "%$search%";
    }

    if (!empty($type)) {
        if ($type === 'outdoor') {
            $placeholders = implode(',', array_fill(0, count($kilteri_definicio), '?'));
            $sql .= " AND m.munka_nev IN ($placeholders)";
            $params = array_merge($params, $kilteri_definicio);
        } elseif ($type === 'indoor') {
            $placeholders = implode(',', array_fill(0, count($belteri_definicio), '?'));
            $sql .= " AND m.munka_nev IN ($placeholders)";
            $params = array_merge($params, $belteri_definicio);
        }
    }

    if (!empty($category)) {
        $sql .= " AND m.munka_nev = ?";
        $params[] = $category;
    }

    $sql .= " ORDER BY m.kiemelt DESC, m.datum_ido ASC";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $munkak = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($munkak) > 0) {
            $output = '';
            foreach ($munkak as $munka) {
                $kepek = !empty($munka['kepek']) ? explode('|', $munka['kepek']) : ['default_job.png'];
                $kep_path = 'assets/images/referencia_kepek/';
                
                $is_premium = ($munka['kiemelt'] == 1);
                $card_class = $is_premium ? 'job-listing-card premium-card' : 'job-listing-card';
                
                $output .= '<div class="' . $card_class . '" onclick="window.location.href=\'work?job_id=' . $munka['id'] . '\'" style="cursor: pointer; position: relative;">';
                
                if ($is_premium) {
                    $output .= '<div class="premium-badge" style="position: absolute; top: 10px; left: 10px; background: linear-gradient(135deg, #f59e0b, #d97706); color: white; padding: 5px 12px; border-radius: 20px; font-weight: bold; font-size: 0.8rem; z-index: 10; box-shadow: 0 4px 6px rgba(0,0,0,0.2);">★ Kiemelt</div>';
                }

                $output .= '<div class="job-card-slider">';
                if (count($kepek) > 1) {
                    $output .= '<button class="slider-btn prev" type="button" onclick="event.stopPropagation(); moveSlide(this, -1)">&#10094;</button>';
                    $output .= '<button class="slider-btn next" type="button" onclick="event.stopPropagation(); moveSlide(this, 1)">&#10095;</button>';
                }
                $output .= '<div class="slider-track" data-current="0">';
                
                foreach ($kepek as $kep) {
                    if ($kep == 'default_job.png' || empty($kep)) {
                        $output .= '<div class="slider-img no-image-placeholder">';
                        $output .= '<i class="fa fa-camera-retro"></i>'; 
                        $output .= '<span>Nincs feltöltött kép</span>';
                        $output .= '</div>';
                    } else {
                        $src = (strpos($kep, 'http') === 0) ? $kep : $kep_path . $kep;
                        $output .= '<img src="' . htmlspecialchars($src) . '" alt="Munka kép" class="slider-img">';
                    }
                }
                $output .= '</div>';
                $output .= '</div>';

                $output .= '<div class="job-card-content">';
                $output .= '<div class="job-header">';
                $output .= '<span class="job-category">' . htmlspecialchars($munka['munka_nev']) . '</span>';
                $output .= '<span class="job-price">' . number_format($munka['ar'], 0, ',', ' ') . ' Ft</span>';
                $output .= '</div>';
                
                $leiras_rovid = mb_substr($munka['munka_leiras'], 0, 30, 'UTF-8');
                if (mb_strlen($munka['munka_leiras'], 'UTF-8') > 30) $leiras_rovid .= '...';
                
                $output .= '<h3 class="job-title">' . htmlspecialchars($leiras_rovid) . '</h3>';
                
                $output .= '<div class="job-details">';
                $output .= '<p class="job-desc">' . nl2br(htmlspecialchars($munka['munka_leiras'])) . '</p>';
                $output .= '</div>';

                $output .= '<div class="job-footer">';
                $output .= '<div class="job-deadline"><i class="fa fa-clock-o"></i> Határidő: ' . date('Y.m.d H:i', strtotime($munka['datum_ido'])) . '</div>';
                $output .= '<button type="button" onclick="event.stopPropagation(); window.location.href=\'work?job_id=' . $munka['id'] . '\'" class="btn-details" style="border:none; cursor:pointer;">Részletek</button>';
                $output .= '</div>';
                $output .= '</div>';
                $output .= '</div>';
            }
            return $output;
        } else {
            return '<div class="no-jobs-found">Nincs megjeleníthető aktív munka a megadott feltételekkel.</div>';
        }
    } catch (PDOException $e) {
        return '<div class="error">Adatbázis hiba: ' . $e->getMessage() . '</div>';
    }
}

if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    while (ob_get_level()) ob_end_clean(); 
    header('Content-Type: text/html; charset=utf-8');
    
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $type = isset($_GET['type']) ? $_GET['type'] : '';
    $cat = isset($_GET['category']) ? $_GET['category'] : '';
    
    echo renderJobCards($conn, $search, $type, $cat);
    exit; 
}

$elerheto_kategoriak_db = [];
if ($db_connected) {
    try {
        $stmt_cat = $conn->query("SELECT DISTINCT munka_nev FROM munkak WHERE statusz = 'Aktiv' AND datum_ido > NOW() AND aktiv = 1 AND (kiemelt = 0 OR (kiemelt = 1 AND fizetve = 1)) ORDER BY munka_nev ASC");
        $elerheto_kategoriak_db = $stmt_cat->fetchAll(PDO::FETCH_COLUMN);
    } catch (Exception $e) {}
}

$kilteri_szurt = array_values(array_intersect($kilteri_definicio, $elerheto_kategoriak_db));
$belteri_szurt = array_values(array_intersect($belteri_definicio, $elerheto_kategoriak_db));
$osszes_elerheto = $elerheto_kategoriak_db;
sort($osszes_elerheto);

$initial_jobs_html = renderJobCards($conn);
?>
    <style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    :root { --placeholder-bg: #fff8e1; --placeholder-text: #b38b00; --placeholder-icon: #ffbe33; }
    body.dark, body.dark-mode, [data-theme="dark"] { --placeholder-bg: #2d2d2d; --placeholder-text: #e0e0e0; --placeholder-icon: #ffbe33; }
    .jobs-page-container { max-width: 1400px; margin: 30px auto; padding: 0 20px; color: var(--primary-text, #333); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; animation: fadeInUp 0.8s ease-out; }
    .jobs-filter-bar { background: var(--card-bg, #ffffff); padding: 25px; border-radius: 15px; box-shadow: 0 5px 20px var(--shadow-color, rgba(0,0,0,0.08)); border: 1px solid var(--border-color, #eaeaea); margin-bottom: 30px; transition: background 0.3s; animation: fadeInUp 0.7s ease-out 0.1s both; }
    .jobs-filter-bar h2 { color: var(--primary-text, #333); margin-top: 0; margin-bottom: 20px; font-size: 1.5rem; border-left: 5px solid var(--accent-color, #667eea); padding-left: 15px; }
    .filter-row { display: flex; gap: 20px; flex-wrap: wrap; }
    .filter-group { flex: 1; min-width: 200px; display: flex; flex-direction: column; }
    .search-group { margin-bottom: 15px; }
    .filter-group label { font-weight: 600; margin-bottom: 8px; color: var(--secondary-text, #555); font-size: 0.9rem; }
    .filter-group input, .filter-group select { padding: 12px 15px; border: 2px solid var(--border-color, #ddd); border-radius: 8px; background: var(--primary-bg, #f9f9f9); color: var(--primary-text, #333); font-size: 1rem; transition: all 0.3s ease; width: 100%; box-sizing: border-box; }
    .filter-group input:focus, .filter-group select:focus { border-color: var(--accent-color, #667eea); outline: none; box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); }
    .jobs-grid-container { display: grid; grid-template-columns: repeat(4, 1fr); gap: 25px; min-height: 400px; align-items: stretch; animation: fadeInUp 0.7s ease-out 0.2s both; }
    
    @media (max-width: 1300px) { .jobs-grid-container { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 992px) { .jobs-grid-container { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 600px) { .jobs-grid-container { grid-template-columns: 1fr; } }

    .job-listing-card { background: var(--card-bg, #ffffff); border: 1px solid var(--border-color, #eee); border-radius: 12px; overflow: hidden; transition: transform 0.3s ease, box-shadow 0.3s ease; display: flex; flex-direction: column; height: 100%; position: relative; }
    .job-listing-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px var(--shadow-color, rgba(0,0,0,0.15)); border-color: var(--accent-color, #667eea); }
    
    /* Prémium kártya stílusok */
    .premium-card { border: 2px solid #f59e0b !important; box-shadow: 0 8px 25px rgba(245, 158, 11, 0.15) !important; }
    body.dark .premium-card, [data-theme="dark"] .premium-card { background: linear-gradient(to bottom right, var(--card-bg), #29200f); border-color: #d97706 !important; }

    .job-card-slider { position: relative; width: 100%; height: 200px; overflow: hidden; background: #f0f0f0; flex-shrink: 0; }
    .slider-track { display: flex; height: 100%; transition: transform 0.4s ease-in-out; width: 100%; }
    .slider-img { min-width: 100%; height: 100%; object-fit: cover; display: block; }
    .no-image-placeholder { width: 100%; min-width: 100%; height: 100%; background-color: var(--placeholder-bg); color: var(--placeholder-text); display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 20px; box-sizing: border-box; transition: background-color 0.3s, color 0.3s; }
    .no-image-placeholder i { font-size: 3rem; margin-bottom: 10px; color: var(--placeholder-icon); }
    .no-image-placeholder span { font-weight: 600; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .slider-btn { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(0, 0, 0, 0.6); color: white; border: none; cursor: pointer; padding: 0; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s; z-index: 5; }
    .job-card-slider:hover .slider-btn { opacity: 1; }
    .slider-btn:hover { background: rgba(0, 0, 0, 0.9); }
    .slider-btn.prev { left: 10px; }
    .slider-btn.next { right: 10px; }
    .job-card-content { padding: 20px; display: flex; flex-direction: column; flex-grow: 1; }
    .job-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; }
    .job-category { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; color: var(--accent-color, #667eea); font-weight: 800; background: rgba(102, 126, 234, 0.1); padding: 4px 8px; border-radius: 4px; max-width: 60%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .job-price { font-weight: 700; color: #2f855a; font-size: 1rem; white-space: nowrap; }
    .job-title { font-size: 1.15rem; color: var(--primary-text, #333); margin: 0 0 10px 0; line-height: 1.4; font-weight: 700; }
    .job-desc { font-size: 0.9rem; color: var(--secondary-text, #666); margin: 0; margin-bottom: 15px; flex-grow: 1; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    .job-footer { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-color, #eee); padding-top: 15px; margin-top: auto; }
    .job-deadline { font-size: 0.8rem; color: var(--error-text, #e53e3e); font-weight: 600; }
    .btn-details { background: var(--button-bg, #667eea); color: #ffffff; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: 600; transition: background 0.3s, transform 0.2s; }
    .btn-details:hover { background: var(--accent-color, #5a67d8); transform: scale(1.05); }
    .no-jobs-found { grid-column: 1 / -1; text-align: center; padding: 50px 20px; background: var(--tertiary-bg, #f5f5f5); border-radius: 10px; color: var(--secondary-text, #666); }
</style>


<div class="jobs-page-container">
    <div class="jobs-filter-bar">
        <h2>Munkák Keresése</h2>
        
        <div class="filter-group search-group">
            <input type="text" id="jobSearch" placeholder="Keresés munka nevére (pl. Kertrendezés)...">
        </div>

        <div class="filter-row">
            <div class="filter-group">
                <label>Típus:</label>
                <select id="mainTypeFilter">
                    <option value="">Összes munka</option>
                    <option value="outdoor">Kültéri Munkák</option>
                    <option value="indoor">Beltéri Munkák</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Alkategória (Csak aktív):</label>
                <select id="subCatFilter">
                    <option value="">Összes alkategória</option>
                </select>
            </div>
        </div>
    </div>

    <div class="jobs-grid-container" id="jobsGrid">
        <?php echo $initial_jobs_html; ?>
    </div>
</div>

<script>
    const availableOutdoor = <?php echo json_encode($kilteri_szurt); ?>;
    const availableIndoor = <?php echo json_encode($belteri_szurt); ?>;
    const availableAll = <?php echo json_encode($osszes_elerheto); ?>;

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('jobSearch');
        const mainTypeSelect = document.getElementById('mainTypeFilter');
        const subCatSelect = document.getElementById('subCatFilter');
        const jobsGrid = document.getElementById('jobsGrid');

        populateSubCats('');

        let timeout = null;
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(fetchJobs, 300);
        });

        mainTypeSelect.addEventListener('change', function() {
            const type = this.value;
            subCatSelect.value = "";
            populateSubCats(type);
            fetchJobs();
        });

        subCatSelect.addEventListener('change', fetchJobs);

        function populateSubCats(type) {
            subCatSelect.innerHTML = '<option value="">Összes alkategória</option>';
            subCatSelect.disabled = false;

            let options = [];
            if (type === 'outdoor') options = availableOutdoor;
            else if (type === 'indoor') options = availableIndoor;
            else options = availableAll;

            if (options.length > 0) {
                options.forEach(cat => {
                    const opt = document.createElement('option');
                    opt.value = cat;
                    opt.textContent = cat;
                    subCatSelect.appendChild(opt);
                });
            } else {
                const opt = document.createElement('option');
                opt.textContent = "Nincs elérhető aktív munka";
                subCatSelect.appendChild(opt);
                subCatSelect.disabled = true;
            }
        }

        function fetchJobs() {
            const search = searchInput.value;
            const type = mainTypeSelect.value;
            const cat = subCatSelect.value;

            jobsGrid.style.opacity = '0.5';

            let fetchUrl = window.location.href;
            if (fetchUrl.includes('?')) fetchUrl = fetchUrl.split('?')[0];

            const params = new URLSearchParams({
                ajax: '1',
                search: search,
                type: type,
                category: cat
            });

            fetch(fetchUrl + '?' + params.toString())
                .then(response => response.text())
                .then(html => {
                    jobsGrid.innerHTML = html;
                    jobsGrid.style.opacity = '1';
                })
                .catch(err => {
                    console.error('Hiba:', err);
                    jobsGrid.style.opacity = '1';
                });
        }
    });

    function moveSlide(btn, direction) {
        const sliderContainer = btn.closest('.job-card-slider');
        const track = sliderContainer.querySelector('.slider-track');
        const images = track.querySelectorAll('.slider-img');
        const totalImages = images.length;

        let currentIndex = parseInt(track.getAttribute('data-current') || 0);
        let newIndex = currentIndex + direction;

        if (newIndex < 0) newIndex = totalImages - 1;
        if (newIndex >= totalImages) newIndex = 0;

        track.style.transform = `translateX(-${newIndex * 100}%)`;
        track.setAttribute('data-current', newIndex);
    }
</script>