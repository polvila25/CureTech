<?php
/**
 * Pesos y algoritmo de triaje en PHP.
 *
 * - Usa `getPacientes()` (de `m_getpacientes.php`) para obtener pacientes.
 * - Calcula un indicador por paciente similar al algoritmo Python.
 * - Actualiza el campo `indicador` en la tabla `pacientes`.
 *
 * Uso CLI: `php src/model/pesos_modelo.php`
 */

require_once __DIR__ . '/m_conectaDB.php';
require_once __DIR__ . '/m_getpacientes.php';

// Pesos (valores inspirados en `pesos_modelo.py`)
$PESOS_ENF_CRONICAS = array(
    "cancer activo" => 10,
    "insuficiencia cardiaca" => 9,
    "demencia alzheimer" => 9,
    "epoc" => 8,
    "ictus previo" => 8,
    "insuficiencia renal" => 7,
    "cirrosis hepatica" => 7,
    "diabetes tipo 2" => 6,
    "fibrilacion auricular" => 6,
    "obesidad morbida" => 6,
    "depresion mayor" => 6,
    "artritis reumatoide" => 5,
    "vih sida" => 5,
    "hipertension arterial" => 4,
    "asma bronquial" => 4
);

$PESOS_ENF_ACTUALES = array(
    "dolor toracico" => 10,
    "disnea" => 9,
    "descompensacion diabetes" => 8,
    "crisis hipertensiva" => 8,
    "fiebre alta" => 7,
    "traumatismo caida" => 6,
    "dolor abdominal" => 6,
    "infeccion respiratoria" => 5,
    "infeccion orina" => 5,
    "crisis ansiedad" => 5,
    "gastroenteritis" => 4,
    "mareos vertigos" => 4,
    "migrana intensa" => 4,
    "lumbago agudo" => 3,
    "erupcion cutanea" => 3
);

$PESOS_SOCIAL = array(
    "vive solo mayor 75" => 5,
    "dependiente" => 5,
    "alcoholismo" => 5,
    "pobreza economica" => 4,
    "aislamiento" => 4,
    "fumador activo" => 3,
    "Depresion" => 3,
    "problemas vivienda" => 3,
    "obesidad" => 3,
    "barrera idioma" => 2
);

function parse_tension($tension_str) {
    if (!$tension_str) return array(null, null);
    $s = str_replace(' ', '', $tension_str);
    $parts = explode('/', $s);
    if (count($parts) >= 2) {
        $sys = intval($parts[0]);
        $dia = intval($parts[1]);
        return array($sys, $dia);
    }
    return array(null, null);
}

function sumatorio_diagnostico($p) {
    $indice = 0;

    // Tensión
    list($sys, $dia) = parse_tension(isset($p['tension']) ? $p['tension'] : null);
    if ($sys !== null && $dia !== null) {
        if ($sys >= 180 || $dia >= 120) {
            $indice += 10;
        } elseif ($sys >= 160 || $dia >= 100) {
            $indice += 8;
        } elseif ($sys >= 140 || $dia >= 90) {
            $indice += 5;
        } elseif ($sys >= 120 || $dia >= 80) {
            $indice += 2;
        }
    }

    // Glucosa
    if (isset($p['glucosa']) && $p['glucosa'] !== null && $p['glucosa'] !== '') {
        $g = intval($p['glucosa']);
        if ($g >= 200) $indice += 10;
        elseif ($g >= 150) $indice += 6;
        elseif ($g >= 130) $indice += 2;
    }

    // Colesterol
    if (isset($p['colesterol']) && $p['colesterol'] !== null && $p['colesterol'] !== '') {
        $c = intval($p['colesterol']);
        if ($c > 160) $indice += 6;
        elseif ($c >= 140) $indice += 2;
    }

    return $indice;
}

function calcular_indicador($p) {
    global $PESOS_ENF_CRONICAS, $PESOS_ENF_ACTUALES, $PESOS_SOCIAL;

    $indice = 0;

    // Cronicas
    $cron = isset($p['enfermedades_cronicas']) ? $p['enfermedades_cronicas'] : (isset($p['cronicas']) ? $p['cronicas'] : array());
    if (!is_array($cron)) $cron = array();
    foreach ($cron as $ec) {
        $key = trim(strtolower($ec));
        if (isset($PESOS_ENF_CRONICAS[$key])) $indice += $PESOS_ENF_CRONICAS[$key];
    }

    // Actuales
    $act = isset($p['enfermedades_actuales']) ? $p['enfermedades_actuales'] : (isset($p['actuales']) ? $p['actuales'] : array());
    if (!is_array($act)) $act = array();
    foreach ($act as $ea) {
        $key = trim(strtolower($ea));
        if (isset($PESOS_ENF_ACTUALES[$key])) $indice += $PESOS_ENF_ACTUALES[$key];
    }

    // Social
    $soc = isset($p['factor_social']) ? $p['factor_social'] : (isset($p['social']) ? $p['social'] : array());
    if (!is_array($soc)) $soc = array();
    foreach ($soc as $s) {
        $key = trim($s);
        if (isset($PESOS_SOCIAL[$key])) $indice += $PESOS_SOCIAL[$key];
    }

    // Diagnóstico
    $indice += sumatorio_diagnostico($p);

    // Edad multiplicador
    $edad = isset($p['edad']) ? intval($p['edad']) : 0;
    $mult = 1.0;
    if ($edad > 85) $mult = 1.25;
    elseif ($edad > 75) $mult = 1.18;
    elseif ($edad > 60) $mult = 1.1;

    $valor = round($indice * $mult, 2);
    return $valor;
}

function update_indicador_db($id, $indicador) {
    $conn = connectaDB();
    $sql = "UPDATE pacientes SET indicador = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $conn->close();
        return false;
    }
    // bind as string and int
    $stmt->bind_param('di', $indicador, $id);
    $res = $stmt->execute();
    $stmt->close();
    $conn->close();
    return $res !== false;
}

function calcular_y_actualizar_todos() {
    $pacientes = getPacientes(); // m_getpacientes devuelve arrays con campos json decodificados
    $resultados = array();
    foreach ($pacientes as $row) {
        // Preparar estructura esperada por calcular_indicador
        $p = $row;
        // Asegurarse claves
        if (!isset($p['enfermedades_cronicas'])) $p['enfermedades_cronicas'] = array();
        if (!isset($p['enfermedades_actuales'])) $p['enfermedades_actuales'] = array();
        if (!isset($p['factor_social'])) $p['factor_social'] = array();

        $indicador = calcular_indicador($p);
        $ok = update_indicador_db($row['id'], $indicador);
        $resultados[] = array('id' => $row['id'], 'nombre' => $row['nombre'], 'indicador' => $indicador, 'updated' => $ok);
    }
    return $resultados;
}

// Si se ejecuta por CLI, calcular y mostrar resultados
if (php_sapi_name() === 'cli') {
    try {
        $res = calcular_y_actualizar_todos();
        foreach ($res as $r) {
            $ok = $r['updated'] ? 'OK' : 'ERROR';
            echo "Paciente {$r['id']} - {$r['nombre']}: indicador={$r['indicador']} ({$ok})\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

?>
