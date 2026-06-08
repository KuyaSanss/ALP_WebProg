<?php
    session_start();

    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "perpustakaandb";

    $conn = mysqli_connect($host, $user, $password, $database);

    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    // 1. PROSES UPDATE DATA (Jika form disubmit / Tombol Save ditekan)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = intval($_POST['id']); // Mengambil ID dari input hidden
        $nama = mysqli_real_escape_string($conn, $_POST['nama']);
        $nim = mysqli_real_escape_string($conn, $_POST['nim']);
        $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);

        // Query UPDATE untuk menimpa data lama dengan data baru
        $update_sql = "UPDATE anggota SET 
                        Nama = '$nama', 
                        NIM = '$nim', 
                        Alamat = '$alamat', 
                        NomerHandphone = '$phone' 
                       WHERE AnggotaID = $id";

        if (mysqli_query($conn, $update_sql)) {
            header("Location: member.php");
            exit;
        } else {
            $error_message = "Error updating record: " . mysqli_error($conn);
        }
    }

    // 2. PROSES TAMPILKAN DATA (Saat halaman pertama kali dibuka lewat link Edit)
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $sql = "SELECT * FROM anggota WHERE AnggotaID = $id";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $member = mysqli_fetch_assoc($result);
        } else {
            echo "Data anggota tidak ditemukan!";
            exit;
        }
    } else {
        // Jika tidak ada ID di URL, kembalikan ke halaman member
        header("Location: member.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Member - Knowledge Journey</title>
    <link rel="stylesheet" href="../css/book.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <header class="navbar">
        <div class="logo">
            <div class="logo-icon"><i class="fa-solid fa-book-open"></i></div>
            <span class="logo-text">Knowledge Journey</span>
        </div>
        
        <nav class="nav-links">
            <a href="index.php"><i class="fa-solid fa-house"></i> Dashboard</a>
            <a href="member.php" class="active"><i class="fa-solid fa-users"></i> Members</a>
            <a href="book.php"><i class="fa-solid fa-book"></i> Books</a>
            <a href="staff.php"><i class="fa-solid fa-user-tie"></i> Staff</a>
            <a href="peminjaman.php"><i class="fa-solid fa-clipboard-list"></i> Borrowing</a>
            <a href="pengembalian.php"><i class="fa-solid fa-rotate-left"></i> Returns</a>
            <a href="denda.php"><i class="fa-solid fa-dollar-sign"></i> Fines</a>
        </nav>

        <div class="nav-actions">
            <div class="avatar">A</div>
        </div>
    </header>

    <main class="main-container">
        
        <div class="page-header" style="margin-bottom: 25px;">
            <div>
                <h1 class="page-title">Edit Member</h1>
                <p class="page-subtitle">Update student information</p>
            </div>
            <a href="adminMember.php" style="text-decoration: none;">
                <button class="btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Back to Members
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
                <input type="hidden" name="id" value="<?= $member['AnggotaID'] ?>">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="nama">Full Name</label>
                        <input type="text" id="nama" name="nama" class="form-control" value="<?= htmlspecialchars($member['Nama']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="nim">Student ID (NIM)</label>
                        <input type="text" id="nim" name="nim" class="form-control" value="<?= htmlspecialchars($member['NIM']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" class="form-control" value="<?= htmlspecialchars($member['NomerHandphone']) ?>" required>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <label class="form-label" for="alamat">Home Address</label>
                    <textarea id="alamat" name="alamat" class="form-control" required><?= htmlspecialchars($member['Alamat']) ?></textarea>
                </div>

                <div class="form-actions">
                    <a href="member.php" style="text-decoration: none;">
                        <button type="button" class="btn-secondary">Cancel</button>
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
<?php mysqli_close($conn); ?>