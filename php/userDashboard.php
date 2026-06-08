<?php
    require_once "userSessionChecker.php";

    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "perpustakaandb";

    $conn = mysqli_connect($host, $user, $password, $database);

    if(isset($_SESSION['Nama'])){
        $nama = $_SESSION['Nama'];
        $idAnggota = $_SESSION['AnggotaID'];
    }

    // jumlah buku dipinjam
    $sqlJumlahBuku = "
    SELECT COUNT(*) AS jumlah
    FROM detailpeminjaman dp
    JOIN peminjaman p
    ON dp.PeminjamanID = p.PeminjamanID
    WHERE p.AnggotaID = $idAnggota
    AND dp.StatusPeminjaman = 'Dipinjam'
    ";

    $resultJumlahBuku = mysqli_query($conn, $sqlJumlahBuku);
    $dataJumlahBuku = mysqli_fetch_assoc($resultJumlahBuku);

    $jumlahBuku = $dataJumlahBuku['jumlah'];

    // tenggat minggu ini
    $sqlTenggatMingguIni = "
    SELECT COUNT(*) AS jumlah
    FROM detailpeminjaman dp
    JOIN peminjaman p
    ON dp.PeminjamanID = p.PeminjamanID
    WHERE p.AnggotaID = $idAnggota
    AND dp.StatusPeminjaman = 'Dipinjam'
    AND p.TanggalTenggatPengembalian >= CURDATE()
    AND p.TanggalTenggatPengembalian <= DATE_ADD(CURDATE(), INTERVAL 3 DAY)
    ";

    $resultTenggatMingguIni = mysqli_query($conn, $sqlTenggatMingguIni);
    $dataTenggatMingguIni = mysqli_fetch_assoc($resultTenggatMingguIni);

    $jumlahTenggatMingguIni = $dataTenggatMingguIni['jumlah'];

    // Denda Belum Dibayar
    $sqlDendaBelumBayar = "
    SELECT
        SUM(
            DATEDIFF(
                CURDATE(),
                p.TanggalTenggatPengembalian
            ) * 5000
        ) AS total
    FROM detailpeminjaman dp
    JOIN peminjaman p
    ON dp.PeminjamanID = p.PeminjamanID
    WHERE p.AnggotaID = $idAnggota
    AND dp.StatusPeminjaman = 'Dipinjam'
    AND CURDATE() > p.TanggalTenggatPengembalian
    ";

    $resultDendaBelumBayar = mysqli_query($conn, $sqlDendaBelumBayar);
    $dataDendaBelumBayar = mysqli_fetch_assoc($resultDendaBelumBayar);

    $jumlahDendaBelumBayar = $dataDendaBelumBayar['total'] ?? 0;

    // buku dipinjam
    $sqlBukuDipinjam = "
    SELECT
        b.JudulBuku,
        b.NamaPengarang,
        b.CoverBuku,
        p.TanggalTenggatPengembalian,
        dp.StatusPeminjaman
    FROM detailpeminjaman dp
    JOIN peminjaman p
    ON dp.PeminjamanID = p.PeminjamanID
    JOIN buku b
    ON dp.BukuID = b.BukuID
    WHERE p.AnggotaID = $idAnggota
    AND dp.StatusPeminjaman = 'Dipinjam'
    ORDER BY p.TanggalTenggatPengembalian ASC
    ";

    $resultBukuDipinjam = mysqli_query($conn, $sqlBukuDipinjam);
    $jumlahBukuDipinjam = mysqli_num_rows($resultBukuDipinjam);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/userHome.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globalJS.js"></script>
    
