<?php
require_once ROOT_PATH . '/includes/admin_functions.php';

check_admin_access();
check_expired_bans();

if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    switch ($_GET['action']) {
        case 'get_user_data':
            if (isset($_GET['user_id'])) {
                $user_id = intval($_GET['user_id']);
                $user_data = get_user_by_id($user_id);
                if ($user_data) {
                    echo json_encode(['success' => true, 'user' => $user_data]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Felhasználó nem található']);
                }
            }
            exit;
            
        case 'refresh_data':
            $tab = $_GET['tab'] ?? 'dashboard';
            $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
            $search = isset($_GET['search']) ? trim($_GET['search']) : '';
            $status = isset($_GET['status']) ? $_GET['status'] : '';
            $category = isset($_GET['category']) ? $_GET['category'] : '';
            $order_by = isset($_GET['order_by']) ? $_GET['order_by'] : '';
            $order_dir = isset($_GET['order_dir']) ? $_GET['order_dir'] : 'DESC';
            
            $data = [];
            switch ($tab) {
                case 'users': $data = get_users($page, 15, $search, $status, $order_by, $order_dir); break;
                case 'admin-activities': $data = get_admin_activities($page, 15, $search, $order_by, $order_dir); break;
                case 'user-activities': $data = get_user_activities($page, 15, $search, $category, $order_by, $order_dir); break;
                case 'email-verifications': $data = get_email_verifications($page, 15, $search, $order_by, $order_dir); break;
                case 'password-resets': $data = get_password_resets($page, 15, $search, $order_by, $order_dir); break;
                case 'deleted-users': $data = get_deleted_users($page, 15, $search, $order_by, $order_dir); break;
                case 'comments': $data = get_comments($page, 15, $search, $order_by, $order_dir); break;
                case 'deleted-comments': $data = get_deleted_comments($page, 15, $search, $order_by, $order_dir); break;
                case 'dashboard':
                    $stats = get_admin_stats();
                    $recent_activities = get_user_activities(1, 10)['activities'];
                    echo json_encode(['success' => true, 'stats' => $stats, 'recent_activities' => $recent_activities]);
                    exit;
                case 'check_expired_bans':
                    $expired_count = check_expired_bans();
                    echo json_encode(['success' => true, 'expired_count' => $expired_count]);
                    exit;
                case 'get_comment':
                    if (isset($_GET['comment_id'])) {
                        $comment_id = intval($_GET['comment_id']);
                        $comment_data = get_comment_by_id($comment_id);
                        if ($comment_data) {
                            echo json_encode(['success' => true, 'comment' => $comment_data]);
                        } else {
                            echo json_encode(['success' => false, 'message' => 'Megjegyzés nem található']);
                        }
                    }
                    exit;
            }
            echo json_encode(['success' => true, 'data' => $data]);
            exit;
    }
}

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : '';
$order_dir = isset($_GET['order_dir']) ? $_GET['order_dir'] : 'DESC';
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard';

$stats = get_admin_stats();
$data = [];

