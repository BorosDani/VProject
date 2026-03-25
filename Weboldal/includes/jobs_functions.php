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

    function getJobCategory($category){
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

    }

    function getWorkData($id){
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM munkak WHERE id = :id");
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getAccountData($id){
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM felhasznalok WHERE fid = :id");
        $stmt->execute([":id" => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function checkLoginStatus(){
        if (isset($_SESSION["fnev"]) && !empty($_SESSION["fnev"])) {
            return true;
        }
        return false;
    }

    function checkApplication($work){
        $account = $_SESSION["fid"] ?? 0;
        global $conn;

        $stmt = $conn->prepare("
            SELECT COUNT(*) 
            FROM jelentkezesek 
            WHERE munka_id = :work 
            AND felhasznalo_id = :account
        ");
        
        $stmt->execute([
            ":work" => $work,
            ":account" => $account
        ]);

        return $stmt->fetchColumn() > 0;
    }

    function applyForJob($jid, $uid)
    {
        global $conn;
        try {
            $check_sql = "SELECT COUNT(*) FROM jelentkezesek WHERE munka_id = ? AND felhasznalo_id = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->execute([$jid, $uid]);

            if ($check_stmt->fetchColumn() > 0) {
                $_SESSION['error'] = "Már korábban jelentkeztél erre a munkára!";
                return false;
            }

            $insert_sql = "INSERT INTO jelentkezesek (munka_id, felhasznalo_id, idopont) VALUES (?, ?, NOW())";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->execute([$jid, $uid]);

            $update_sql = "UPDATE munkak SET statusz = 'folyamatban' WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->execute([$jid]);

            sendApplicationEmail($conn, $jid, $uid);

            $_SESSION['success'] = "Sikeresen jelentkeztél! A hirdetőt emailben értesítettük.";
            return true;

        } catch (Exception $e) {
            $_SESSION['error'] = "Hiba történt: " . $e->getMessage();
            return false;
        }
    }

    function sendApplicationEmail($jid, $uid)
    {
        try {

            $work = getWorkData($jid);
            if (!$work) {
                return;
            }

            $owner = getAccountData($work['felhasznalo_id']);
            if (!$owner || empty($owner['email'])) {
                return;
            }

            $applicant = getAccountData($uid);
            if (!$applicant) {
                return;
            }

            $to = $owner['email'];
            $subject = "Új jelentkezés a munkádra: " . $work['munka_nev'];

            $ownerName = htmlspecialchars($owner['fnev']);
            $workName = htmlspecialchars($work['munka_nev']);
            $applicantName = htmlspecialchars($applicant['fnev']);
            $applicantEmail = htmlspecialchars($applicant['email']);
            $applicantPhone = !empty($applicant['telefon']) 
                ? htmlspecialchars($applicant['telefon']) 
                : 'Nincs megadva';

            $message = "
            <html>
            <body style='font-family: Arial, sans-serif;'>
                <h2>Új jelentkezés érkezett!</h2>
                <p>Kedves <strong>{$ownerName}</strong>!</p>
                <p>Új jelentkező érkezett a következő munkára:</p>
                <strong>{$workName}</strong>
                <hr>
                <h3>Jelentkező adatai:</h3>
                <p><strong>Név:</strong> {$applicantName}</p>
                <p><strong>Email:</strong> {$applicantEmail}</p>
                <p><strong>Telefon:</strong> {$applicantPhone}</p>
                <hr>
                <p>Jelentkezés időpontja: " . date('Y.m.d. H:i:s') . "</p>
            </body>
            </html>";

            $headers  = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8\r\n";
            $headers .= "From: Villám Meló <ertesites@villammelo.hu>\r\n";
            $headers .= "Reply-To: {$applicantEmail}\r\n";

            mail($to, $subject, $message, $headers);

        } catch (Exception $e) {
            error_log("Email küldési hiba: " . $e->getMessage());
        }
    }

    function uploadWork($post) {
        $hibak = [];
        $siker = '';

        $felhasznalo_id = $_SESSION['fid'];
        $munka_kategoria = isset($post['munka_kategoria']) ? trim($post['munka_kategoria']) : '';
        $munka_leiras = trim($post['munka_leiras']);
        $ar = floatval($post['ar']);
        $telefonszam = !empty($post['telefonszam']) ? trim($post['telefonszam']) : NULL;
        $email = !empty($post['email']) ? trim($post['email']) : NULL;
        $munka_datum = $post['munka_datum'];
        $munka_ido = $post['munka_ido'];
        
        $kiemelt = isset($post['kiemelt_munka']) ? 1 : 0;
        $fizetve = 0; 
        $aktiv_allapot = ($kiemelt == 1) ? 0 : 1;

        if (empty($munka_kategoria)) $hibak[] = "Kategória kötelező!";
        if (empty($munka_leiras) || strlen($munka_leiras) < 20) $hibak[] = "A leírás túl rövid!";
        if ($ar <= 0) $hibak[] = "Adj meg érvényes árat!";

        if (empty($hibak)) {
            try {
                global $conn;
                $conn->beginTransaction();

                $datum_ido = $munka_datum . ' ' . $munka_ido . ':00';

                $sql = "INSERT INTO munkak 
                        (felhasznalo_id, munka_nev, munka_leiras, ar, telefonszam, email, datum_ido, aktiv, kiemelt, fizetve) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt = $conn->prepare($sql);
                $stmt->execute([
                    $felhasznalo_id,
                    $munka_kategoria,
                    $munka_leiras,
                    $ar,
                    $telefonszam,
                    $email,
                    $datum_ido,
                    $aktiv_allapot,
                    $kiemelt,
                    $fizetve
                ]);

                $munka_id = $conn->lastInsertId();
                $conn->commit();

                if ($kiemelt == 1) {
                    header("Location: " . base_url('fizetes?job_id=' . $munka_id));
                    exit;
                }

                $siker = "Sikeres feltöltés!";

            } catch (PDOException $e) {
                $conn->rollBack();
                $hibak[] = "Hiba: " . $e->getMessage();
            }
        }

        return [
            'hibak' => $hibak,
            'siker' => $siker
        ];
    }

    function getReferenceImageUrls($id){
        global $conn;
        $sql_kepek = "SELECT * FROM referencia_kepek WHERE munka_id = ? ORDER BY sorrend ASC, id ASC";
        $stmt_kepek = $conn->prepare($sql_kepek);
        $stmt_kepek->execute([$id]);
        $kepek = $stmt_kepek->fetchAll();
        $urls = [];
        foreach($kepek as $kep){
            if (filter_var($kep['kep_url'], FILTER_VALIDATE_URL)) {
                $kep_url = $kep['kep_url'];
            } else {
                $kep_url = '/assets/images/referencia_kepek/' . $kep['kep_url'];
            }
            array_push($urls, $kep_url);
        }
        return $urls;
    }
?>