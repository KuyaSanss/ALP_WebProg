<?php
    session_start();

    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "perpustakaandb";

    $conn = mysqli_connect($host, $user, $password, $database);

    if(isset($_POST['nama']) && isset($_POST['NIM'])) {
        $nama = $_POST['nama'];
        $NIM = $_POST['NIM'];

        $query = "
            SELECT *
            FROM anggota
            WHERE Nama = '$nama'
            AND NIM = '$NIM'
        ";

        $result = mysqli_query($conn, $query);
        if(!$result){
            die(mysqli_error($conn));
        }

        if(mysqli_num_rows($result) > 0) {
            $anggota = mysqli_fetch_assoc($result);

            $_SESSION['AnggotaID'] = $anggota['AnggotaID'];
            $_SESSION['Nama'] = $anggota['Nama'];

            header("Location: userDashboard.php");
            exit();
        }
        else {
            echo "
                <script>
                    alert('Nama dan/atau NIM salah')
                </script>
            ";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://googleapis.com"></script>

    <link rel="stylesheet" href="../css/loginRegister.css">
    <link rel="stylesheet" href="https://cloudflare.com">
</head>
<body>
    <div class="flex">
        <div class="hidden md:block bg-[radial-gradient(circle_at_50%_50%,#6d9b79_0%,#679573_35%,#578663_100%)] w-[50%] min-h-screen p-20">
            <div class="flex text-white text-[30px] items-center gap-[20px]">
                <img src="Logo.png" alt="logo" class="logo">
                <p>
                    <b>Knowledge journey</b>
                </p>
            </div>

            <br>
            <br>
            <br>

            <div class="text-white">
                <p class="text-[60px]">
                    <b>Welcome to Our Academic Library</b>
                </p>
                <br>
                <p class="text-[20px]">
                    Explore thousands of books, manage your borrowings, and embark on your knowledge journey with ease.
                </p>
            </div>

            <br>
            <br>
            <br>

            <div class="flex h-100% justify-between">
                <?php 
                    $query = "SELECT COUNT(*) AS total_buku FROM buku";
                    $result = mysqli_query($conn, $query);

                    $data = mysqli_fetch_assoc($result);

                    $total_buku = $data['total_buku'];
                ?>
                <div class="approvalCard">
                    <p class="approvalCard-number">
                        <b>
                            <?php echo $total_buku ?>   
                        </b>
                    </p>
                    <p class="approvalCard-text">
                        Book Available
                    </p>
                </div>
                
                <?php 
                    $query = "SELECT COUNT(*) AS total_anggota FROM anggota";
                    $result = mysqli_query($conn, $query);

                    $data = mysqli_fetch_assoc($result);

                    $total_anggota = $data['total_anggota'];
                ?>
                <div class="approvalCard">
                    <p class="approvalCard-number">
                        <b>
                            <?php echo $total_anggota ?>   
                        </b>
                    </p>
                    <p class="approvalCard-text">
                        Active Members
                    </p>
                </div>
                
                <div class="approvalCard">
                    <p class="approvalCard-number">
                        <b>
                            99%   
                        </b>
                    </p>
                    <p class="approvalCard-text">
                        Satisfaction Rate
                    </p>
                </div>
            </div>
        </div>
            
        <div class="w-full md:w-[50%] pt-10 pb-10 px-6 md:pl-20 md:pr-20 flex flex-col gap-12 md:gap-20 max-w-[700px] mx-auto">
            <div>
                <div class="flex md:hidden items-center gap-[12px] mb-[20px]">
                    <img src="Logo.png" alt="logo" class="w-[50px] h-[50px] rounded-full">
                    <p class="text-[22px] font-bold text-[#3e5f44]">
                        Knowledge Journey
                    </p>
                </div>
                <p class="text-[32px] md:text-[40px]">
                    <b>Welcome Back</b>
                </p>
                <p>
                    sign in to continue your knowledge journey
                </p>
            </div>
        
            <form action="" method="post">
                <div>
                    <label for="">Nama</label>
                    <input type="text" name="nama" placeholder="&#9993; Enter your name" autocomplete="off">
                </div>
                
                <br>

                <div>
                    <label for="">NIM</label>
                    <input type="text" name="NIM" placeholder="&#128274; Enter your NIM" autocomplete="off">
                </div>

                <br>

                <button type="submit" class="w-full py-[12px] bg-[#3e5f44] text-white rounded-[20px]"><b>Sign In</b></button>
                <p class="text-center text-[#4a556a] text-[14px] md:text-[16px]">dont have an account? <a href="register.php" class="text-[#507156]">Create Account</a></p>
            </form>
        </div>
    </div>
</body>
</html>