<?php
require "session.php";
require "../koneksi.php";

$query = mysqli_query($con, "SELECT a.*, b.nama AS nama_kategori FROM produk a JOIN kategori b ON a.kategori_id=b.id");
$jmlh_prd = mysqli_num_rows($query);

$queryKategori = mysqli_query($con, "SELECT * FROM kategori");

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/fontawesome.min.css">
</head>
<style>
.no-decoration {
    text-decoration: none;
}

from-div {
    margin-bottom: 10px;
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
                    Produk
                </li>
            </ol>
        </nav>

        <div class="my-5 col-12 col-md-6">
            <h3>Tambah Produk</h3>

            <form action="" method="post" enctype="multipart/form-data">
                <div>
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" class="form-control mt-1" autocomplete="off" required>
                </div>
                <div>
                    <label for="kategori">Kategori</label>
                    <select name="kategori" id="kategori" class="form-control mt-1" required>
                        <option value="">Pilih salah satu</option>
                        <?php
                        while($data=mysqli_fetch_array($queryKategori)){
                            ?>
                        <option value="<?= $data['id'];?>"><?=$data['nama'];?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="harga">Harga</label>
                    <input type="number" name="harga" id="harga" class="form-control mt-1" required>
                </div>
                <div>
                    <label for="foto">Foto</label>
                    <input type="file" name="foto" id="foto" class="form-control mt-1">
                </div>
                <div>
                    <label for="detail">Detail</label>
                    <textarea name="detail" id="detail" cols="30" rows="10" class="form-control mt-1"></textarea>
                </div>
                <div>
                    <label for="stok">Stok</label>
                    <select name="stok" id="stok" class="form-control mt-1">
                        <option value="tersedia">Tersedia</option>
                        <option value="habis">Habis</option>
                    </select>
                </div>
                <div class=" mt-3">
                    <button class="btn btn-primary" type="submit" name="simpan">Simpan</button>
                </div>
            </form>

            <?php
            if(isset($_POST['simpan'])){
                $nama = htmlspecialchars($_POST['nama']);
                $kategori = htmlspecialchars($_POST['kategori']);
                $harga = htmlspecialchars($_POST['harga']);
                $detail = htmlspecialchars($_POST['detail']);
                $stok = htmlspecialchars($_POST['stok']);

                $target_dir = "../image/";
                $nama_file = basename($_FILES["foto"]["name"]);
                $target_file = $target_dir . $nama_file;
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                $image_size = $_FILES["foto"]["size"];
                $random_name = generateRandomString(20);
                $new_name = $random_name . "." . $imageFileType;
                
                if($nama=='' || $kategori=='' || $harga==''){
                    ?>
            <div class="alert alert-warning mt-3" role="alert">
                Nama, Kategori dan Harga wajib di isi
            </div>
            <?php
                }else{
                    if($nama_file!=''){
                        if($image_size>500000){
                            ?>
            <div class="alert alert-warning mt-3" role="alert">
                Gambar tidak boleh lebih dari 500 Kb
            </div>
            <?php
                        }else{
                            if($imageFileType!='jpg' && $imageFileType!='png' && $imageFileType!='jpeg'){
                                ?>
            <div class="alert alert-warning mt-3" role="alert">
                Gambar harus png, jpg dan jpeg
            </div>
            <?php
                            }else{
                                move_uploaded_file($_FILES["foto"]["tmp_name"], $target_dir . $new_name);
                            }
                        }
                    }

                    $queryTambah = mysqli_query($con, "INSERT INTO produk (kategori_id, nama, harga, foto, detail, stok) VALUES ('$kategori', '$nama', '$harga', '$new_name', '$detail', '$stok')");
                    if($queryTambah){
                        ?> <div class=" alert alert-primary mt-3" role="alert">
                Produk Berhasil di tambahkan
            </div>
            <meta http-equiv="refresh" content="2; url=produk.php" />
            <?php
                    }else{
                        echo mysqli_error($con);
                    }
                }
            }
            ?>
        </div>

        <div class="mt-3 mb-5">
            <div class="d-flex justify-content-between mt-3">
                <h2>List Produk</h2>
                <button class="btn btn-primary" type="submit" name="tbh_produk">Tambah Produk</button>
            </div>

            <div class="table-responsive mt-4">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if($jmlh_prd==0){
                                ?>
                        <tr>
                            <td colspan="6" class="text-center">Tidak Data Kategori</td>
                        </tr>
                        <?php
                            }else{
                                $jum = 1;
                                while ($data=mysqli_fetch_array($query)) {
                                    ?>
                        <tr>
                            <td><?=$jum;?></td>
                            <td><?= $data['nama'];?></td>
                            <td><?= $data['nama_kategori'];?></td>
                            <td><?= $data['harga'];?></td>
                            <td><?= $data['stok'];?></td>
                            <td>
                                <a href="produk-detail.php?q=<?= $data['id']?>" class="btn btn-info"><i
                                        class="fas fa-search"></i></a>
                            </td>
                        </tr>
                        <?php
                                    $jum++;
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <script src=" ../bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="../fontawesome/js/all.min.js"></script>
</body>

</html>