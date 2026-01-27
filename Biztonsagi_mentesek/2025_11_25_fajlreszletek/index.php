<?php
require_once("includes/functions.php");

$url = $_GET['url'] ?? 'home';
$pages = ['home', 'login', 'register', 'jobs', 'myJobs','applications','profile', 'work','contact', 'logout','about', 'workUpload', 'webadmin', 'jelszo_visszaallitas'];
$noLayoutPages = ['login', 'register', 'logout', 'jelszo_visszaallitas', 'webadmin'];

if (!in_array($url, $pages)) $url = '404';

if (!in_array($url, $noLayoutPages)) 
{
    include ROOT_PATH . '/includes/header.php';
}

include ROOT_PATH . "/pages/{$url}.php";

if (!in_array($url, $noLayoutPages)) 
{
    include ROOT_PATH . '/includes/footer.php';
}
?>