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

    // 1. PROSES UPDATE DATA (Saat tombol Save Changes ditekan)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = intval($_POST['id']);
        $nama = mysqli_real_escape_string($conn, $_POST['nama']);
        $nim = mysqli_real_escape_string($conn, $_POST['nim']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

        $update_sql = "UPDATE anggota SET 
                        Nama = '$nama', 
                        NIM = '$nim', 
                        NomerHandphone = '$phone', 
                        Alamat = '$alamat' 
                       WHERE AnggotaID = $id";

        if (mysqli_query($conn, $update_sql)) {
            header("Location: adminMember.php");
            exit;
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
    }

    // 2. AMBIL DATA LAMA (Saat halaman pertama kali dibuka berdasarkan ID di URL)
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $result = mysqli_query($conn, "SELECT * FROM anggota WHERE AnggotaID = $id");
        if (mysqli_num_rows($result) > 0) {
            $member = mysqli_fetch_assoc($result);
        } else {
            die("Data anggota tidak ditemukan!");
        }
    } else {
        header("Location: adminMember.php");
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
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // SOLUSI UTAMA: Menonaktifkan preflight agar Tailwind tidak merusak desain CSS manual (book.css / global.css)
        tailwind.config = {
            corePlugins: {
                preflight: false,
            }
        }
    </script>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/adminJS.js"></script>
</head>
<body>
    <div id="adminNavBarPosition"></div>

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
                    <a href="adminMember.php" style="text-decoration: none;">
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