<?php
require_once __DIR__ . '/m_conectaDB.php';

function updatePaciente(int $id, array $data): bool {
    $conn = connectaDB();

    $sql = "UPDATE pacientes SET
        nombre = ?,
        edad = ?,
        enfermedades_cronicas = ?,
        enfermedades_actuales = ?,
        tension = ?,
        glucosa = ?,
        visita_pendiente = ?,
        factor_social = ?,
        estado_paciente = ?,
        fecha_ultima_visita = ?,
        indicador = ?,
        colesterol = ?
        WHERE id = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $conn->close();
        return false;
    }

    // Preparar valores (JSON encode para arrays)
    $enf_cron_json = isset($data['enfermedades_cronicas']) ? json_encode($data['enfermedades_cronicas'], JSON_UNESCAPED_UNICODE) : null;
    $enf_act_json = isset($data['enfermedades_actuales']) ? json_encode($data['enfermedades_actuales'], JSON_UNESCAPED_UNICODE) : null;
    $factor_json = isset($data['factor_social']) ? json_encode($data['factor_social'], JSON_UNESCAPED_UNICODE) : null;

    // Normalizar valores a strings o nulls
    $nombre = $data['nombre'] ?? null;
    $edad = $data['edad'] !== null ? $data['edad'] : null;
    $tension = $data['tension'] ?? null;
    $glucosa = $data['glucosa'] !== null ? $data['glucosa'] : null;
    $visita_pendiente = isset($data['visita_pendiente']) ? (int)$data['visita_pendiente'] : 0;
    $estado_paciente = $data['estado_paciente'] ?? null;
    $fecha_ultima_visita = $data['fecha_ultima_visita'] ?? null;
    $indicador = $data['indicador'] !== null ? $data['indicador'] : null;
    $colesterol = $data['colesterol'] !== null ? $data['colesterol'] : null;


    // Para simplicidad convertimos todos a strings en bind (mysqli acepta)
    $types = str_repeat('s', 13);
    $stmt->bind_param(
        $types,
        $nombre,
        $edad,
        $enf_cron_json,
        $enf_act_json,
        $tension,
        $glucosa,
        $visita_pendiente,
        $factor_json,
        $estado_paciente,
        $fecha_ultima_visita,
        $indicador,
        $colesterol,
        $id
    );

    $exec = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $exec !== false;
}
?>