<?php   
    $action = $_GET['action'] ?? 'home';

    switch ($action) {
        case 'home':
            require __DIR__ . '/resource/r_home.php';
            break;
        
        default:
            include '404.php';
            break;
    }

?>