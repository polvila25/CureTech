<div class="paciente-detalle-container">
    <div class="paciente-header">
        <h1><?php echo $paciente['nombre']; ?></h1>
        <span class="estado-<?php echo strtolower($paciente['estado_paciente'] ?? 'desconocido'); ?>"><?php echo $paciente['estado_paciente'] ?? 'Desconocido'; ?></span>
    </div>

    <div class="paciente-info">
        <section class="info-section">
            <h2>Información General</h2>
            <p><strong>ID:</strong> <?php echo $paciente['id']; ?></p>
            <p><strong>Edad:</strong> <?php echo $paciente['edad'] ?? 'N/A'; ?> años</p>
            <p><strong>Última visita:</strong> <?php echo $paciente['fecha_ultima_visita'] ?? 'Sin registro'; ?></p>
            <p><strong>Tipo de visita:</strong> <?php echo $paciente['tipo_visita'] ?? 'N/A'; ?></p>
            <p><strong>Visita pendiente:</strong> <?php echo ($paciente['visita_pendiente'] ?? false) ? 'Sí' : 'No'; ?></p>
        </section>

        <section class="info-section">
            <h2>Signos Vitales</h2>
            <p><strong>Tensión:</strong> <?php echo $paciente['tension'] ?? 'N/A'; ?></p>
            <p><strong>Glucosa:</strong> <?php echo ($paciente['glucosa'] ?? 'N/A') . ' mg/dL'; ?></p>
            <p><strong>Colesterol:</strong> <?php echo ($paciente['colesterol'] ?? 'N/A') . ' mg/dL'; ?></p>
            <p><strong>Indicador:</strong> <?php echo $paciente['indicador'] ?? 'N/A'; ?></p>
        </section>

        <section class="info-section">
            <h2>Enfermedades Crónicas</h2>
            <?php if (!empty($paciente['enfermedades_cronicas'])): ?>
                <ul>
                    <?php foreach ($paciente['enfermedades_cronicas'] as $enfermedad): ?>
                        <li><?php echo $enfermedad; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Sin enfermedades crónicas registradas</p>
            <?php endif; ?>
        </section>

        <section class="info-section">
            <h2>Enfermedades Actuales</h2>
            <?php if (!empty($paciente['enfermedades_actuales'])): ?>
                <ul>
                    <?php foreach ($paciente['enfermedades_actuales'] as $enfermedad): ?>
                        <li><?php echo $enfermedad; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Sin enfermedades actuales registradas</p>
            <?php endif; ?>
        </section>

        <section class="info-section">
            <h2>Factor Social</h2>
            <?php if (!empty($paciente['factor_social'])): ?>
                <ul>
                    <?php foreach ($paciente['factor_social'] as $factor): ?>
                        <li><?php echo $factor; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Sin factores sociales registrados</p>
            <?php endif; ?>
        </section>
    </div>

    <div class="paciente-actions">
        <a href="index.php?action=pacienteeditar&id=<?php echo intval($paciente['id']); ?>" class="btn btn-primary">Editar</a>
        <a href="index.php?action=pacientes" class="btn btn-secondary">Volver</a>
    </div>
</div>