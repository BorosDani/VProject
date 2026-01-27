<?php
// =============================
// ADATBÁZIS HELYZET ELLENŐRZÉSE - PDO VÁLTOZAT
// =============================

// Profilkép alap URL
$profile_image_base_url = '/assets/images/profile/';
$default_avatar_url = 'https://ui-avatars.com/api/';

// Ellenőrizzük, hogy a $conn változó valóban PDO-e
if (isset($conn) && $conn instanceof PDO) {
    try {
        // SQL: Munkak táblából vesszük a telefonszámot és email-t
        // MÓDOSÍTÁS: hozzáadva az f.fnev mező
        $mukodo_sql = "SELECT m.*, 
                              f.fnev,  -- Felhasználónév hozzáadva
                              f.knev, 
                              f.vnev,
                              f.profilkep,
                              f.varmegye,
                              f.fid
                       FROM munkak m
                       LEFT JOIN felhasznalok f ON m.felhasznalo_id = f.fid
                       WHERE m.statusz = 'aktiv'
                       ORDER BY m.datum_ido DESC";
        
        $stmt = $conn->query($mukodo_sql);
        
        if ($stmt) {
            $munkak = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Referencia képek hozzáadása
            foreach ($munkak as &$munka) {
                try {
                    $kep_sql = "SELECT kep_url FROM referencia_kepek WHERE munka_id = :munka_id";
                    $kep_stmt = $conn->prepare($kep_sql);
                    $kep_stmt->execute([':munka_id' => $munka['id']]);
                    $referencia_kepek = $kep_stmt->fetchAll(PDO::FETCH_COLUMN, 0);
                    
                    // Teljes elérési út hozzáadása a képekhez
                    $munka['referencia_kepek'] = [];
                    if (!empty($referencia_kepek)) {
                        shuffle($referencia_kepek);
                        $max_random_kep = min(4, count($referencia_kepek));
                        $random_kepek = array_slice($referencia_kepek, 0, $max_random_kep);
                        
                        foreach ($random_kepek as $kep) {
                            if (!empty($kep)) {
                                if (filter_var($kep, FILTER_VALIDATE_URL)) {
                                    $munka['referencia_kepek'][] = $kep;
                                } else {
                                    $munka['referencia_kepek'][] = '/assets/images/referencia_kepek/' . $kep;
                                }
                            }
                        }
                    }
                    
                    // Profilkép URL hozzáadása
                    if (!empty($munka['profilkep'])) {
                        if (filter_var($munka['profilkep'], FILTER_VALIDATE_URL)) {
                            $munka['profilkep_url'] = $munka['profilkep'];
                        } else {
                            $munka['profilkep_url'] = $profile_image_base_url . $munka['profilkep'];
                        }
                    } else {
                        // MÓDOSÍTÁS: a default avatar URL használja a felhasználónevet ha van, különben 'F'
                        $avatar_name = !empty($munka['fnev']) ? $munka['fnev'] : 
                                     (!empty($munka['knev']) ? $munka['knev'] : 'F');
                        $munka['profilkep_url'] = $default_avatar_url . '?name=' . urlencode($avatar_name) . '&size=100&background=007bff&color=fff';
                    }
                    
                } catch (PDOException $e) {
                    $munka['referencia_kepek'] = [];
                    $avatar_name = !empty($munka['fnev']) ? $munka['fnev'] : 
                                 (!empty($munka['knev']) ? $munka['knev'] : 'F');
                    $munka['profilkep_url'] = $default_avatar_url . '?name=' . urlencode($avatar_name) . '&size=100&background=007bff&color=fff';
                }
            }
            unset($munka);
            
            // EGYSZERŰ KATEGORIZÁLÁS - A MUNKA NEVÉT KÖZVETLENÜL HASZNÁLJUK
            $kategorizalt_munkak = [
                'kulteri' => [],
                'belteri' => [],
                'egyeb' => []
            ];
            
            // Munkakategóriák listája a feltöltési oldalról
            $belteri_munkak = [
                'Padlócsiszolás', 'Festés (beltér)', 'Bútor szerelés', 'Csaptelep csere', 'Ablaktisztítás', 'Padlóviaszozas',
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
            
            $kilteri_munkak = [
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
            
            // Kategorizálás - EGYSZERŰ VERZIÓ
            foreach ($munkak as $munka) {
                $munka_nev = trim($munka['munka_nev']);
                $talalt = false;
                
                // Ellenőrizzük a beltéri munkákat
                foreach ($belteri_munkak as $kategoria) {
                    if ($munka_nev === $kategoria) {
                        if (!isset($kategorizalt_munkak['belteri'][$kategoria])) {
                            $kategorizalt_munkak['belteri'][$kategoria] = [];
                        }
                        $kategorizalt_munkak['belteri'][$kategoria][] = $munka;
                        $talalt = true;
                        break;
                    }
                }
                
                // Ha nem találtunk beltéri kategóriában, próbáljuk a kültérit
                if (!$talalt) {
                    foreach ($kilteri_munkak as $kategoria) {
                        if ($munka_nev === $kategoria) {
                            if (!isset($kategorizalt_munkak['kulteri'][$kategoria])) {
                                $kategorizalt_munkak['kulteri'][$kategoria] = [];
                            }
                            $kategorizalt_munkak['kulteri'][$kategoria][] = $munka;
                            $talalt = true;
                            break;
                        }
                    }
                }
                
                // Ha egyik kategóriában sem találtuk, akkor "Egyéb"
                if (!$talalt) {
                    $kategoria_tipus = 'egyeb';
                    $kategoria_nev = 'Egyéb';
                    
                    // Próbáljuk megállapítani a szövegből, hogy beltéri vagy kültéri-e
                    $szoveg = strtolower($munka_nev . ' ' . ($munka['munka_leiras'] ?? ''));
                    
                    if (strpos($szoveg, 'beltéri') !== false || strpos($szoveg, 'belteri') !== false || 
                        strpos($szoveg, 'szoba') !== false || strpos($szoveg, 'lakás') !== false) {
                        $kategoria_tipus = 'belteri';
                        $kategoria_nev = 'Egyéb (beltéri)';
                    } elseif (strpos($szoveg, 'kültéri') !== false || strpos($szoveg, 'kulteri') !== false || 
                             strpos($szoveg, 'kert') !== false || strpos($szoveg, 'udvar') !== false) {
                        $kategoria_tipus = 'kulteri';
                        $kategoria_nev = 'Egyéb (kültéri)';
                    }
                    
                    if (!isset($kategorizalt_munkak[$kategoria_tipus][$kategoria_nev])) {
                        $kategorizalt_munkak[$kategoria_tipus][$kategoria_nev] = [];
                    }
                    $kategorizalt_munkak[$kategoria_tipus][$kategoria_nev][] = $munka;
                }
            }
            
            // DEBUG: Nézzük meg, hogy mi került a kategorizált munkákba
            error_log("Összes munka: " . count($munkak));
            error_log("Beltéri kategóriák: " . count($kategorizalt_munkak['belteri']));
            error_log("Kültéri kategóriák: " . count($kategorizalt_munkak['kulteri']));
            
            foreach ($kategorizalt_munkak['belteri'] as $kategoria => $munkak_lista) {
                error_log("Beltéri kategória '$kategoria': " . count($munkak_lista) . " munka");
            }
            foreach ($kategorizalt_munkak['kulteri'] as $kategoria => $munkak_lista) {
                error_log("Kültéri kategória '$kategoria': " . count($munkak_lista) . " munka");
            }
            
            // HTML KIMENET
            ?>
            <style>
                /* ==========================================================================
                   VILLÁM MELÓ STÍLUSOK - SMOOTH ABOUT STÍLUSHÓZ HASONLÓ
                   ========================================================================== */

                :root {
                    --primary-bg: #f8f9fa;
                    --secondary-bg: #ffffff;
                    --tertiary-bg: #e9ecef;
                    --primary-text: #333333;
                    --secondary-text: #666666;
                    --accent-color: #667eea;
                    --accent-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    --border-color: #dee2e6;
                    --card-bg: #ffffff;
                    --header-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    --footer-bg: #333333;
                    --error-bg: #f8d7da;
                    --error-text: #721c24;
                    --success-bg: #d1edff;
                    --success-text: #0c5460;
                    --shadow-color: rgba(0, 0, 0, 0.1);
                    --button-bg: #667eea;
                    --button-hover-bg: #5a6fd8;
                    --button-text: white;
                    --button-border: #667eea;
                    --secondary-button-bg: #6c757d;
                    --secondary-button-hover-bg: #5a6268;
                    --job-price-bg: #27ae60;
                    --job-price-text: white;
                    --job-card-hover-shadow: 0 5px 15px rgba(0,0,0,0.1);
                    --category-header-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    --smooth-timing: cubic-bezier(0.25, 0.46, 0.45, 0.94);
                }

                [data-theme="dark"] {
                    --primary-bg: #1a1a1a;
                    --secondary-bg: #2d3748;
                    --tertiary-bg: #4a5568;
                    --primary-text: #e2e8f0;
                    --secondary-text: #cbd5e0;
                    --accent-color: #90cdf4;
                    --accent-gradient: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
                    --border-color: #4a5568;
                    --card-bg: #2d3748;
                    --header-bg: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
                    --footer-bg: #2d3748;
                    --error-bg: #742a2a;
                    --error-text: #fed7d7;
                    --success-bg: #276749;
                    --success-text: #c6f6d5;
                    --shadow-color: rgba(0, 0, 0, 0.3);
                    --button-bg: #4a5568;
                    --button-hover-bg: #51607a;
                    --button-text: #e2e8f0;
                    --button-border: #4a5568;
                    --secondary-button-bg: #718096;
                    --secondary-button-hover-bg: #4a5568;
                    --job-price-bg: #2ecc71;
                    --job-price-text: white;
                    --job-card-hover-shadow: 0 5px 15px rgba(0,0,0,0.4);
                    --category-header-bg: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
                }

                /* ANIMÁCIÓK */
                @keyframes fadeInUp {
                    from {
                        opacity: 0;
                        transform: translateY(20px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                @keyframes fadeIn {
                    from {
                        opacity: 0;
                    }
                    to {
                        opacity: 1;
                    }
                }

                @keyframes slideInUp {
                    from {
                        transform: translateY(30px);
                        opacity: 0;
                    }
                    to {
                        transform: translateY(0);
                        opacity: 1;
                    }
                }

                @keyframes scaleIn {
                    from {
                        transform: scale(0.95);
                        opacity: 0;
                    }
                    to {
                        transform: scale(1);
                        opacity: 1;
                    }
                }

                /* ALAP STÍLUSOK */
                .villam-melo-container * {
                    box-sizing: border-box;
                    margin: 0;
                    padding: 0;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                }

                .villam-melo-container {
                    background-color: var(--primary-bg);
                    color: var(--primary-text);
                    line-height: 1.6;
                    padding: 30px 20px;
                    max-width: 1200px;
                    margin: 0 auto;
                    animation: fadeInUp 0.8s var(--smooth-timing);
                }

                .villam-header {
                    text-align: center;
                    margin-bottom: 40px;
                    padding-bottom: 30px;
                    border-bottom: 2px solid var(--accent-color);
                    animation: fadeInUp 0.7s var(--smooth-timing) 0.1s both;
                }

                .villam-header h1 {
                    color: var(--primary-text);
                    margin-bottom: 15px;
                    font-size: 2.5rem;
                    font-weight: 700;
                    animation: fadeInUp 0.7s var(--smooth-timing) 0.2s both;
                }

                .villam-header h1 i {
                    color: #ffc107;
                    margin-right: 10px;
                }

                .subtitle {
                    color: var(--secondary-text);
                    font-size: 1.1rem;
                    margin-bottom: 25px;
                    animation: fadeInUp 0.7s var(--smooth-timing) 0.3s both;
                }

                /* STATISZTIKA */
                .stats {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
                    gap: 20px;
                    margin-top: 25px;
                    max-width: 400px;
                    margin-left: auto;
                    margin-right: auto;
                    animation: fadeInUp 0.7s var(--smooth-timing) 0.4s both;
                }

                .stat-item {
                    background-color: var(--card-bg);
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 2px 10px var(--shadow-color);
                    text-align: center;
                    transition: all 0.3s var(--smooth-timing);
                    border: 1px solid var(--border-color);
                    animation: scaleIn 0.7s var(--smooth-timing) 0.5s both;
                }

                .stat-item:nth-child(1) { animation-delay: 0.3s; }
                .stat-item:nth-child(2) { animation-delay: 0.4s; }

                .stat-item:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 4px 15px var(--shadow-color);
                }

                .stat-number {
                    font-size: 1.5rem;
                    font-weight: bold;
                    color: var(--accent-color);
                    margin-bottom: 8px;
                    display: block;
                }

                .stat-label {
                    color: var(--secondary-text);
                    font-size: 0.9rem;
                }

                /* KATEGÓRIA CSOPORTOK */
                .category-group {
                    margin-bottom: 30px;
                    background-color: var(--card-bg);
                    border-radius: 8px;
                    overflow: hidden;
                    box-shadow: 0 2px 10px var(--shadow-color);
                    transition: all 0.3s var(--smooth-timing);
                    border: 1px solid var(--border-color);
                    animation: fadeInUp 0.7s var(--smooth-timing) 0.5s both;
                }

                .category-group:nth-child(1) { animation-delay: 0.5s; }
                .category-group:nth-child(2) { animation-delay: 0.6s; }
                .category-group:nth-child(3) { animation-delay: 0.7s; }

                .category-group:hover {
                    box-shadow: 0 4px 20px var(--shadow-color);
                }

                .category-header {
                    background: var(--category-header-bg);
                    color: white;
                    padding: 20px 25px;
                    font-size: 1.3rem;
                    font-weight: 600;
                    cursor: pointer;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    transition: all 0.3s var(--smooth-timing);
                    user-select: none;
                }

                .category-header i {
                    transition: transform 0.3s var(--smooth-timing);
                }

                .category-header.collapsed i {
                    transform: rotate(-90deg);
                }

                .subcategory-wrapper {
                    padding: 0;
                    max-height: 0;
                    overflow: hidden;
                    transition: max-height 0.4s var(--smooth-timing), padding 0.3s var(--smooth-timing);
                }

                .subcategory-wrapper.expanded {
                    max-height: 5000px;
                    padding: 10px 0;
                }

                .subcategory {
                    padding: 25px;
                    border-bottom: 1px solid var(--border-color);
                    animation: fadeIn 0.5s var(--smooth-timing) 0.1s both;
                }

                .subcategory:nth-child(1) { animation-delay: 0.2s; }
                .subcategory:nth-child(2) { animation-delay: 0.3s; }
                .subcategory:nth-child(3) { animation-delay: 0.4s; }
                .subcategory:nth-child(4) { animation-delay: 0.5s; }
                .subcategory:nth-child(5) { animation-delay: 0.6s; }

                .subcategory-title {
                    color: var(--primary-text);
                    font-size: 1.2rem;
                    margin-bottom: 20px;
                    padding-bottom: 10px;
                    border-bottom: 2px solid var(--border-color);
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    animation: slideInUp 0.6s var(--smooth-timing) 0.3s both;
                }

                .job-count {
                    background: var(--accent-color);
                    color: var(--button-text);
                    padding: 4px 12px;
                    border-radius: 15px;
                    font-size: 0.9rem;
                    font-weight: 500;
                }

                /* MUNKÁK GRID */
                .jobs-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
                    gap: 20px;
                    margin-top: 20px;
                }

                .job-card {
                    background-color: var(--card-bg);
                    border-radius: 8px;
                    padding: 20px;
                    box-shadow: 0 2px 10px var(--shadow-color);
                    transition: all 0.3s var(--smooth-timing);
                    border: 1px solid var(--border-color);
                    cursor: pointer;
                    position: relative;
                    display: flex;
                    flex-direction: column;
                    animation: fadeInUp 0.7s var(--smooth-timing) forwards;
                    opacity: 0;
                }

                .jobs-grid .job-card:nth-child(1) { animation-delay: 0.2s; }
                .jobs-grid .job-card:nth-child(2) { animation-delay: 0.3s; }
                .jobs-grid .job-card:nth-child(3) { animation-delay: 0.4s; }

                .job-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 5px 20px var(--shadow-color);
                    border-color: var(--accent-color);
                }

                .job-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    margin-bottom: 15px;
                }

                .job-title {
                    color: var(--primary-text);
                    font-size: 1.2rem;
                    font-weight: 600;
                    margin-bottom: 10px;
                    line-height: 1.3;
                    flex: 1;
                }

                .job-price {
                    background-color: var(--job-price-bg);
                    color: var(--job-price-text);
                    padding: 6px 14px;
                    border-radius: 15px;
                    font-weight: 600;
                    font-size: 1.1rem;
                    white-space: nowrap;
                    margin-left: 10px;
                }

                .job-provider {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    margin-bottom: 15px;
                    color: var(--accent-color);
                    font-weight: 500;
                }

                .provider-avatar {
                    width: 50px;
                    height: 50px;
                    border-radius: 50%;
                    overflow: hidden;
                    border: 2px solid var(--accent-color);
                    background-color: var(--tertiary-bg);
                    flex-shrink: 0;
                }

                .provider-avatar img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    transition: transform 0.3s var(--smooth-timing);
                }

                .job-card:hover .provider-avatar img {
                    transform: scale(1.05);
                }

                /* REFERENCIA KÉPEK */
                .reference-images {
                    margin-bottom: 15px;
                    width: 100%;
                    position: relative;
                    min-height: 180px;
                }

                .reference-images-container {
                    display: grid;
                    grid-template-columns: 1fr;
                    gap: 10px;
                    width: 100%;
                }

                .reference-image {
                    width: 100%;
                    height: 180px;
                    border-radius: 6px;
                    overflow: hidden;
                    border: 2px solid var(--border-color);
                    background-color: var(--tertiary-bg);
                    position: relative;
                    transition: all 0.3s var(--smooth-timing);
                    cursor: pointer;
                }

                .reference-image:hover {
                    border-color: var(--accent-color);
                    transform: translateY(-3px);
                    box-shadow: 0 4px 12px var(--shadow-color);
                }

                .reference-image img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    transition: transform 0.3s var(--smooth-timing);
                }

                .reference-image:hover img {
                    transform: scale(1.05);
                }

                .reference-images.multiple-images .reference-images-container {
                    grid-template-columns: repeat(2, 1fr);
                }

                .reference-images.multiple-images .reference-image {
                    height: 140px;
                }

                .no-images {
                    color: var(--secondary-text);
                    font-style: italic;
                    font-size: 0.95rem;
                    padding: 40px 20px;
                    background-color: var(--tertiary-bg);
                    border-radius: 6px;
                    width: 100%;
                    text-align: center;
                    height: 180px;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                }

                .no-images i {
                    font-size: 2rem;
                    margin-bottom: 10px;
                    color: var(--border-color);
                }

                .job-description {
                    color: var(--secondary-text);
                    margin-bottom: 15px;
                    line-height: 1.5;
                    font-size: 0.95rem;
                    flex: 1;
                }

                .job-meta {
                    display: flex;
                    flex-direction: column;
                    color: var(--secondary-text);
                    font-size: 0.9rem;
                    margin-top: 15px;
                    padding-top: 15px;
                    border-top: 1px solid var(--border-color);
                    gap: 8px;
                }

                .job-meta > div {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    width: 100%;
                }

                .job-meta i {
                    color: var(--accent-color);
                    min-width: 16px;
                }

                /* GOMBOK */
                .show-more-container {
                    text-align: center;
                    padding: 20px;
                    margin-top: 20px;
                    animation: fadeInUp 0.7s var(--smooth-timing) 0.6s both;
                }

                .show-more-btn {
                    background-color: var(--button-bg);
                    color: var(--button-text);
                    border: none;
                    padding: 12px 28px;
                    border-radius: 6px;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.3s var(--smooth-timing);
                    font-size: 1rem;
                }

                .show-more-btn:hover {
                    background-color: var(--button-hover-bg);
                    transform: translateY(-2px);
                    box-shadow: 0 4px 12px var(--shadow-color);
                }

                /* ÜRES KATEGÓRIA */
                .empty-category {
                    text-align: center;
                    padding: 40px 20px;
                    color: var(--secondary-text);
                    font-style: italic;
                    background-color: var(--tertiary-bg);
                    border-radius: 8px;
                    margin: 20px 0;
                    animation: fadeInUp 0.7s var(--smooth-timing) 0.4s both;
                }

                .empty-category i {
                    font-size: 2.5rem;
                    margin-bottom: 15px;
                    color: var(--border-color);
                }

                /* KATEGÓRIA CÍMEK */
                .category-header {
                    font-size: 1.3rem;
                    font-weight: 600;
                }

                .category-header span {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }

                /* ANIMÁCIÓK A JOB CARDS-NÁL */
                @keyframes cardAppear {
                    0% {
                        opacity: 0;
                        transform: translateY(15px);
                    }
                    100% {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .job-card {
                    animation: cardAppear 0.5s var(--smooth-timing) forwards;
                    opacity: 0;
                }

                /* RESZPONZÍV STÍLUSOK */
                @media (max-width: 768px) {
                    .villam-melo-container {
                        padding: 20px 15px;
                    }
                    
                    .villam-header h1 {
                        font-size: 2rem;
                    }
                    
                    .jobs-grid {
                        grid-template-columns: 1fr;
                    }
                    
                    .stats {
                        grid-template-columns: repeat(2, 1fr);
                        max-width: 100%;
                    }
                    
                    .category-header {
                        font-size: 1.1rem;
                        padding: 15px 20px;
                    }
                    
                    .job-card {
                        padding: 15px;
                    }
                    
                    .reference-image {
                        height: 160px;
                    }
                    
                    .reference-images.multiple-images .reference-images-container {
                        grid-template-columns: 1fr;
                    }
                    
                    .reference-images.multiple-images .reference-image {
                        height: 120px;
                    }
                    
                    .subcategory {
                        padding: 20px;
                    }
                }

                @media (max-width: 480px) {
                    .villam-header h1 {
                        font-size: 1.8rem;
                    }
                    
                    .subtitle {
                        font-size: 1rem;
                    }
                    
                    .job-title {
                        font-size: 1.1rem;
                    }
                    
                    .job-price {
                        font-size: 1rem;
                        padding: 5px 10px;
                    }
                    
                    .provider-avatar {
                        width: 45px;
                        height: 45px;
                    }
                    
                    .reference-image {
                        height: 140px;
                    }
                }

                /* KÜLÖNLEGES ANIMÁCIÓ AZ ÚJ MUNKÁKHOZ */
                @keyframes newCardSlide {
                    0% {
                        opacity: 0;
                        transform: translateY(20px) scale(0.95);
                    }
                    70% {
                        opacity: 0.8;
                        transform: translateY(-5px) scale(1.02);
                    }
                    100% {
                        opacity: 1;
                        transform: translateY(0) scale(1);
                    }
                }

                /* SIMPLE FADE FOR STATS */
                .stat-item {
                    opacity: 0;
                    animation: fadeIn 0.5s var(--smooth-timing) forwards;
                }

                .stat-item:nth-child(1) { animation-delay: 0.3s; }
                .stat-item:nth-child(2) { animation-delay: 0.4s; }
            </style>

            <!-- TARTALOM -->
            <div class="villam-melo-container">
                <div class="container">
                    <div class="villam-header">
                        <h1><i class="fas fa-bolt"></i> Villám Meló</h1>
                        <p class="subtitle">Gyors, helyi munkalehetőségek</p>
                        
                        <div class="stats">
                            <div class="stat-item">
                                <div class="stat-number"><?php echo count($munkak); ?></div>
                                <div class="stat-label">Aktív munka</div>
                            </div>
                            <div class="stat-item">
                                <?php 
                                $osszes_alkategoria = 0;
                                foreach ($kategorizalt_munkak as $alkategoriak) {
                                    $osszes_alkategoria += count($alkategoriak);
                                }
                                ?>
                                <div class="stat-number"><?php echo $osszes_alkategoria; ?></div>
                                <div class="stat-label">Alkategória</div>
                            </div>
                        </div>
                    </div>
                    
                    <main class="villam-main">
                        <?php 
                        // DEBUG kiírás
                        echo '<!-- DEBUG: Összes munka = ' . count($munkak) . ' -->';
                        echo '<!-- DEBUG: Beltéri kategóriák = ' . count($kategorizalt_munkak['belteri']) . ' -->';
                        echo '<!-- DEBUG: Kültéri kategóriák = ' . count($kategorizalt_munkak['kulteri']) . ' -->';
                        
                        if (empty($munkak)): ?>
                            <div class="empty-category">
                                <i class="fas fa-inbox"></i>
                                <h3>Jelenleg nincsenek elérhető munkák</h3>
                                <p>Legyél te az első, aki felad egy munkát!</p>
                            </div>
                        <?php else: ?>
                            <?php 
                            $kategoria_cimek = [
                                'kulteri' => '🌳 Kültéri munkák',
                                'belteri' => '🏠 Beltéri munkák', 
                                'egyeb' => '📦 Egyéb munkák'
                            ];
                            
                            foreach ($kategorizalt_munkak as $kategoria_tipus => $alkategoriak):
                                if (empty($alkategoriak)) continue;
                                
                                echo '<!-- DEBUG: Kategória ' . $kategoria_tipus . ' = ' . count($alkategoriak) . ' alkategória -->';
                            ?>
                                <div class="category-group">
                                    <div class="category-header collapsed" onclick="toggleCategory(this)">
                                        <span><?php echo $kategoria_cimek[$kategoria_tipus] ?? ucfirst($kategoria_tipus); ?></span>
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                    
                                    <div class="subcategory-wrapper">
                                        <?php 
                                        $subcategory_index = 0;
                                        foreach ($alkategoriak as $alkategoria_nev => $alkategoria_munkak): 
                                            $subcategory_index++;
                                        ?>
                                            <?php 
                                            echo '<!-- DEBUG: Alkategória ' . $alkategoria_nev . ' = ' . count($alkategoria_munkak) . ' munka -->';
                                            ?>
                                            <div class="subcategory" style="animation-delay: <?php echo $subcategory_index * 0.1; ?>s">
                                                <h3 class="subcategory-title">
                                                    <?php echo htmlspecialchars($alkategoria_nev); ?>
                                                    <span class="job-count">
                                                        <?php echo count($alkategoria_munkak); ?> munka
                                                    </span>
                                                </h3>
                                                
                                                <?php if (!empty($alkategoria_munkak)): ?>
                                                    <?php 
                                                    $lathato_limit = 3;
                                                    $osszes_munka = count($alkategoria_munkak);
                                                    $mutatott_munkak = array_slice($alkategoria_munkak, 0, $lathato_limit);
                                                    ?>
                                                    
                                                    <div class="jobs-grid">
                                                        <?php 
                                                        $job_index = 0;
                                                        foreach ($mutatott_munkak as $munka): 
                                                            $job_index++;
                                                        ?>
                                                            <div class="job-card" onclick="openJobDetails(<?php echo $munka['id']; ?>)" 
                                                                 style="animation-delay: <?php echo $subcategory_index * 0.1 + $job_index * 0.1; ?>s">
                                                                <div class="job-header">
                                                                    <h3 class="job-title"><?php echo htmlspecialchars($munka['munka_nev']); ?></h3>
                                                                    <div class="job-price"><?php echo number_format($munka['ar'], 0, ',', ' '); ?> Ft</div>
                                                                </div>
                                                                
                                                                <div class="job-provider">
                                                                    <div class="provider-avatar">
                                                                        <img src="<?php echo htmlspecialchars($munka['profilkep_url'] ?? ''); ?>" 
                                                                             alt="<?php echo htmlspecialchars($munka['fnev'] ?? 'Felhasználó'); ?>"
                                                                             onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($munka['fnev'] ?? 'F'); ?>&size=100&background=007bff&color=fff'">
                                                                    </div>
                                                                    <div>
                                                                        <!-- MÓDOSÍTÁS: felhasználónév jelenik meg főként -->
                                                                        <div style="font-weight: 600; margin-bottom: 2px;">
                                                                            <?php echo htmlspecialchars($munka['fnev'] ?? 'Ismeretlen'); ?>
                                                                        </div>
                                                                        <!-- MÓDOSÍTÁS: teljes név jelenik meg alatta szürkével, ha van -->
                                                                        <?php if (!empty($munka['vnev']) && !empty($munka['knev'])): ?>
                                                                            <div style="font-size: 0.85em; color: var(--secondary-text);">
                                                                                <?php echo htmlspecialchars($munka['vnev'] . ' ' . $munka['knev']); ?>
                                                                            </div>
                                                                        <?php elseif (!empty($munka['varmegye'])): ?>
                                                                            <div style="font-size: 0.85em; color: var(--secondary-text);">
                                                                                <i class="fas fa-map-marker-alt"></i> 
                                                                                <?php echo htmlspecialchars($munka['varmegye']); ?>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                                
                                                                <!-- REFERENCIA KÉPEK -->
                                                                <div class="reference-images <?php echo count($munka['referencia_kepek']) > 1 ? 'multiple-images' : ''; ?>">
                                                                    <?php if (!empty($munka['referencia_kepek'])): ?>
                                                                        <div class="reference-images-container">
                                                                        <?php foreach ($munka['referencia_kepek'] as $index => $kep): ?>
                                                                            <div class="reference-image" onclick="event.stopPropagation(); openImageModal('<?php echo htmlspecialchars($kep); ?>')">
                                                                                <img src="<?php echo htmlspecialchars($kep); ?>" 
                                                                                     alt="Referencia kép"
                                                                                     onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1581578731548-c64695cc6952?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'">
                                                                            </div>
                                                                        <?php endforeach; ?>
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <div class="no-images">
                                                                            <i class="fas fa-image"></i>
                                                                            <span>Nincsenek referencia képek</span>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                                
                                                                <?php if (!empty($munka['munka_leiras'])): ?>
                                                                    <div class="job-description">
                                                                        <?php 
                                                                        $leiras = htmlspecialchars($munka['munka_leiras']);
                                                                        echo strlen($leiras) > 150 ? substr($leiras, 0, 150) . '...' : $leiras;
                                                                        ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                                
                                                                <div class="job-meta">
                                                                    <div>
                                                                        <i class="far fa-calendar-alt"></i>
                                                                        <?php echo date('Y.m.d.', strtotime($munka['datum_ido'])); ?>
                                                                    </div>
                                                                    <?php if (!empty($munka['telefonszam'])): ?>
                                                                    <div onclick="copyToClipboard(event, '<?php echo htmlspecialchars($munka['telefonszam']); ?>', 'telefon')" style="cursor: pointer;">
                                                                        <i class="fas fa-phone"></i>
                                                                        <?php echo htmlspecialchars($munka['telefonszam']); ?>
                                                                    </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    
                                                    <?php if ($osszes_munka > $lathato_limit): ?>
                                                        <?php 
                                                        $tovabbi_munkak = array_slice($alkategoria_munkak, $lathato_limit);
                                                        $tovabbi_munkak_id = md5($alkategoria_nev);
                                                        ?>
                                                        
                                                        <div class="show-more-container">
                                                            <button class="show-more-btn" onclick="showMoreJobs('<?php echo $tovabbi_munkak_id; ?>')">
                                                                További <?php echo count($tovabbi_munkak); ?> munka megjelenítése
                                                                <i class="fas fa-arrow-down"></i>
                                                            </button>
                                                        </div>
                                                        
                                                        <!-- További munkák (rejtve) -->
                                                        <div id="more-jobs-<?php echo $tovabbi_munkak_id; ?>" class="jobs-grid" style="display: none; margin-top: 20px;">
                                                            <?php 
                                                            $additional_index = 0;
                                                            foreach ($tovabbi_munkak as $munka): 
                                                                $additional_index++;
                                                            ?>
                                                                <div class="job-card" onclick="openJobDetails(<?php echo $munka['id']; ?>)" 
                                                                     style="animation-delay: <?php echo $additional_index * 0.1; ?>s">
                                                                    <div class="job-header">
                                                                        <h3 class="job-title"><?php echo htmlspecialchars($munka['munka_nev']); ?></h3>
                                                                        <div class="job-price"><?php echo number_format($munka['ar'], 0, ',', ' '); ?> Ft</div>
                                                                    </div>
                                                                    
                                                                    <div class="job-provider">
                                                                        <div class="provider-avatar">
                                                                            <img src="<?php echo htmlspecialchars($munka['profilkep_url'] ?? ''); ?>" 
                                                                                 alt="<?php echo htmlspecialchars($munka['fnev'] ?? 'Felhasználó'); ?>"
                                                                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($munka['fnev'] ?? 'F'); ?>&size=100&background=007bff&color=fff'">
                                                                        </div>
                                                                        <div>
                                                                            <!-- MÓDOSÍTÁS: felhasználónév jelenik meg főként -->
                                                                            <div style="font-weight: 600; margin-bottom: 2px;">
                                                                                <?php echo htmlspecialchars($munka['fnev'] ?? 'Ismeretlen'); ?>
                                                                            </div>
                                                                            <!-- MÓDOSÍTÁS: teljes név jelenik meg alatta szürkével, ha van -->
                                                                            <?php if (!empty($munka['vnev']) && !empty($munka['knev'])): ?>
                                                                                <div style="font-size: 0.85em; color: var(--secondary-text);">
                                                                                    <?php echo htmlspecialchars($munka['vnev'] . ' ' . $munka['knev']); ?>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <!-- REFERENCIA KÉPEK -->
                                                                    <div class="reference-images <?php echo count($munka['referencia_kepek']) > 1 ? 'multiple-images' : ''; ?>">
                                                                        <?php if (!empty($munka['referencia_kepek'])): ?>
                                                                            <div class="reference-images-container">
                                                                            <?php foreach ($munka['referencia_kepek'] as $kep): ?>
                                                                                <div class="reference-image" onclick="event.stopPropagation(); openImageModal('<?php echo htmlspecialchars($kep); ?>')">
                                                                                    <img src="<?php echo htmlspecialchars($kep); ?>" 
                                                                                         alt="Referencia kép"
                                                                                         onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1581578731548-c64695cc6952?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'">
                                                                                </div>
                                                                            <?php endforeach; ?>
                                                                            </div>
                                                                        <?php else: ?>
                                                                            <div class="no-images">
                                                                                <i class="fas fa-image"></i>
                                                                                <span>Nincsenek referencia képek</span>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    
                                                                    <?php if (!empty($munka['munka_leiras'])): ?>
                                                                        <div class="job-description">
                                                                            <?php 
                                                                            $leiras = htmlspecialchars($munka['munka_leiras']);
                                                                            echo strlen($leiras) > 100 ? substr($leiras, 0, 100) . '...' : $leiras;
                                                                            ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    
                                                                    <div class="job-meta">
                                                                        <div>
                                                                            <i class="far fa-calendar-alt"></i>
                                                                            <?php echo date('Y.m.d.', strtotime($munka['datum_ido'])); ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <div class="empty-category">
                                                        <i class="fas fa-clipboard-list"></i>
                                                        <p>Erre a kategóriára még nem töltöttek fel munkát.</p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </main>
                </div>
            </div>

            <!-- JAVASCRIPT -->
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Alap animációk aktiválása
                setTimeout(() => {
                    document.querySelector('.villam-melo-container').style.opacity = '1';
                }, 100);
                
                // Kép hiba kezelés
                const profileImages = document.querySelectorAll('.provider-avatar img');
                profileImages.forEach(img => {
                    img.addEventListener('error', function() {
                        const name = this.alt || 'F';
                        this.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&size=100&background=007bff&color=fff`;
                    });
                });
                
                const referenceImages = document.querySelectorAll('.reference-image img');
                referenceImages.forEach(img => {
                    img.addEventListener('error', function() {
                        this.src = 'https://images.unsplash.com/photo-1581578731548-c64695cc6952?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80';
                        this.alt = 'Alapértelmezett kép';
                    });
                });
                
                // Kategória fejlécek animációja
                const categoryHeaders = document.querySelectorAll('.category-header.collapsed');
                categoryHeaders.forEach(header => {
                    const icon = header.querySelector('i');
                    if (icon) {
                        icon.style.transform = 'rotate(-90deg)';
                    }
                });
                
                // Smooth scroll animáció a lapon belül
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                };
                
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.animationPlayState = 'running';
                            observer.unobserve(entry.target);
                        }
                    });
                }, observerOptions);
                
                // Obszerváljuk az animált elemeket
                document.querySelectorAll('.job-card, .category-group, .stat-item, .subcategory').forEach(el => {
                    observer.observe(el);
                });
            });
            
            function toggleCategory(headerElement) {
                const wrapper = headerElement.nextElementSibling;
                headerElement.classList.toggle('collapsed');
                wrapper.classList.toggle('expanded');
                
                const icon = headerElement.querySelector('i');
                if (headerElement.classList.contains('collapsed')) {
                    icon.style.transform = 'rotate(-90deg)';
                } else {
                    icon.style.transform = 'rotate(0deg)';
                }
                
                // Animáljuk a kibontást/becsukást
                if (wrapper.classList.contains('expanded')) {
                    const subcategories = wrapper.querySelectorAll('.subcategory');
                    subcategories.forEach((sub, index) => {
                        setTimeout(() => {
                            sub.style.opacity = '1';
                            sub.style.transform = 'translateX(0)';
                        }, index * 100);
                    });
                }
            }
            
            function showMoreJobs(kategoriId) {
                const moreJobsContainer = document.getElementById('more-jobs-' + kategoriId);
                const buttonContainer = event.target.closest('.show-more-container');
                
                if (moreJobsContainer && moreJobsContainer.style.display === 'none') {
                    // Animált megjelenítés
                    moreJobsContainer.style.display = 'grid';
                    moreJobsContainer.style.opacity = '0';
                    moreJobsContainer.style.transform = 'translateY(20px)';
                    
                    // Animáció aktiválása
                    setTimeout(() => {
                        moreJobsContainer.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                        moreJobsContainer.style.opacity = '1';
                        moreJobsContainer.style.transform = 'translateY(0)';
                        
                        // Gomb eltűntetése
                        buttonContainer.style.opacity = '0';
                        buttonContainer.style.transform = 'translateY(-10px)';
                        setTimeout(() => {
                            buttonContainer.style.display = 'none';
                        }, 300);
                        
                        // Smooth scroll a megjelenített elemekhez
                        setTimeout(() => {
                            moreJobsContainer.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }, 400);
                    }, 50);
                    
                    // Az új kártyák animációjának aktiválása
                    const jobCards = moreJobsContainer.querySelectorAll('.job-card');
                    jobCards.forEach((card, index) => {
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0) scale(1)';
                        }, index * 100 + 200);
                    });
                }
            }
            
            function openJobDetails(jobId) {
                // Klikk animáció
                const card = event.currentTarget;
                card.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    card.style.transform = '';
                }, 150);
                
                setTimeout(() => {
                    window.location.href = '/work?job_id=' + jobId;
                }, 200);
            }
            
            function copyToClipboard(e, text, type) {
                e.stopPropagation();
                navigator.clipboard.writeText(text).then(() => {
                    const originalText = e.target.textContent || e.target.innerText;
                    const originalHTML = e.target.innerHTML;
                    
                    // Sikeres animáció
                    e.target.style.color = 'var(--accent-color)';
                    e.target.style.transform = 'scale(1.05)';
                    
                    // Ikon animáció
                    const icon = e.target.querySelector('i');
                    if (icon) {
                        icon.style.transform = 'rotate(360deg) scale(1.2)';
                        icon.style.color = '#27ae60';
                    }
                    
                    // Visszaállítás
                    setTimeout(() => {
                        e.target.style.color = '';
                        e.target.style.transform = '';
                        if (icon) {
                            icon.style.transform = '';
                            icon.style.color = '';
                        }
                    }, 1000);
                });
            }
            
            function openImageModal(imageUrl) {
                // Létrehozunk egy modal-t a képek számára
                const modal = document.createElement('div');
                modal.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0,0,0,0.9);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 9999;
                    opacity: 0;
                    transition: opacity 0.3s;
                `;
                
                const img = document.createElement('img');
                img.src = imageUrl;
                img.style.cssText = `
                    max-width: 90%;
                    max-height: 90%;
                    border-radius: 10px;
                    transform: scale(0.8);
                    transition: transform 0.3s;
                `;
                
                modal.appendChild(img);
                document.body.appendChild(modal);
                
                // Animált megjelenítés
                setTimeout(() => {
                    modal.style.opacity = '1';
                    img.style.transform = 'scale(1)';
                }, 10);
                
                // Klikkelésre bezárás
                modal.addEventListener('click', function(e) {
                    if (e.target === modal || e.target === img) {
                        modal.style.opacity = '0';
                        img.style.transform = 'scale(0.8)';
                        setTimeout(() => {
                            document.body.removeChild(modal);
                        }, 300);
                    }
                });
                
                // ESC billentyűvel is bezárható
                document.addEventListener('keydown', function escHandler(e) {
                    if (e.key === 'Escape') {
                        modal.style.opacity = '0';
                        img.style.transform = 'scale(0.8)';
                        setTimeout(() => {
                            document.body.removeChild(modal);
                            document.removeEventListener('keydown', escHandler);
                        }, 300);
                    }
                });
            }
            </script>
            <?php
            
        } else {
            echo "<div style='padding: 20px; color: red;'>Lekérdezési hiba</div>";
        }
        
    } catch (PDOException $e) {
        echo "<div style='padding: 20px; color: red;'>PDO hiba: " . $e->getMessage() . "</div>";
    }
    
} else {
    echo "<div style='padding: 20px; color: red;'>Nincs adatbázis kapcsolat!</div>";
}
?>