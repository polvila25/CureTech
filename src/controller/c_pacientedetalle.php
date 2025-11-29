<?php
    require_once __DIR__. '/../model/m_conectaDB.php';

    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $conn = connectaDB();
        
        $sql = "SELECT * FROM pacientes WHERE id = " . $id;
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $paciente = $result->fetch_assoc();
            
            // Convertir JSON strings a arrays
            $paciente['enfermedades_cronicas'] = json_decode($paciente['enfermedades_cronicas'], true);
            $paciente['enfermedades_actuales'] = json_decode($paciente['enfermedades_actuales'], true);
            $paciente['factor_social'] = json_decode($paciente['factor_social'], true);
        } else {
            die("Paciente no encontrado");
        }
        $conn->close();
    } else {
        die("ID de paciente no especificado");
    }

    require __DIR__. '/../view/v_pacientedetalle.php';

?>