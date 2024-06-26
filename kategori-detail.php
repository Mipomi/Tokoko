<?php
    require "session.php";
    require "../koneksi.php";

    $id = $_GET['q'];

    $query = mysqli_query($con, "SELECT * FROM kategori WHERE id='$id'");
    $data = mysqli_fetch_array($query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
</head>

<body>
    <?php require"navbar.php";?>
    <div class="container mt-5">
        <h2>Detail Kategori</h2>

        <div class="col-12 col-md-6">
            <form action="" method="post">
                <div>
                    <label for="kategori">Kategori</label>
                    <input type="text" id="kategori" name="kategori" class="form-control mt-1" autocomplete="off"
                        value="<?= $data['nama'];?>">
                </div>
                <div class="mt-3 d-flex justify-content-between">
                    <button class="btn btn-primary" type="submit" name="editBtn">Edit</button>
                    <button class="btn btn-danger" type="submit" name="deleteBtn">Delete</button>
                </div>
            </form>
            <?php
            if(isset($_POST['editBtn'])){
                $kategori = htmlspecialchars($_POST['kategori']);

                if($data['nama']==$kategori){
                    ?>
            <meta http-equiv="refresh" content="0; url=kategori.php" />
            <?php
                }else{
                    $query = mysqli_query($con, "SELECT nama FROM kategori WHERE nama='$kategori'");
                    $jmlh_data = mysqli_num_rows($query);
                    
                    if($jmlh_data>0){
                        ?>
            <div class="alert alert-warning mt-3" role="alert">
                Kategori Sudah Ada
            </div>

            <?php
                    }else{
                        $querySimpan = mysqli_query($con, "UPDATE kategori SET nama='$kategori' WHERE id='$id'");
                        if($querySimpan){
                            ?> <div class=" alert alert-primary mt-3" role="alert">
                Berhasil di update
            </div>

            <meta http-equiv="refresh" content="2; url=kategori.php" />
            <?php
                        }else{
                            echo mysqli_error($con);
                        }
                        
                    }
                }
            }
            
            if(isset($_POST['deleteBtn'])){
                $queryCheck = mysqli_query($con, "SELECT * FROM produk WHERE kategori_id='$id'");
                $dataCount = mysqli_num_rows($queryCheck);

                if($dataCount>0){
                    ?> <div class=" alert alert-warning mt-3" role="alert">
                kategori tidak bisa di hapus, masih terdapat produk
            </div>
            <?php
            die();
            }

            $queryDelete = mysqli_query($con, "DELETE FROM kategori WHERE id='$id'");
            if($queryDelete){
            ?> <div class=" alert alert-primary mt-3" role="alert">
                Berhasil di Hapus
            </div>

            <meta http-equiv="refresh" content="2; url=kategori.php" />
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