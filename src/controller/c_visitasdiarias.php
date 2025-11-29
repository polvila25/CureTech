<?php 
    require __DIR__. '/../view/v_visitasdiarias.php'; 
    require_once __DIR__. '/../model/m_conectaDB.php';
    require_once __DIR__. '/../model/m_getpacientes.php';

    $pacientes = getPacientes();

    require __DIR__ '/../view/visitasdiaris.php';
?>