<?php
    require_once "adminSessionChecker.php";

    // Memastikan request datang dari metode POST dan memiliki data 'id'
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        $host = "localhost";
        $user = "root";
        $password = "";
        $database = "perpustakaandb";

        $conn = mysqli_connect($host, $user, $password, $database);

        if (!$conn) {
            die("Koneksi gagal");
        }

        $id = intval($_POST['id']);

        // Mengeksekusi penghapusan data
        $sql = "DELETE FROM Anggota WHERE AnggotaID = $id";

        if (mysqli_query($conn, $sql)) {
            // Jika berhasil, kirimkan teks 'success' ke AJAX
            echo 'success';
        } else {
            // Jika gagal (misal karena constraint relasi tabel), kirim pesan error
            echo 'error: ' . mysqli_error($conn);
        }

        mysqli_close($conn);
    }
?>