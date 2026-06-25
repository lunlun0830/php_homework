<?php
session_start();

$role=$_POST["role"];

if(($_POST["role"]=="Teacher"))
    {
        echo"Teacher is not allowed to fill the form.";
        header("Location=a1133352view.php");
    }
else
    {
        header("Location: a1133352form.php");
    }
?>