<?php

    require_once __DIR__. '/../model/m_conectaDB.php';
    require_once __DIR__. '/../model/m_getpacientes.php';

    // Obtener los pacientes de la base de datos
    $pacientes = getPacientes();

    

    require __DIR__. '/../view/v_pacientes.php';

?>