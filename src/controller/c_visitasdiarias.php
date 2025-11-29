<?php
require_once __DIR__ . '/../model/m_conectaDB.php';
require_once __DIR__ . '/../model/m_getvisitas_diarias.php';

// Obtener y ordenar las visitas diarias (por defecto 30)
$res = visitas_diarias(30);
$pacientes_diarios = $res['selected'];
$pacientes_por_grupo = $res['by_group'];

// Cargar la vista (asegúrate que existe `v_visitasdiarias.php`)
require __DIR__ . '/../view/v_visitasdiarias.php';
?>