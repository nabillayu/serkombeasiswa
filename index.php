<?php

include 'functions.php';
$pdo = pdo_connect_mysql();
session_start();

if(isset($_POST['submit'])){

   $username = isset($_POST['username']) ? $_POST['username'] : '';
   $passasli= isset($_POST['password']) ? $_POST['password'] : '';
   $pass = md5($_POST['password']);

   $stmt = $pdo->prepare(" SELECT * FROM akun WHERE username = '$username' and password = '$pass' ");
   //untuk mengambil data dari tabel akun
   $stmt->execute();
   $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $num_row = $pdo->query(" SELECT COUNT(*) FROM akun WHERE username = '$username' and password = '$pass' ")->fetchColumn();

  //mengecek apakah username dan pasword tidak kosong
  if(empty($username) || empty($passasli)){
    //jika kosong maka tampilkan error di page ini
    $error[] = 'Lengkapi form';
      echo '<script>alert("Isi dan lengkapi!")</script>';
  }else{
    //jika ada maka langsung mengecek apakah ada username dan password di database
   if($num_row > 0){
    //jika ada maka mengecek id role yang ada di tabel akun
    if($row['role'] == '1'){
        $_SESSION['user_name'] = $row['username'];
        $_SESSION['id_akun'] = $row['id'];
        $_SESSION['id_role'] = $row['role'];
        header('location:index-mahasiswa.php');
    }elseif($row['role'] == '2'){
        $_SESSION['user_admin'] = $row['username'];
        $_SESSION['id_akun'] = $row['id'];
        $_SESSION['id_role'] = $row['role'];
        header('location:index-admin.php');
    }
   }else{
      $error[] = 'Username atau Password salah!';
      echo '<script>alert("Username atau Password salah!")</script>';
   }
  } 

};

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>Login Beasiswa TELKOM</title>
        <meta content="Admin Dashboard" name="description" />
        <meta content="Mannatthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <link rel="shortcut icon" href="assets/images/favicon.png">

        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="assets/css/style.css" rel="stylesheet" type="text/css">

    </head>


    <body class="fixed-left">

        <!-- Begin page -->
        <div class="accountbg"></div>
        <div class="wrapper-page">

            <div class="card">
                <div class="card-body">

                    <h3 class="text-center mt-0 m-b-15">
                        <a href="index.php" class="logo logo-admin"><img src="assets/images/logo.png" height="200" alt="logo"></a>
                    </h3>
                    <div class="p-3">
                    <p>Login menggunakan akun igracias untuk mengakses pendaftaran Beasiswa</p>
                        <form action="" method="post">

                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="username">USERNAME:</label>
                                    <input class="form-control" type="text" required="" placeholder="Username" name="username" id="username">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12">
                                <label for="password">PASSWORD:</label>
                                    <input class="form-control" type="password" required="" placeholder="Password" name="password" id="password">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-12">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1">
                                        <label class="custom-control-label" for="customCheck1">Remember me</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group text-center row m-t-20">
                                <div class="col-12">
                                    <input class="btn btn-danger btn-block" type="submit" name="submit" value="Log in">
                                </div>
                            </div>
                            <?php
                      if(isset($error)){
                        foreach($error as $error){
                            echo '<span class="error-msg">'.$error.'</span>';
                        };
                      };
                      ?>
                        </form>
                    </div>

                </div>
            </div>
        </div>


        <!-- jQuery  -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/modernizr.min.js"></script>
        <script src="assets/js/detect.js"></script>
        <script src="assets/js/fastclick.js"></script>
        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.blockUI.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>

        <!-- App js -->
        <script src="assets/js/app.js"></script>

    </body>
</html>