<?php
    session_start();

    if(!isset($_SESSION['StaffID'])){
        header("Location: index.php");
        exit();
    }
?>