<div class="pacientes-container">
    <?php foreach ($pacientes as $paciente): ?>
        <a href="index.php?action=pacientedetalle&id=<?php echo $paciente['id']; ?>" class="paciente-card">
            <div class="paciente-box">
                <h3><?php echo $paciente['nombre']; ?></h3>
                <p>Edad: <?php echo $paciente['edad']; ?> años</p>
                <p>Estado: <span class="estado-<?php echo strtolower($paciente['estado_paciente']); ?>"><?php echo $paciente['estado_paciente']; ?></span></p>
                <p>Última visita: <?php echo $paciente['fecha_ultima_visita']; ?></p>
            </div>
        </a>
    <?php endforeach; ?>
</div>