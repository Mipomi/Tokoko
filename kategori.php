<?php
require "session.php";
require "../koneksi.php";

$queryKategori = mysqli_query($con, "SELECT * FROM kategori");
$jmlh_ktg = mysqli_num_rows($queryKategori);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/fontawesome.min.css">
</head>
<style>
.no-decoration {
    text-decoration: none;
}
</style>

<body>
    <?php require"navbar.php";?>
    <div class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="../Admin" class="no-decoration text-muted">
                        <i class="fas fa-home"></i>Home
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Kategori
                </li>
            </ol>
        </nav>
        <div class="my-5 col-12 col-md-6">
            <h3>Tambah Kategori</h3>

            <form action="" method="post">
                <div>
                    <label for="kategori">Kategori</label>
                    <input type="text" id="kategori" name="kategori" class="form-control mt-1"
                        placeholder="Masukan kategori" autocomplete="off">
                </div>
                <div class="mt-3">
                    <button class="btn btn-primary" type="submit" name="simpanKategori">Simpan</button>
                </div>
            </form>
            <?php
            if(isset($_POST['simpanKategori'])){
                $kategori = htmlspecialchars($_POST['kategori']);

                $queryExist = mysqli_query($con, "SELECT nama FROM kategori WHERE nama='$kategori'");
                $jmlh_data_ktg = mysqli_num_rows($queryExist);

                if($jmlh_data_ktg>0){
                    ?>
            <div class="alert alert-warning mt-3" role="alert">
                Kategori Sudah Ada
            </div>
            <meta http-equiv="refresh" content="2; url=kategori.php" />
            <?php
                }else{
                    $querySimpan = mysqli_query($con, "INSERT INTO kategori (nama) VALUES ('$kategori')");
                    if($querySimpan){
                        ?> <div class=" alert alert-primary mt-3" role="alert">
                Berhasil di tambahkan
            </div>
            <?php
                    }else{
                        echo mysqli_error($con);
                    }
                    
                }
            }
            ?>
        </div>

        <div class="mt-3">
            <h2>List Kategori</h2>
            <div class="table-responsive mt-4">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($jmlh_ktg==0){
                            ?>
                        <tr>
                            <td colspan="3" class="text-center">Tidak Data Kategori</td>
                        </tr>
                        <?php
                        }else{
                            $no = 1;
                            while ($data=mysqli_fetch_array($queryKategori)) {
                        ?>
                        <tr>
                            <td><?=$no;?></td>
                            <td><?= $data['nama'];?></td>
                            <td>
                                <a href="kategori-detail.php?q=<?= $data['id']?>" class="btn btn-info"><i
                                        class="fas fa-search"></i></a>
                            </td>
                        </tr>
                        <?php
                            $no++;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src=" ../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../fontawesome/js/all.min.js"></script>
</body>

</html>