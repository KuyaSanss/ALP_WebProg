<?php
    require_once "adminSessionChecker.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        $host = "localhost";
        $user = "root";
        $password = "";
        $database = "perpustakaandb";

        $conn = mysqli_connect($host, $user, $password, $database);

        $id = intval($_POST['id']);
        $sql = "DELETE FROM buku WHERE BukuID = $id";

        if (mysqli_query($conn, $sql)) {
            echo 'success';
        } else {
            echo 'error: ' . mysqli_error($conn);
        }
        mysqli_close($conn);
    }
?>