<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="icon" type="image/png" href="images/design.png">
    </head>

    <body class="container">
        <header>
            <?php require_once __DIR__. '/../controller/c_menuSuperior.php'; ?>
        </header>
        <section id="blocllista">
            <?php require __DIR__. '/../controller/c_pacientes.php'; ?>
        </section>
        
    </body>
    
</html>