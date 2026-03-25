<?php
function check_admin_access() {
    if (!isset($_SESSION['fid']) || $_SESSION['szerep'] !== 'admin') {
        header('Location: ' . base_url('login'));
        exit;
    }
}

function get_admin_stats() {
    global $conn;
    $stats = [];
    try {
        $sql = "SELECT COUNT(*) as total, 
                       SUM(CASE WHEN statusz = 'Aktiv' THEN 1 ELSE 0 END) as active,
                       SUM(CASE WHEN statusz = 'Fuggoben' THEN 1 ELSE 0 END) as pending,
                       SUM(CASE WHEN statusz = 'Kitiltott' THEN 1 ELSE 0 END) as banned
                FROM felhasznalok";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stats['users'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $sql = "SELECT COUNT(*) as total,
                       COUNT(CASE WHEN DATE(idopont) = CURDATE() THEN 1 END) as today
                FROM admin_tevekenyseg";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stats['admin_activities'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $sql = "SELECT COUNT(*) as total,
                       COUNT(CASE WHEN DATE(idopont) = CURDATE() THEN 1 END) as today,
                       COUNT(CASE WHEN sikeresseg = 'sikertelen' THEN 1 END) as failed
                FROM felhasznalo_tevekenyseg";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stats['user_activities'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $sql = "SELECT COUNT(*) as total,
                       COUNT(CASE WHEN ellenorzve IS NOT NULL THEN 1 END) as verified,
                       COUNT(CASE WHEN ellenorzve IS NULL AND lejarati_ido > NOW() THEN 1 END) as pending
                FROM email_ell";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stats['email_verifications'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $sql = "SELECT COUNT(*) as total,
                       COUNT(CASE WHEN felhasznalva = 1 THEN 1 END) as used,
                       COUNT(CASE WHEN felhasznalva = 0 AND lejarati_ido > NOW() THEN 1 END) as active
                FROM jelszo_visszaallitasok";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stats['password_resets'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $sql = "SELECT COUNT(*) as total,
                       COUNT(CASE WHEN DATE(idopont) = CURDATE() THEN 1 END) as today,
                       AVG(ertekeles) as avg_rating
                FROM megjegyzesek";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stats['comments'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $sql = "SELECT COUNT(*) as total FROM torolt_felhasznalok";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stats['deleted_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        $sql = "SELECT COUNT(*) as total FROM torolt_megjegyzesek";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stats['deleted_comments'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
    } catch (PDOException $e) {
        error_log("Admin stats error: " . $e->getMessage());
        return false;
    }
    return $stats;
}

function format_date($dateString, $format = 'Y.m.d H:i') {
    if (empty($dateString) || $dateString === '0000-00-00 00:00:00' || $dateString === '0000-00-00') {
        return '-';
    }
    $timestamp = strtotime($dateString);
    if ($timestamp === false || $timestamp < 0) {
        return '-';
    }
    return date($format, $timestamp);
}

function get_users($page = 1, $per_page = 15, $search = '', $status = '', $order_by = 'regisztralt', $order_dir = 'DESC') {
    global $conn;
    $offset = ($page - 1) * $per_page;
    $params = [];
    $where = [];
    
    $valid_columns = ['fid', 'fnev', 'email', 'knev', 'vnev', 'szerep', 'statusz', 'regisztralt', 'modositott', 'belepett'];
    $order_by = in_array($order_by, $valid_columns) ? $order_by : 'regisztralt';
    $order_dir = $order_dir === 'ASC' ? 'ASC' : 'DESC';
    
    if (!empty($search)) {
        $where[] = "(fnev LIKE ? OR email LIKE ? OR knev LIKE ? OR vnev LIKE ?)";
        $search_term = "%$search%";
        $params = array_merge($params, [$search_term, $search_term, $search_term, $search_term]);
    }
    if (!empty($status) && in_array($status, ['Aktiv', 'Fuggoben', 'Kitiltott'])) {
        $where[] = "statusz = ?";
        $params[] = $status;
    }
    
    $where_sql = '';
    if (!empty($where)) {
        $where_sql = 'WHERE ' . implode(' AND ', $where);
    }
    
    $count_sql = "SELECT COUNT(*) as total FROM felhasznalok $where_sql";
    $stmt = $conn->prepare($count_sql);
    $stmt->execute($params);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $sql = "SELECT fid, szerep, statusz, nem, email, fnev, knev, vnev, profilkep, 
                   szuletett, telefon, varmegye, regisztralt, modositott, belepett,
                   email_megerositve, statusz_ok, statusz_meddig
            FROM felhasznalok 
            $where_sql 
            ORDER BY $order_by $order_dir 
            LIMIT ? OFFSET ?";
    
    $params[] = $per_page;
    $params[] = $offset;
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return [
        'users' => $users,
        'total' => $total,
        'pages' => ceil($total / $per_page),
        'current_page' => $page,
        'order_by' => $order_by,
        'order_dir' => $order_dir
    ];
}

