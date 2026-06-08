<?php
    session_start();

    if(!isset($_SESSION['AnggotaID'])){
        header("Location: index.php");
        exit();
    }
?>