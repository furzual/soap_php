<?php
if(!isset($_POST["age"])){
    header("Location: practica6.html");
}
else{
    if(empty($_POST["age"])){
        header("Location: practica6.html");
    }
    if(!is_numeric($_POST["age"])){
        header("Location: practica6html");
    }
}
$age = $_POST["age"];

if($_POST["age"] >= 18){
    echo "<font color='green'> es mayor</font>";
}
else{
    echo "<font color='red'> es menor</font>";
}
?>