function get_comments($page = 1, $per_page = 15, $search = '', $order_by = 'idopont', $order_dir = 'DESC') {
    global $conn;
    $offset = ($page - 1) * $per_page;
    $params = [];
    $where = [];
    
    $base_sql = "SELECT m.*, f.fnev, f.email, f.profilkep 
                 FROM megjegyzesek m
                 LEFT JOIN felhasznalok f ON m.fid = f.fid";
    
    if (!empty($search)) {
        $where[] = "(m.megjegyzes LIKE ? OR f.fnev LIKE ? OR f.email LIKE ?)";
        $search_term = "%$search%";
        $params = array_merge($params, [$search_term, $search_term, $search_term]);
    }
    
    $where_sql = '';
    if (!empty($where)) {
        $where_sql = ' WHERE ' . implode(' AND ', $where);
    }
    
    $valid_columns = ['meid', 'dolgozo_id', 'ertekeles', 'idopont'];
    if ($order_by === 'fnev' || $order_by === 'email') {
        $order_clause = "f.$order_by";
    } elseif (in_array($order_by, $valid_columns)) {
        $order_clause = "m.$order_by";
    } else {
        $order_clause = "m.idopont";
    }
    
    $order_dir = $order_dir === 'ASC' ? 'ASC' : 'DESC';
    
    $count_sql = "SELECT COUNT(*) as total FROM megjegyzesek m LEFT JOIN felhasznalok f ON m.fid = f.fid" . $where_sql;
    $stmt = $conn->prepare($count_sql);
    $stmt->execute($params);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $sql = $base_sql . $where_sql . " ORDER BY $order_clause $order_dir LIMIT ? OFFSET ?";
    $params[] = (int)$per_page;
    $params[] = (int)$offset;
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return [
        'comments' => $comments,
        'total' => $total,
        'pages' => $per_page > 0 ? ceil($total / $per_page) : 0,
        'current_page' => $page
    ];
}

function get_deleted_comments($page = 1, $per_page = 15, $search = '', $order_by = 'torles_datuma', $order_dir = 'DESC') {
    global $conn;
    $offset = ($page - 1) * $per_page;
    $params = [];
    $where = [];
    
    $base_sql = "SELECT tm.*, 
                        f.fnev, f.email, f.profilkep,
                        tf.fnev as torlo_felhasznalo
                 FROM torolt_megjegyzesek tm
                 LEFT JOIN felhasznalok f ON tm.fid = f.fid
                 LEFT JOIN felhasznalok tf ON tm.torleste_fid = tf.fid";
    
    if (!empty($search)) {
        $where[] = "(tm.megjegyzes LIKE ? OR f.fnev LIKE ? OR f.email LIKE ?)";
        $search_term = "%$search%";
        $params = array_merge($params, [$search_term, $search_term, $search_term]);
    }
    
    $where_sql = '';
    if (!empty($where)) {
        $where_sql = ' WHERE ' . implode(' AND ', $where);
    }
    
    $valid_columns = ['tid', 'meid', 'dolgozo_id', 'ertekeles', 'idopont', 'torles_datuma', 'torles_tipusa'];
    if ($order_by === 'fnev' || $order_by === 'email') {
        $order_clause = "f.$order_by";
    } elseif ($order_by === 'torlo_felhasznalo') {
        $order_clause = "tf.fnev";
    } elseif (in_array($order_by, $valid_columns)) {
        $order_clause = "tm.$order_by";
    } else {
        $order_clause = "tm.torles_datuma";
    }
    
    $order_dir = $order_dir === 'ASC' ? 'ASC' : 'DESC';
    
    $count_sql = "SELECT COUNT(*) as total FROM torolt_megjegyzesek tm LEFT JOIN felhasznalok f ON tm.fid = f.fid" . $where_sql;
    $stmt = $conn->prepare($count_sql);
    $stmt->execute($params);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $sql = $base_sql . $where_sql . " ORDER BY $order_clause $order_dir LIMIT ? OFFSET ?";
    $params[] = (int)$per_page;
    $params[] = (int)$offset;
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return [
        'comments' => $comments,
        'total' => $total,
        'pages' => $per_page > 0 ? ceil($total / $per_page) : 0,
        'current_page' => $page
    ];
}