</head>
<body>
    <div id="navBarPosition"></div>
    <p class="text-[28px] md:text-[35px]"><b>Welcome back, <?php echo $nama ?>!</b></p>
    <p class="text-[#4f5a65] mb-[50px]">Continue your learning journey today</p>

    <div class="flex flex-col md:flex-row w-full gap-[20px] md:gap-0 md:justify-between mb-[50px]">
        <div class="userInfo">
            <div>
                <i class="fa-solid fa-book"></i>
            </div>
            
            <h1><b><?php echo $jumlahBuku; ?></b></h1>
            <p>Buku yang sedang dipinjam</p>
        </div>
        <div class="userInfo">
            <div>
                <i class="fa-regular fa-calendar"></i>
            </div>
            
            <h1><b><?php echo $jumlahTenggatMingguIni; ?></b></h1>
            <p>Tenggat kurang dari 3 hari</p>
        </div>
        <div class="userInfo">
            <div>
                <i class="fa-solid fa-dollar-sign"></i>
            </div>
            
            <h1 class="text-red-700 md:!text-[40px] !text-[22px] "><b>Rp.<?php echo $jumlahDendaBelumBayar ?></b></h1>
            <p>Total denda</p>
        </div>
    </div>

    <p class="text-[25px]"><b>Buku yang sedang dipinjam</b></p>
    <div class="mt-[50px]">
        <div class="mt-[30px]">
            <?php if($jumlahBukuDipinjam == 0){ ?>
                <div class="bg-white rounded-[20px] shadow-md p-[40px] text-center">
                    <i class="fa-solid fa-book-open text-[50px] text-gray-300 mb-[15px]"></i>
                    <p class="text-[22px] font-semibold text-gray-500">
                        Anda sedang tidak meminjam buku apapun!
                    </p>
                </div>
            <?php } else { ?>
                <div class="flex flex-col gap-[20px]">
                        <?php while($buku = mysqli_fetch_assoc($resultBukuDipinjam)) { ?>
                            <div class="bg-white rounded-[20px] shadow-md p-[16px] flex flex-col md:flex-row md:items-center md:justify-between gap-[20px]">
                                <div class="flex flex-col sm:flex-row gap-[20px] items-center sm:items-start text-center sm:text-left">
                                    <img 
                                    src="<?php echo $buku['CoverBuku']; ?>"
                                    alt="Cover Buku"
                                    class="w-[100px] h-[125px] md:w-[80px] md:h-[100px] object-cover rounded-[15px]">
                                    <div>
                                        <h3 class="text-[22px] md:text-[28px] font-bold">
                                            <?php echo $buku['JudulBuku']; ?>
                                        </h3>
                                        <p class="text-gray-500 text-[18px]">
                                            <?php echo $buku['NamaPengarang']; ?>
                                        </p>
                                        <div class="flex items-center gap-[10px] mt-[10px]">
                                            <i class="fa-regular fa-calendar text-gray-500"></i>
                                            <span class="text-gray-500">
                                                Due:
                                                <?php echo $buku['TanggalTenggatPengembalian']; ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <?php

                                    $dendaRealtime = 0;

                                    if(
                                        strtotime($buku['TanggalTenggatPengembalian'])
                                        <
                                        strtotime(date('Y-m-d'))
                                    ){

                                        $hariTerlambat = floor(
                                            (
                                                strtotime(date('Y-m-d'))
                                                -
                                                strtotime(
                                                    $buku['TanggalTenggatPengembalian']
                                                )
                                            ) / 86400
                                        );

                                        $dendaRealtime =
                                        $hariTerlambat * 5000;
                                    }
                                    ?>

                                    <?php if($dendaRealtime > 0){ ?>

                                        <div class="flex flex-col items-end gap-[8px]">

                                            <span class="bg-red-100 text-red-700 px-[15px] py-[8px] rounded-full font-semibold">
                                                Due
                                            </span>

                                            <span class="text-red-600 font-semibold">
                                                Fine: Rp <?=
                                                number_format(
                                                    $dendaRealtime,
                                                    0,
                                                    ',',
                                                    '.'
                                                );
                                                ?>
                                            </span>

                                        </div>

                                    <?php } else { ?>

                                        <span class="bg-green-100 text-green-700 px-[15px] py-[8px] rounded-full font-semibold">
                                            Aktif
                                        </span>

                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>