<?php

function getPacientes() {
    require_once __DIR__ . '/m_conectaDB.php';
    
    $conn = connectaDB();
    
    $sql = "SELECT * FROM pacientes";
    $result = $conn->query($sql);
    
    if (!$result) {
        die("Error en la consulta: " . $conn->error);
    }
    
    $pacientes = [];
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Convertir JSON strings a arrays
            $row['enfermedades_cronicas'] = json_decode($row['enfermedades_cronicas'], true);
            $row['enfermedades_actuales'] = json_decode($row['enfermedades_actuales'], true);
            $row['factor_social'] = json_decode($row['factor_social'], true);
            
            $pacientes[] = $row;
        }
    }
    
    $conn->close();
    
    return $pacientes;
}

?>