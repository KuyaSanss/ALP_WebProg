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

    $jumlahDendaBelumBayar = $dataDendaBelumBayar['total'] ?: 0;


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

    $sqlHistory = "
    SELECT
        b.JudulBuku,
        b.NamaPengarang,
        b.CoverBuku,
        p.TanggalPeminjaman,
        p.TanggalTenggatPengembalian,
        dp.StatusPeminjaman,
        COALESCE(d.NominalBayar, 0) AS NominalBayar
    FROM detailpeminjaman dp
    JOIN peminjaman p
    ON dp.PeminjamanID = p.PeminjamanID
    JOIN buku b
    ON dp.BukuID = b.BukuID
    LEFT JOIN denda d
    ON p.PeminjamanID = d.PeminjamanID
    WHERE p.AnggotaID = $idAnggota
    AND dp.StatusPeminjaman IN ('Dikembalikan','Hilang','Rusak')
    ORDER BY p.TanggalPeminjaman DESC
    ";

    $resultHistory = mysqli_query($conn, $sqlHistory);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your borrowings</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/userHome.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globalJS.js"></script>
    
</head>
<body>
    <div id="navBarPosition"></div>
    <p class="text-[28px] md:text-[35px]"><b>My Borrowings</b></p>
    <p class="text-[#4f5a65] mb-[50px]">Buku yang anda pinjam</p>
    <?php if($jumlahDendaBelumBayar > 0){ ?>
    <div class="w-full bg-red-50 border border-red-200 rounded-[20px] p-[20px] md:p-[25px] mb-[40px] flex flex-col md:flex-row items-start gap-[15px]">
        
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-[10px] text-center sm:text-left">
            <i class="fa-solid fa-circle-exclamation text-red-600 text-[40px]"></i>
            <div>
                <h2 class="text-red-700 text-[24px] font-bold">
                Denda Belum Dibayar
                </h2>
                <p class="text-red-600 text-[18px]">
                    Kamu memiliki denda sebesar
                    <b>Rp.<?php echo number_format($jumlahDendaBelumBayar, 2, ',', '.'); ?></b>.
                    Segera lakukan pembayaran di perpustakaan.
                </p>
            </div>  
        </div>
    </div>
    <?php } ?>

    <p class="text-[25px]"><b>Buku yang sedang dipinjam</b></p>

    <div class="mt-[30px]">
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
                                <div class="flex flex-col sm:flex-row gap-[20px] items-center sm:items-center text-center sm:text-left">
                                    <img 
                                    src="<?php echo $buku['CoverBuku']; ?>"
                                    alt="Cover Buku"
                                    class="w-[110px] h-[140px] md:w-[80px] md:h-[100px] object-cover rounded-[15px]">
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

                                <div class="w-full md:w-auto flex justify-center md:justify-end">
                                    <?php if(strtotime($buku['TanggalTenggatPengembalian']) < strtotime(date('Y-m-d'))){ ?>
                                        <span class="bg-red-100 text-red-700 px-[15px] py-[8px] rounded-full font-semibold">
                                            Due
                                        </span>

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

    <br>

    <p class="text-[25px]"><b>History peminjaman</b></p>

    <div class="bg-white rounded-[20px] shadow-md overflow-x-auto mt-[20px]">
        <div class="grid grid-cols-[300px_150px_150px_150px_150px] bg-gray-50 border-b font-semibold text-[#4f5a65] min-w-[900px]">
            <div class="p-[20px]">Buku</div>
            <div class="p-[20px]">Tanggal Pinjam</div>
            <div class="p-[20px]">Tenggat</div>
            <div class="p-[20px]">Status</div>
            <div class="p-[20px]">Denda</div>

        </div>

        <?php while($history = mysqli_fetch_assoc($resultHistory)){ ?>

            <div class="grid grid-cols-[300px_150px_150px_150px_150px] items-center border-b min-w-[900px]">

                <div class="flex items-center gap-[15px] p-[20px]">

                    <img
                    src="<?php echo $history['CoverBuku']; ?>"
                    class="w-[50px] h-[70px] object-cover rounded-[10px]">

                    <div>

                        <h3 class="font-semibold text-[18px]">
                            <?php echo $history['JudulBuku']; ?>
                        </h3>

                        <p class="text-gray-500">
                            <?php echo $history['NamaPengarang']; ?>
                        </p>

                    </div>

                </div>

                <div class="p-[20px]">
                    <?php echo $history['TanggalPeminjaman']; ?>
                </div>

                <div class="p-[20px]">
                    <?php echo $history['TanggalTenggatPengembalian']; ?>
                </div>

                <div class="p-[20px]">

                    <?php if($history['StatusPeminjaman'] == 'Dikembalikan'){ ?>

                        <span class="bg-gray-100 text-gray-700 px-[12px] py-[5px] rounded-full">
                            Dikembalikan
                        </span>

                    <?php } elseif($history['StatusPeminjaman'] == 'Dipinjam'){ ?>

                        <span class="bg-green-100 text-green-700 px-[12px] py-[5px] rounded-full">
                            Dipinjam
                        </span>

                    <?php } elseif($history['StatusPeminjaman'] == 'Hilang'){ ?>

                        <span class="bg-red-100 text-red-700 px-[12px] py-[5px] rounded-full">
                            Hilang
                        </span>

                    <?php } else { ?>

                        <span class="bg-orange-100 text-orange-700 px-[12px] py-[5px] rounded-full">
                            Rusak
                        </span>

                    <?php } ?>

                </div>

                <div class="p-[20px]">

                    <?php if($history['NominalBayar'] > 0){ ?>

                        <span class="text-red-600 font-semibold">
                            Rp.<?php echo number_format($history['NominalBayar'], 2, ',', '.'); ?>
                        </span>

                    <?php } else { ?>

                        <span class="text-green-600 font-semibold">
                            Rp.0,00
                        </span>

                    <?php } ?>

                </div>

            </div>

        <?php } ?>

    </div>
</body>
</html>