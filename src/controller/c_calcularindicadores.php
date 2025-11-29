<?php
    require_once __DIR__ . '/../model/m_pesos_modelo.php';

    // Ejecutar cálculo
    $resultado = calcular_y_actualizar_todos();

    require_once __DIR__ . '/../view/v_pesos_modelo.php';
?>

