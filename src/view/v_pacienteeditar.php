<div class="paciente-editar-container">
    <h1>Editar paciente: <?php echo htmlspecialchars($paciente['nombre'] ?? ''); ?></h1>
    <?php if (!empty($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" action="index.php?action=pacienteeditar&id=<?php echo intval($paciente['id']); ?>">
        <label>Nombre
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($paciente['nombre'] ?? ''); ?>">
        </label>

        <label>Edad
            <input type="number" name="edad" value="<?php echo htmlspecialchars($paciente['edad'] ?? ''); ?>">
        </label>

        <label>Tensión
            <input type="text" name="tension" value="<?php echo htmlspecialchars($paciente['tension'] ?? ''); ?>">
        </label>

        <label>Glucosa
            <input type="number" name="glucosa" value="<?php echo htmlspecialchars($paciente['glucosa'] ?? ''); ?>">
        </label>

        <label>Colesterol
            <input type="number" name="colesterol" value="<?php echo htmlspecialchars($paciente['colesterol'] ?? ''); ?>">
        </label>

        <label>Indicador
            <input type="text" name="indicador" value="<?php echo htmlspecialchars($paciente['indicador'] ?? ''); ?>">
        </label>

        <label>Tipo de visita
            <input type="text" name="tipo_visita" value="<?php echo htmlspecialchars($paciente['tipo_visita'] ?? ''); ?>">
        </label>

        <label>Visita pendiente
            <input type="checkbox" name="visita_pendiente" value="1" <?php echo (!empty($paciente['visita_pendiente']) ? 'checked' : ''); ?>>
        </label>

        <label>Estado paciente
            <input type="text" name="estado_paciente" value="<?php echo htmlspecialchars($paciente['estado_paciente'] ?? ''); ?>">
        </label>

        <label>Fecha última visita
            <input type="date" name="fecha_ultima_visita" value="<?php echo htmlspecialchars($paciente['fecha_ultima_visita'] ?? ''); ?>">
        </label>

        <label>Enfermedades crónicas (separadas por comas)
            <textarea name="enfermedades_cronicas"><?php echo htmlspecialchars(implode(', ', $paciente['enfermedades_cronicas'] ?? [])); ?></textarea>
        </label>

        <label>Enfermedades actuales (separadas por comas)
            <textarea name="enfermedades_actuales"><?php echo htmlspecialchars(implode(', ', $paciente['enfermedades_actuales'] ?? [])); ?></textarea>
        </label>

        <label>Factor social (separados por comas)
            <textarea name="factor_social"><?php echo htmlspecialchars(implode(', ', $paciente['factor_social'] ?? [])); ?></textarea>
        </label>

        <div class="form-actions">
            <button type="submit">Guardar</button>
            <a href="index.php?action=pacientedetalle&id=<?php echo intval($paciente['id']); ?>">Cancelar</a>
        </div>
    </form>
</div>