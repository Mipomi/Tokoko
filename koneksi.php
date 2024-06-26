<?php
    $con = mysqli_connect("localhost", "root", "", "tokotu");
    if(mysqli_connect_errno()){
        echo "gagal terhubung ke database: " . mysqli_connect_error();
        exit();
    }
?>