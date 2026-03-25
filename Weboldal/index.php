<?php

require_once("includes/functions.php");



$url = $_GET['url'] ?? 'home';

$pages = ['home', 'login', 'register', 'jobs', 'myJobs','profile', 'work','contact', 'logout','about', 'workUpload', 'webadmin', 'jelszo_visszaallitas','editJob','Jelentkezeseim', 'rateProfile'];

$noLayoutPages = ['login', 'register', 'logout', 'jelszo_visszaallitas', 'webadmin','fizetes'];



$tiltas_info = ellenoriz_tiltast();

if ($tiltas_info) {

    echo '<div class="error-message">';

    echo '<h3>🚫 Fiókod ki van tiltva!</h3>';

    echo '<p><strong>Ok:</strong> ' . htmlspecialchars($tiltas_info['ok']) . '</p>';

    if ($tiltas_info['meddig'] && $tiltas_info['meddig'] != '0000-00-00 00:00:00') {

        echo '<p><strong>Meddig:</strong> ' . date('Y-m-d H:i', strtotime($tiltas_info['meddig'])) . '</p>';

    } else {

        echo '<p><strong>Meddig:</strong> Határozatlan ideig</p>';

    }

    echo '</div>';

    exit;

}



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





if ($_SESSION['email'] == "kothenczmartin@gmail.com"){

    session_destroy();

}

    

?>