<div class="resultado-calculo">
    <h1>Indicadores recalculados ✅</h1>
    <p>Se han actualizado los indicadores y estados de los pacientes. Aquí se muestran los 5 primeros:</p>

    <div class="cajas-pacientes">
        <?php foreach (array_slice($resultado, 0, 5) as $r): ?>
            <div class="caja-paciente">
                <h3><?php echo htmlspecialchars($r['nombre']); ?></h3>
                <p><strong>ID:</strong> <?php echo htmlspecialchars($r['id']); ?></p>
                <p><strong>Indicador:</strong> <?php echo htmlspecialchars($r['indicador']); ?></p>
                <p><strong>Estado:</strong> <?php echo htmlspecialchars($r['estado']); ?></p>
                <p><?php echo $r['updated'] ? 'Actualizado correctamente' : 'Error: '.$r['msg']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <div>
        <form action="index.php" method="get">
            <input type="hidden" name="action" value="calcularindicadores">
            <button type="submit">
                Recalcular indicadores otra vez
            </button>
        </form>
    </div>
</div>