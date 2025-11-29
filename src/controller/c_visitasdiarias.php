<?php 
    require __DIR__. '/../view/v_visitasdiarias.php'; 
    require_once __DIR__. '/../model/m_conectaDB.php';
    require_once __DIR__. '/../model/m_getpacientes.php';
    require_once __DIR__. '/../model/m_getVisitasDiarias.php';

    //obtener pacientes de la BD
    $pacientes = getPacientes();
    //ordenar las visitas del dia
    $pacientes_diarios = visitas_diarias($pacientes);

    require __DIR__ '/../view/visitasdiaris.php';
?>