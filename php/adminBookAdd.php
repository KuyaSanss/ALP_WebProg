<?php
    require_once "adminSessionChecker.php";

    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "perpustakaandb";

    $conn = mysqli_connect($host, $user, $password, $database);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $judul = mysqli_real_escape_string($conn, $_POST['judul']);
        $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);
        $pengarang = mysqli_real_escape_string($conn, $_POST['pengarang']);
        $penerbit = mysqli_real_escape_string($conn, $_POST['penerbit']);
        $tahun = intval($_POST['tahun']);
        $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
        $cover = mysqli_real_escape_string($conn, $_POST['cover']); // Asumsi URL gambar

        $sql = "INSERT INTO buku (JudulBuku, ISBN, NamaPengarang, Penerbit, TahunTerbit, KategoriBuku, CoverBuku, StatusKetersediaan) 
                VALUES ('$judul', '$isbn', '$pengarang', '$penerbit', $tahun, '$kategori', '$cover', 'Tersedia')";

        if (mysqli_query($conn, $sql)) {
            header("Location: adminBook.php"); // Ganti nama file ini sesuai file utama buku Anda
            exit;
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Book - Knowledge Journey</title>
    <link rel="stylesheet" href="../css/book.css">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="../js/globalJS.js"></script>
</head>
<body>
    <div id="adminNavBarPosition"></div>

    <main class="main-container">
        <div class="page-header" style="margin-bottom: 25px;">
            <div>
                <h1 class="page-title">Add New Book</h1>
                <p class="page-subtitle">Register a new book into the library catalog</p>
            </div>
            <a href="adminBook.php" style="text-decoration: none;"> <button class="btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Back to Books
                </button>
            </a>
        </div>

        <?php if(isset($error_message)): ?>
            <div style="background-color: #FEE2E2; color: #9B1C1C; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <?= $error_message ?>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <form method="POST" action="">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="judul">Book Title</label>
                        <input type="text" id="judul" name="judul" class="form-control" placeholder="Enter book title" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="isbn">ISBN</label>
                        <input type="text" id="isbn" name="isbn" class="form-control" placeholder="Enter ISBN number" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="pengarang">Author</label>
                        <input type="text" id="pengarang" name="pengarang" class="form-control" placeholder="Enter author's name" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="penerbit">Publisher</label>
                        <input type="text" id="penerbit" name="penerbit" class="form-control" placeholder="Enter publisher's name" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="tahun">Publication Year</label>
                        <input type="number" id="tahun" name="tahun" class="form-control" placeholder="e.g. 2024" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="kategori">Category</label>
                        <select id="kategori" name="kategori" class="form-control" required>
                            <option value="" disabled selected>Select category...</option>
                            <option value="Novel">Novel</option>
                            <option value="Komik">Komik</option>
                            <option value="Pendidikan">Pendidikan</option>
                            <option value="Teknologi">Teknologi</option>
                            <option value="Sains">Sains</option>
                            <option value="Sejarah">Sejarah</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <label class="form-label" for="cover">Cover Image URL</label>
                    <input type="text" id="cover" name="cover" class="form-control" placeholder="Enter image URL link (https://...)">
                </div>

                <div class="form-actions">
                    <a href="adminBook.php" style="text-decoration: none;"> <button type="button" class="btn-secondary">Cancel</button>
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fa-solid fa-save"></i> Save Book
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>