<?php
    require_once "adminSessionChecker.php";

    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "perpustakaandb";

    $conn = mysqli_connect($host, $user, $password, $database);

    // 1. UPDATE DATA
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = intval($_POST['id']);
        $nama = mysqli_real_escape_string($conn, $_POST['nama']);
        $jabatan = mysqli_real_escape_string($conn, $_POST['jabatan']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);

        $update_sql = "UPDATE staff SET NamaStaff = '$nama', Jabatan = '$jabatan', NomerHandphone = '$phone' WHERE StaffID = $id";

        if (mysqli_query($conn, $update_sql)) {
            header("Location: adminStaff.php");
            exit;
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
    }

    // 2. AMBIL DATA AWAL
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $result = mysqli_query($conn, "SELECT * FROM staff WHERE StaffID = $id");
        if (mysqli_num_rows($result) > 0) {
            $staff = mysqli_fetch_assoc($result);
        } else {
            die("Data staff tidak ditemukan!");
        }
    } else {
        header("Location: adminStaff.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Staff - Knowledge Journey</title>
    <link rel="stylesheet" href="../css/book.css">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="../js/adminJS.js"></script>
</head>
<body>
    <div id="adminNavBarPosition"></div>

    <main class="main-container">
        <div class="page-header" style="margin-bottom: 25px;">
            <div>
                <h1 class="page-title">Edit Staff</h1>
                <p class="page-subtitle">Update employee information and roles</p>
            </div>
            <a href="adminStaff.php" style="text-decoration: none;">
                <button class="btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Back to Staff
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
                <input type="hidden" name="id" value="<?= $staff['StaffID'] ?>">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="nama">Full Name</label>
                        <input type="text" id="nama" name="nama" class="form-control" value="<?= htmlspecialchars($staff['NamaStaff']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="jabatan">Position</label>
                        <select id="jabatan" name="jabatan" class="form-control" required>
                            <option value="Admin" <?= ($staff['Jabatan'] == 'Admin') ? 'selected' : '' ?>>Admin</option>
                            <option value="Pustakawan" <?= ($staff['Jabatan'] == 'Pustakawan') ? 'selected' : '' ?>>Pustakawan</option>
                            <option value="Supervisor" <?= ($staff['Jabatan'] == 'Supervisor') ? 'selected' : '' ?>>Supervisor</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" class="form-control" value="<?= htmlspecialchars($staff['NomerHandphone']) ?>" required>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="adminStaff.php" style="text-decoration: none;">
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