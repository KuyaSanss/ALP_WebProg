<?php
    require_once "adminSessionChecker.php";

    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "perpustakaandb";

    $conn = mysqli_connect($host, $user, $password, $database);

    // 1. PROSES UPDATE
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = intval($_POST['id']);
        $judul = mysqli_real_escape_string($conn, $_POST['judul']);
        $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);
        $pengarang = mysqli_real_escape_string($conn, $_POST['pengarang']);
        $penerbit = mysqli_real_escape_string($conn, $_POST['penerbit']);
        $tahun = intval($_POST['tahun']);
        $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
        $cover = mysqli_real_escape_string($conn, $_POST['cover']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);

        $update_sql = "UPDATE buku SET 
                        JudulBuku = '$judul', ISBN = '$isbn', NamaPengarang = '$pengarang', 
                        Penerbit = '$penerbit', TahunTerbit = $tahun, KategoriBuku = '$kategori', 
                        CoverBuku = '$cover', StatusKetersediaan = '$status' 
                       WHERE BukuID = $id";

        if (mysqli_query($conn, $update_sql)) {
            header("Location: adminBook.php"); // Sesuaikan nama file utama buku
            exit;
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
    }

    // 2. AMBIL DATA AWAL
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $result = mysqli_query($conn, "SELECT * FROM buku WHERE BukuID = $id");
        if (mysqli_num_rows($result) > 0) {
            $book = mysqli_fetch_assoc($result);
        } else {
            die("Data buku tidak ditemukan!");
        }
    } else {
        header("Location: book.php"); // Sesuaikan
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Book - Knowledge Journey</title>
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
                <h1 class="page-title">Edit Book</h1>
                <p class="page-subtitle">Update book details and inventory status</p>
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
                <input type="hidden" name="id" value="<?= $book['BukuID'] ?>">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="judul">Book Title</label>
                        <input type="text" id="judul" name="judul" class="form-control" value="<?= htmlspecialchars($book['JudulBuku']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="isbn">ISBN</label>
                        <input type="text" id="isbn" name="isbn" class="form-control" value="<?= htmlspecialchars($book['ISBN']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="pengarang">Author</label>
                        <input type="text" id="pengarang" name="pengarang" class="form-control" value="<?= htmlspecialchars($book['NamaPengarang']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="penerbit">Publisher</label>
                        <input type="text" id="penerbit" name="penerbit" class="form-control" value="<?= htmlspecialchars($book['Penerbit']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="tahun">Publication Year</label>
                        <input type="number" id="tahun" name="tahun" class="form-control" value="<?= htmlspecialchars($book['TahunTerbit']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="kategori">Category</label>
                        <select id="kategori" name="kategori" class="form-control" required>
                            <?php 
                                $categories = ['Novel', 'Komik', 'Pendidikan', 'Teknologi', 'Sains', 'Sejarah', 'Lainnya'];
                                foreach($categories as $cat) {
                                    $selected = ($book['KategoriBuku'] == $cat) ? 'selected' : '';
                                    echo "<option value='$cat' $selected>$cat</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="cover">Cover Image URL</label>
                        <input type="text" id="cover" name="cover" class="form-control" value="<?= htmlspecialchars($book['CoverBuku'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="status">Inventory Status</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="Tersedia" <?= ($book['StatusKetersediaan'] == 'Tersedia') ? 'selected' : '' ?>>Tersedia (Available)</option>
                            <option value="Dipinjam" <?= ($book['StatusKetersediaan'] == 'Dipinjam') ? 'selected' : '' ?>>Dipinjam (Unavailable)</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="adminBookEdit.php" style="text-decoration: none;"> <button type="button" class="btn-secondary">Cancel</button>
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fa-solid fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>