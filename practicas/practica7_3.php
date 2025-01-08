<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body bgcolor="<?php
echo $_SESSION['sesion_color'];
?>">
    
    Bienvenido <?php
    echo $_SESSION['sesion_color'];
    echo $_COOKIE['cookie_color'];
    ?>
</body>
</html>