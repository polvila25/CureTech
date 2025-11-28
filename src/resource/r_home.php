<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="icon" type="image/jpg" href="img/logo1.jpg">
    </head>

    <body class="container">
        <header>
            <?php require __DIR__. '/../controller/c_upperMenu.php'; ?>
        </header>
        <section id="blocllista">
            <?php require __DIR__. '/../controller/c_home.php'; ?>
        </section>
        
        <?php require __DIR__. '/../controller/c_lowerMenu.php'; ?>
    </body>
    
</html>