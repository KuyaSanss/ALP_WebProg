<?php
    require_once "adminSessionChecker.php";

    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "perpustakaandb";

    $conn = mysqli_connect($host, $user, $password, $database);

    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    $data = null;
    $hariTerlambat = 0;
    $denda = 0;

    if(isset($_POST['search'])){

        $peminjamanID = $_POST['peminjamanID'];

        $query = "
        SELECT
            dp.DetailPeminjamanID,
            dp.BukuID,
            dp.PeminjamanID,
            b.JudulBuku,
            a.Nama,
            p.TanggalPeminjaman,
            p.TanggalTenggatPengembalian
        FROM detailpeminjaman dp
        JOIN peminjaman p
            ON dp.PeminjamanID = p.PeminjamanID
        JOIN anggota a
            ON p.AnggotaID = a.AnggotaID
        JOIN buku b
            ON dp.BukuID = b.BukuID
        WHERE dp.PeminjamanID = '$peminjamanID'
        AND dp.StatusPeminjaman = 'Dipinjam'
        ";

        $result = mysqli_query($conn,$query);

        if(mysqli_num_rows($result) > 0){

            $data = mysqli_fetch_assoc($result);

            $today = date("Y-m-d");

            if($today > $data['TanggalTenggatPengembalian']){

                $hariTerlambat = floor(
                    (
                        strtotime($today)
                        -
                        strtotime(
                            $data['TanggalTenggatPengembalian']
                        )
                    ) / 86400
                );

                $denda = $hariTerlambat * 5000;
            }
        }
    }

    if(isset($_POST['submitReturn'])){

        $detailID = $_POST['detailID'];
        $bukuID = $_POST['bukuID'];
        $peminjamanID = $_POST['peminjamanID'];

        $hariTerlambat = $_POST['hariTerlambat'];
        $denda = $_POST['denda'];

        mysqli_begin_transaction($conn);

        try{

            mysqli_query(
                $conn,
                "
                UPDATE detailpeminjaman
                SET StatusPeminjaman='Dikembalikan'
                WHERE DetailPeminjamanID='$detailID'
                "
            );

            mysqli_query(
                $conn,
                "
                UPDATE buku
                SET StatusKetersediaan='Tersedia'
                WHERE BukuID='$bukuID'
                "
            );

            if($denda > 0){

                $metodeBayar = $_POST['metodeBayar'];

                mysqli_query(
                    $conn,
                    "
                    INSERT INTO denda(
                        NominalBayar,
                        StatusPembayaran,
                        HariKeterlambatan,
                        TanggalBayar,
                        MetodeBayar,
                        PeminjamanID
                    )
                    VALUES(
                        '$denda',
                        'Lunas',
                        '$hariTerlambat',
                        CURDATE(),
                        '$metodeBayar',
                        '$peminjamanID'
                    )
                    "
                );
            }

            mysqli_commit($conn);

            echo "
            <script>
                alert('Book returned successfully');
                window.location='adminPengembalian.php';
            </script>
            ";

        }catch(Exception $e){

            mysqli_rollback($conn);

            echo "
            <script>
                alert('Failed to return book');
            </script>
            ";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Returns Management - Knowledge Journey</title>
    <link rel="stylesheet" href="../css/book.css">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globalJS.js"></script>
</head>
<body>
    <div id="adminNavBarPosition"></div>

    <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="../css/global.css">
        <h1 class="text-3xl font-bold mb-6">
            Return Book
        </h1>
        <form method="POST">
            <label class="block mb-2 font-medium">
                Borrowing ID
            </label>
            <input
                type="number"
                name="peminjamanID"
                required
                class="w-full border rounded-lg p-3"
            >
            <button
                type="submit"
                name="search"
                class="mt-4 bg-green-700 text-white px-5 py-3 rounded-lg"
            >
                Search
            </button>
        </form>

        <?php if($data): ?>

        <hr class="my-8">

        <form method="POST">

            <input type="hidden" name="detailID" value="<?= $data['DetailPeminjamanID'] ?>">
            <input type="hidden" name="bukuID" value="<?= $data['BukuID'] ?>">
            <input type="hidden" name="peminjamanID" value="<?= $data['PeminjamanID'] ?>">
            <input type="hidden" name="hariTerlambat" value="<?= $hariTerlambat ?>">
            <input type="hidden" name="denda" value="<?= $denda ?>">

            <div class="mb-4">
                <label class="font-medium">
                    Member Name
                </label>
                <input
                    value="<?= htmlspecialchars($data['Nama']) ?>"
                    readonly
                    class="w-full border rounded-lg p-3 bg-gray-100"
                >
            </div>
            <div class="mb-4">
                <label class="font-medium">
                    Book Title
                </label>

                <input
                    value="<?= htmlspecialchars($data['JudulBuku']) ?>"
                    readonly
                    class="w-full border rounded-lg p-3 bg-gray-100"
                >
            </div>
            <div class="mb-4">
                <label class="font-medium">
                    Due Date
                </label>

                <input
                    value="<?= $data['TanggalTenggatPengembalian'] ?>"
                    readonly
                    class="w-full border rounded-lg p-3 bg-gray-100"
                >
            </div>

            <div class="mb-4">
                <label class="font-medium">
                    Late Days
                </label>

                <input
                    value="<?= $hariTerlambat ?>"
                    readonly
                    class="w-full border rounded-lg p-3 bg-gray-100"
                >
            </div>

            <div class="mb-4">
                <label class="font-medium">
                    Fine
                </label>

                <input
                    value="Rp <?= number_format($denda,0,',','.') ?>"
                    readonly
                    class="w-full border rounded-lg p-3 bg-gray-100"
                >
            </div>

            <?php if($denda > 0): ?>

            <div class="mb-4">
                <label class="font-medium">
                    Payment Method
                </label>

                <select
                    name="metodeBayar"
                    required
                    class="w-full border rounded-lg p-3"
                >
                    <option value="">Choose Method</option>
                    <option value="Cash">Cash</option>
                    <option value="Transfer">Transfer</option>
                    <option value="QRIS">QRIS</option>
                </select>
            </div>

            <?php endif; ?>

            <button
                type="submit"
                name="submitReturn"
                class="bg-[#3E5F44] text-white px-6 py-3 rounded-lg"
            >
                Complete Return
            </button>

        </form>

        <?php endif; ?>
</body>
</html>
<?php mysqli_close($conn); ?>