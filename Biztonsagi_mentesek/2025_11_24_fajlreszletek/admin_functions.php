<?php
// includes/admin_functions.php - WebAdmin specifikus funkciók - Javított verzió

// Admin jogosultság ellenőrzése
function check_admin_access() {
    if (!isset($_SESSION['fid']) || $_SESSION['szerep'] !== 'admin') {
        header('Location: ' . base_url('login'));
        exit;
    }
}

// Statisztikák lekérése
function get_admin_stats() {
    global $conn;
    
    $stats = [];
    
    try {
        // Összes felhasználó
        $sql = "SELECT COUNT(*) as total, 
                       SUM(CASE WHEN statusz = 'Aktiv' THEN 1 ELSE 0 END) as active,
                       SUM(CASE WHEN statusz = 'Fuggoben' THEN 1 ELSE 0 END) as pending,
                       SUM(CASE WHEN statusz = 'Kitiltott' THEN 1 ELSE 0 END) as banned
                FROM felhasznalok";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stats['users'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Admin tevékenységek
        $sql = "SELECT COUNT(*) as total,
                       COUNT(CASE WHEN DATE(idopont) = CURDATE() THEN 1 END) as today
                FROM admin_tevekenyseg";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stats['admin_activities'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Felhasználói tevékenységek
        $sql = "SELECT COUNT(*) as total,
                       COUNT(CASE WHEN DATE(idopont) = CURDATE() THEN 1 END) as today,
                       COUNT(CASE WHEN sikeresseg = 'sikertelen' THEN 1 END) as failed
                FROM felhasznalo_tevekenyseg";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stats['user_activities'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Email ellenőrzések
        $sql = "SELECT COUNT(*) as total,
                       COUNT(CASE WHEN ellenorzve IS NOT NULL THEN 1 END) as verified,
                       COUNT(CASE WHEN ellenorzve IS NULL AND lejarati_ido > NOW() THEN 1 END) as pending
                FROM email_ell";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stats['email_verifications'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Jelszó visszaállítások
        $sql = "SELECT COUNT(*) as total,
                       COUNT(CASE WHEN felhasznalva = 1 THEN 1 END) as used,
                       COUNT(CASE WHEN felhasznalva = 0 AND lejarati_ido > NOW() THEN 1 END) as active
                FROM jelszo_visszaallitasok";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stats['password_resets'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Törölt felhasználók
        $sql = "SELECT COUNT(*) as total FROM torolt_felhasznalok";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stats['deleted_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
    } catch (PDOException $e) {
        error_log("Admin stats error: " . $e->getMessage());
        return false;
    }
    
    return $stats;
}

// Felhasználók lekérése rendezéssel
function get_users($page = 1, $per_page = 15, $search = '', $status = '', $order_by = 'regisztralt', $order_dir = 'DESC') {
    global $conn;
    
    $offset = ($page - 1) * $per_page;
    $params = [];
    $where = [];
    
    // Érvényes rendezési oszlopok
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
    
    // Összes rekord száma
    $count_sql = "SELECT COUNT(*) as total FROM felhasznalok $where_sql";
    $stmt = $conn->prepare($count_sql);
    $stmt->execute($params);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Adatok lekérése - JAVÍTVA: belepited -> belepett
    $sql = "SELECT fid, szerep, statusz, nem, email, fnev, knev, vnev, profilkep, 
                   szuletett, telefon, varos, regisztralt, modositott, belepett 
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

// Admin tevékenységek lekérése
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
    
    // Összes rekord száma
    $count_sql = "SELECT COUNT(*) as total FROM admin_tevekenyseg at $where_sql";
    $stmt = $conn->prepare($count_sql);
    $stmt->execute($params);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Adatok lekérése
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

// Felhasználói tevékenységek lekérése
function get_user_activities($page = 1, $per_page = 15, $search = '', $category = '', $order_by = 'idopont', $order_dir = 'DESC') {
    global $conn;
    
    $offset = ($page - 1) * $per_page;
    $params = [];
    $where = [];
    
    $valid_columns = ['ftid', 'tevekenyseg', 'kategoria', 'sikeresseg', 'idopont'];
    $order_by = in_array($order_by, $valid_columns) ? $order_by : 'idopont';
    $order_dir = $order_dir === 'ASC' ? 'ASC' : 'DESC';
    
    if (!empty($search)) {
        $where[] = "(ft.tevekenyseg LIKE ? OR ft.modositott_mezo LIKE ? OR f.fnev LIKE ? OR f.email LIKE ?)";
        $search_term = "%$search%";
        $params = array_merge($params, [$search_term, $search_term, $search_term, $search_term]);
    }
    
    if (!empty($category) && in_array($category, ['bejelentkezes', 'profil', 'biztonsag', 'egyeb'])) {
        $where[] = "ft.kategoria = ?";
        $params[] = $category;
    }
    
    $where_sql = '';
    if (!empty($where)) {
        $where_sql = 'WHERE ' . implode(' AND ', $where);
    }
    
    // Összes rekord száma
    $count_sql = "SELECT COUNT(*) as total 
                  FROM felhasznalo_tevekenyseg ft
                  LEFT JOIN felhasznalok f ON ft.fid = f.fid
                  $where_sql";
    $stmt = $conn->prepare($count_sql);
    $stmt->execute($params);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Adatok lekérése
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

// Email ellenőrzések lekérése
function get_email_verifications($page = 1, $per_page = 15, $search = '', $order_by = 'idopont', $order_dir = 'DESC') {
    global $conn;
    
    $offset = ($page - 1) * $per_page;
    $params = [];
    $where = [];
    
    $valid_columns = ['emid', 'fnev', 'email', 'ellenorzve', 'lejarati_ido', 'idopont'];
    $order_by = in_array($order_by, $valid_columns) ? $order_by : 'idopont';
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
    
    // Összes rekord száma
    $count_sql = "SELECT COUNT(*) as total 
                  FROM email_ell ee
                  JOIN felhasznalok f ON ee.fid = f.fid
                  $where_sql";
    $stmt = $conn->prepare($count_sql);
    $stmt->execute($params);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Adatok lekérése
    $sql = "SELECT ee.*, f.fnev, f.email, f.statusz
            FROM email_ell ee
            JOIN felhasznalok f ON ee.fid = f.fid
            $where_sql 
            ORDER BY $order_by $order_dir 
            LIMIT ? OFFSET ?";
    
    $params[] = $per_page;
    $params[] = $offset;
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $verifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return [
        'verifications' => $verifications,
        'total' => $total,
        'pages' => ceil($total / $per_page),
        'current_page' => $page,
        'order_by' => $order_by,
        'order_dir' => $order_dir
    ];
}

// Jelszó visszaállítások lekérése
function get_password_resets($page = 1, $per_page = 15, $search = '', $order_by = 'idopont', $order_dir = 'DESC') {
    global $conn;
    
    $offset = ($page - 1) * $per_page;
    $params = [];
    $where = [];
    
    $valid_columns = ['jvid', 'fnev', 'email', 'felhasznalva', 'lejarati_ido', 'idopont'];
    $order_by = in_array($order_by, $valid_columns) ? $order_by : 'idopont';
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
    
    // Összes rekord száma
    $count_sql = "SELECT COUNT(*) as total 
                  FROM jelszo_visszaallitasok jv
                  JOIN felhasznalok f ON jv.fid = f.fid
                  $where_sql";
    $stmt = $conn->prepare($count_sql);
    $stmt->execute($params);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Adatok lekérése
    $sql = "SELECT jv.*, f.fnev, f.email, f.statusz
            FROM jelszo_visszaallitasok jv
            JOIN felhasznalok f ON jv.fid = f.fid
            $where_sql 
            ORDER BY $order_by $order_dir 
            LIMIT ? OFFSET ?";
    
    $params[] = $per_page;
    $params[] = $offset;
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $resets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return [
        'resets' => $resets,
        'total' => $total,
        'pages' => ceil($total / $per_page),
        'current_page' => $page,
        'order_by' => $order_by,
        'order_dir' => $order_dir
    ];
}

// Törölt felhasználók lekérése
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
    
    // Összes rekord száma
    $count_sql = "SELECT COUNT(*) as total FROM torolt_felhasznalok $where_sql";
    $stmt = $conn->prepare($count_sql);
    $stmt->execute($params);
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    // Adatok lekérése
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

// Felhasználó adatainak frissítése
function update_user_data($user_id, $data) {
    global $conn;
    
    try {
        $allowed_fields = ['fnev', 'email', 'knev', 'vnev', 'nem', 'szuletett', 'telefon', 'varos', 'reszletek'];
        $updates = [];
        $params = [];
        
        foreach ($data as $field => $value) {
            if (in_array($field, $allowed_fields) && $value !== '') {
                $updates[] = "$field = ?";
                $params[] = $value;
            }
        }
        
        if (empty($updates)) {
            return false;
        }
        
        $sql = "UPDATE felhasznalok SET " . implode(', ', $updates) . ", modositott = CURRENT_TIMESTAMP WHERE fid = ?";
        $params[] = $user_id;
        
        $stmt = $conn->prepare($sql);
        $success = $stmt->execute($params);
        
        if ($success) {
            log_admin_activity(
                $_SESSION['fid'],
                $user_id,
                "Felhasználó adatainak módosítása",
                "Mezők: " . implode(', ', array_keys($data))
            );
        }
        
        return $success;
        
    } catch (PDOException $e) {
        error_log("Update user data error: " . $e->getMessage());
        return false;
    }
}

// Felhasználó szerepének módosítása
function update_user_role($user_id, $role) {
    global $conn;
    
    try {
        $sql = "UPDATE felhasznalok SET szerep = ?, modositott = CURRENT_TIMESTAMP WHERE fid = ?";
        $stmt = $conn->prepare($sql);
        $success = $stmt->execute([$role, $user_id]);
        
        if ($success) {
            $action = $role === 'admin' ? 'Admin jog megadása' : 'Admin jog elvétele';
            log_admin_activity(
                $_SESSION['fid'],
                $user_id,
                $action
            );
        }
        
        return $success;
        
    } catch (PDOException $e) {
        error_log("Update user role error: " . $e->getMessage());
        return false;
    }
}

// Felhasználó státusz módosítása
function update_user_status($user_id, $status, $reason = '', $until = null) {
    global $conn;
    
    try {
        $sql = "UPDATE felhasznalok 
                SET statusz = ?, 
                    statusz_ok = ?, 
                    statusz_meddig = ?,
                    modositott = CURRENT_TIMESTAMP 
                WHERE fid = ?";
        
        $stmt = $conn->prepare($sql);
        $success = $stmt->execute([$status, $reason, $until, $user_id]);
        
        if ($success) {
            log_admin_activity(
                $_SESSION['fid'],
                $user_id,
                "Felhasználó státusz módosítása: " . $status,
                $reason
            );
        }
        
        return $success;
        
    } catch (PDOException $e) {
        error_log("User status update error: " . $e->getMessage());
        return false;
    }
}

// Tevékenység törlése - JAVÍTVA: hibakezelés javítva
function delete_activity($activity_id, $table) {
    global $conn;
    
    $valid_tables = ['felhasznalo_tevekenyseg', 'admin_tevekenyseg', 'email_ell', 'jelszo_visszaallitasok'];
    
    if (!in_array($table, $valid_tables)) {
        error_log("Invalid table for delete_activity: $table");
        return false;
    }
    
    try {
        $id_column = substr($table, 0, 2) . 'id'; // ftid, atid, emid, jvid
        $sql = "DELETE FROM $table WHERE $id_column = ?";
        $stmt = $conn->prepare($sql);
        $success = $stmt->execute([$activity_id]);
        
        if ($success) {
            log_admin_activity(
                $_SESSION['fid'],
                null,
                "Tevékenység törlése",
                "Tábla: $table, ID: $activity_id"
            );
        }
        
        return $success;
        
    } catch (PDOException $e) {
        error_log("Delete activity error: " . $e->getMessage());
        return false;
    }
}

// Admin tevékenység naplózása
function log_admin_activity($admin_id, $target_user_id = null, $activity, $details = '') {
    global $conn;
    
    try {
        $sql = "INSERT INTO admin_tevekenyseg (admin_fid, cel_fid, tevekenyseg, reszletek) 
                VALUES (?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        return $stmt->execute([$admin_id, $target_user_id, $activity, $details]);
        
    } catch (PDOException $e) {
        error_log("Admin activity log error: " . $e->getMessage());
        return false;
    }
}

// Felhasználó törlése
function delete_user($user_id) {
    global $conn;
    
    try {
        $conn->beginTransaction();
        
        // Felhasználó adatainak lekérése
        $sql = "SELECT * FROM felhasznalok WHERE fid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id]);
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user_data) {
            throw new Exception("Felhasználó nem található");
        }
        
        // Adatok másolása a torolt_felhasznalok táblába
        $insert_sql = "INSERT INTO torolt_felhasznalok 
                      (fid, szerep, statusz, nem, email, fnev, jelszo, knev, vnev, 
                       profilkep, szuletett, telefon, varos, reszletek, regisztralt, 
                       modositott, belepett, statusz_ok, statusz_meddig) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->execute([
            $user_data['fid'], $user_data['szerep'], $user_data['statusz'], 
            $user_data['nem'], $user_data['email'], $user_data['fnev'], 
            $user_data['jelszo'], $user_data['knev'], $user_data['vnev'], 
            $user_data['profilkep'], $user_data['szuletett'], $user_data['telefon'], 
            $user_data['varos'], $user_data['reszletek'], $user_data['regisztralt'], 
            $user_data['modositott'], $user_data['belepett'], 
            $user_data['statusz_ok'], $user_data['statusz_meddig']
        ]);
        
        // Kapcsolódó adatok törlése
        $tables = ['email_ell', 'jelszo_visszaallitasok', 'felhasznalo_tevekenyseg'];
        
        foreach ($tables as $table) {
            $delete_sql = "DELETE FROM $table WHERE fid = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->execute([$user_id]);
        }
        
        // Admin tevékenységek frissítése (cel_fid null-ra állítása)
        $update_sql = "UPDATE admin_tevekenyseg SET cel_fid = NULL WHERE cel_fid = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->execute([$user_id]);
        
        // Felhasználó törlése
        $delete_user_sql = "DELETE FROM felhasznalok WHERE fid = ?";
        $delete_user_stmt = $conn->prepare($delete_user_sql);
        $delete_user_stmt->execute([$user_id]);
        
        $conn->commit();
        
        // Naplózás
        log_admin_activity(
            $_SESSION['fid'],
            $user_id,
            "Felhasználó törlése",
            "Felhasználó: " . $user_data['fnev'] . " (" . $user_data['email'] . ")"
        );
        
        return true;
        
    } catch (Exception $e) {
        $conn->rollBack();
        error_log("User deletion error: " . $e->getMessage());
        return false;
    }
}

// Felhasználó adatainak lekérése ID alapján
function get_user_by_id($user_id) {
    global $conn;
    
    try {
        $sql = "SELECT fid, fnev, email, knev, vnev, nem, szuletett, telefon, varos, reszletek 
                FROM felhasznalok 
                WHERE fid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Get user by ID error: " . $e->getMessage());
        return false;
    }
}

// Lapozó generálása
function generate_pagination($current_page, $total_pages, $base_url) {
    if ($total_pages <= 1) return '';
    
    $pagination = '';
    $max_pages_to_show = 10;
    $half = floor($max_pages_to_show / 2);
    
    $start_page = max(1, $current_page - $half);
    $end_page = min($total_pages, $start_page + $max_pages_to_show - 1);
    
    // Korrekció ha kevés oldal van
    if ($end_page - $start_page + 1 < $max_pages_to_show) {
        $start_page = max(1, $end_page - $max_pages_to_show + 1);
    }
    
    // Előző oldal gomb
    if ($current_page > 1) {
        $prev_url = $base_url . '&page=' . ($current_page - 1);
        $pagination .= '<li><a href="' . $prev_url . '">&laquo;</a></li>';
    }
    
    // Első oldal
    if ($start_page > 1) {
        $pagination .= '<li><a href="' . $base_url . '&page=1">1</a></li>';
        if ($start_page > 2) {
            $pagination .= '<li><span class="pagination-ellipsis">...</span></li>';
        }
    }
    
    // Oldalak
    for ($i = $start_page; $i <= $end_page; $i++) {
        $active = $i == $current_page ? 'active' : '';
        $page_url = $base_url . '&page=' . $i;
        $pagination .= '<li><a href="' . $page_url . '" class="' . $active . '">' . $i . '</a></li>';
    }
    
    // Utolsó oldal
    if ($end_page < $total_pages) {
        if ($end_page < $total_pages - 1) {
            $pagination .= '<li><span class="pagination-ellipsis">...</span></li>';
        }
        $pagination .= '<li><a href="' . $base_url . '&page=' . $total_pages . '">' . $total_pages . '</a></li>';
    }
    
    // Következő oldal gomb
    if ($current_page < $total_pages) {
        $next_url = $base_url . '&page=' . ($current_page + 1);
        $pagination .= '<li><a href="' . $next_url . '">&raquo;</a></li>';
    }
    
    return $pagination;
}

// Formátum dátumokhoz
function format_date($date, $format = 'Y.m.d H:i') {
    if (empty($date)) return '-';
    $timestamp = strtotime($date);
    return date($format, $timestamp);
}

// Státusz formázása
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

// Nem formázása
function format_gender($gender) {
    $genders = [
        'ferfi' => 'Férfi',
        'no' => 'Nő',
        'egyeb' => 'Egyéb',
        'nem_publikus' => 'Nem publikus'
    ];
    
    return $genders[$gender] ?? $gender;
}

// Tevékenység kategória formázása
function format_activity_category($category) {
    $categories = [
        'bejelentkezes' => 'Bejelentkezés',
        'profil' => 'Profil',
        'biztonsag' => 'Biztonság',
        'egyeb' => 'Egyéb'
    ];
    
    return $categories[$category] ?? $category;
}

// Email ellenőrzés státusz formázása
function format_email_status($verification) {
    if ($verification['ellenorzve']) {
        return '<span class="admin-badge admin-badge-success">Megerősítve</span>';
    } elseif (strtotime($verification['lejarati_ido']) < time()) {
        return '<span class="admin-badge admin-badge-danger">Lejárt</span>';
    } else {
        return '<span class="admin-badge admin-badge-warning">Függőben</span>';
    }
}

// Jelszó visszaállítás státusz formázása
function format_reset_status($reset) {
    if ($reset['felhasznalva']) {
        return '<span class="admin-badge admin-badge-success">Felhasználva</span>';
    } elseif (strtotime($reset['lejarati_ido']) < time()) {
        return '<span class="admin-badge admin-badge-danger">Lejárt</span>';
    } else {
        return '<span class="admin-badge admin-badge-warning">Aktív</span>';
    }
}
?>