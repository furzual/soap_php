<?php
if(!isset($_POST["number1"]) || !isset($_POST["number2"])){
    header("Location: practica5.html");
}
else{
    if(empty($_POST["number1"]) || empty($_POST["number2"])){
        header("Location: practica5.html");
    }
}
$number1 = $_POST["number1"];
$number2 = $_POST["number2"];
$res = $number1 + $number2;
echo $res;
?>