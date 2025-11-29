<div class="pacientes-container">
    <?php foreach ($pacientes as $paciente): ?>
        <a href="index.php?action=pacientedetalle&id=<?php echo $paciente['id']; ?>" class="paciente-card">
            <div class="paciente-box">
                <h3><?php echo $paciente['nombre']; ?></h3>
                <p>Edad: <?php echo $paciente['edad']; ?> años</p>

                <p>Estado:
                    <?php 
                        $estado = strtolower($paciente['estado_paciente']); 
                        $icono = "images/" . $estado . ".png"; 
                    ?>

                    <span class="estado-<?php echo $estado; ?>">
                        <?php echo $paciente['estado_paciente']; ?>
                    </span>
                    <img src="<?php echo $icono; ?>" alt="<?php echo $estado; ?>" class="icono-estado">

                </p>

                <p>Última visita: <?php echo $paciente['fecha_ultima_visita']; ?></p>
            </div>
        </a>
    <?php endforeach; ?>
</div>