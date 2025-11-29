<?php

/**
 * Selecciona hasta $limit pacientes para visitas diarias.
 * - Filtra pacientes con `visita_pendiente` == 1 (si hay); si no hay ninguno, usa todos.
 * - Ordena por fecha de última visita (la más lejana / antigua primero).
 * - Agrupa por `estado_paciente` (critico, bajo, normal, revision) y selecciona
 *   según una distribución por defecto para obtener un total de $limit.
 *
 * Entrada: array de pacientes (como devuelve `getPacientes()`)
 * Salida: array con claves:
 *   - 'selected' => lista de pacientes seleccionados (máx $limit)
 *   - 'by_group' => arrays por grupo con los pacientes ordenados
 */
function visitas_diarias($pacientes, $limit = 30, $distribution = null)
{
    // Default distribution (porcentaje) si no se pasa:
    if ($distribution === null) {
        $distribution = array(
            'critico' => 0.30,
            'bajo' => 0.25,
            'normal' => 0.30,
            'revision' => 0.15
        );
    }

    // Filtrar candidatos: preferir los que tienen visita_pendiente == 1
    $candidates = array();
    foreach ($pacientes as $p) {
        if (isset($p['visita_pendiente']) && (int)$p['visita_pendiente'] === 1) {
            $candidates[] = $p;
        }
    }
    if (count($candidates) === 0) {
        // si no hay visitas pendientes explícitas, considerar todos
        $candidates = $pacientes;
    }

    // Helpers
    $to_ts = function ($date) {
        if (empty($date)) return 0;
        $t = strtotime($date);
        return $t === false ? 0 : $t;
    };

    // Clasificar estado en 4 grupos (normal por defecto)
    $classify_estado = function ($estado) {
        $s = strtolower(trim((string)$estado));
        if ($s === '') return 'normal';
        if (strpos($s, 'crit') !== false) return 'critico';
        if (strpos($s, 'alert') !== false) return 'bajo';
        if (strpos($s, 'bajo') !== false) return 'bajo';
        if (strpos($s, 'revision') !== false || strpos($s, 'revisión') !== false) return 'revision';
        if (strpos($s, 'estable') !== false) return 'normal';
        return 'normal';
    };

    // Inicializar grupos
    $groups = array('critico' => array(), 'bajo' => array(), 'normal' => array(), 'revision' => array());

    // Rellenar grupos y añadir campo ts_last_visit (timestamp de fecha_ultima_visita)
    foreach ($candidates as $p) {
        $ts = $to_ts($p['fecha_ultima_visita'] ?? null);
        $grp = $classify_estado($p['estado_paciente'] ?? '');
        $p['_ts_last_visit'] = $ts;
        $groups[$grp][] = $p;
    }

    // Ordenar cada grupo por fecha de última visita asc (más antigua primero)
    foreach ($groups as $k => &$arr) {
        usort($arr, function ($a, $b) {
            $ta = $a['_ts_last_visit'] ?? 0;
            $tb = $b['_ts_last_visit'] ?? 0;
            if ($ta == $tb) return 0;
            return ($ta < $tb) ? -1 : 1; // más antiguo (menor ts) primero
        });
    }
    unset($arr);

    // Calcular cuotas por grupo en base a $distribution
    $quotas = array();
    $sum = 0;
    foreach ($distribution as $k => $v) {
        $q = (int) floor($v * $limit);
        $quotas[$k] = $q;
        $sum += $q;
    }
    // Ajustar por resto para que la suma sea $limit
    $remaining = $limit - $sum;
    $order_for_fill = array('normal', 'critico', 'bajo', 'revision');
    $i = 0;
    while ($remaining > 0) {
        $key = $order_for_fill[$i % count($order_for_fill)];
        $quotas[$key] = ($quotas[$key] ?? 0) + 1;
        $remaining--;
        $i++;
    }

    // Selección inicial según cuotas
    $selected = array();
    foreach (array('critico', 'bajo', 'normal', 'revision') as $grp) {
        $take = $quotas[$grp] ?? 0;
        if ($take <= 0) continue;
        $available = $groups[$grp];
        for ($j = 0; $j < $take && $j < count($available); $j++) {
            $selected[] = $available[$j];
        }
    }

    // Si no llegamos a $limit, rellenar con restantes por prioridad de grupo
    if (count($selected) < $limit) {
        $needed = $limit - count($selected);
        $priority_groups = array('critico', 'bajo', 'normal', 'revision');
        foreach ($priority_groups as $grp) {
            $available = $groups[$grp];
            $start = $quotas[$grp] ?? 0;
            for ($j = $start; $j < count($available) && $needed > 0; $j++) {
                $selected[] = $available[$j];
                $needed--;
            }
            if ($needed <= 0) break;
        }
    }

    // Truncar al límite por si sobra
    if (count($selected) > $limit) $selected = array_slice($selected, 0, $limit);

    // Devolver selección y detalle por grupo
    return array(
        'selected' => $selected,
        'by_group' => $groups,
        'quotas' => $quotas,
        'limit' => $limit
    );
}
}