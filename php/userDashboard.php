<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <?php
        $host = "localhost";
        $user = "root";
        $password = "";
        $database = "perpustakaandb";

        $conn = mysqli_connect($host, $user, $password, $database);

        if (!$conn) {
            die("Koneksi gagal: " . mysqli_connect_error());
        }

        // CREATE
        if(isset($_POST['simpan'])){

            $nama = $_POST['nama'];
            $alamat = $_POST['alamat'];
            $nohp = $_POST['NoTel'];
            $nim = $_POST['nim'];

            mysqli_query($conn,"
                INSERT INTO anggota
                (Nama,Alamat,NomerHandphone,NIM)
                VALUES
                ('$nama','$alamat','$nohp','$nim')
            ");

            header("Location: anggota.php");
            exit;
        }

        // DELETE
        if(isset($_GET['hapus'])){
            $id = $_GET['hapus'];

            mysqli_query($conn,"
                DELETE FROM anggota
                WHERE AnggotaID = '$id'
            ");

            header("Location: anggota.php");
            exit;
        }

        // AMBIL DATA EDIT
        $editData = null;

        if(isset($_GET['edit'])){
            $id = $_GET['edit'];

            $result = mysqli_query($conn,"
                SELECT * FROM anggota
                WHERE AnggotaID = '$id'
            ");

            $editData = mysqli_fetch_assoc($result);
        }

        // UPDATE
        if(isset($_POST['update'])){

            $id = $_POST['id'];
            $nama = $_POST['nama'];
            $alamat = $_POST['alamat'];
            $nohp = $_POST['NoTel'];
            $nim = $_POST['nim'];

            mysqli_query($conn,"
                UPDATE anggota
                SET
                    Nama='$nama',
                    Alamat='$alamat',
                    NomerHandphone='$nohp',
                    NIM='$nim'
                WHERE AnggotaID='$id'
            ");

            header("Location: anggota.php");
            exit;
        }

        $query = mysqli_query($conn, "SELECT * FROM anggota");
    ?>

    <div class="flex justify-between">
        <form action="" method="POST" class="flex flex-col text-start">

            <input type="hidden" name="id"
            value="<?= $editData['AnggotaID'] ?? ''; ?>">

            <div>
                <label>Name:</label>
                <input
                    type="text"
                    name="nama"
                    value="<?= $editData['Nama'] ?? ''; ?>"
                    class="max-w-[200px] rounded-[7px] border-[1px]">
            </div>

            <div>
                <label>Alamat:</label>
                <input
                    type="text"
                    name="alamat"
                    value="<?= $editData['Alamat'] ?? ''; ?>"
                    class="max-w-[200px] rounded-[7px] border-[1px]">
            </div>

            <div>
                <label>No HP:</label>
                <input
                    type="text"
                    name="NoTel"
                    value="<?= $editData['NomerHandphone'] ?? ''; ?>"
                    class="max-w-[200px] rounded-[7px] border-[1px]">
            </div>

            <div>
                <label>NIM:</label>
                <input
                    type="number"
                    name="nim"
                    value="<?= $editData['NIM'] ?? ''; ?>"
                    class="max-w-[200px] rounded-[7px] border-[1px]">
            </div>

            <?php if($editData) { ?>
                <button
                    name="update"
                    type="submit"
                    class="p-[5px] bg-yellow-300 rounded-[7px] mt-[20px] w-[100px]">
                    Update
                </button>
            <?php } else { ?>
                <button
                    name="simpan"
                    type="submit"
                    class="p-[5px] bg-blue-200 rounded-[7px] mt-[20px] w-[100px]">
                    Submit
                </button>
            <?php } ?>

        </form>

        <table class="border-collapse border-[1px]">
            <tr class="border-[1px]">
                <th>ID</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>No HP</th>
                <th>NIM</th>
                <th>Aksi</th>
            </tr>

            <?php while($data = mysqli_fetch_assoc($query)) { ?>
            <tr class="border-[1px]">
                <td><?= $data['AnggotaID']; ?></td>
                <td><?= $data['Nama']; ?></td>
                <td><?= $data['Alamat']; ?></td>
                <td><?= $data['NomerHandphone']; ?></td>
                <td><?= $data['NIM']; ?></td>

                <td>
                    <a
                        href="?edit=<?= $data['AnggotaID']; ?>"
                        class="bg-yellow-300 p-[5px] rounded">
                        Edit
                    </a>

                    <a
                        href="?hapus=<?= $data['AnggotaID']; ?>"
                        onclick="return confirm('Yakin ingin menghapus data?')"
                        class="bg-red-400 p-[5px] rounded">
                        Delete
                    </a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>