switch ($tab) {
    case 'users': $data = get_users($page, 15, $search, $status, $order_by, $order_dir); break;
    case 'admin-activities': $data = get_admin_activities($page, 15, $search, $order_by, $order_dir); break;
    case 'user-activities': $data = get_user_activities($page, 15, $search, $category, $order_by, $order_dir); break;
    case 'email-verifications': $data = get_email_verifications($page, 15, $search, $order_by, $order_dir); break;
    case 'password-resets': $data = get_password_resets($page, 15, $search, $order_by, $order_dir); break;
    case 'deleted-users': $data = get_deleted_users($page, 15, $search, $order_by, $order_dir); break;
    case 'comments': $data = get_comments($page, 15, $search, $order_by, $order_dir); break;
    case 'deleted-comments': $data = get_deleted_comments($page, 15, $search, $order_by, $order_dir); break;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    switch ($action) {
        case 'update_status':
            $user_id = $_POST['user_id'] ?? 0;
            $new_status = $_POST['status'] ?? '';
            $reason = $_POST['reason'] ?? '';
            $until = $_POST['until'] ?? null;
            if (in_array($new_status, ['Aktiv', 'Fuggoben', 'Kitiltott'])) {
                $success = update_user_status($user_id, $new_status, $reason, $until);
                if ($success) {
                    $_SESSION['admin_message'] = ['type' => 'success', 'message' => 'Felhasználó státusza sikeresen frissítve!'];
                } else {
                    $_SESSION['admin_message'] = ['type' => 'error', 'message' => 'Hiba történt a státusz frissítése során!'];
                }
            }
            break;
            
        case 'update_user':
            $user_id = $_POST['user_id'] ?? 0;
            $user_data = [
                'fnev' => $_POST['fnev'] ?? '',
                'email' => $_POST['email'] ?? '',
                'knev' => $_POST['knev'] ?? '',
                'vnev' => $_POST['vnev'] ?? '',
                'nem' => $_POST['nem'] ?? 'nem_publikus',
                'szuletett' => $_POST['szuletett'] ?? null,
                'telefon' => $_POST['telefon'] ?? '',
                'varmegye' => $_POST['varos'] ?? '',
                'reszletek' => $_POST['reszletek'] ?? ''
            ];
            $success = update_user_data($user_id, $user_data);
            if ($success) {
                $_SESSION['admin_message'] = ['type' => 'success', 'message' => 'Felhasználó adatai sikeresen frissítve!'];
            } else {
                $_SESSION['admin_message'] = ['type' => 'error', 'message' => 'Hiba történt az adatok frissítése során!'];
            }
            break;
            
        case 'update_role':
            $user_id = $_POST['user_id'] ?? 0;
            $role = $_POST['role'] ?? 'felhasznalo';
            $success = update_user_role($user_id, $role);
            if ($success) {
                $role_text = $role === 'admin' ? 'admin' : 'felhasználó';
                $_SESSION['admin_message'] = ['type' => 'success', 'message' => "Felhasználó szerepe sikeresen módosítva {$role_text} rangra!"];
            } else {
                $_SESSION['admin_message'] = ['type' => 'error', 'message' => 'Hiba történt a szerep módosítása során!'];
            }
            break;
            
        case 'delete_user':
            $user_id = $_POST['user_id'] ?? 0;
            $success = delete_user($user_id);
            if ($success) {
                $_SESSION['admin_message'] = ['type' => 'success', 'message' => 'Felhasználó sikeresen törölve!'];
            } else {
                $_SESSION['admin_message'] = ['type' => 'error', 'message' => 'Hiba történt a felhasználó törlése során!'];
            }
            break;
            
        case 'delete_activity':
            $activity_id = $_POST['activity_id'] ?? 0;
            $table = $_POST['table'] ?? '';
            $success = delete_activity($activity_id, $table);
            if ($success) {
                $_SESSION['admin_message'] = ['type' => 'success', 'message' => 'Tevékenység sikeresen törölve!'];
            } else {
                $_SESSION['admin_message'] = ['type' => 'error', 'message' => 'Hiba történt a tevékenység törlése során!'];
            }
            break;
            
        case 'delete_comment':
            $meid = $_POST['meid'] ?? 0;
            $torles_oka = $_POST['torles_oka'] ?? '';
            if ($meid > 0) {
                $success = move_comment_to_deleted($meid, 'admin', $torles_oka);
                if ($success) {
                    $_SESSION['admin_message'] = ['type' => 'success', 'message' => 'Megjegyzés sikeresen törölve!'];
                } else {
                    $_SESSION['admin_message'] = ['type' => 'error', 'message' => 'Hiba történt a törlés során!'];
                }
            }
            break;

        case 'update_comment':
            $meid = $_POST['meid'] ?? 0;
            $megjegyzes = $_POST['megjegyzes'] ?? '';
            $ertekeles = $_POST['ertekeles'] ?? 5;
            if ($meid > 0) {
                $success = update_comment($meid, $megjegyzes, $ertekeles);
                if ($success) {
                    $_SESSION['admin_message'] = ['type' => 'success', 'message' => 'Megjegyzés sikeresen frissítve!'];
                } else {
                    $_SESSION['admin_message'] = ['type' => 'error', 'message' => 'Hiba történt a szerkesztés során!'];
                }
            }
            break;
            
        case 'permanent_delete_comment':
            $meid = $_POST['meid'] ?? 0;
            if ($meid > 0) {
                $success = permanent_delete_comment($meid);
                if ($success) {
                    $_SESSION['admin_message'] = ['type' => 'success', 'message' => 'Megjegyzés véglegesen törölve!'];
                } else {
                    $_SESSION['admin_message'] = ['type' => 'error', 'message' => 'Hiba történt a végleges törlés során!'];
                }
            }
            break;
            
        case 'bulk_delete_comments':
            $ids_string = $_POST['selected_ids'] ?? '';
            if (!empty($ids_string)) {
                $ids = explode(',', $ids_string);
                $count = bulk_delete_comments($ids);
                $_SESSION['admin_message'] = ['type' => 'success', 'message' => "$count megjegyzés sikeresen törölve!"];
            }
            break;
            
        case 'bulk_permanent_delete_comments':
            $ids_string = $_POST['selected_ids'] ?? '';
            if (!empty($ids_string)) {
                $ids = explode(',', $ids_string);
                $count = bulk_permanent_delete_comments($ids);
                $_SESSION['admin_message'] = ['type' => 'success', 'message' => "$count megjegyzés VÉGLEGESEN törölve!"];
            }
            break;
    }
    header('Location: ' . base_url('webadmin?tab=' . $tab));
    exit;
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebAdmin - Villám Meló</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/admin.css'); ?>">
</head>
<body>
    <div class="admin-container">
        <aside class="admin-sidebar">
            <div class="admin-sidebar-header">
                <div class="admin-sidebar-title"><a href="index.php">Villám Meló</a></div>
                <div class="admin-sidebar-subtitle">WebAdmin Panel</div>
            </div>
            
            <nav class="admin-nav">
                <div class="admin-nav-item">
                    <a href="#dashboard" class="admin-nav-link <?php echo $tab === 'dashboard' ? 'active' : ''; ?>" data-tab="dashboard">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Áttekintés</span>
                    </a>
                </div>
                
                <div class="admin-nav-item">
                    <a href="#users" class="admin-nav-link <?php echo $tab === 'users' ? 'active' : ''; ?>" data-tab="users">
                        <i class="fas fa-users"></i>
                        <span>Felhasználók</span>
                    </a>
                </div>
                
                <div class="admin-nav-item">
                    <a href="#admin-activities" class="admin-nav-link <?php echo $tab === 'admin-activities' ? 'active' : ''; ?>" data-tab="admin-activities">
                        <i class="fas fa-user-shield"></i>
                        <span>Admin Tevékenységek</span>
                    </a>
                </div>
                
                <div class="admin-nav-item">
                    <a href="#user-activities" class="admin-nav-link <?php echo $tab === 'user-activities' ? 'active' : ''; ?>" data-tab="user-activities">
                        <i class="fas fa-history"></i>
                        <span>Felhasználói Tevékenységek</span>
                    </a>
                </div>
                
                <div class="admin-nav-item">
                    <a href="#email-verifications" class="admin-nav-link <?php echo $tab === 'email-verifications' ? 'active' : ''; ?>" data-tab="email-verifications">
                        <i class="fas fa-envelope"></i>
                        <span>Email Ellenőrzések</span>
                    </a>
                </div>
                
                <div class="admin-nav-item">
                    <a href="#password-resets" class="admin-nav-link <?php echo $tab === 'password-resets' ? 'active' : ''; ?>" data-tab="password-resets">
                        <i class="fas fa-key"></i>
                        <span>Jelszó Visszaállítások</span>
                    </a>
                </div>
                              
                <div class="admin-nav-item">
                    <a href="#comments" class="admin-nav-link <?php echo $tab === 'comments' ? 'active' : ''; ?>" data-tab="comments">
                        <i class="fas fa-comments"></i>
                        <span>Megjegyzések</span>
                    </a>
                </div>
                
                <div class="admin-nav-item">
                    <a href="#deleted-comments" class="admin-nav-link <?php echo $tab === 'deleted-comments' ? 'active' : ''; ?>" data-tab="deleted-comments">
                        <i class="fas fa-trash-alt"></i>
                        <span>Törölt Megjegyzések</span>
                    </a>
                </div>

                <div class="admin-nav-item">
                    <a href="#deleted-users" class="admin-nav-link <?php echo $tab === 'deleted-users' ? 'active' : ''; ?>" data-tab="deleted-users">
                        <i class="fas fa-trash-alt"></i>
                        <span>Törölt Felhasználók</span>
                    </a>
                </div>
            </nav>
        </aside>

        <main class="admin-main">
            <header class="admin-header">
                <div class="admin-header-content">
                    <h1>
                        <?php 
                        $titles = [
                            'dashboard' => 'Áttekintés',
                            'users' => 'Felhasználók Kezelése',
                            'admin-activities' => 'Admin Tevékenységek',
                            'user-activities' => 'Felhasználói Tevékenységek',
                            'email-verifications' => 'Email Ellenőrzések',
                            'password-resets' => 'Jelszó Visszaállítások',
                            'deleted-users' => 'Törölt Felhasználók',
                            'comments' => 'Megjegyzések Kezelése',
                            'deleted-comments' => 'Törölt Megjegyzések'
                        ];
                        echo $titles[$tab] ?? 'WebAdmin';
                        ?>
                    </h1>
                    
                    <div class="admin-user-menu">
                        <div class="theme-toggle">
                            <label class="theme-switch">
                                <input type="checkbox" id="themeToggle">
                                <span class="theme-slider"></span>
                            </label>
                        </div>
                        
                        <div class="admin-user-info">
                            <img src="<?php echo base_url('assets/images/profile/' . ($_SESSION['profilkep'] ?? 'default.png')); ?>" 
                                alt="Profilkép" class="admin-user-avatar" 
                                onerror="this.src='<?php echo base_url('assets/images/profile/default.png'); ?>'">
                            <span><?php echo htmlspecialchars($_SESSION['fnev']); ?></span>
                            <i class="fas fa-chevron-down" style="font-size: 0.8rem;"></i>
                        </div>
                        
                        <div class="admin-user-dropdown">
                            <a href="<?php echo base_url('home'); ?>">
                                <i class="fas fa-home"></i>
                                <span>Villám Meló</span>
                            </a>
                            <a href="<?php echo base_url('logout'); ?>">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Kijelentkezés</span>
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <?php if (isset($_SESSION['admin_message'])): ?>
                <div class="admin-alert admin-alert-<?php echo $_SESSION['admin_message']['type']; ?>">
                    <?php echo $_SESSION['admin_message']['message']; ?>
                </div>
                <?php unset($_SESSION['admin_message']); ?>
            <?php endif; ?>

            <div class="admin-tab-content <?php echo $tab === 'dashboard' ? 'active' : ''; ?>" id="dashboard-content">
                <?php if ($stats): ?>
                <div class="admin-stats-grid" id="stats-container">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Összes Felhasználó</div>
                        <div class="admin-stat-value"><?php echo $stats['users']['total']; ?></div>
                        <div class="admin-stat-details">
                            Aktív: <?php echo $stats['users']['active']; ?> | 
                            Függőben: <?php echo $stats['users']['pending']; ?>
                        </div>
                    </div>
                    
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Kitiltott Felhasználók</div>
                        <div class="admin-stat-value"><?php echo $stats['users']['banned']; ?></div>
                        <div class="admin-stat-details">
                            Összes kitiltott felhasználó
                        </div>
                    </div>
                    
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Admin Tevékenységek</div>
                        <div class="admin-stat-value"><?php echo $stats['admin_activities']['total']; ?></div>
                        <div class="admin-stat-details">
                            Ma: <?php echo $stats['admin_activities']['today']; ?>
                        </div>
                    </div>
                    
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Felhasználói Tevékenységek</div>
                        <div class="admin-stat-value"><?php echo $stats['user_activities']['total']; ?></div>
                        <div class="admin-stat-details">
                            Ma: <?php echo $stats['user_activities']['today']; ?> | 
                            Sikertelen: <?php echo $stats['user_activities']['failed']; ?>
                        </div>
                    </div>
                    
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Email Ellenőrzések</div>
                        <div class="admin-stat-value"><?php echo $stats['email_verifications']['total']; ?></div>
                        <div class="admin-stat-details">
                            Megerősítve: <?php echo $stats['email_verifications']['verified']; ?> | 
                            Függőben: <?php echo $stats['email_verifications']['pending']; ?>
                        </div>
                    </div>
                    
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Megjegyzések</div>
                        <div class="admin-stat-value"><?php echo $stats['comments']['total'] ?? 0; ?></div>
                        <div class="admin-stat-details">
                            Ma: <?php echo $stats['comments']['today'] ?? 0; ?> | 
                            Átlag: <?php echo number_format($stats['comments']['avg_rating'] ?? 0, 1); ?>/5
                        </div>
                    </div>
                </div>

                <div class="admin-card">
                    <div class="admin-card-header">
                        <h2>Legutóbbi Tevékenységek</h2>
                    </div>
                    <div class="admin-card-body">
                        <div id="recent-activities-container">
                            <?php 
                            $recent_activities = get_user_activities(1, 10)['activities'];
                            if (!empty($recent_activities)): 
                            ?>
                                <div class="admin-table-container">
                                    <table class="admin-table">
                                        <thead>
                                            <tr>
                                                <th>Felhasználó</th>
                                                <th>Tevékenység</th>
                                                <th>Kategória</th>
                                                <th>Időpont</th>
                                                <th>Sikeresség</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recent_activities as $activity): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($activity['fnev'] ?? 'Ismeretlen'); ?></td>
                                                <td><?php echo htmlspecialchars($activity['tevekenyseg']); ?></td>
                                                <td><?php echo format_activity_category($activity['kategoria']); ?></td>
                                                <td><?php echo format_date($activity['idopont']); ?></td>
                                                <td>
                                                    <?php if ($activity['sikeresseg'] === 'sikeres'): ?>
                                                        <span class="admin-badge admin-badge-success">Sikeres</span>
                                                    <?php else: ?>
                                                        <span class="admin-badge admin-badge-danger">Sikertelen</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p>Nincsenek tevékenységek.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="admin-tab-content <?php echo $tab === 'users' ? 'active' : ''; ?>" id="users-content">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h2>Felhasználók Kezelése</h2>
                    </div>
                    <div class="admin-card-body">
                        <div class="admin-search-bar">
                            <form method="GET" class="admin-search-form" style="display: flex; gap: 10px; width: 100%;">
                                <input type="hidden" name="url" value="webadmin">
                                <input type="hidden" name="tab" value="users">
                                
                                <?php if (!empty($order_by)): ?>
                                    <input type="hidden" name="order_by" value="<?php echo htmlspecialchars($order_by); ?>">
                                <?php endif; ?>
                                <?php if (!empty($order_dir)): ?>
                                    <input type="hidden" name="order_dir" value="<?php echo htmlspecialchars($order_dir); ?>">
                                <?php endif; ?>
                                
                                <input type="text" name="search" class="admin-form-control" placeholder="Keresés..." value="<?php echo htmlspecialchars($search); ?>">
                                <select name="status" class="admin-form-control" style="width: 200px;">
                                    <option value="">Minden státusz</option>
                                    <option value="Aktiv" <?php echo $status === 'Aktiv' ? 'selected' : ''; ?>>Aktív</option>
                                    <option value="Fuggoben" <?php echo $status === 'Fuggoben' ? 'selected' : ''; ?>>Függőben</option>
                                    <option value="Kitiltott" <?php echo $status === 'Kitiltott' ? 'selected' : ''; ?>>Kitiltott</option>
                                </select>
                                <button type="submit" class="admin-btn admin-btn-primary">Keresés</button>
                                
                                <?php if ($search || $status): ?>
                                    <a href="<?php 
                                        $params = ['url' => 'webadmin', 'tab' => 'users'];
                                        if (!empty($order_by)) $params['order_by'] = $order_by;
                                        if (!empty($order_dir)) $params['order_dir'] = $order_dir;
                                        echo base_url('webadmin?' . http_build_query($params));
                                    ?>" class="admin-btn admin-btn-secondary">Összes</a>
                                <?php endif; ?>
                            </form>
                        </div>

                        <div class="admin-table-container">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th class="sortable <?php echo $order_by === 'fid' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="fid">ID</th>
                                        <th class="sortable <?php echo $order_by === 'fnev' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="fnev">Felhasználónév</th>
                                        <th class="sortable <?php echo $order_by === 'email' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="email">Email</th>
                                        <th>Név</th>
                                        <th class="sortable <?php echo $order_by === 'szerep' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="szerep">Szerep</th>
                                        <th class="sortable <?php echo $order_by === 'statusz' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="statusz">Státusz</th>
                                        <th>Email Megerősítve</th>
                                        <th>Kitiltás Oka</th>
                                        <th>Kitiltás Határideje</th>
                                        <th class="sortable <?php echo $order_by === 'regisztralt' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="regisztralt">Regisztrált</th>
                                        <th>Műveletek</th>
                                    </tr>
                                </thead>
                                <tbody id="users-table-body">
                                    <?php if (!empty($data['users'])): ?>
                                        <?php foreach ($data['users'] as $user): ?>
                                        <tr>
                                            <td><?php echo $user['fid']; ?></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($user['fnev']); ?></strong>
                                            </td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td>
                                                <?php if ($user['vnev'] || $user['knev']): ?>
                                                    <?php echo htmlspecialchars(($user['vnev'] ?? '') . ' ' . ($user['knev'] ?? '')); ?>
                                                <?php else: ?>
                                                    <em>Nincs megadva</em>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="admin-badge <?php echo $user['szerep'] === 'admin' ? 'admin-badge-warning' : 'admin-badge-secondary'; ?>">
                                                    <?php echo $user['szerep']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo format_status($user['statusz']); ?></td>
                                            <td>
                                                <?php if ($user['email_megerositve']): ?>
                                                    <span class="admin-badge admin-badge-success">Igen</span>
                                                <?php else: ?>
                                                    <span class="admin-badge admin-badge-warning">Nem</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($user['statusz_ok'] ?? '-'); ?></td>
                                            <td><?php echo $user['statusz_meddig'] ? format_date($user['statusz_meddig']) : '-'; ?></td>
                                            <td><?php echo format_date($user['regisztralt']); ?></td>
                                            <td>
                                                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                                    <button class="admin-btn admin-btn-sm admin-btn-primary" 
                                                            onclick="showEditModal(<?php echo $user['fid']; ?>)">
                                                        Szerkesztés
                                                    </button>
                                                    <button class="admin-btn admin-btn-sm admin-btn-info" 
                                                            onclick="showRoleModal(<?php echo $user['fid']; ?>, '<?php echo $user['szerep']; ?>', '<?php echo htmlspecialchars($user['fnev']); ?>')">
                                                        Szerep
                                                    </button>
                                                    <button class="admin-btn admin-btn-sm admin-btn-warning" 
                                                            onclick="showStatusModal(<?php echo $user['fid']; ?>, '<?php echo $user['statusz']; ?>', '<?php echo htmlspecialchars($user['fnev']); ?>')">
                                                        Státusz
                                                    </button>
                                                    <?php if ($user['szerep'] !== 'admin'): ?>
                                                    <button class="admin-btn admin-btn-sm admin-btn-danger" 
                                                            onclick="deleteUser(<?php echo $user['fid']; ?>, '<?php echo htmlspecialchars($user['fnev']); ?>')">
                                                        Törlés
                                                    </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="11" style="text-align: center; padding: 2rem;">
                                                Nincsenek felhasználók.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="admin-pagination-wrapper" style="<?php echo (isset($data['pages']) && $data['pages'] > 1) ? '' : 'display: none;'; ?>">
                            <div class="pagination-info">
                                <?php if (isset($data['total'])): ?>
                                    <span>Oldal <?php echo $data['current_page']; ?> / <?php echo $data['pages']; ?></span>
                                    <span style="margin-left: 15px;">Összesen: <?php echo $data['total']; ?> felhasználó</span>
                                <?php endif; ?>
                            </div>
                            <ul class="admin-pagination" id="users-pagination">
                                <?php 
                                if (isset($data['pages']) && $data['pages'] > 1) {
                                    $params = [
                                        'url' => 'webadmin',
                                        'tab' => 'users',
                                        'search' => $search,
                                        'status' => $status,
                                        'order_by' => $order_by,
                                        'order_dir' => $order_dir
                                    ];
                                    $params = array_filter($params, function($value) { return $value !== '' && $value !== null; });
                                    $base_url = base_url('webadmin?' . http_build_query($params));
                                    echo generate_pagination($data['current_page'], $data['pages'], $base_url);
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-tab-content <?php echo $tab === 'admin-activities' ? 'active' : ''; ?>" id="admin-activities-content">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h2>Admin Tevékenységek</h2>
                    </div>
                    <div class="admin-card-body">
                    <div class="admin-search-bar">
                        <form method="GET" class="admin-search-form" style="display: flex; gap: 10px; width: 100%;">
                            <input type="hidden" name="url" value="webadmin">
                            <input type="hidden" name="tab" value="admin-activities">
                            
                            <?php if (!empty($order_by)): ?>
                                <input type="hidden" name="order_by" value="<?php echo htmlspecialchars($order_by); ?>">
                            <?php endif; ?>
                            <?php if (!empty($order_dir)): ?>
                                <input type="hidden" name="order_dir" value="<?php echo htmlspecialchars($order_dir); ?>">
                            <?php endif; ?>
                            
                            <input type="text" name="search" class="admin-form-control" placeholder="Keresés..." value="<?php echo htmlspecialchars($search); ?>">
                            <button type="submit" class="admin-btn admin-btn-primary">Keresés</button>
                            
                            <?php if ($search): ?>
                                <a href="<?php 
                                    $params = ['url' => 'webadmin', 'tab' => 'admin-activities'];
                                    if (!empty($order_by)) $params['order_by'] = $order_by;
                                    if (!empty($order_dir)) $params['order_dir'] = $order_dir;
                                    echo base_url('webadmin?' . http_build_query($params));
                                ?>" class="admin-btn admin-btn-secondary">Összes</a>
                            <?php endif; ?>
                        </form>
                    </div>

                        <div class="admin-table-container">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th class="sortable <?php echo $order_by === 'atid' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="atid">ID</th>
                                        <th>Adminisztrátor</th>
                                        <th>Cél felhasználó</th>
                                        <th class="sortable <?php echo $order_by === 'tevekenyseg' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="tevekenyseg">Tevékenység</th>
                                        <th>Részletek</th>
                                        <th class="sortable <?php echo $order_by === 'idopont' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="idopont">Időpont</th>
                                    </tr>
                                </thead>
                                <tbody id="admin-activities-table-body">
                                    <?php if (!empty($data['activities'])): ?>
                                        <?php foreach ($data['activities'] as $activity): ?>
                                        <tr>
                                            <td><?php echo $activity['atid']; ?></td>
                                            <td>
                                                <?php if ($activity['admin_felhasznalo']): ?>
                                                    <?php echo htmlspecialchars($activity['admin_felhasznalo']); ?>
                                                <?php else: ?>
                                                    <em>Ismeretlen</em>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($activity['cel_felhasznalo']): ?>
                                                    <?php echo htmlspecialchars($activity['cel_felhasznalo']); ?>
                                                <?php else: ?>
                                                    <em>-</em>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($activity['tevekenyseg']); ?></td>
                                            <td><?php echo htmlspecialchars($activity['reszletek'] ?? '-'); ?></td>
                                            <td><?php echo format_date($activity['idopont']); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" style="text-align: center; padding: 2rem;">
                                                Nincsenek admin tevékenységek.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="admin-pagination-wrapper" style="<?php echo (isset($data['pages']) && $data['pages'] > 1) ? '' : 'display: none;'; ?>">
                            <div class="pagination-info">
                                <?php if (isset($data['total'])): ?>
                                    <span>Oldal <?php echo $data['current_page']; ?> / <?php echo $data['pages']; ?></span>
                                    <span style="margin-left: 15px;">Összesen: <?php echo $data['total']; ?> tevékenység</span>
                                <?php endif; ?>
                            </div>
                            <ul class="admin-pagination" id="admin-activities-pagination">
                                <?php 
                                if (isset($data['pages']) && $data['pages'] > 1) {
                                    $base_url = base_url('webadmin?tab=admin-activities&search=' . urlencode($search) . '&order_by=' . $order_by . '&order_dir=' . $order_dir);
                                    echo generate_pagination($data['current_page'], $data['pages'], $base_url);
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-tab-content <?php echo $tab === 'user-activities' ? 'active' : ''; ?>" id="user-activities-content">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h2>Felhasználói Tevékenységek</h2>
                    </div>
                    <div class="admin-card-body">
                    <div class="admin-search-bar">
                        <form method="GET" class="admin-search-form" style="display: flex; gap: 10px; width: 100%;">
                            <input type="hidden" name="url" value="webadmin">
                            <input type="hidden" name="tab" value="user-activities">
                            
                            <?php if (!empty($order_by)): ?>
                                <input type="hidden" name="order_by" value="<?php echo htmlspecialchars($order_by); ?>">
                            <?php endif; ?>
                            <?php if (!empty($order_dir)): ?>
                                <input type="hidden" name="order_dir" value="<?php echo htmlspecialchars($order_dir); ?>">
                            <?php endif; ?>
                            
                            <input type="text" name="search" class="admin-form-control" placeholder="Keresés..." value="<?php echo htmlspecialchars($search); ?>">
                            <select name="category" class="admin-form-control" style="width: 200px;">
                                <option value="">Minden kategória</option>
                                <option value="bejelentkezes" <?php echo $category === 'bejelentkezes' ? 'selected' : ''; ?>>Bejelentkezés</option>
                                <option value="profil" <?php echo $category === 'profil' ? 'selected' : ''; ?>>Profil</option>
                                <option value="biztonsag" <?php echo $category === 'biztonsag' ? 'selected' : ''; ?>>Biztonság</option>
                                <option value="egyeb" <?php echo $category === 'egyeb' ? 'selected' : ''; ?>>Egyéb</option>
                            </select>
                            <button type="submit" class="admin-btn admin-btn-primary">Keresés</button>
                            
                            <?php if ($search || $category): ?>
                                <a href="<?php 
                                    $params = ['url' => 'webadmin', 'tab' => 'user-activities'];
                                    if (!empty($order_by)) $params['order_by'] = $order_by;
                                    if (!empty($order_dir)) $params['order_dir'] = $order_dir;
                                    echo base_url('webadmin?' . http_build_query($params));
                                ?>" class="admin-btn admin-btn-secondary">Összes</a>
                            <?php endif; ?>
                        </form>
                    </div>

                        <div class="admin-table-container">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th class="sortable <?php echo $order_by === 'ftid' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="ftid">ID</th>
                                        <th>Felhasználó</th>
                                        <th class="sortable <?php echo $order_by === 'tevekenyseg' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="tevekenyseg">Tevékenység</th>
                                        <th class="sortable <?php echo $order_by === 'kategoria' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="kategoria">Kategória</th>
                                        <th class="sortable <?php echo $order_by === 'idopont' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="idopont">Időpont</th>
                                        <th class="sortable <?php echo $order_by === 'sikeresseg' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="sikeresseg">Sikeresség</th>
                                        <th>Műveletek</th>
                                    </tr>
                                </thead>
                                <tbody id="user-activities-table-body">
                                    <?php if (!empty($data['activities'])): ?>
                                        <?php foreach ($data['activities'] as $activity): ?>
                                        <tr>
                                            <td><?php echo $activity['ftid']; ?></td>
                                            <td>
                                                <?php if ($activity['fnev']): ?>
                                                    <?php echo htmlspecialchars($activity['fnev']); ?><br>
                                                    <small><?php echo htmlspecialchars($activity['email']); ?></small>
                                                <?php else: ?>
                                                    <em>Ismeretlen</em>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($activity['tevekenyseg']); ?></td>
                                            <td>
                                                <span class="admin-badge admin-badge-secondary">
                                                    <?php echo format_activity_category($activity['kategoria']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo format_date($activity['idopont']); ?></td>
                                            <td>
                                                <?php if ($activity['sikeresseg'] === 'sikeres'): ?>
                                                    <span class="admin-badge admin-badge-success">Sikeres</span>
                                                <?php else: ?>
                                                    <span class="admin-badge admin-badge-danger">Sikertelen</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button class="admin-btn admin-btn-sm admin-btn-danger" 
                                                        onclick="deleteActivity(<?php echo $activity['ftid']; ?>, '<?php echo htmlspecialchars($activity['tevekenyseg']); ?>', '<?php echo htmlspecialchars($activity['fnev']); ?>')">
                                                    Törlés
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" style="text-align: center; padding: 2rem;">
                                                Nincsenek felhasználói tevékenységek.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="admin-pagination-wrapper" style="<?php echo (isset($data['pages']) && $data['pages'] > 1) ? '' : 'display: none;'; ?>">
                            <div class="pagination-info">
                                <?php if (isset($data['total'])): ?>
                                    <span>Oldal <?php echo $data['current_page']; ?> / <?php echo $data['pages']; ?></span>
                                    <span style="margin-left: 15px;">Összesen: <?php echo $data['total']; ?> tevékenység</span>
                                <?php endif; ?>
                            </div>
                            <ul class="admin-pagination" id="user-activities-pagination">
                                <?php 
                                if (isset($data['pages']) && $data['pages'] > 1) {
                                    $base_url = base_url('webadmin?tab=user-activities&search=' . urlencode($search) . '&category=' . urlencode($category) . '&order_by=' . $order_by . '&order_dir=' . $order_dir);
                                    echo generate_pagination($data['current_page'], $data['pages'], $base_url);
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-tab-content <?php echo $tab === 'email-verifications' ? 'active' : ''; ?>" id="email-verifications-content">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h2>Email Ellenőrzések</h2>
                    </div>
                    <div class="admin-card-body">
                        <div class="admin-search-bar">
                            <form method="GET" class="admin-search-form" style="display: flex; gap: 10px; width: 100%;">
                                <input type="hidden" name="url" value="webadmin">
                                <input type="hidden" name="tab" value="email-verifications">
                                <input type="text" name="search" class="admin-form-control" placeholder="Keresés..." value="<?php echo htmlspecialchars($search); ?>">
                                <button type="submit" class="admin-btn admin-btn-primary">Keresés</button>
                                <?php if ($search): ?>
                                    <a href="<?php echo base_url('webadmin?tab=email-verifications'); ?>" class="admin-btn admin-btn-secondary">Összes</a>
                                <?php endif; ?>
                            </form>
                        </div>

                        <div class="admin-table-container">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th class="sortable <?php echo $order_by === 'emid' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="emid">ID</th>
                                        <th>Felhasználó</th>
                                        <th>Email</th>
                                        <th class="sortable <?php echo $order_by === 'ellenorzve' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="ellenorzve">Státusz</th>
                                        <th class="sortable <?php echo $order_by === 'lejarati_ido' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="lejarati_ido">Lejárat</th>
                                        <th class="sortable <?php echo $order_by === 'idopont' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="idopont">Létrehozva</th>
                                        <th>Műveletek</th>
                                    </tr>
                                </thead>
                                <tbody id="email-verifications-table-body">
                                    <?php if (!empty($data['verifications'])): ?>
                                        <?php foreach ($data['verifications'] as $verification): ?>
                                        <tr>
                                            <td><?php echo $verification['emid']; ?></td>
                                            <td><?php echo htmlspecialchars($verification['fnev']); ?></td>
                                            <td><?php echo htmlspecialchars($verification['email']); ?></td>
                                            <td><?php echo format_email_status($verification); ?></td>
                                            <td><?php echo format_date($verification['lejarati_ido']); ?></td>
                                            <td><?php echo format_date($verification['idopont']); ?></td>
                                            <td>
                                                <button class="admin-btn admin-btn-sm admin-btn-danger" 
                                                        onclick="deleteActivity(<?php echo $verification['emid']; ?>, 'Email ellenőrzés', '<?php echo htmlspecialchars($verification['fnev']); ?>')">
                                                    Törlés
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" style="text-align: center; padding: 2rem;">
                                                Nincsenek email ellenőrzések.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="admin-pagination-wrapper" style="<?php echo (isset($data['pages']) && $data['pages'] > 1) ? '' : 'display: none;'; ?>">
                            <div class="pagination-info">
                                <?php if (isset($data['total'])): ?>
                                    <span>Oldal <?php echo $data['current_page']; ?> / <?php echo $data['pages']; ?></span>
                                    <span style="margin-left: 15px;">Összesen: <?php echo $data['total']; ?> email ellenőrzés</span>
                                <?php endif; ?>
                            </div>
                            <ul class="admin-pagination" id="email-verifications-pagination">
                                <?php 
                                if (isset($data['pages']) && $data['pages'] > 1) {
                                    $base_url = base_url('webadmin?tab=email-verifications&search=' . urlencode($search) . '&order_by=' . $order_by . '&order_dir=' . $order_dir);
                                    echo generate_pagination($data['current_page'], $data['pages'], $base_url);
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-tab-content <?php echo $tab === 'password-resets' ? 'active' : ''; ?>" id="password-resets-content">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h2>Jelszó Visszaállítások</h2>
                    </div>
                    <div class="admin-card-body">
                        <div class="admin-search-bar">
                            <form method="GET" class="admin-search-form" style="display: flex; gap: 10px; width: 100%;">
                                <input type="hidden" name="url" value="webadmin">
                                <input type="hidden" name="tab" value="password-resets">
                                <input type="text" name="search" class="admin-form-control" placeholder="Keresés..." value="<?php echo htmlspecialchars($search); ?>">
                                <button type="submit" class="admin-btn admin-btn-primary">Keresés</button>
                                <?php if ($search): ?>
                                    <a href="<?php echo base_url('webadmin?tab=password-resets'); ?>" class="admin-btn admin-btn-secondary">Összes</a>
                                <?php endif; ?>
                            </form>
                        </div>

                        <div class="admin-table-container">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th class="sortable <?php echo $order_by === 'jvid' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="jvid">ID</th>
                                        <th>Felhasználó</th>
                                        <th>Email</th>
                                        <th class="sortable <?php echo $order_by === 'felhasznalva' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="felhasznalva">Státusz</th>
                                        <th class="sortable <?php echo $order_by === 'lejarati_ido' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="lejarati_ido">Lejárat</th>
                                        <th class="sortable <?php echo $order_by === 'idopont' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="idopont">Létrehozva</th>
                                        <th>Műveletek</th>
                                    </tr>
                                </thead>
                                <tbody id="password-resets-table-body">
                                    <?php if (!empty($data['resets'])): ?>
                                        <?php foreach ($data['resets'] as $reset): ?>
                                        <tr>
                                            <td><?php echo $reset['jvid']; ?></td>
                                            <td><?php echo htmlspecialchars($reset['fnev']); ?></td>
                                            <td><?php echo htmlspecialchars($reset['email']); ?></td>
                                            <td><?php echo format_reset_status($reset); ?></td>
                                            <td><?php echo format_date($reset['lejarati_ido']); ?></td>
                                            <td><?php echo format_date($reset['idopont']); ?></td>
                                            <td>
                                                <button class="admin-btn admin-btn-sm admin-btn-danger" 
                                                        onclick="deleteActivity(<?php echo $reset['jvid']; ?>, 'Jelszó visszaállítás', '<?php echo htmlspecialchars($reset['fnev']); ?>')">
                                                    Törlés
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" style="text-align: center; padding: 2rem;">
                                                Nincsenek jelszó visszaállítások.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="admin-pagination-wrapper" style="<?php echo (isset($data['pages']) && $data['pages'] > 1) ? '' : 'display: none;'; ?>">
                            <div class="pagination-info">
                                <?php if (isset($data['total'])): ?>
                                    <span>Oldal <?php echo $data['current_page']; ?> / <?php echo $data['pages']; ?></span>
                                    <span style="margin-left: 15px;">Összesen: <?php echo $data['total']; ?> jelszó visszaállítás</span>
                                <?php endif; ?>
                            </div>
                            <ul class="admin-pagination" id="password-resets-pagination">
                                <?php 
                                if (isset($data['pages']) && $data['pages'] > 1) {
                                    $base_url = base_url('webadmin?tab=password-resets&search=' . urlencode($search) . '&order_by=' . $order_by . '&order_dir=' . $order_dir);
                                    echo generate_pagination($data['current_page'], $data['pages'], $base_url);
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-tab-content <?php echo $tab === 'deleted-users' ? 'active' : ''; ?>" id="deleted-users-content">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h2>Törölt Felhasználók</h2>
                    </div>
                    <div class="admin-card-body">
                        <div class="admin-search-bar">
                            <form method="GET" class="admin-search-form" style="display: flex; gap: 10px; width: 100%;">
                                <input type="hidden" name="url" value="webadmin">
                                <input type="hidden" name="tab" value="deleted-users">
                                <input type="text" name="search" class="admin-form-control" placeholder="Keresés..." value="<?php echo htmlspecialchars($search); ?>">
                                <button type="submit" class="admin-btn admin-btn-primary">Keresés</button>
                                <?php if ($search): ?>
                                    <a href="<?php echo base_url('webadmin?tab=deleted-users'); ?>" class="admin-btn admin-btn-secondary">Összes</a>
                                <?php endif; ?>
                            </form>
                        </div>

                        <div class="admin-table-container">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th class="sortable <?php echo $order_by === 'fid' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="fid">ID</th>
                                        <th class="sortable <?php echo $order_by === 'fnev' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="fnev">Felhasználónév</th>
                                        <th class="sortable <?php echo $order_by === 'email' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="email">Email</th>
                                        <th>Név</th>
                                        <th class="sortable <?php echo $order_by === 'szerep' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="szerep">Szerep</th>
                                        <th class="sortable <?php echo $order_by === 'regisztralt' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="regisztralt">Regisztrált</th>
                                        <th class="sortable <?php echo $order_by === 'torles_idopontja' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="torles_idopontja">Törölve</th>
                                    </tr>
                                </thead>
                                <tbody id="deleted-users-table-body">
                                    <?php if (!empty($data['users'])): ?>
                                        <?php foreach ($data['users'] as $user): ?>
                                        <tr>
                                            <td><?php echo $user['fid']; ?></td>
                                            <td><?php echo htmlspecialchars($user['fnev']); ?></td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td>
                                                <?php if ($user['vnev'] || $user['knev']): ?>
                                                    <?php echo htmlspecialchars(($user['vnev'] ?? '') . ' ' . ($user['knev'] ?? '')); ?>
                                                <?php else: ?>
                                                    <em>Nincs megadva</em>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="admin-badge <?php echo $user['szerep'] === 'admin' ? 'admin-badge-warning' : 'admin-badge-secondary'; ?>">
                                                    <?php echo $user['szerep']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo format_date($user['regisztralt']); ?></td>
                                            <td><?php echo format_date($user['torles_idopontja']); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" style="text-align: center; padding: 2rem;">
                                                Nincsenek törölt felhasználók.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="admin-pagination-wrapper" style="<?php echo (isset($data['pages']) && $data['pages'] > 1) ? '' : 'display: none;'; ?>">
                            <div class="pagination-info">
                                <?php if (isset($data['total'])): ?>
                                    <span>Oldal <?php echo $data['current_page']; ?> / <?php echo $data['pages']; ?></span>
                                    <span style="margin-left: 15px;">Összesen: <?php echo $data['total']; ?> törölt felhasználó</span>
                                <?php endif; ?>
                            </div>
                            <ul class="admin-pagination" id="deleted-users-pagination">
                                <?php 
                                if (isset($data['pages']) && $data['pages'] > 1) {
                                    $base_url = base_url('webadmin?tab=deleted-users&search=' . urlencode($search) . '&order_by=' . $order_by . '&order_dir=' . $order_dir);
                                    echo generate_pagination($data['current_page'], $data['pages'], $base_url);
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-tab-content <?php echo $tab === 'comments' ? 'active' : ''; ?>" id="comments-content">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h2>Megjegyzések Kezelése</h2>
                    </div>
                    <div class="admin-card-body">
                        <div class="admin-search-bar" style="justify-content: space-between; flex-wrap: wrap;">
                            <form method="GET" class="admin-search-form" style="display: flex; gap: 10px; flex: 1;">
                                <input type="hidden" name="url" value="webadmin">
                                <input type="hidden" name="tab" value="comments">
                                <?php if (!empty($order_by)): ?>
                                    <input type="hidden" name="order_by" value="<?php echo htmlspecialchars($order_by); ?>">
                                <?php endif; ?>
                                <?php if (!empty($order_dir)): ?>
                                    <input type="hidden" name="order_dir" value="<?php echo htmlspecialchars($order_dir); ?>">
                                <?php endif; ?>
                                <input type="text" name="search" class="admin-form-control" placeholder="Keresés megjegyzésben vagy felhasználónévben..." value="<?php echo htmlspecialchars($search); ?>">
                                <button type="submit" class="admin-btn admin-btn-primary">Keresés</button>
                                <?php if ($search): ?>
                                    <a href="<?php 
                                        $params = ['url' => 'webadmin', 'tab' => 'comments'];
                                        if (!empty($order_by)) $params['order_by'] = $order_by;
                                        if (!empty($order_dir)) $params['order_dir'] = $order_dir;
                                        echo base_url('webadmin?' . http_build_query($params));
                                    ?>" class="admin-btn admin-btn-secondary">Összes</a>
                                <?php endif; ?>
                            </form>
                            <div style="display: flex; gap: 10px; align-items: center; border-left: 1px solid var(--border-color); padding-left: 15px;">
                                <span>Kiválasztva: <strong id="selected-comments-count">0</strong></span>
                                <button type="button" class="admin-btn admin-btn-danger" onclick="submitBulkAction('comments')">
                                    <i class="fas fa-trash"></i> Csoportos Törlés
                                </button>
                            </div>
                        </div>

                        <div class="admin-table-container">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;"><input type="checkbox" id="select-all-comments" onchange="toggleSelectAll(this, 'comments')"></th>
                                        <th class="sortable <?php echo $order_by === 'meid' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="meid">ID</th>
                                        <th>Felhasználó</th>
                                        <th class="sortable <?php echo $order_by === 'dolgozo_id' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="dolgozo_id">Dolgozó ID</th>
                                        <th>Megjegyzés</th>
                                        <th class="sortable <?php echo $order_by === 'ertekeles' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="ertekeles">Értékelés</th>
                                        <th class="sortable <?php echo $order_by === 'idopont' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="idopont">Időpont</th>
                                        <th>Műveletek</th>
                                    </tr>
                                </thead>
                                <tbody id="comments-table-body">
                                    <?php if (!empty($data['comments'])): ?>
                                        <?php foreach ($data['comments'] as $comment): ?>
                                        <tr data-comment-id="<?php echo $comment['meid']; ?>">
                                            <td><input type="checkbox" class="comment-checkbox" value="<?php echo $comment['meid']; ?>" onchange="updateSelectedCount('comments')"></td>
                                            <td><?php echo $comment['meid']; ?></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($comment['fnev'] ?? 'Ismeretlen'); ?></strong><br>
                                                <small><?php echo htmlspecialchars($comment['email'] ?? 'N/A'); ?></small>
                                            </td>
                                            <td>D<?php echo $comment['dolgozo_id']; ?></td>
                                            <td><?php echo nl2br(htmlspecialchars($comment['megjegyzes'] ?? '')); ?></td>
                                            <td>
                                                <span style="color: #ffc107; white-space: nowrap; font-weight: bold;">
                                                    <?php 
                                                    $rating = $comment['ertekeles'] ?? 0;
                                                    for ($i = 1; $i <= 5; $i++): 
                                                        if ($i <= $rating): echo '<i class="fas fa-star"></i>';
                                                        else: echo '<i class="far fa-star"></i>';
                                                        endif; 
                                                    endfor; 
                                                    ?>
                                                    (<?php echo $rating; ?>/5)
                                                </span>
                                            </td>
                                            <td><?php echo format_date($comment['idopont']); ?></td>
                                            <td>
                                                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                                    <button class="admin-btn admin-btn-sm admin-btn-primary" onclick="showEditCommentModal(<?php echo $comment['meid']; ?>)">Szerkesztés</button>
                                                    <button class="admin-btn admin-btn-sm admin-btn-danger" onclick="showAdminDeleteCommentModal(<?php echo $comment['meid']; ?>)">Törlés</button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" style="text-align: center; padding: 2rem;">Nincsenek megjegyzések.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="admin-pagination-wrapper" style="<?php echo (isset($data['pages']) && $data['pages'] > 1) ? '' : 'display: none;'; ?>">
                            <div class="pagination-info">
                                <?php if (isset($data['total'])): ?>
                                    <span>Oldal <?php echo $data['current_page']; ?> / <?php echo $data['pages']; ?></span>
                                    <span style="margin-left: 15px;">Összesen: <?php echo $data['total']; ?> megjegyzés</span>
                                <?php endif; ?>
                            </div>
                            <ul class="admin-pagination" id="comments-pagination">
                                <?php 
                                if (isset($data['pages']) && $data['pages'] > 1) {
                                    $base_url = base_url('webadmin?tab=comments&search=' . urlencode($search) . '&order_by=' . $order_by . '&order_dir=' . $order_dir);
                                    echo generate_pagination($data['current_page'], $data['pages'], $base_url);
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-tab-content <?php echo $tab === 'deleted-comments' ? 'active' : ''; ?>" id="deleted-comments-content">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h2>Törölt Megjegyzések</h2>
                    </div>
                    <div class="admin-card-body">
                        <div class="admin-search-bar" style="justify-content: space-between; flex-wrap: wrap;">
                            <form method="GET" class="admin-search-form" style="display: flex; gap: 10px; flex: 1;">
                                <input type="hidden" name="url" value="webadmin">
                                <input type="hidden" name="tab" value="deleted-comments">
                                <?php if (!empty($order_by)): ?>
                                    <input type="hidden" name="order_by" value="<?php echo htmlspecialchars($order_by); ?>">
                                <?php endif; ?>
                                <?php if (!empty($order_dir)): ?>
                                    <input type="hidden" name="order_dir" value="<?php echo htmlspecialchars($order_dir); ?>">
                                <?php endif; ?>
                                <input type="text" name="search" class="admin-form-control" placeholder="Keresés megjegyzésben vagy felhasználónévben..." value="<?php echo htmlspecialchars($search); ?>">
                                <button type="submit" class="admin-btn admin-btn-primary">Keresés</button>
                                <?php if ($search): ?>
                                    <a href="<?php 
                                        $params = ['url' => 'webadmin', 'tab' => 'deleted-comments'];
                                        if (!empty($order_by)) $params['order_by'] = $order_by;
                                        if (!empty($order_dir)) $params['order_dir'] = $order_dir;
                                        echo base_url('webadmin?' . http_build_query($params));
                                    ?>" class="admin-btn admin-btn-secondary">Összes</a>
                                <?php endif; ?>
                            </form>
                            <div style="display: flex; gap: 10px; align-items: center; border-left: 1px solid var(--border-color); padding-left: 15px;">
                                <span>Kiválasztva: <strong id="selected-deleted-comments-count">0</strong></span>
                                <button type="button" class="admin-btn admin-btn-danger" onclick="submitBulkAction('deleted-comments')">
                                    <i class="fas fa-ban"></i> Csoportos Végleges Törlés
                                </button>
                            </div>
                        </div>

                        <div class="admin-table-container">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;"><input type="checkbox" id="select-all-deleted-comments" onchange="toggleSelectAll(this, 'deleted-comments')"></th>
                                        <th class="sortable <?php echo $order_by === 'meid' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="meid">ID</th>
                                        <th>Felhasználó</th>
                                        <th class="sortable <?php echo $order_by === 'dolgozo_id' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="dolgozo_id">Dolgozó ID</th>
                                        <th>Megjegyzés</th>
                                        <th class="sortable <?php echo $order_by === 'ertekeles' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="ertekeles">Értékelés</th>
                                        <th class="sortable <?php echo $order_by === 'idopont' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="idopont">Időpont</th>
                                        <th class="sortable <?php echo $order_by === 'torles_datuma' ? 'sort-' . strtolower($data['order_dir'] ?? 'desc') : ''; ?>" data-column="torles_datuma">Törölve</th>
                                        <th>Törlés Típusa</th>
                                        <th>Műveletek</th>
                                    </tr>
                                </thead>
                                <tbody id="deleted-comments-table-body">
                                    <?php if (!empty($data['comments'])): ?>
                                        <?php foreach ($data['comments'] as $comment): 
                                            $deletionBadgeClass = $comment['torles_tipusa'] === 'admin' ? 'admin-badge-primary' : ($comment['torles_tipusa'] === 'felhasznalo' ? 'admin-badge-danger' : 'admin-badge-secondary');
                                        ?>
                                        <tr>
                                            <td><input type="checkbox" class="deleted-comment-checkbox" value="<?php echo $comment['meid']; ?>" onchange="updateSelectedCount('deleted-comments')"></td>
                                            <td><?php echo $comment['meid']; ?></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($comment['fnev'] ?? 'Ismeretlen'); ?></strong><br>
                                                <small><?php echo htmlspecialchars($comment['email'] ?? 'N/A'); ?></small>
                                            </td>
                                            <td>D<?php echo $comment['dolgozo_id']; ?></td>
                                            <td><?php echo nl2br(htmlspecialchars($comment['megjegyzes'] ?? '')); ?></td>
                                            <td>
                                                <span style="color: #ffc107; white-space: nowrap; font-weight: bold;">
                                                    <?php 
                                                    $rating = $comment['ertekeles'] ?? 0;
                                                    for ($i = 1; $i <= 5; $i++): 
                                                        if ($i <= $rating): echo '<i class="fas fa-star"></i>';
                                                        else: echo '<i class="far fa-star"></i>';
                                                        endif; 
                                                    endfor; 
                                                    ?>
                                                    (<?php echo $rating; ?>/5)
                                                </span>
                                            </td>
                                            <td><?php echo format_date($comment['idopont']); ?></td>
                                            <td><?php echo format_date($comment['torles_datuma']); ?></td>
                                            <td><span class="admin-badge <?php echo $deletionBadgeClass; ?>"><?php echo $comment['torles_tipusa']; ?></span></td>
                                            <td>
                                                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                                    <button class="admin-btn admin-btn-sm admin-btn-danger" onclick="permanentDeleteComment(<?php echo $comment['meid']; ?>)">Végleges Törlés</button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="10" style="text-align: center; padding: 2rem;">Nincsenek törölt megjegyzések.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="admin-pagination-wrapper" style="<?php echo (isset($data['pages']) && $data['pages'] > 1) ? '' : 'display: none;'; ?>">
                            <div class="pagination-info">
                                <?php if (isset($data['total'])): ?>
                                    <span>Oldal <?php echo $data['current_page']; ?> / <?php echo $data['pages']; ?></span>
                                    <span style="margin-left: 15px;">Összesen: <?php echo $data['total']; ?> törölt megjegyzés</span>
                                <?php endif; ?>
                            </div>
                            <ul class="admin-pagination" id="deleted-comments-pagination">
                                <?php 
                                if (isset($data['pages']) && $data['pages'] > 1) {
                                    $base_url = base_url('webadmin?tab=deleted-comments&search=' . urlencode($search) . '&order_by=' . $order_by . '&order_dir=' . $order_dir);
                                    echo generate_pagination($data['current_page'], $data['pages'], $base_url);
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <div id="statusModal" class="admin-modal">
        <div class="admin-modal-content">
            <div class="admin-modal-header">
                <h3 class="admin-modal-title">Felhasználó státusz módosítása</h3>
                <button type="button" class="admin-modal-close">&times;</button>
            </div>
            <div class="admin-modal-body">
                <p>Felhasználó: <strong id="status_user_name"></strong></p>
                <form id="statusForm" method="POST">
                    <input type="hidden" name="action" value="update_status">
                    <input type="hidden" name="user_id" id="status_user_id">
                    <div class="admin-form-group">
                        <label class="admin-form-label">Új státusz</label>
                        <select name="status" class="admin-form-control" required>
                            <option value="Aktiv">Aktív</option>
                            <option value="Fuggoben">Függőben</option>
                            <option value="Kitiltott">Kitiltás</option>
                        </select>
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-form-label">Oka</label>
                        <textarea name="reason" class="admin-form-control" rows="3"></textarea>
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-form-label">Meddig</label>
                        <input type="datetime-local" name="until" class="admin-form-control">
                        <small class="admin-form-text">Ha nincs megadva, a kitiltás örök lesz.</small>
                    </div>
                </form>
            </div>
            <div class="admin-modal-footer">
                <button type="button" class="admin-btn admin-btn-secondary" onclick="admin.closeModal(document.getElementById('statusModal'))">Mégse</button>
                <button type="submit" form="statusForm" class="admin-btn admin-btn-primary">Mentés</button>
            </div>
        </div>
    </div>

    <div id="editModal" class="admin-modal">
        <div class="admin-modal-content">
            <div class="admin-modal-header">
                <h3 class="admin-modal-title">Felhasználó szerkesztése</h3>
                <button type="button" class="admin-modal-close">&times;</button>
            </div>
            <div class="admin-modal-body">
                <form id="editForm" method="POST">
                    <input type="hidden" name="action" value="update_user">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    <div class="admin-form-group">
                        <label class="admin-form-label">Felhasználónév *</label>
                        <input type="text" name="fnev" id="edit_fnev" class="admin-form-control" required>
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-form-label">Email *</label>
                        <input type="email" name="email" id="edit_email" class="admin-form-control" required>
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-form-label">Keresztnév</label>
                        <input type="text" name="knev" id="edit_knev" class="admin-form-control">
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-form-label">Vezetéknév</label>
                        <input type="text" name="vnev" id="edit_vnev" class="admin-form-control">
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-form-label">Nem</label>
                        <select name="nem" id="edit_nem" class="admin-form-control">
                            <option value="nem_publikus">Nem publikus</option>
                            <option value="ferfi">Férfi</option>
                            <option value="no">Nő</option>
                            <option value="egyeb">Egyéb</option>
                        </select>
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-form-label">Születési dátum</label>
                        <input type="date" name="szuletett" id="edit_szuletett" class="admin-form-control">
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-form-label">Telefon</label>
                        <input type="tel" name="telefon" id="edit_telefon" class="admin-form-control">
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-form-label">Vármegye</label>
                        <input type="text" name="varos" id="edit_varos" class="admin-form-control">
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-form-label">Részletek</label>
                        <textarea name="reszletek" id="edit_reszletek" class="admin-form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="admin-modal-footer">
                <button type="button" class="admin-btn admin-btn-secondary" onclick="admin.closeModal(document.getElementById('editModal'))">Mégse</button>
                <button type="submit" form="editForm" class="admin-btn admin-btn-primary">Mentés</button>
            </div>
        </div>
    </div>

    <div id="roleModal" class="admin-modal">
        <div class="admin-modal-content">
            <div class="admin-modal-header">
                <h3 class="admin-modal-title">Felhasználó szerepének módosítása</h3>
                <button type="button" class="admin-modal-close">&times;</button>
            </div>
            <div class="admin-modal-body">
                <p>Felhasználó: <strong id="role_user_name"></strong></p>
                <form id="roleForm" method="POST">
                    <input type="hidden" name="action" value="update_role">
                    <input type="hidden" name="user_id" id="role_user_id">
                    <input type="hidden" name="role" id="role_value">
                </form>
                <div style="display: flex; gap: 10px; justify-content: center; margin: 1rem 0;">
                    <button type="button" class="admin-btn admin-btn-warning" id="makeAdminBtn" onclick="document.getElementById('role_value').value='admin'; document.getElementById('roleForm').submit();">
                        Adminná Tétel
                    </button>
                    <button type="button" class="admin-btn admin-btn-secondary" id="makeUserBtn" onclick="document.getElementById('role_value').value='felhasznalo'; document.getElementById('roleForm').submit();">
                        Admin Jog Elvétele
                    </button>
                </div>
            </div>
            <div class="admin-modal-footer">
                <button type="button" class="admin-btn admin-btn-secondary" onclick="admin.closeModal(document.getElementById('roleModal'))">Mégse</button>
            </div>
        </div>
    </div>

    <div id="adminDeleteCommentModal" class="admin-modal">
        <div class="admin-modal-content" style="max-width: 500px;">
            <div class="admin-modal-header">
                <h3 class="admin-modal-title">Admin Törlés</h3>
                <button type="button" class="admin-modal-close" onclick="closeAdminDeleteCommentModal()">&times;</button>
            </div>
            <div class="admin-modal-body">
                <p id="adminDeleteCommentMessage">Biztosan törölni szeretné ezt a megjegyzést (admin törlés)?</p>
                <form id="adminDeleteCommentForm" method="POST">
                    <input type="hidden" name="action" value="delete_comment">
                    <input type="hidden" name="meid" id="admin_delete_comment_id">
                    <div class="admin-form-group">
                        <label class="admin-form-label">Törlés oka</label>
                        <textarea name="torles_oka" class="admin-form-control" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="admin-modal-footer">
                <button type="button" class="admin-btn admin-btn-secondary" onclick="closeAdminDeleteCommentModal()">Mégse</button>
                <button type="submit" form="adminDeleteCommentForm" class="admin-btn admin-btn-danger">Admin Törlés</button>
            </div>
        </div>
    </div>

    <div id="editCommentModal" class="admin-modal">
        <div class="admin-modal-content">
            <div class="admin-modal-header">
                <h3 class="admin-modal-title">Megjegyzés Szerkesztése</h3>
                <button type="button" class="admin-modal-close" onclick="closeEditCommentModal()">&times;</button>
            </div>
            <div class="admin-modal-body">
                <form id="editCommentForm" method="POST">
                    <input type="hidden" name="action" value="update_comment">
                    <input type="hidden" name="meid" id="edit_comment_id">
                    
                    <div class="admin-form-group">
                        <label class="admin-form-label">Értékelés</label>
                        <select name="ertekeles" id="edit_comment_rating" class="admin-form-control" required>
                            <option value="5">5 Csillag (Kiváló)</option>
                            <option value="4">4 Csillag (Jó)</option>
                            <option value="3">3 Csillag (Közepes)</option>
                            <option value="2">2 Csillag (Gyenge)</option>
                            <option value="1">1 Csillag (Nagyon rossz)</option>
                        </select>
                    </div>
                    
                    <div class="admin-form-group">
                        <label class="admin-form-label">Megjegyzés szövege</label>
                        <textarea name="megjegyzes" id="edit_comment_text" class="admin-form-control" rows="6" required></textarea>
                    </div>
                </form>
            </div>
            <div class="admin-modal-footer">
                <button type="button" class="admin-btn admin-btn-secondary" onclick="closeEditCommentModal()">Mégse</button>
                <button type="submit" form="editCommentForm" class="admin-btn admin-btn-primary">Mentés</button>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url('assets/js/admin.js'); ?>"></script>
</body>
</html>