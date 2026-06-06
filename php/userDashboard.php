<?php
    session_start();

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
    AND p.TanggalTenggatPengembalian <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)
    ";

    $resultTenggatMingguIni = mysqli_query($conn, $sqlTenggatMingguIni);
    $dataTenggatMingguIni = mysqli_fetch_assoc($resultTenggatMingguIni);

    $jumlahTenggatMingguIni = $dataTenggatMingguIni['jumlah'];

    // Denda Belum Dibayar
    $sqlDendaBelumBayar = "
    SELECT SUM(d.NominalBayar) AS total
    FROM denda d
    JOIN peminjaman p
    ON d.PeminjamanID = p.PeminjamanID
    WHERE p.AnggotaID = $idAnggota
    AND d.StatusPembayaran = 'Belum Bayar'
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
    ";

    $resultBukuDipinjam = mysqli_query($conn, $sqlBukuDipinjam);
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
<body class="bg-[#e8ffd7] p-20 pt-[120px]">
    <div id="navBarPosition"></div>
    <p class="text-[35px]"><b>Welcome back, <?php echo $nama ?>!</b></p>
    <p class="text-[#4f5a65] mb-[50px]">Continue your learning journey today</p>

    <div class="flex w-[100%] justify-between mb-[50px]">
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
            <p>Tenggat minggu ini</p>
        </div>
        <div class="userInfo">
            <div>
                <i class="fa-solid fa-dollar-sign"></i>
            </div>
            
            <h1 class="text-red-700"><b>Rp.<?php echo $jumlahDendaBelumBayar ?></b></h1>
            <p>Total denda</p>
        </div>
    </div>

    <p class="text-[25px]"><b>Buku yang sedang dipinjam</b></p>
    <div class="mt-[50px]">
        <div class="flex flex-col gap-[20px]">
            <?php while($buku = mysqli_fetch_assoc($resultBukuDipinjam)) { ?>
                <div class="bg-white rounded-[20px] shadow-md p-[16px] flex items-center justify-between">
                    <div class="flex gap-[20px] items-center">
                        <img 
                        src="../<?php echo $buku['CoverBuku']; ?>"
                        alt="Cover Buku"
                        class="w-[80px] h-[100px] object-cover rounded-[15px]">
                        <div>
                            <h3 class="text-[28px] font-bold">
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
                            if(strtotime($buku['TanggalTenggatPengembalian']) < strtotime(date('Y-m-d'))){
                        ?>
                            <span class="bg-red-100 text-red-700 px-[15px] py-[8px] rounded-full font-semibold">
                                Due
                            </span>

                        <?php
                            } else {
                        ?>

                            <span class="bg-green-100 text-green-700 px-[15px] py-[8px] rounded-full font-semibold">
                                Active
                            </span>

                        <?php
                            }
                        ?>
                    </div>

                </div>

            <?php } ?>

        </div>
    </div>
</body>
</html>