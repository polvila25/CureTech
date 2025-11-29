<?php
    $action = $_GET['action'] ?? 'visitasdiarias';

    switch ($action) {
        case 'visitasdiarias':
            require __DIR__ . '/resource/r_visitasdiarias.php';
            break;

        case 'pacientes':
            require __DIR__ . '/resource/r_pacientes.php';
            break;
        
        case 'pacientedetalle':
            require __DIR__ . '/resource/r_pacientedetalle.php';
            break;

        case 'pacienteeditar':
            require __DIR__ . '/resource/r_pacienteeditar.php';
            break;
            
        case 'calcularindicadores':
            require __DIR__ . '/resource/r_calcularindicadores.php';
            break;

        case 'alertas':
            require __DIR__ . '/resource/r_alertas.php';
            break;
        
        default:
            include '404.php';
            break;
    }

?>