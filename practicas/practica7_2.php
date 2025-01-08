<?php
session_start();
if (isset($_POST['colors'])){
    setCookie('cookie_color',$_POST['colors'],time()+30);
    $_SESSION['sesion_color'] = $_POST['colors'];
}
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
<a href="practica7_3.php">Ir a página 3</a>
    
</body>
</html>