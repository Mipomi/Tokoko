<?php
    require "session.php";
    require "../koneksi.php";

    $id = $_GET['q'];

    $query = mysqli_query($con, "SELECT a.*, b.nama AS nama_kategori FROM produk a JOIN kategori b ON a.kategori_id=b.id WHERE a.id='$id'");
    $data = mysqli_fetch_array($query);
    
    $queryKategori = mysqli_query($con, "SELECT * FROM kategori WHERE id!='$data[kategori_id]'");

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
    <title>Document</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>
<style>
.no-decoration {
    text-decoration: none;
}

form-div {
    margin-bottom: 10px;
}
</style>

<body>
    <?php require"navbar.php";?>
    <div class="container mt-5">
        <h2>Detail Produk</h2>

        <div class="col-12 col-md-6 mb-5">
            <form action="" method="post" enctype="multipart/form-data">
                <div>
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" value="<?= $data['nama'];?>" class="form-control mt-1"
                        autocomplete="off" required>
                </div>
                <div>
                    <label for="kategori">Kategori</label>
                    <select name="kategori" id="kategori" class="form-control mt-1" required>
                        <option value="<?= $data['nama_kategori'];?>"><?= $data['nama_kategori'];?></option>
                        <?php
                        while($dataktg=mysqli_fetch_array($queryKategori)){
                            ?>
                        <option value=" <?= $dataktg['id'];?>"><?=$dataktg['nama'];?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="harga">Harga</label>
                    <input type="number" name="harga" id="harga" value="<?= $data['harga'];?>" class="form-control mt-1"
                        required>
                </div>
                <div>
                    <label for="currentfoto">Foto produk</label>
                    <img src="../image/<?= $data['foto'];?>" alt="" width="300px">
                </div>
                <div>
                    <label for="foto">Foto</label>
                    <input type="file" name="foto" id="foto" class="form-control mt-1">
                </div>
                <div>
                    <label for="detail">Detail</label>
                    <textarea name="detail" id="detail" cols="30" rows="10" class="form-control mt-1">
                        <?=$data['detail'];?>
                    </textarea>
                </div>
                <div>
                    <label for="stok">Stok</label>
                    <select name="stok" id="stok" class="form-control mt-1">
                        <option value="<?=$data['stok'];?>"><?=$data['stok'];?></option>
                        <?php
                            if($data['stok']=='tersedia'){
                                ?>
                        <option value="habis">Habis</option>
                        <?php
                            }else{
                        ?>
                        <option value="tersedia">Tersedia</option>
                        <?php
                            } ?>
                    </select>
                </div>
                <div class="d-flex justify-content-between mt-3">
                    <button class="btn btn-primary" type="submit" name="edit">Edit</button>
                    <button class="btn btn-danger" type="submit" name="hapus">Hapus</button>
                </div>
            </form>
            <?php
            if(isset($_POST['edit'])){
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
                    $query = mysqli_query($con, "UPDATE produk SET kategori_id='$kategori', nama='$nama', harga='$harga', detail='$detail', stok='$stok' WHERE id='$id'");
                    
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
                                
                                $queryUpdate = mysqli_query($con, "UPDATE produk SET foto='$new_name' WHERE id='$id'");
                                if($queryUpdate){
                                    ?>
            <div class=" alert alert-primary mt-3" role="alert">
                Produk Berhasil di Update
            </div>
            <meta http-equiv="refresh" content="2; url=produk.php" />
            <?php
                                }else{
                                    echo mysqli_error($con);
                                }
                            }
                        }
                    }
                }
            }

            if(isset($_POST['hapus'])){
                $queryHapus = mysqli_query($con, "DELETE FROM produk WHERE id='$id'");
                
                if($queryHapus){
                    ?>
            <div class=" alert alert-primary mt-3" role="alert">
                Produk Berhasil di Hapus
            </div>
            <meta http-equiv="refresh" content="2; url=produk.php" />
            <?php
                }else{
                    echo mysqli_error($con);
                }
            }
            ?>
        </div>
    </div>
    <script src=" ../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>