<?php 

    require_once "src/controllers/Controller.php";
    require_once "src/utils/Router.php";

    $assets = Controller::base_url("src/assets/css");
    $pathCSS = "$assets/style.css";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= $pathCSS ?>">
</head>
<body>

    <?php include_once "header.php" ?>
    <div id="main-content">
        <?php 
            $router = new Router;
            $router->route();
        ?>
    </div>
</body>
</html>