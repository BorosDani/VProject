<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *'); 

$file = 'views.json';

$page_id = isset($_GET['id']) ? preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['id']) : 'ismeretlen_oldal';

$action = isset($_GET['action']) ? $_GET['action'] : 'read';

if (!file_exists($file)) {
    file_put_contents($file, json_encode([]));
}

$json_data = file_get_contents($file);
$views_data = json_decode($json_data, true);

if (!is_array($views_data)) {
    $views_data = [];
}

if (!isset($views_data[$page_id])) {
    $views_data[$page_id] = 0;
}

if ($action === 'add') {
    $views_data[$page_id]++;
    file_put_contents($file, json_encode($views_data), LOCK_EX);
}

echo json_encode([
    'status' => 'success',
    'id' => $page_id,
    'views' => $views_data[$page_id],
    'action_taken' => $action
]);
?>