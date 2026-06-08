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

    // Memproses data jika form di-submit (tombol Save ditekan)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nama = mysqli_real_escape_string($conn, $_POST['nama']);
        $nim = mysqli_real_escape_string($conn, $_POST['nim']);
        $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);

        // Query untuk menyimpan data anggota baru
        $sql = "INSERT INTO anggota (Nama, NIM, Alamat, NomerHandphone) 
                VALUES ('$nama', '$nim', '$alamat', '$phone')";

        if (mysqli_query($conn, $sql)) {
            // Jika berhasil disimpan, langsung arahkan kembali ke halaman Member
            header("Location: member.php");
            exit;
        } else {
            $error_message = "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Member - Knowledge Journey</title>
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
                <h1 class="page-title">Add New Member</h1>
                <p class="page-subtitle">Register a new student to the library system</p>
            </div>
            <a href="member.php" style="text-decoration: none;">
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
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="nama">Full Name</label>
                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Enter member's full name" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="nim">Student ID (NIM)</label>
                        <input type="text" id="nim" name="nim" class="form-control" placeholder="Enter student ID" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" class="form-control" placeholder="e.g. 08123456789" required>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <label class="form-label" for="alamat">Home Address</label>
                    <textarea id="alamat" name="alamat" class="form-control" placeholder="Enter complete address" required></textarea>
                </div>

                <div class="form-actions">
                    <a href="member.php" style="text-decoration: none;">
                        <button type="button" class="btn-secondary">Cancel</button>
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fa-solid fa-save"></i> Save Member
                    </button>
                </div>
            </form>
        </div>

    </main>
</body>
</html>
<?php mysqli_close($conn); ?>