<?php
require_once("includes/jobs_functions.php");
if (!isset($_SESSION['fid'])) {
    header("Location: " . base_url('login'));
    exit;
}


$kilteri_munkak = [
    'Kertrendezés', 'Fűnyírás', 'Ültetés', 'Festés (kültér)', 'Burkolás', 'Külső vízvezeték javítás',
    'Kerítés javítás', 'Tetőfedés', 'Csatornázás', 'Kerti medence karbantartás', 'Locsolórendszer telepítés',
    'Kerti világítás', 'Padlóburkolás (kültér)', 'Faanyag kezelés', 'Zöldhulladék elszállítás',
    'Járdasó hintés', 'Kerti pad festés', 'Gyepszegély nyírás', 'Kerti tó kialakítás', 'Virágágyás rendezés',
    'Sövénynyírás', 'Öntözőrendszer javítás', 'Kőműves munka (kültér)', 'Vakolás (kültér)', 'Festőmunka (kültér)',
    'Garázskapu javítás', 'Bejárati ajtó csere', 'Bejárati ajtó festés', 'Erkély üvegezés', 'Tetőcsatorna tisztítás',
    'Tetőfedő csere', 'Hóvédő szerelés', 'Kerti locsoló szivattyú javítás', 'Kutyaház építés', 'Macskaajtó beépítés',
    'Homokozó építés', 'Trambulin szerelés',
    'Kerti hinta szerelés', 'Kerti tűzrakóhely építés', 'Szalmatető készítés', 'Nádas kerítés építés', 'Fa kerítés festése',
    'Sövénynyírás géppel', 'Kerti tó szűrőrendszer telepítése', 'Láncfűrész lánc cseréje', 'Kerti permetező javítása',
    'Kültéri szennyvízcsatorna tisztítása', 'Madáretető készítés', 'Kerti világítás telepítés', 'Okos locsolórendszer telepítés',
    'Egyéb (kültéri)'
];

$belteri_munkak = [
    'Padlócsiszolás', 'Villanyszerelés','Vízszerelés', 'Festés (beltér)', 'Bútor szerelés', 'Csaptelep csere', 'Ablaktisztítás', 'Padlóviaszozás',
    'Redőny szerelés', 'Klima telepítés', 'Kályha tisztítás', 'Mélygarázs építés', 'Terasz burkolás', 'Padlófűtés telepítés',
    'Okosotthon rendszer telepítés', 'Tapéta felragasztás', 'Járólap csere', 'Ablak csere',
    'Szellőztető rendszer telepítés', 'Pince átalakítás', 'Tetőtér beépítés', 'Konyhabútor szerelés', 'Fürdőszoba felújítás',
    'Tűzhely csere', 'Hűtőszekrény javítás', 'Mosógép javítás', 'Légkondicionáló tisztítás', 'Kéményseprés', 'Porszívó javítás',
    'Számítógép összeszerelés', 'Hálózati kábel húzás', 'Biztonsági rendszer telepítés', 'Táblakeret készítés',
    'Redőny javítás', 'Tárolórendszer szerelés', 'Parketta ragasztás', 'Csempe javítás', 'Vízóra csere', 'Gázóra csere',
    'Szivargyújtó javítás', 'Páraelszívó szerelés', 'Konyhai elszívó javítás', 'Vízhűtő szerelés', 'Forgó ajtó beépítés',
    'Fürdőszobai szárító szerelése', 'Kád és wc csempe burkolás', 'Konyhapult kialakítás', 'Konyhai mosogató csere',
    'Bútorlábak cseréje', 'Redőny zsanérjának cseréje', 'Lámpa okossá tétele', 'Sötétedésre kapcsoló beállítása',
    'Megfigyelő kamera telepítése', 'Vízvezeték szigetelése',
    'Gázcső szigetelése', 'Hőszigetelés kialakítása', 'Hangszigetelés telepítése', 'Riasztó berendezés telepítése',
    'Gardrób beépítés', 'Fali polc szerelés', 'TV konzol szerelés', 'Hálószobai kandalló telepítés', 'Mélyhűtő javítás',
    'Sütőjavítás', 'Mikrohullámú sütő javítás', 'Kávéfőző javítás', 'Szobabicikli szerelése', 'Faltámasz szerelése',
    'Gardróbszekrény beépítés', 'Könyvespolc kialakítás', 'Egyéb (beltéri)'
];