function move_comment_to_deleted($meid, $torles_tipusa = 'admin', $torles_oka = '') {
    global $conn;
    try {
        $conn->beginTransaction();
        $sql = "SELECT * FROM megjegyzesek WHERE meid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$meid]);
        $comment = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$comment) throw new Exception("Nincs ilyen megjegyzés");

        $insert_sql = "INSERT INTO torolt_megjegyzesek 
                      (meid, fid, dolgozo_id, megjegyzes, ertekeles, idopont, 
                       torles_tipusa, torleste_fid, torles_datuma, torles_oka)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
        $torleste_fid = $_SESSION['fid'] ?? $comment['fid']; 
        
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->execute([
            $comment['meid'],
            $comment['fid'],
            $comment['dolgozo_id'],
            $comment['megjegyzes'],
            $comment['ertekeles'],
            $comment['idopont'],
            $torles_tipusa,
            $torleste_fid,
            $torles_oka
        ]);
        
        $conn->prepare("DELETE FROM megjegyzesek WHERE meid = ?")->execute([$meid]);
        
        if ($torles_tipusa === 'admin' && isset($_SESSION['fid'])) {
            log_admin_activity($_SESSION['fid'], $comment['fid'], "Megjegyzés törlése", "Megjegyzés ID: {$meid}, Oka: " . ($torles_oka ?: 'Nincs megadva'));
        }
        
        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollBack();
        error_log("Hiba: " . $e->getMessage());
        return false;
    }
}

function get_comment_by_id($comment_id) {
    global $conn;
    try {
        $sql = "SELECT m.*, f.fnev, f.email, f.profilkep 
                FROM megjegyzesek m
                LEFT JOIN felhasznalok f ON m.fid = f.fid
                WHERE m.meid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$comment_id]);
        $comment = $stmt->fetch(PDO::FETCH_ASSOC);
        return $comment ? $comment : null;
    } catch (PDOException $e) {
        error_log("get_comment_by_id error: " . $e->getMessage());
        return null;
    }
}

function get_admin_activities($page = 1, $per_page = 15, $search = '', $order_by = 'idopont', $order_dir = 'DESC') {
    global $conn;
    $offset = ($page - 1) * $per_page;
    $params = [];
    $where = [];
    
    $valid_columns = ['atid', 'tevekenyseg', 'idopont'];
    $order_by = in_array($order_by, $valid_columns) ? $order_by : 'idopont';
    $order_dir = $order_dir === 'ASC' ? 'ASC' : 'DESC';
    
    if (!empty($search)) {
        $where[] = "(at.tevekenyseg LIKE ? OR at.reszletek LIKE ? OR a.fnev LIKE ? OR f.fnev LIKE ?)";
        $search_term = "%$search%";
        $params = array_merge($params, [$search_term, $search_term, $search_term, $search_term]);
    }
    
    $where_sql = '';
    if (!empty($where)) {
        $where_sql = 'WHERE ' . implode(' AND ', $where);
    }
    
    $count_sql = "SELECT COUNT(*) as total FROM admin_tevekenyseg at $where_sql";
    $stmt = $conn->prepare($count_sql);
    $stmt->execute($params);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $sql = "SELECT at.*, 
                   a.fnev as admin_felhasznalo,
                   f.fnev as cel_felhasznalo
            FROM admin_tevekenyseg at
            LEFT JOIN felhasznalok a ON at.admin_fid = a.fid
            LEFT JOIN felhasznalok f ON at.cel_fid = f.fid
            $where_sql 
            ORDER BY $order_by $order_dir 
            LIMIT ? OFFSET ?";
    
    $params[] = $per_page;
    $params[] = $offset;
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return [
        'activities' => $activities,
        'total' => $total,
        'pages' => ceil($total / $per_page),
        'current_page' => $page,
        'order_by' => $order_by,
        'order_dir' => $order_dir
    ];
}

function get_user_activities($page = 1, $per_page = 15, $search = '', $category = '', $order_by = 'idopont', $order_dir = 'DESC') {
    global $conn;
    $offset = ($page - 1) * $per_page;
    $params = [];
    $where = [];
    
    $valid_columns = ['ftid', 'tevekenyseg', 'kategoria', 'sikeresseg', 'idopont'];
    $order_by = in_array($order_by, $valid_columns) ? $order_by : 'idopont';
    $order_dir = $order_dir === 'ASC' ? 'ASC' : 'DESC';
    
    if (!empty($search)) {
        $where[] = "(ft.tevekenyseg LIKE ? OR ft.modositott_mezo LIKE ? OR ft.reszletek LIKE ? OR f.fnev LIKE ? OR f.email LIKE ?)";
        $search_term = "%$search%";
        $params = array_merge($params, [$search_term, $search_term, $search_term, $search_term, $search_term]);
    }
    
    if (!empty($category) && in_array($category, ['bejelentkezes', 'profil', 'biztonsag', 'egyeb'])) {
        $where[] = "ft.kategoria = ?";
        $params[] = $category;
    }
    
    $where_sql = '';
    if (!empty($where)) {
        $where_sql = 'WHERE ' . implode(' AND ', $where);
    }
    
    $count_sql = "SELECT COUNT(*) as total FROM felhasznalo_tevekenyseg ft LEFT JOIN felhasznalok f ON ft.fid = f.fid $where_sql";
    $stmt = $conn->prepare($count_sql);
    $stmt->execute($params);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $sql = "SELECT ft.*, f.fnev, f.email
            FROM felhasznalo_tevekenyseg ft
            LEFT JOIN felhasznalok f ON ft.fid = f.fid
            $where_sql 
            ORDER BY $order_by $order_dir 
            LIMIT ? OFFSET ?";
    
    $params[] = $per_page;
    $params[] = $offset;
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return [
        'activities' => $activities,
        'total' => $total,
        'pages' => ceil($total / $per_page),
        'current_page' => $page,
        'order_by' => $order_by,
        'order_dir' => $order_dir
    ];
}

