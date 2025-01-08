<?php
session_start();
if(!isset($_POST["environment-sel"])){

    $_SESSION['env-sel'] = $_POST['environment-sel'];
}
else{
    //echo 'No env';
    header("Location: demo.php");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    echo $_SESSION['env-sel'];
    ?>
</body>
</html>