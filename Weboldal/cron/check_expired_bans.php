<?php

require_once '../config/database.php';
require_once '../includes/admin_functions.php';

try {
    $count = check_expired_bans();
    
    if ($count > 0) {
        echo date('Y-m-d H:i:s') . " - $count lejárt kitiltás visszaállítva\n";
        
    } else {
        echo date('Y-m-d H:i:s') . " - Nincsenek lejárt kitiltások\n";
    }
    
} catch (Exception $e) {
    echo date('Y-m-d H:i:s') . " - Hiba: " . $e->getMessage() . "\n";
    error_log("Cron job error: " . $e->getMessage());
}