function log_user_activity($user_id, $activity, $category = 'egyeb', $success = 'sikeres', $details = '') {
    global $conn;
    try {
        $sql = "INSERT INTO felhasznalo_tevekenyseg (fid, tevekenyseg, kategoria, sikeresseg, reszletek) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$user_id, $activity, $category, $success, $details]);
    } catch (PDOException $e) {
        error_log("User activity log error: " . $e->getMessage());
        return false;
    }
}

function get_email_verifications($page = 1, $per_page = 15, $search = '', $order_by = 'idopont', $order_dir = 'DESC') {
    global $conn;
    $offset = ($page - 1) * $per_page;
    $params = [];
    $where = [];
    
    $valid_columns = ['emid', 'ellenorzve', 'lejarati_ido', 'idopont'];
    $order_by = in_array($order_by, $valid_columns) ? "e.$order_by" : 'e.idopont';
    $order_dir = $order_dir === 'ASC' ? 'ASC' : 'DESC';
    
    if (!empty($search)) {
        $where[] = "(f.fnev LIKE ? OR f.email LIKE ?)";
        $search_term = "%$search%";
        $params = array_merge($params, [$search_term, $search_term]);
    }
    
    $where_sql = '';
    if (!empty($where)) {
        $where_sql = 'WHERE ' . implode(' AND ', $where);
    }
    
    $count_sql = "SELECT COUNT(*) as total FROM email_ell e LEFT JOIN felhasznalok f ON e.fid = f.fid $where_sql";
    $stmt = $conn->prepare($count_sql);
    $stmt->execute($params);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $sql = "SELECT e.*, f.fnev, f.email
            FROM email_ell e
            LEFT JOIN felhasznalok f ON e.fid = f.fid
            $where_sql 
            ORDER BY $order_by $order_dir 
            LIMIT ? OFFSET ?";
    
    $params[] = (int)$per_page;
    $params[] = (int)$offset;
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $verifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return [
        'verifications' => $verifications,
        'total' => $total,
        'pages' => $per_page > 0 ? ceil($total / $per_page) : 0,
        'current_page' => $page,
        'order_by' => str_replace('e.', '', $order_by),
        'order_dir' => $order_dir
    ];
}

function get_password_resets($page = 1, $per_page = 15, $search = '', $order_by = 'idopont', $order_dir = 'DESC') {
    global $conn;
    $offset = ($page - 1) * $per_page;
    $params = [];
    $where = [];
    
    $valid_columns = ['jvid', 'felhasznalva', 'lejarati_ido', 'idopont'];
    $order_by = in_array($order_by, $valid_columns) ? "j.$order_by" : 'j.idopont';
    $order_dir = $order_dir === 'ASC' ? 'ASC' : 'DESC';
    
    if (!empty($search)) {
        $where[] = "(f.fnev LIKE ? OR f.email LIKE ?)";
        $search_term = "%$search%";
        $params = array_merge($params, [$search_term, $search_term]);
    }
    
    $where_sql = '';
    if (!empty($where)) {
        $where_sql = 'WHERE ' . implode(' AND ', $where);
    }
    
    $count_sql = "SELECT COUNT(*) as total FROM jelszo_visszaallitasok j LEFT JOIN felhasznalok f ON j.fid = f.fid $where_sql";
    $stmt = $conn->prepare($count_sql);
    $stmt->execute($params);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $sql = "SELECT j.*, f.fnev, f.email
            FROM jelszo_visszaallitasok j
            LEFT JOIN felhasznalok f ON j.fid = f.fid
            $where_sql 
            ORDER BY $order_by $order_dir 
            LIMIT ? OFFSET ?";
    
    $params[] = (int)$per_page;
    $params[] = (int)$offset;
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $resets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return [
        'resets' => $resets,
        'total' => $total,
        'pages' => $per_page > 0 ? ceil($total / $per_page) : 0,
        'current_page' => $page,
        'order_by' => str_replace('j.', '', $order_by),
        'order_dir' => $order_dir
    ];
}

