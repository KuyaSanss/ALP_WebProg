<?php
    require_once "userSessionChecker.php";
    session_start();

    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "perpustakaandb";

    $conn = mysqli_connect($host, $user, $password, $database);

    $id = $_GET['id'];

    $sql = "
    SELECT *
    FROM buku
    WHERE BukuID = $id
    ";

    $result = mysqli_query($conn, $sql);
    $buku = mysqli_fetch_assoc($result);

    if(isset($_POST['pinjamBuku'])){

        if($buku['StatusKetersediaan'] != 'Tersedia'){
            die("Buku sedang dipinjam.");
        }

        $idAnggota = $_SESSION['AnggotaID'];

        $tanggalPinjam = date('Y-m-d');
        $tanggalTenggat = date('Y-m-d', strtotime('+7 days'));

        mysqli_query($conn,"
            INSERT INTO peminjaman(
                TanggalPeminjaman,
                TanggalTenggatPengembalian,
                AnggotaID,
                StaffID
            )
            VALUES(
                '$tanggalPinjam',
                '$tanggalTenggat',
                $idAnggota,
                1
            )
        ");

        $peminjamanID = mysqli_insert_id($conn);

        mysqli_query($conn,"
            INSERT INTO detailpeminjaman(
                StatusPeminjaman,
                BukuID,
                PeminjamanID
            )
            VALUES(
                'Dipinjam',
                $id,
                $peminjamanID
            )
        ");

        mysqli_query($conn,"
            UPDATE buku
            SET StatusKetersediaan = 'Dipinjam'
            WHERE BukuID = $id
        ");

        header("Location: userBorrowings.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>detail peminjaman</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/userHome.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globalJS.js"></script>
    
</head>
<body>
    <div id="navBarPosition"></div>

    <a href="userBooks.php" class="text-[#4f5a65] text-[16px] md:text-[18px]">
        <i class="fa-solid fa-arrow-left"></i>
        Back to Books
    </a>

    <div class="bg-white rounded-[25px] shadow-md p-[20px] md:p-[40px] flex flex-col lg:flex-row gap-[30px] md:gap-[40px] mt-[30px]">
        <div class="w-full lg:w-[350px]">
            <img
            src="<?php echo $buku['CoverBuku']; ?>"
            alt="<?php echo $buku['JudulBuku']; ?>"
            class="w-full h-[350px] sm:h-[450px] lg:h-[500px] object-cover rounded-[20px]">

            <?php if($buku['StatusKetersediaan'] == 'Tersedia'){ ?>
                <form method="POST">
                    <button type="submit" name="pinjamBuku" class="w-full mt-[20px] bg-[#487051] hover:bg-[#3e5f44] text-white py-[15px] rounded-[15px] text-[18px] md:text-[20px] font-semibold transition-all duration-200">
                        Pinjam Buku
                    </button>
                </form>
            <?php } else { ?>
                <button class="w-full mt-[20px] bg-gray-400 text-white py-[15px] rounded-[15px] text-[20px] font-semibold cursor-not-allowed">
                    Sedang Dipinjam
                </button>
            <?php } ?>
        </div>

        <div class="flex-1">
            <h1 class="text-[30px] md:text-[50px] font-bold leading-tight">
                <?php echo $buku['JudulBuku']; ?>
            </h1>

            <p class="text-[20px] md:text-[28px] text-[#4f5a65] mt-[10px]">
                <?php echo $buku['NamaPengarang']; ?>
            </p>

            <br>

            <div class="flex flex-wrap items-center gap-[15px] mb-[20px]">

                <?php if($buku['StatusKetersediaan'] == 'Tersedia'){ ?>
                    <span class="bg-green-100 text-green-700 px-[15px] py-[6px] rounded-full font-semibold">
                        Tersedia
                    </span>
                <?php } else { ?>
                    <span class="bg-red-100 text-red-700 px-[15px] py-[6px] rounded-full font-semibold">
                        Dipinjam
                    </span>
                <?php } ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-[20px] mt-[40px]">

                <div class="bg-gray-50 rounded-[15px] p-[20px]">
                    <p class="text-gray-500 mb-[5px]">ISBN</p>
                    <p class="text-[18px] md:text-[22px] font-semibold break-words">
                        <?php echo $buku['ISBN']; ?>
                    </p>
                </div>

                <div class="bg-gray-50 rounded-[15px] p-[20px]">
                    <p class="text-gray-500 mb-[5px]">Penerbit</p>
                    <p class="text-[18px] md:text-[22px] font-semibold break-words">
                        <?php echo $buku['Penerbit']; ?>
                    </p>
                </div>

                <div class="bg-gray-50 rounded-[15px] p-[20px]">
                    <p class="text-gray-500 mb-[5px]">Tahun Terbit</p>
                    <p class="text-[18px] md:text-[22px] font-semibold break-words">
                        <?php echo $buku['TahunTerbit']; ?>
                    </p>
                </div>

                <div class="bg-gray-50 rounded-[15px] p-[20px]">
                    <p class="text-gray-500 mb-[5px]">Kategori</p>
                    <p class="text-[18px] md:text-[22px] font-semibold break-words">
                        <?php echo $buku['KategoriBuku']; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>