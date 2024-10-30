<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
session_start();

if(!isset($_SESSION['user_name'])){
   header('location:index.php');
}

$user = $_SESSION['user_name'];
$id_akun = $_SESSION['id_akun'];
$id_role = $_SESSION['id_role'];

$stmt = $pdo->prepare("SELECT * FROM akun WHERE username = '$user' ");
   $stmt->execute();
   // Fetch the records so we can display them in our template.
   $row = $stmt->fetch(PDO::FETCH_ASSOC);
$photos = $row['foto'];

?>
<?=template_header("Profile","mahasiswa",$id_role,$photos)?>
            <div class="page-content-wrapper ">
                        <div class="container-fluid">

                            <div class="row">
                                    <div class="col-sm-12">
                                        <div class="page-title-box">
                                            <div class="btn-group float-right">
                                                <ol class="breadcrumb hide-phone p-0 m-0">
                                                    <li class="breadcrumb-item"><a href="index-mahasiswa.php">Beasiswa TEL-U</a></li>
                                                    <li class="breadcrumb-item active">Profile</li>
                                                </ol>
                                            </div>
                                            <h4 class="page-title">My Profile</h4>
                                        </div>
                                    </div>
                                </div>
                                <!-- end page title end breadcrumb -->
                                <div class="row">
                                            
                                            <div class="col-xl-12">
                                                <div class="card m-b-30">
                                                    <div class="card-body">
                                            <form action="" method="post" enctype="multipart/form-data">
                                                        <h4 class="mt-0 header-title">Foto Profile</h4>
                                                        <p class="text-muted m-b-30 font-14">Silahkan edit bila perlu</p>
                                                        <input type="file" name="file" id="input-file-now-custom-1" class="dropify" accept=".jpg, .jpeg, .png" data-default-file="assets/images/users/<?=$row['foto']?>" />
                                            <br>
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                <input type="submit" name="editFoto" class="btn btn-outline-info" value="Edit">
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->

                                <div class="row">
                                <div class="col-12">
                                    <div class="card m-b-30">
                                        <div class="card-body">
            
                                            <h4 class="mt-0 header-title">Data Profile</h4>
                                            <p class="text-muted m-b-30 font-14">Silahkan edit jika perlu</p>
                                            <form action="" method="post">
                                            <div class="form-group row">
                                                <label for="example-text-input" class="col-sm-2 col-form-label">Nama</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control" type="text" value="<?=$row['nama']?>" id="example-text-input" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="example-text-input" class="col-sm-2 col-form-label">NIM</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control" type="text" value="<?=$row['nim']?>" id="example-text-input" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="example-text-input" class="col-sm-2 col-form-label">Email</label>
                                                <div class="col-sm-10">
                                                <input type="email" name="email" class="form-control" value="<?=$row['email']?>" required
                                                                parsley-type="email" placeholder="Masukkan email anda..."/>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                            <label for="example-text-input" class="col-sm-2 col-form-label"></label>
                                                <div class="col-sm-10">
                                                <input type="submit" name="submit" class="btn btn-outline-info" value="Edit">
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->

                        </div><!-- container -->

                    </div> <!-- Page content Wrapper -->
                    <button type="button" class="btn btn-primary waves-effect waves-light" style="display: none;" id="sa-success2">Click me</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" style="display: none;" id="sa-success3">Click me</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" style="display: none;" id="sa-error2">Click me</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" style="display: none;" id="sa-error3">Click me</button>

<?php 
if(isset($_POST['submit'])){
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $nim = isset($_POST['nim']) ? $_POST['nim'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';

        $stmt1 = $pdo->prepare("UPDATE akun set nama = '$nama' ,email = '$email',nim = '$nim' where id = '$id_akun'");
        if($stmt1->execute()){
            echo "
            <script>
            $(document).ready(function() {
                $('#sa-success2').click();
            });
        </script>";

        }else{
            echo "
            <script>
            $(document).ready(function() {
                $('#sa-error2').click();
            });
        </script>";
        }
}

if(isset($_POST['editFoto'])){
    $file = $_FILES["file"]["name"];
    $newFileName = $id_akun."_".$user."_".$file;
    $targetDir = "assets/images/users/"; 
    $targetFile = $targetDir . $newFileName;

    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));

    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        $uploadOk = 0;
    }

        $stmt1 = $pdo->prepare("UPDATE akun set foto = '$newFileName' where id = '$id_akun'");
        if($uploadOk == 0){
            echo "
            <script>
            $(document).ready(function() {
                $('#sa-error3').click();
            });
        </script>";
        }else{
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile) && $stmt1->execute()){
            echo "
            <script>
            $(document).ready(function() {
                $('#sa-success3').click();
            });
        </script>";
        }else{
            echo "
            <script>
            $(document).ready(function() {
                $('#sa-error2').click();
            });
        </script>";
        }
    }
}
?>

<?=template_footer()?>