function check_expired_bans() {
    global $conn;
    try {
        $select_sql = "SELECT fid, fnev, email, statusz_meddig 
                      FROM felhasznalok 
                      WHERE statusz = 'Kitiltott' 
                      AND statusz_meddig IS NOT NULL 
                      AND statusz_meddig != '0000-00-00 00:00:00'
                      AND statusz_meddig != ''
                      AND statusz_meddig <= NOW()";
        
        $select_stmt = $conn->prepare($select_sql);
        $select_stmt->execute();
        $expired_users = $select_stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($expired_users)) return 0;
        
        $update_sql = "UPDATE felhasznalok 
                      SET statusz = 'Aktiv', 
                          statusz_ok = NULL, 
                          statusz_meddig = NULL,
                          modositott = CURRENT_TIMESTAMP 
                      WHERE statusz = 'Kitiltott' 
                      AND statusz_meddig IS NOT NULL 
                      AND statusz_meddig != '0000-00-00 00:00:00'
                      AND statusz_meddig != ''
                      AND statusz_meddig <= NOW()";
        
        $update_stmt = $conn->prepare($update_sql);
        $success = $update_stmt->execute();
        $affected_rows = $update_stmt->rowCount();
        
        if ($affected_rows > 0) {
            $user_list = [];
            foreach ($expired_users as $user) {
                $user_list[] = $user['fnev'] . " (" . $user['email'] . ")";
                log_admin_activity(null, $user['fid'], "Kitiltás automatikus lejárta", "Felhasználó automatikusan visszaállítva aktív státuszra. Eredeti lejárat: " . ($user['statusz_meddig'] ? format_date($user['statusz_meddig']) : 'nincs megadva'));
            }
            $log_message = $affected_rows . " lejárt kitiltás automatikusan visszaállítva: " . implode(", ", $user_list);
            error_log($log_message);
            
            $log_sql = "INSERT INTO admin_tevekenyseg (admin_fid, cel_fid, tevekenyseg, reszletek) VALUES (0, NULL, 'Kitiltások automatikus visszaállítása', ?)";
            $log_stmt = $conn->prepare($log_sql);
            $log_stmt->execute([$log_message]);
            
            $_SESSION['admin_message'] = ['type' => 'success', 'message' => $affected_rows . " lejárt kitiltás automatikusan visszaállítva"];
        }
        return $affected_rows;
    } catch (PDOException $e) {
        error_log("Check expired bans error: " . $e->getMessage());
        return false;
    }
}

function get_deleted_users($page = 1, $per_page = 15, $search = '', $order_by = 'torles_idopontja', $order_dir = 'DESC') {
    global $conn;
    $offset = ($page - 1) * $per_page;
    $params = [];
    $where = [];
    
    $valid_columns = ['fid', 'fnev', 'email', 'regisztralt', 'torles_idopontja'];
    $order_by = in_array($order_by, $valid_columns) ? $order_by : 'torles_idopontja';
    $order_dir = $order_dir === 'ASC' ? 'ASC' : 'DESC';
    
    if (!empty($search)) {
        $where[] = "(fnev LIKE ? OR email LIKE ? OR knev LIKE ? OR vnev LIKE ?)";
        $search_term = "%$search%";
        $params = array_merge($params, [$search_term, $search_term, $search_term, $search_term]);
    }
    
    $where_sql = '';
    if (!empty($where)) {
        $where_sql = 'WHERE ' . implode(' AND ', $where);
    }
    
    $count_sql = "SELECT COUNT(*) as total FROM torolt_felhasznalok $where_sql";
    $stmt = $conn->prepare($count_sql);
    $stmt->execute($params);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    $sql = "SELECT * FROM torolt_felhasznalok 
            $where_sql 
            ORDER BY $order_by $order_dir 
            LIMIT ? OFFSET ?";
    
    $params[] = $per_page;
    $params[] = $offset;
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return [
        'users' => $users,
        'total' => $total,
        'pages' => ceil($total / $per_page),
        'current_page' => $page,
        'order_by' => $order_by,
        'order_dir' => $order_dir
    ];
}