$hibak = [];
$siker = '';
$feltoltott_kepek = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['munka_feltoltes'])) {
    uploadWork($_POST);
}
?>

<div class="work-upload-page">
    <div class="work-upload-container">
        <h1 class="work-upload-title">Munka Feltöltése</h1>
        
        <?php if (!empty($siker)): ?>
            <div class="uzenet uzenet-siker">
                <?= $siker ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($hibak)): ?>
            <div class="uzenet uzenet-hiba">
                <?php foreach ($hibak as $hiba): ?>
                    <p><?= $hiba ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <style>
            .image-upload-area { margin-top: 20px; }
            .upload-box { border: 3px dashed var(--border-color); border-radius: 12px; padding: 40px 20px; text-align: center; cursor: pointer; transition: all 0.3s ease; background: var(--primary-bg); position: relative; }
            .upload-box:hover, .upload-box.highlight { border-color: var(--accent-color); background: rgba(102, 126, 234, 0.05); }
            .upload-icon { width: 64px; height: 64px; margin: 0 auto 20px; fill: var(--accent-color); transition: transform 0.3s ease; pointer-events: none; }
            .upload-text, .upload-subtext { pointer-events: none; }
            .hidden-file-input { display: none; }
            .work-upload-page { max-width: 1200px; margin: 30px auto; padding: 0 20px; animation: fadeInUp 0.5s ease-out; }
            .work-upload-container { background: var(--card-bg); border-radius: 15px; padding: 40px; box-shadow: 0 10px 40px var(--shadow-color); border: 1px solid var(--border-color); transition: all 0.3s ease; }
            .work-upload-title { text-align: center; font-size: 2.2rem; margin-bottom: 30px; color: var(--primary-text); position: relative; padding-bottom: 15px; }
            .work-upload-title::after { content: ''; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 80px; height: 4px; background: var(--accent-gradient); border-radius: 2px; }
            .work-upload-form { margin-top: 20px; }
            .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 40px; }
            .form-column { display: flex; flex-direction: column; gap: 25px; }
            .form-group { display: flex; flex-direction: column; }
            .form-group label { font-weight: 600; margin-bottom: 8px; color: var(--primary-text); font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 6px; }
            .form-group label::before { content: '•'; color: var(--accent-color); font-size: 18px; }
            .form-group input, .form-group textarea, .form-group select { padding: 14px 16px; border: 2px solid var(--border-color); border-radius: 10px; font-size: 16px; transition: all 0.3s ease; background: var(--primary-bg); color: var(--primary-text); font-family: inherit; }
            .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline: none; border-color: var(--accent-color); background: var(--secondary-bg); box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); }
            .form-group input::placeholder, .form-group textarea::placeholder { color: var(--secondary-text); opacity: 0.7; }
            .form-group.full-width { grid-column: 1 / -1; }
            .character-count { text-align: right; font-size: 12px; color: var(--secondary-text); margin-top: 5px; opacity: 0.8; }
            .form-section { background: var(--tertiary-bg); padding: 25px; border-radius: 12px; border: 1px solid var(--border-color); margin-top: 10px; }
            .section-title { font-size: 1.3rem; margin-bottom: 10px; color: var(--primary-text); display: flex; align-items: center; gap: 10px; }
            .section-title::before { content: ''; display: block; width: 4px; height: 20px; background: var(--accent-gradient); border-radius: 2px; }
            .section-description { font-size: 0.9rem; color: var(--secondary-text); margin-bottom: 20px; opacity: 0.8; }
            .upload-text { font-size: 1.2rem; font-weight: 600; color: var(--primary-text); margin-bottom: 8px; }
            .upload-subtext { font-size: 0.9rem; color: var(--secondary-text); opacity: 0.8; }
            .image-preview-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 15px; margin-top: 25px; }
            .preview-item { position: relative; animation: scaleIn 0.3s ease-out; }
            .preview-image-container { background: var(--secondary-bg); border-radius: 10px; overflow: hidden; border: 2px solid var(--border-color); transition: all 0.3s ease; position: relative; }
            .preview-item:hover .preview-image-container { transform: translateY(-5px); border-color: var(--accent-color); box-shadow: 0 8px 25px var(--shadow-color); }
            .preview-image { width: 100%; height: 150px; object-fit: cover; display: block; transition: transform 0.3s ease; }
            .preview-item:hover .preview-image { transform: scale(1.05); }
            .remove-image-btn { position: absolute; top: 10px; right: 10px; width: 32px; height: 32px; background: rgba(231, 76, 60, 0.9); border: none; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; backdrop-filter: blur(5px); }
            .remove-image-btn:hover { background: #e74c3c; transform: scale(1.1); }
            .remove-image-btn svg { width: 16px; height: 16px; fill: white; }
            .image-info { padding: 12px; background: rgba(0, 0, 0, 0.7); color: white; position: absolute; bottom: 0; left: 0; right: 0; transform: translateY(100%); transition: transform 0.3s ease; }
            .preview-item:hover .image-info { transform: translateY(0); }
            .image-name { font-size: 12px; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            .image-size { font-size: 10px; opacity: 0.8; display: block; margin-top: 4px; }
            .upload-hint { margin-top: 20px; padding: 15px; background: rgba(102, 126, 234, 0.1); border-radius: 8px; border-left: 4px solid var(--accent-color); }
            .upload-hint p { color: var(--primary-text); font-size: 0.9rem; margin: 0; display: flex; align-items: center; gap: 8px; }
            .upload-hint p::before { content: '💡'; font-size: 1.1rem; }
            .form-actions { display: flex; justify-content: center; gap: 20px; margin-top: 40px; padding-top: 30px; border-top: 1px solid var(--border-color); }
            .btn { padding: 15px 30px; border: none; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center; gap: 10px; min-width: 200px; }
            .btn-icon { width: 20px; height: 20px; fill: currentColor; }
            .btn-primary { background: var(--accent-gradient); color: var(--button-text); border: 2px solid transparent; }
            .btn-primary:hover { transform: translateY(-2px); background: var(--button-hover-bg); box-shadow: 0 5px 20px var(--shadow-color); }
            .btn-secondary { background: var(--secondary-button-bg); color: var(--button-text); border: 2px solid transparent; }
            .btn-secondary:hover { background: var(--secondary-button-hover-bg); transform: translateY(-2px); box-shadow: 0 5px 20px var(--shadow-color); }
            .accordion-container { background: var(--tertiary-bg); padding: 0; border-radius: 12px; border: 1px solid var(--border-color); margin-bottom: 25px; overflow: hidden; }
            .accordion-item { border-bottom: 1px solid var(--border-color); }
            .accordion-item:last-child { border-bottom: none; }
            .accordion-header { padding: 18px 25px; background: var(--secondary-bg); cursor: pointer; display: flex; justify-content: space-between; align-items: center; transition: all 0.3s ease; }
            .accordion-header:hover { background: var(--hover-bg); }
            .accordion-header.active { background: var(--accent-gradient); }
            .accordion-title { display: flex; align-items: center; gap: 12px; font-weight: 600; color: var(--primary-text); font-size: 16px; }
            .accordion-header.active .accordion-title { color: white; }
            .accordion-icon { font-size: 1.4rem; }
            .accordion-arrow { transition: transform 0.3s ease; width: 20px; height: 20px; fill: var(--secondary-text); }
            .accordion-header.active .accordion-arrow { fill: white; transform: rotate(180deg); }
            .accordion-content { max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out, padding 0.3s ease; background: var(--primary-bg); }
            .accordion-content.open { max-height: 500px; padding: 20px 25px; }
            .category-search-container { margin-bottom: 15px; }
            .category-search-input { width: 100%; padding: 10px 14px; border: 2px solid var(--border-color); border-radius: 8px; font-size: 14px; background: var(--primary-bg); color: var(--primary-text); transition: all 0.3s ease; }
            .category-search-input:focus { outline: none; border-color: var(--accent-color); box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); }
            .category-select { width: 100%; height: 250px; padding: 12px; border: 2px solid var(--border-color); border-radius: 10px; font-size: 15px; transition: all 0.3s ease; background: var(--primary-bg); color: var(--primary-text); font-family: inherit; resize: none; overflow-y: auto; cursor: pointer; }
            .category-select:focus { outline: none; border-color: var(--accent-color); background: var(--secondary-bg); box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); }
            .category-select option { padding: 8px 12px; margin: 2px 0; border-radius: 6px; transition: all 0.2s ease; cursor: pointer; }
            .category-select option:hover { background: var(--accent-color); color: white; }
            .category-select option:checked { background: var(--accent-gradient); color: white; font-weight: 600; }
            .category-select option.other-option { font-weight: 600; color: var(--accent-color); background: rgba(102, 126, 234, 0.1); border-top: 1px solid var(--border-color); margin-top: 5px; padding-top: 10px; }
            .category-count { margin-top: 8px; font-size: 0.85rem; color: var(--secondary-text); opacity: 0.8; text-align: right; }
            .category-hint { margin-top: 15px; font-size: 0.85rem; color: var(--secondary-text); opacity: 0.8; font-style: italic; text-align: center; padding: 10px; border-top: 1px solid var(--border-color); }
            .selected-category-display { padding: 15px 25px; background: rgba(102, 126, 234, 0.1); border-radius: 8px; margin: 15px 25px; display: flex; justify-content: space-between; align-items: center; border: 1px solid var(--accent-color); }
            .selected-category-text { font-weight: 600; color: var(--accent-color); display: flex; align-items: center; gap: 8px; }
            .selected-category-text::before { content: '✓'; font-size: 1.2rem; }
            .clear-selection-btn { background: var(--secondary-button-bg); color: var(--button-text); border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.85rem; transition: all 0.3s ease; }
            .clear-selection-btn:hover { background: var(--secondary-button-hover-bg); }

            .premium-checkbox-container:hover {
                background: rgba(245, 158, 11, 0.15) !important;
                box-shadow: 0 0 10px rgba(245, 158, 11, 0.3);
            }
            .premium-section {
                border-color: #f59e0b !important;
                background: rgba(245, 158, 11, 0.03) !important;
            }

            @media (max-width: 992px) { .work-upload-container { padding: 30px; } .form-grid { gap: 30px; } }
            @media (max-width: 768px) { .work-upload-page { padding: 0 15px; margin: 20px auto; } .work-upload-container { padding: 25px; } .work-upload-title { font-size: 1.8rem; } .form-grid { grid-template-columns: 1fr; gap: 25px; } .form-column { gap: 20px; } .form-section { padding: 20px; } .accordion-content.open { max-height: 400px; padding: 15px 20px; } .accordion-header { padding: 15px 20px; } .category-select { height: 200px; } .image-preview-container { grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 12px; } .form-actions { flex-direction: column; align-items: center; gap: 15px; } .btn { width: 100%; max-width: 300px; } .upload-box { padding: 30px 15px; } .upload-icon { width: 48px; height: 48px; } .selected-category-display { margin: 15px 20px; padding: 12px 20px; } }
            @media (max-width: 576px) { .work-upload-container { padding: 20px; } .work-upload-title { font-size: 1.6rem; } .form-group input, .form-group textarea, .form-group select { padding: 12px 14px; font-size: 15px; } .accordion-container { border-radius: 10px; } .image-preview-container { grid-template-columns: repeat(2, 1fr); } .upload-text { font-size: 1.1rem; } .section-title { font-size: 1.2rem; } .category-select { height: 180px; font-size: 14px; } }
            @media (max-width: 480px) { .image-preview-container { grid-template-columns: 1fr; } .upload-box { padding: 25px 12px; } .upload-icon { width: 40px; height: 40px; margin-bottom: 15px; } .category-select { height: 150px; } .accordion-header { padding: 12px 15px; } .accordion-content.open { padding: 12px 15px; } }
            
            [data-theme="dark"] .upload-box { background: var(--secondary-bg); border-color: var(--tertiary-bg); }
            [data-theme="dark"] .upload-box:hover, [data-theme="dark"] .upload-box.highlight { background: rgba(102, 126, 234, 0.1); border-color: var(--accent-color); }
            [data-theme="dark"] .form-section { background: var(--secondary-bg); }
            [data-theme="dark"] .upload-hint { background: rgba(102, 126, 234, 0.15); }
            [data-theme="dark"] .preview-image-container { background: var(--tertiary-bg); }
            [data-theme="dark"] .accordion-container { background: var(--secondary-bg); }
            [data-theme="dark"] .category-select option.other-option { background: rgba(102, 126, 234, 0.2); }
            [data-theme="dark"] .accordion-header { background: var(--tertiary-bg); }
            [data-theme="dark"] .accordion-header:hover { background: var(--hover-bg); }
            [data-theme="dark"] .selected-category-display { background: rgba(102, 126, 234, 0.15); }
            
            @media print { .work-upload-page { margin: 0; padding: 0; } .work-upload-container { box-shadow: none; border: 1px solid #ccc; padding: 20px; } .upload-box, .form-actions .btn-secondary { display: none; } }
        </style>

        <form method="POST" enctype="multipart/form-data" class="work-upload-form">
            <input type="hidden" id="selected_category" name="munka_kategoria" value="<?= isset($munka_kategoria) ? htmlspecialchars($munka_kategoria) : '' ?>">
            
            <div class="accordion-container">
                <h3 style="padding: 20px 25px 0; font-size: 1.2rem; color: var(--primary-text);">Válassz munkakategóriát</h3>
                
                <div id="selectedCategoryDisplay" class="selected-category-display" style="display: <?= isset($munka_kategoria) && !empty($munka_kategoria) ? 'flex' : 'none' ?>;">
                    <div class="selected-category-text">
                        Kiválasztott kategória: <span id="selectedCategoryText"><?= isset($munka_kategoria) ? htmlspecialchars($munka_kategoria) : '' ?></span>
                    </div>
                    <button type="button" id="clearSelectionBtn" class="clear-selection-btn">Törlés</button>
                </div>
                
                <div class="accordion-item">
                    <div class="accordion-header" data-target="indoorAccordion">
                        <div class="accordion-title">
                            <span class="accordion-icon">🏠</span>
                            Beltéri munkák
                        </div>
                        <svg class="accordion-arrow" viewBox="0 0 24 24">
                            <path d="M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z" />
                        </svg>
                    </div>
                    <div id="indoorAccordion" class="accordion-content">
                        <div class="category-search-container">
                            <input type="text" id="indoorSearch" class="category-search-input" 
                                   placeholder="Keresés a beltéri munkák között...">
                        </div>
                        <select id="indoor_select" size="10" class="category-select">
                            <option value="">-- Válassz beltéri munkát --</option>
                            <?php foreach ($belteri_munkak as $kategoria): ?>
                                <?php 
                                $selected = (isset($munka_kategoria) && $munka_kategoria == $kategoria) ? 'selected' : '';
                                $isOther = ($kategoria == 'Egyéb (beltéri)') ? 'other-option' : '';
                                ?>
                                <option value="<?= htmlspecialchars($kategoria) ?>" 
                                        <?= $selected ?> 
                                        class="<?= $isOther ?>">
                                    <?= htmlspecialchars($kategoria) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="category-count" id="indoorCount"><?= count($belteri_munkak) ?> lehetőség</div>
                    </div>
                </div>
                
                <div class="accordion-item">
                    <div class="accordion-header" data-target="outdoorAccordion">
                        <div class="accordion-title">
                            <span class="accordion-icon">🌳</span>
                            Kültéri munkák
                        </div>
                        <svg class="accordion-arrow" viewBox="0 0 24 24">
                            <path d="M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z" />
                        </svg>
                    </div>
                    <div id="outdoorAccordion" class="accordion-content">
                        <div class="category-search-container">
                            <input type="text" id="outdoorSearch" class="category-search-input" 
                                   placeholder="Keresés a kültéri munkák között...">
                        </div>
                        <select id="outdoor_select" size="10" class="category-select">
                            <option value="">-- Válassz kültéri munkát --</option>
                            <?php foreach ($kilteri_munkak as $kategoria): ?>
                                <?php 
                                $selected = (isset($munka_kategoria) && $munka_kategoria == $kategoria) ? 'selected' : '';
                                $isOther = ($kategoria == 'Egyéb (kültéri)') ? 'other-option' : '';
                                ?>
                                <option value="<?= htmlspecialchars($kategoria) ?>" 
                                        <?= $selected ?> 
                                        class="<?= $isOther ?>">
                                    <?= htmlspecialchars($kategoria) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="category-count" id="outdoorCount"><?= count($kilteri_munkak) ?> lehetőség</div>
                    </div>
                </div>
                
                <div class="category-hint">
                    Csak az egyik kategóriából lehet választani
                </div>
            </div>
            
            <div class="form-grid">
                <div class="form-column">
                    <div class="form-group">
                        <label for="ar">Ár (Ft) *</label>
                        <input type="number" id="ar" name="ar" 
                               value="<?= isset($ar) ? htmlspecialchars($ar) : '' ?>" 
                               placeholder="5000" min="0" step="100" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="munka_datum">Munka napja *</label>
                        <input type="date" id="munka_datum" name="munka_datum" 
                               value="<?= isset($munka_datum) ? htmlspecialchars($munka_datum) : '' ?>" 
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="munka_ido">Munka időpontja *</label>
                        <input type="time" id="munka_ido" name="munka_ido" 
                               value="<?= isset($munka_ido) ? htmlspecialchars($munka_ido) : '' ?>" 
                               required>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="section-title">Elérhetőségek (opcionális)</h3>
                        
                        <div class="form-group">
                            <label for="telefonszam">Telefonszám</label>
                            <input type="tel" id="telefonszam" name="telefonszam" 
                                   value="<?= isset($telefonszam) ? htmlspecialchars($telefonszam) : '' ?>" 
                                   placeholder="+36 20 123 4567">
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email cím</label>
                            <input type="email" id="email" name="email" 
                                   value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" 
                                   placeholder="pelda@email.hu">
                        </div>
                    </div>
                </div>
                
                <div class="form-column">
                    <div class="form-group full-width">
                        <label for="munka_leiras">Munka részletes leírása *</label>
                        <textarea id="munka_leiras" name="munka_leiras" rows="8" 
                                  placeholder="Írd le részletesen a munkát: Részletezd a munkát, Milyen eszközökre van szükség, Milyen hosszú a munka, stb." 
                                  required><?= isset($munka_leiras) ? htmlspecialchars($munka_leiras) : '' ?></textarea>
                        <div class="character-count">
                            <span id="charCount">0</span> / 2000 karakter
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h3 class="section-title">Munka képek</h3>
                        <p class="section-description">Tölts fel képet és mutasd be a munkakört. (maximum 5 kép)</p>
                        
                        <div class="image-upload-area">
                            <div class="upload-box" id="uploadBox">
                                <svg class="upload-icon" viewBox="0 0 24 24">
                                    <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                                </svg>
                                <p class="upload-text">Kattints ide vagy húzd ide a képeket</p>
                                <p class="upload-subtext">JPG, PNG, GIF, WebP (max 10MB)</p>
                            </div>
                            
                            <input type="file" id="imageInput" name="referencia_kepek[]" 
                                   accept="image/*" multiple class="hidden-file-input">
                            
                            <div class="image-preview-container" id="imagePreviewContainer">
                                </div>
                            
                            <div class="upload-hint">
                                <p>Tipp: Tölts fel minőségi képeket a munkádról!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section premium-section">
                <h3 class="section-title" style="color: #f59e0b;"><i class="fa fa-star"></i> Kiemelt munka (Opcionális)</h3>
                <label class="premium-checkbox-container" style="display: flex; align-items: center; gap: 10px; cursor: pointer; padding: 15px; background: rgba(245, 158, 11, 0.1); border: 2px solid #f59e0b; border-radius: 8px; transition: 0.3s;">
                    <input type="checkbox" name="kiemelt_munka" id="kiemelt_munka" value="1" style="width: 20px; height: 20px; cursor: pointer;">
                    <span style="font-weight: bold; color: var(--primary-text); font-size: 1.1rem;">Legyen kiemelt a munkám!</span>
                </label>
                <p class="premium-desc" style="margin-top: 10px; font-size: 0.9rem; color: var(--secondary-text);">
                    Fizetéssel előrébb sorolhatja a munkáját, hamarabb és láthatóbban helyezzük el a sorban, mint a többi munkát. 
                    <strong>A kiemelés díjköteles, melyet a következő oldalon egyenlíthet ki.</strong> 
                    <br><small><i class="fa fa-info-circle"></i> Amíg a fizetés nem történik meg, a munka nem lesz közzétéve a felületen.</small>
                </p>
            </div>
            
            <div class="form-actions">
                <button type="submit" name="munka_feltoltes" class="btn btn-primary">
                    <svg class="btn-icon" viewBox="0 0 24 24">
                        <path d="M17,3H5C3.89,3 3,3.9 3,5V19C3,20.1 3.9,21 5,21H19C20.1,21 21,20.1 21,19V7L17,3M19,19H5V5H16.17L19,7.83V19M12,12C10.34,12 9,13.34 9,15C9,16.66 10.34,18 12,18C13.66,18 15,16.66 15,15C15,13.34 13.66,12 12,12M6,6H15V10H6V6Z" />
                    </svg>
                    Munka feltöltése
                </button>
                
                <button type="reset" class="btn btn-secondary">
                    <svg class="btn-icon" viewBox="0 0 24 24">
                        <path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" />
                    </svg>
                    Űrlap törlése
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const leirasTextarea = document.getElementById('munka_leiras');
    const charCount = document.getElementById('charCount');
    
    if(leirasTextarea) {
        charCount.textContent = leirasTextarea.value.length;
        leirasTextarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
    }
    
    const accordionHeaders = document.querySelectorAll('.accordion-header');
    
    function closeAllAccordions() {
        accordionHeaders.forEach(header => {
            const targetId = header.getAttribute('data-target');
            const targetContent = document.getElementById(targetId);
            const arrow = header.querySelector('.accordion-arrow');
            
            if(targetContent) {
                targetContent.classList.remove('open');
                header.classList.remove('active');
            }
            if(arrow) {
                arrow.style.transform = 'rotate(0)';
            }
        });
    }
    
    accordionHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const targetContent = document.getElementById(targetId);
            const arrow = this.querySelector('.accordion-arrow');
            
            const isOpen = targetContent.classList.contains('open');
            
            if (!isOpen) {
                closeAllAccordions();
            }
            
            if (!isOpen) {
                targetContent.classList.add('open');
                this.classList.add('active');
                arrow.style.transform = 'rotate(180deg)';
            }
        });
    });
    
    const indoorSelect = document.getElementById('indoor_select');
    const outdoorSelect = document.getElementById('outdoor_select');
    const selectedCategoryInput = document.getElementById('selected_category');
    const selectedCategoryDisplay = document.getElementById('selectedCategoryDisplay');
    const selectedCategoryText = document.getElementById('selectedCategoryText');
    const clearSelectionBtn = document.getElementById('clearSelectionBtn');
    
    function updateSelectedCategoryDisplay() {
        const selectedValue = selectedCategoryInput.value;
        if (selectedValue) {
            selectedCategoryText.textContent = selectedValue;
            selectedCategoryDisplay.style.display = 'flex';
        } else {
            selectedCategoryDisplay.style.display = 'none';
        }
    }
    
    function updateSelectedCategory() {
        const indoorValue = indoorSelect.value;
        const outdoorValue = outdoorSelect.value;
        
        if (indoorValue) {
            selectedCategoryInput.value = indoorValue;
        } else if (outdoorValue) {
            selectedCategoryInput.value = outdoorValue;
        } else {
            selectedCategoryInput.value = "";
        }
        updateSelectedCategoryDisplay();
    }
    
    function handleCategoryChange(selectElement, otherSelectElement) {
        if (selectElement.value) {
            otherSelectElement.value = "";
            updateSelectedCategory();
            closeAllAccordions();
        }
    }
    
    indoorSelect.addEventListener('change', function() {
        handleCategoryChange(this, outdoorSelect);
    });
    
    outdoorSelect.addEventListener('change', function() {
        handleCategoryChange(this, indoorSelect);
    });
    
    clearSelectionBtn.addEventListener('click', function() {
        indoorSelect.value = "";
        outdoorSelect.value = "";
        selectedCategoryInput.value = "";
        closeAllAccordions();
        updateSelectedCategoryDisplay();
    });
    
    // --- 4. Kereső funkció ---
    const indoorSearch = document.getElementById('indoorSearch');
    const outdoorSearch = document.getElementById('outdoorSearch');
    const indoorCount = document.getElementById('indoorCount');
    const outdoorCount = document.getElementById('outdoorCount');
    
    function filterOptions(selectElement, searchTerm, countElement) {
        const options = selectElement.options;
        let visibleCount = 0;
        options[0].style.display = ''; 
        
        for (let i = 1; i < options.length; i++) {
            const text = options[i].text.toLowerCase();
            if (!searchTerm || text.includes(searchTerm)) {
                options[i].style.display = '';
                visibleCount++;
            } else {
                options[i].style.display = 'none';
            }
        }
        countElement.textContent = visibleCount + ' találat';
    }
    
    indoorSearch.addEventListener('input', function() {
        filterOptions(indoorSelect, this.value.toLowerCase(), indoorCount);
    });
    outdoorSearch.addEventListener('input', function() {
        filterOptions(outdoorSelect, this.value.toLowerCase(), outdoorCount);
    });
    
    filterOptions(indoorSelect, '', indoorCount);
    filterOptions(outdoorSelect, '', outdoorCount);

    const imageInput = document.getElementById('imageInput');
    const uploadBox = document.getElementById('uploadBox');
    const previewContainer = document.getElementById('imagePreviewContainer');
    const maxFiles = 5;
    const dt = new DataTransfer(); 

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadBox.addEventListener(eventName, function(e) {
            e.preventDefault();
            e.stopPropagation();
        }, false);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        uploadBox.addEventListener(eventName, () => uploadBox.classList.add('highlight'), false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadBox.addEventListener(eventName, () => uploadBox.classList.remove('highlight'), false);
    });

    uploadBox.addEventListener('drop', function(e) {
        const files = e.dataTransfer.files;
        handleFiles(files);
    }, false);

    uploadBox.addEventListener('click', function() {
        imageInput.click();
    });

    imageInput.addEventListener('change', function() {
        handleFiles(this.files);
    });

    function handleFiles(files) {
        Array.from(files).forEach(file => {
            if (dt.items.length >= maxFiles) {
                alert(`Maximum ${maxFiles} képet tölthetsz fel!`);
                return;
            }
            if (!file.type.match('image.*')) {
                alert("Csak képet tölthetsz fel!");
                return;
            }
            dt.items.add(file);
        });
        updatePreview();
    }

    function updatePreview() {
        imageInput.files = dt.files;
        previewContainer.innerHTML = "";

        Array.from(dt.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'preview-item';
                div.innerHTML = `
                    <div class="preview-image-container">
                        <img src="${e.target.result}" class="preview-image">
                        <button type="button" class="remove-image-btn">
                            <svg viewBox="0 0 24 24"><path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" fill="white"/></svg>
                        </button>
                    </div>`;
                
                div.querySelector('.remove-image-btn').addEventListener('click', (ev) => {
                    ev.stopPropagation();
                    removeFile(index);
                });
                previewContainer.appendChild(div);
            };
        });
    }

    function removeFile(index) {
        const newDt = new DataTransfer();
        Array.from(dt.files).forEach((file, i) => {
            if (i !== index) newDt.items.add(file);
        });
        dt.items.clear();
        Array.from(newDt.files).forEach(f => dt.items.add(f));
        updatePreview();
    }

    const dateInput = document.getElementById('munka_datum');
    if(dateInput) dateInput.min = new Date().toISOString().split('T')[0];

    const timeInput = document.getElementById('munka_ido');
    if (timeInput && !timeInput.value) {
        const now = new Date();
        timeInput.value = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
    }

    const form = document.querySelector('.work-upload-form');
    if(form) {
        form.addEventListener('reset', function() {
            setTimeout(() => {
                dt.items.clear();
                updatePreview();
                selectedCategoryDisplay.style.display = 'none';
                if(charCount) charCount.textContent = "0";
                filterOptions(indoorSelect, '', indoorCount);
                filterOptions(outdoorSelect, '', outdoorCount);
                closeAllAccordions();
            }, 10);
        });
    }
});
</script>