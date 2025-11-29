<?php   
    $action = $_GET['action'] ?? 'visitasdiarias';

    switch ($action) {
        case 'visitasdiarias':
            require __DIR__ . '/resource/r_visitasdiarias.php';
            break;

        case 'pacientes':
            require __DIR__ . '/resource/r_pacientes.php';
            break;
        
        default:
            include '404.php';
            break;
    }

?>