function update_user_data($user_id, $data) {
    global $conn;
    try {
        $current_user_data = get_user_by_id($user_id);
        if (!$current_user_data) return false;
        
        $allowed_fields = ['fnev', 'email', 'knev', 'vnev', 'nem', 'szuletett', 'telefon', 'varmegye', 'reszletek'];
        $updates = [];
        $params = [];
        $changed_fields = [];
        
        foreach ($data as $field => $value) {
            if (in_array($field, $allowed_fields)) {
                $current_value = $current_user_data[$field] ?? '';
                $new_value = $value !== '' ? $value : null;
                
                if ($current_value != $new_value) {
                    $updates[] = "$field = ?";
                    $params[] = $new_value;
                    $changed_fields[$field] = ['from' => $current_value, 'to' => $new_value];
                }
            }
        }
        
        if (empty($updates)) return false;
        
        $sql = "UPDATE felhasznalok SET " . implode(', ', $updates) . ", modositott = CURRENT_TIMESTAMP WHERE fid = ?";
        $params[] = $user_id;
        
        $stmt = $conn->prepare($sql);
        $success = $stmt->execute($params);
        
        if ($success && !empty($changed_fields)) {
            $change_details = "Módosított mezők:\n";
            foreach ($changed_fields as $field => $changes) {
                $change_details .= "- $field: '" . ($changes['from'] ?? 'üres') . "' → '" . ($changes['to'] ?? 'üres') . "'\n";
            }
            log_admin_activity($_SESSION['fid'], $user_id, "Felhasználó adatainak módosítása", $change_details);
            log_user_activity($user_id, "Profil adatok módosítása (admin által)", "profil", "sikeres", "Admin módosította a profil adatait. " . count($changed_fields) . " mező változott.");
        }
        return $success;
    } catch (PDOException $e) {
        error_log("Update user data error: " . $e->getMessage());
        return false;
    }
}

function update_user_role($user_id, $role) {
    global $conn;
    try {
        $sql = "UPDATE felhasznalok SET szerep = ?, modositott = CURRENT_TIMESTAMP WHERE fid = ?";
        $stmt = $conn->prepare($sql);
        $success = $stmt->execute([$role, $user_id]);
        
        if ($success) {
            $action = $role === 'admin' ? 'Admin jog megadása' : 'Admin jog elvétele';
            log_admin_activity($_SESSION['fid'], $user_id, $action);
            $activity = $role === 'admin' ? 'Admin jogok megadása' : 'Admin jogok visszavonása';
            log_user_activity($user_id, $activity, "biztonsag", "sikeres", "Admin módosította a felhasználó szerepét: " . $role);
        }
        return $success;
    } catch (PDOException $e) {
        error_log("Update user role error: " . $e->getMessage());
        return false;
    }
}

function update_user_status($user_id, $status, $reason = '', $until = null) {
    global $conn;
    try {
        $email_megerositve = ($status === 'Fuggoben') ? 0 : 1;
        $sql = "UPDATE felhasznalok 
                SET statusz = ?, email_megerositve = ?, statusz_ok = ?, statusz_meddig = ?, modositott = CURRENT_TIMESTAMP 
                WHERE fid = ?";
        $stmt = $conn->prepare($sql);
        $success = $stmt->execute([$status, $email_megerositve, $reason, $until, $user_id]);
        
        if ($success) {
            $status_text = match($status) {
                'Aktiv' => 'aktív',
                'Fuggoben' => 'függőben',
                'Kitiltott' => 'kitiltott',
                default => $status
            };
            log_admin_activity($_SESSION['fid'], $user_id, "Felhasználó státusz módosítása: " . $status, $reason . ($status === 'Fuggoben' ? ' (Email megerősítés visszaállítva)' : ''));
            $activity = match($status) {
                'Aktiv' => 'Fiók aktiválása (admin által)',
                'Fuggoben' => 'Fiók felfüggesztése',
                'Kitiltott' => 'Fiók kitiltása',
                default => 'Státusz módosítás'
            };
            $details = "Státusz módosítva: " . $status_text;
            if ($reason) $details .= ", Oka: " . $reason;
            if ($until && $status === 'Kitiltott') $details .= ", Határidő: " . format_date($until);
            log_user_activity($user_id, $activity, "biztonsag", "sikeres", $details);
        }
        return $success;
    } catch (PDOException $e) {
        error_log("User status update error: " . $e->getMessage());
        return false;
    }
}

function delete_activity($activity_id, $table) {
    global $conn;
    $table_config = [
        'felhasznalo_tevekenyseg' => 'ftid',
        'admin_tevekenyseg' => 'atid',
        'email_ell' => 'emid',
        'jelszo_visszaallitasok' => 'jvid',
        'torolt_felhasznalok' => 'fid',
        'megjegyzesek' => 'meid'
    ];
    
    if (!array_key_exists($table, $table_config)) {
        error_log("Invalid table for delete_activity: $table");
        return false;
    }
    
    $id_column = $table_config[$table];
    try {
        $sql = "DELETE FROM $table WHERE $id_column = ?";
        $stmt = $conn->prepare($sql);
        $success = $stmt->execute([$activity_id]);
        if ($success) {
            log_admin_activity($_SESSION['fid'], null, "Tevékenység törlése", "Tábla: $table, ID: $activity_id");
        }
        return $success;
    } catch (PDOException $e) {
        error_log("Delete activity error: " . $e->getMessage());
        return false;
    }
}

