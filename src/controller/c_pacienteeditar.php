<?php
require_once __DIR__ . '/../model/m_conectaDB.php';
require_once __DIR__ . '/../model/m_pacienteeditar.php';

if (!isset($_GET['id'])) {
    die("ID de paciente no especificado");
}

$id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos del formulario
    $nombre = trim($_POST['nombre'] ?? '');
    $edad = $_POST['edad'] !== '' ? intval($_POST['edad']) : null;
    $tension = trim($_POST['tension'] ?? '');
    $glucosa = $_POST['glucosa'] !== '' ? intval($_POST['glucosa']) : null;
    $visita_pendiente = isset($_POST['visita_pendiente']) ? 1 : 0;
    $tipo_visita = trim($_POST['tipo_visita'] ?? '');
    $estado_paciente = trim($_POST['estado_paciente'] ?? '');
    $fecha_ultima_visita = trim($_POST['fecha_ultima_visita'] ?? '') ?: null;
    $indicador = $_POST['indicador'] !== '' ? floatval($_POST['indicador']) : null;
    $colesterol = $_POST['colesterol'] !== '' ? intval($_POST['colesterol']) : null;

    // Campos JSON: recibimos como texto separado por comas y convertimos a arrays
    $enf_cron_txt = trim($_POST['enfermedades_cronicas'] ?? '');
    $enf_act_txt = trim($_POST['enfermedades_actuales'] ?? '');
    $factor_social_txt = trim($_POST['factor_social'] ?? '');

    $enfermedades_cronicas = $enf_cron_txt === '' ? [] : array_values(array_filter(array_map('trim', explode(',', $enf_cron_txt))));
    $enfermedades_actuales = $enf_act_txt === '' ? [] : array_values(array_filter(array_map('trim', explode(',', $enf_act_txt))));
    $factor_social = $factor_social_txt === '' ? [] : array_values(array_filter(array_map('trim', explode(',', $factor_social_txt))));

    $data = [
        'nombre' => $nombre,
        'edad' => $edad,
        'enfermedades_cronicas' => $enfermedades_cronicas,
        'enfermedades_actuales' => $enfermedades_actuales,
        'tension' => $tension,
        'glucosa' => $glucosa,
        'visita_pendiente' => $visita_pendiente,
        'tipo_visita' => $tipo_visita,
        'factor_social' => $factor_social,
        'estado_paciente' => $estado_paciente,
        'fecha_ultima_visita' => $fecha_ultima_visita,
        'indicador' => $indicador,
        'colesterol' => $colesterol
    ];

    $ok = updatePaciente($id, $data);
    if ($ok) {
        header('Location: index.php?action=pacientedetalle&id=' . $id);
        exit;
    } else {
        $error = "Error al actualizar paciente";
        // Cargar vista con error (la vista puede mostrar $error)
    }
}

// Si GET: cargar datos actuales para mostrar en el formulario
$conn = connectaDB();
$sql = "SELECT * FROM pacientes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
if (!$result || $result->num_rows === 0) {
    $conn->close();
    die("Paciente no encontrado");
}
$paciente = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Decodificar campos JSON para prellenar formulario
$paciente['enfermedades_cronicas'] = json_decode($paciente['enfermedades_cronicas'], true) ?: [];
$paciente['enfermedades_actuales'] = json_decode($paciente['enfermedades_actuales'], true) ?: [];
$paciente['factor_social'] = json_decode($paciente['factor_social'], true) ?: [];

require __DIR__ . '/../view/v_pacienteeditar.php';
?>