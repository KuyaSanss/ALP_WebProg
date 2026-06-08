<?php
    session_start();

    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "perpustakaandb";

    $conn = mysqli_connect($host, $user, $password, $database);

    $kategori = $_GET['kategori'] ?? 'All';
    $search = $_GET['search'] ?? '';

    $sql = "SELECT * FROM buku WHERE 1=1";

    if($kategori != 'All'){
        $sql .= " AND KategoriBuku = '$kategori'";
    }

    if(!empty($search)){
        $sql .= " AND (
            JudulBuku LIKE '%$search%'
            OR NamaPengarang LIKE '%$search%'
        )";
    }

    $result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Looking for books?</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/userHome.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globalJS.js"></script>
    
</head>
<body>
    <div id="navBarPosition"></div>
    <p class="text-[28px] md:text-[35px]"><b>Browse Books</b></p>
    <p class="text-[#4f5a65] mb-[50px]">Cari buku Favorite mu si sini!</p>

    <form action="" method="GET">
        <div class="relative w-full md:w-[70%]">
            <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]"></i>

            <input
                type="text"
                name="search"
                value="<?php echo $search; ?>"
                placeholder="Search by title or author..."
                class="w-full h-[56px] pl-14 pr-5 rounded-2xl border border-gray-300 bg-white text-[18px] outline-none focus:border-black">
        </div>
    </form>
    <br>
    
    <div class="flex flex-wrap items-center gap-[12px] mb-[40px]">
        <i class="fa-solid fa-filter text-[#4f5a65]"></i>
        <span class="font-semibold text-[18px]">
            Category:
        </span>
        <?php
        $kategoriList = [
            'All',
            'Novel',
            'Komik',
            'Pendidikan',
            'Teknologi',
            'Sains',
            'Sejarah',
            'Lainnya'
        ];

        foreach($kategoriList as $item){ $active = ($kategori == $item);
        ?>
            <a href="?kategori=<?php echo $item; ?>&search=<?php echo urlencode($search); ?>" class="px-[15px] md:px-[20px] py-[8px] md:py-[10px] rounded-[15px] border transition-all duration-200 text-[14px] md:text-[16px] <?php echo $active ? 'bg-[#4d6f4e] text-white border-[#4d6f4e]' : 'bg-white text-[#4f5a65] border-gray-300'; ?>">
                <?php echo $item; ?>
            </a>
        <?php } ?>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-[30px]">
        <?php while($buku = mysqli_fetch_assoc($result)){ ?>
            <div class="bg-white rounded-[20px] overflow-hidden shadow-md hover:shadow-xl transition-all duration-300">
                <div class="relative">
                    <img
                    src="<?php echo $buku['CoverBuku']; ?>"
                    alt="<?php echo $buku['JudulBuku']; ?>"
                    class="w-full h-[260px] object-cover">
                    <?php if($buku['StatusKetersediaan'] == 'Tersedia'){ ?>
                        <span class="absolute top-[10px] right-[10px] md:top-[15px] md:right-[15px] bg-green-500 text-white px-[15px] py-[6px] rounded-full text-[14px] font-semibold">
                            Tersedia
                        </span>
                    <?php } else { ?>
                        <span class="absolute top-[10px] right-[10px] md:top-[15px] md:right-[15px] bg-red-500 text-white px-[15px] py-[6px] rounded-full text-[14px] font-semibold">
                            Dipinjam
                        </span>
                    <?php } ?>
                </div>

                <div class="p-[20px]">
                    <h2 class="text-[20px] md:text-[24px] font-bold mb-[5px] overflow-hidden">
                        <?php echo $buku['JudulBuku']; ?>
                    </h2>
                    <p class="text-[#4f5a65] text-[16px] md:text-[18px]">
                        <?php echo $buku['NamaPengarang']; ?>
                    </p>
                    <div class="flex justify-center md:justify-end mt-[20px]">
                        <a href="userDetailBuku.php?id=<?php echo $buku['BukuID']; ?>" class="text-[#4d6f4e] font-semibold hover:underline">
                            <i class="fas fa-book-open"></i>
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</body>
</html>