function log_admin_activity($admin_id, $target_user_id = null, $activity, $details = '') {
    global $conn;
    try {
        $sql = "INSERT INTO admin_tevekenyseg (admin_fid, cel_fid, tevekenyseg, reszletek) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$admin_id, $target_user_id, $activity, $details]);
    } catch (PDOException $e) {
        error_log("Admin activity log error: " . $e->getMessage());
        return false;
    }
}

function delete_user($user_id) {
    global $conn;
    try {
        $conn->beginTransaction();
        $sql = "SELECT * FROM felhasznalok WHERE fid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id]);
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user_data) throw new Exception("Felhasználó nem található");
        
        $sql_comments = "SELECT * FROM megjegyzesek WHERE fid = ?";
        $stmt_comments = $conn->prepare($sql_comments);
        $stmt_comments->execute([$user_id]);
        $comments = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($comments as $comment) {
            $insert_sql = "INSERT INTO torolt_megjegyzesek 
                          (meid, dolgozo_id, fid, megjegyzes, ertekeles, idopont, torles_tipusa, torleste_fid, torles_datuma)
                          VALUES (?, ?, ?, ?, ?, ?, 'automata', ?, NOW())";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->execute([
                $comment['meid'], $comment['dolgozo_id'], $comment['fid'], $comment['megjegyzes'], 
                $comment['ertekeles'], $comment['idopont'], $_SESSION['fid'] ?? null
            ]);
        }
        
        $conn->prepare("DELETE FROM megjegyzesek WHERE fid = ?")->execute([$user_id]);
        
        $insert_sql = "INSERT INTO torolt_felhasznalok 
                      (fid, szerep, statusz, nem, email, fnev, jelszo, knev, vnev, profilkep, szuletett, telefon, varmegye, reszletek, regisztralt, modositott, belepett, statusz_ok, statusz_meddig) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->execute([
            $user_data['fid'], $user_data['szerep'], $user_data['statusz'], $user_data['nem'], $user_data['email'], $user_data['fnev'], $user_data['jelszo'], $user_data['knev'], $user_data['vnev'], $user_data['profilkep'], $user_data['szuletett'], $user_data['telefon'], $user_data['varmegye'], $user_data['reszletek'], $user_data['regisztralt'], $user_data['modositott'], $user_data['belepett'], $user_data['statusz_ok'], $user_data['statusz_meddig']
        ]);
        
        foreach (['email_ell', 'jelszo_visszaallitasok', 'felhasznalo_tevekenyseg'] as $table) {
            $conn->prepare("DELETE FROM $table WHERE fid = ?")->execute([$user_id]);
        }
        
        $conn->prepare("UPDATE admin_tevekenyseg SET cel_fid = NULL WHERE cel_fid = ?")->execute([$user_id]);
        $conn->prepare("DELETE FROM felhasznalok WHERE fid = ?")->execute([$user_id]);
        
        $conn->commit();
        log_admin_activity($_SESSION['fid'], $user_id, "Felhasználó törlése", "Felhasználó: " . $user_data['fnev'] . " (" . $user_data['email'] . "), " . count($comments) . " megjegyzés törölve");
        return true;
    } catch (Exception $e) {
        $conn->rollBack();
        error_log("User deletion error: " . $e->getMessage());
        return false;
    }
}

function get_user_by_id($user_id) {
    global $conn;
    try {
        $sql = "SELECT fid, fnev, email, knev, vnev, nem, szuletett, telefon, varmegye, reszletek, 
                       szerep, statusz, profilkep, regisztralt, modositott, belepett, email_megerositve, statusz_ok, statusz_meddig
                FROM felhasznalok WHERE fid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Get user by ID error: " . $e->getMessage());
        return false;
    }
}

function generate_pagination($current_page, $total_pages, $base_url) {
    if ($total_pages <= 1) return '';
    $pagination = '';
    $max_pages_to_show = 10;
    $half = floor($max_pages_to_show / 2);
    $start_page = max(1, $current_page - $half);
    $end_page = min($total_pages, $start_page + $max_pages_to_show - 1);
    if ($end_page - $start_page + 1 < $max_pages_to_show) $start_page = max(1, $end_page - $max_pages_to_show + 1);
    
    if ($current_page > 1) {
        $pagination .= '<li><a href="' . htmlspecialchars($base_url . '&page=' . ($current_page - 1)) . '">&laquo;</a></li>';
    }
    if ($start_page > 1) {
        $pagination .= '<li><a href="' . htmlspecialchars($base_url . '&page=1') . '">1</a></li>';
        if ($start_page > 2) $pagination .= '<li><span class="pagination-ellipsis">...</span></li>';
    }
    for ($i = $start_page; $i <= $end_page; $i++) {
        $active = $i == $current_page ? 'active' : '';
        $pagination .= '<li><a href="' . htmlspecialchars($base_url . '&page=' . $i) . '" class="' . $active . '">' . $i . '</a></li>';
    }
    if ($end_page < $total_pages) {
        if ($end_page < $total_pages - 1) $pagination .= '<li><span class="pagination-ellipsis">...</span></li>';
        $pagination .= '<li><a href="' . htmlspecialchars($base_url . '&page=' . $total_pages) . '">' . $total_pages . '</a></li>';
    }
    if ($current_page < $total_pages) {
        $pagination .= '<li><a href="' . htmlspecialchars($base_url . '&page=' . ($current_page + 1)) . '">&raquo;</a></li>';
    }
    return $pagination;
}

function format_status($status) {
    $statuses = [
        'Aktiv' => ['label' => 'Aktív', 'class' => 'admin-badge-success'],
        'Fuggoben' => ['label' => 'Függőben', 'class' => 'admin-badge-warning'],
        'Kitiltott' => ['label' => 'Kitiltott', 'class' => 'admin-badge-danger']
    ];
    if (isset($statuses[$status])) {
        return '<span class="admin-badge ' . $statuses[$status]['class'] . '">' . $statuses[$status]['label'] . '</span>';
    }
    return '<span class="admin-badge admin-badge-secondary">' . $status . '</span>';
}

function format_gender($gender) {
    $genders = ['ferfi' => 'Férfi', 'no' => 'Nő', 'egyeb' => 'Egyéb', 'nem_publikus' => 'Nem publikus'];
    return $genders[$gender] ?? $gender;
}

function format_activity_category($category) {
    $categories = ['bejelentkezes' => 'Bejelentkezés', 'profil' => 'Profil', 'biztonsag' => 'Biztonság', 'egyeb' => 'Egyéb'];
    return $categories[$category] ?? $category;
}

function format_email_status($verification) {
    if ($verification['ellenorzve']) return '<span class="admin-badge admin-badge-success">Megerősítve</span>';
    if (empty($verification['lejarati_ido']) || $verification['lejarati_ido'] === '0000-00-00 00:00:00') return '<span class="admin-badge admin-badge-warning">Függőben</span>';
    $lejarati_ido = strtotime($verification['lejarati_ido']);
    if ($lejarati_ido === false || $lejarati_ido < 0) return '<span class="admin-badge admin-badge-warning">Függőben</span>';
    return ($lejarati_ido < time()) ? '<span class="admin-badge admin-badge-danger">Lejárt</span>' : '<span class="admin-badge admin-badge-warning">Függőben</span>';
}

function format_reset_status($reset) {
    if ($reset['felhasznalva']) return '<span class="admin-badge admin-badge-success">Felhasználva</span>';
    if (empty($reset['lejarati_ido']) || $reset['lejarati_ido'] === '0000-00-00 00:00:00') return '<span class="admin-badge admin-badge-warning">Aktív</span>';
    $lejarati_ido = strtotime($reset['lejarati_ido']);
    if ($lejarati_ido === false || $lejarati_ido < 0) return '<span class="admin-badge admin-badge-warning">Aktív</span>';
    return ($lejarati_ido < time()) ? '<span class="admin-badge admin-badge-danger">Lejárt</span>' : '<span class="admin-badge admin-badge-warning">Aktív</span>';
}

function update_comment($meid, $megjegyzes, $ertekeles) {
    global $conn;
    try {
        $sql = "UPDATE megjegyzesek SET megjegyzes = :megjegyzes, ertekeles = :ertekeles WHERE meid = :meid";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':megjegyzes' => $megjegyzes, ':ertekeles' => $ertekeles, ':meid' => $meid]);
    } catch (PDOException $e) {
        error_log("Hiba a megjegyzés szerkesztésekor: " . $e->getMessage());
        return false;
    }
}

function permanent_delete_comment($meid) {
    global $conn;
    try {
        $sql = "DELETE FROM torolt_megjegyzesek WHERE meid = :meid";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':meid' => $meid]);
    } catch (PDOException $e) {
        error_log("Hiba a megjegyzés végleges törlésekor: " . $e->getMessage());
        return false;
    }
}

function bulk_delete_comments($meids, $torles_oka = 'Csoportos admin törlés') {
    $successCount = 0;
    foreach ($meids as $meid) {
        if (move_comment_to_deleted($meid, 'admin', $torles_oka)) {
            $successCount++;
        }
    }
    return $successCount;
}

function bulk_permanent_delete_comments($meids) {
    $successCount = 0;
    foreach ($meids as $meid) {
        if (permanent_delete_comment($meid)) {
            $successCount++;
        }
    }
    return $successCount;
}
?>