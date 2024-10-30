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
$num_row2 = $pdo->query(" SELECT COUNT(*) FROM pengajuan WHERE id_mhs = '$id_akun' and (approved = 'new' or approved = 'yes')")->fetchColumn();

?>
<?=template_header("Registrasi","mahasiswa",$id_role,$photos)?>
            <div class="page-content-wrapper ">

                        <div class="container-fluid">

                            <div class="row">
                                    <div class="col-sm-12">
                                        <div class="page-title-box">
                                            <div class="btn-group float-right">
                                                <ol class="breadcrumb hide-phone p-0 m-0">
                                                    <li class="breadcrumb-item"><a href="index-mahasiswa.php">Beasiswa TEL-U</a></li>
                                                    <li class="breadcrumb-item active">Registrasi</li>
                                                </ol>
                                            </div>
                                            <h4 class="page-title">Daftar Beasiswa</h4>
                                        </div>
                                    </div>
                                </div>
                                <!-- end page title end breadcrumb -->
                                <div class="row">
                                <div class="col-lg-12">
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                        <?php if($num_row2 >= 2){?>
                                            <h4 class="mt-0 header-title">Registrasi Beasiswa - <span style="color: red;">Kuota beasiswa sudah habis!</span></h4>
                                            <p class="text-muted m-b-30 font-14">Anda tidak bisa mendaftar beasiswa TEL-U. Silahkan tunggu status beasiswa yang sudah didaftarkan sebelumnya!</p>
                                            <?php }else{?>
                                                <h4 class="mt-0 header-title">Registrasi Beasiswa</h4>
                                                <p class="text-muted m-b-30 font-14">Silahkan masukkan informasi data diri anda untuk mendaftar beasiswa TEL-U.</p>
                                                <?php }?>
                                            
                                            <form id="form_parsley" action="" enctype="multipart/form-data" method="post">
                                            <?php if($num_row2 >= 2){?>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Nama</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="nama" value="<?=$row['nama']?>" class="form-control" disabled placeholder="Masukkan nama anda..."/>
                                                    </div>
                                                </div>
            
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Email</label>
                                                    <div class="col-sm-10">
                                                        <input type="email" name="email" value="<?=$row['email']?>" class="form-control" disabled
                                                                parsley-type="email" placeholder="Masukkan email anda..."/>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-sm-10">
                                                                <i class="mdi mdi-alert-circle-outline"></i><span>Nama dan email dapat diganti di halaman profile.</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Nomor HP</label>
                                                    <div class="col-sm-10">
                                                        <input data-parsley-type="number" name="hp" type="text"
                                                                class="form-control" disabled
                                                                placeholder="Masukkan Nomor Telepon"/>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Semester saat ini</label>
                                                <div class="col-sm-10">
                                                    <select class="form-control" disabled id="semester" name="semester">
                                                        <option value="">Pilih</option>
                                                        <?php
                                                            for ($semester = 1; $semester <= 8; $semester++) {
                                                                $randomIpk = number_format(rand(350, 400) / 100, 2); // Generate IPK antara 3.50 - 4.00
                                                                echo "<option value=\"$semester\" data-ipk=\"$randomIpk\">$semester</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">IPK terakhir</label>
                                                <div class="col-sm-10">
                                                    <input  type="text" class="form-control" id="ipk" disabled name="ipk"/>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Pilih beasiswa</label>
                                                <div class="col-sm-10">
                                                    <select class="form-control" disabled name="pilihan">
                                                        <option value="">Pilih</option>
                                                        <option value="akademik">Akademik</option>
                                                        <option value="non-akademik">Non-Akademik</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Upload Berkas Syarat</label>
                                                <div class="col-sm-10">
                                                        <input type="file" name="file" id="input-file-now" class="dropify" accept=".jpg, .jpeg, .png, .pdf, .zip, .rar" disabled />
                                                </div>
                                            </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label" style="color: red;">Kuota anda sudah habis!</label>
                                                    <div class="col-sm-10">
                                                            <input type="submit" name="submit" value="Daftar" class="btn btn-primary" disabled>
                                                        <a href="index-mahasiswa.php" class="btn btn-secondary waves-effect m-l-5">
                                                            Batal
                                                        </a>
                                                    </div>
                                                </div>
                                                <?php }else{?>
                                                    <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Nama</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="nama" value="<?=$row['nama']?>" class="form-control" readonly required placeholder="Masukkan nama anda..."/>
                                                        <div style="color: #007bff; background-color: #e0f3ff;"><i class="mdi mdi-alert-circle-outline mr-1"></i><span>Nama dapat diganti di halaman profile.</span></div>
                                                    </div>
                                                </div>
            
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Email</label>
                                                    <div class="col-sm-10">
                                                        <input type="email" name="email" value="<?=$row['email']?>" class="form-control" readonly required
                                                                parsley-type="email" placeholder="Masukkan email anda..."/>
                                                                <div style="color: #007bff; background-color: #e0f3ff;"><i class="mdi mdi-alert-circle-outline mr-1"></i><span>Email dapat diganti di halaman profile.</span></div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Nomor HP</label>
                                                    <div class="col-sm-10">
                                                        <input data-parsley-type="number" name="hp" type="text"
                                                                class="form-control" required
                                                                placeholder="Masukkan Nomor Telepon"/>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Semester saat ini</label>
                                                <div class="col-sm-10">
                                                    <select class="form-control" required id="semester" name="semester">
                                                        <option value="">Pilih</option>
                                                        <?php
                                                            for ($semester = 1; $semester <= 8; $semester++) {
                                                                $randomIpk = number_format(rand(350, 400) / 100, 2); // Generate IPK antara 3.50 - 4.00
                                                                echo "<option value=\"$semester\" data-ipk=\"$randomIpk\">$semester</option>";
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">IPK terakhir</label>
                                                <div class="col-sm-10">
                                                    <input  type="text" class="form-control" id="ipk" readonly name="ipk"/>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Pilih beasiswa</label>
                                                <div class="col-sm-10">
                                                    <select class="form-control" required name="pilihan">
                                                        <option value="">Pilih</option>
                                                        <option value="akademik">Akademik</option>
                                                        <option value="non-akademik">Non-Akademik</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Upload Berkas Syarat</label>
                                                <div class="col-sm-10">
                                                        <input type="file" name="file" id="input-file-now" class="dropify" accept=".jpg, .jpeg, .png, .pdf, .zip, .rar" required />
                                                </div>
                                            </div>
                                                <div class="form-group row">
                                                            <label class="col-sm-2 col-form-label"></label>
                                                    <div class="col-sm-10">
                                                        <input type="submit" name="submit" value="Daftar" class="btn btn-primary">
                                                        <a href="index-mahasiswa.php" class="btn btn-secondary waves-effect m-l-5">
                                                            Batal
                                                        </a>
                                                    </div>
                                                </div>
                                                <?php }?>
                                            </form>
            
                                        </div>
                                    </div>
                                </div> <!-- end col -->
                                </div>
                        </div><!-- container -->

                    </div> <!-- Page content Wrapper -->

                    <button type="button" class="btn btn-primary waves-effect waves-light" style="display: none;" id="sa-success">Click me</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" style="display: none;" id="sa-error">Click me</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" style="display: none;" id="sa-error2r">Click me</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" style="display: none;" id="sa-error3r">Click me</button>


<?php
//coding guidelines
if(isset($_POST['submit'])){
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $hp = isset($_POST['hp']) ? $_POST['hp'] : '';
    $semester = isset($_POST['semester']) ? $_POST['semester'] : '';
    $ipk = isset($_POST['ipk']) ? $_POST['ipk'] : '';
    $pilihan = isset($_POST['pilihan']) ? $_POST['pilihan'] : '';

    $file = $_FILES["file"]["name"];
    $newFileName = $id_akun."_".$nama."_".date("d-m-Y_H-i-s")."_".$file;
    $targetDir = "berkas/"; 
    $targetFile = $targetDir . $newFileName;

    $id_spesial = md5($id_akun."_".$nama."_".date("d-m-Y_H-i-s"));

    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));

    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "pdf"
    && $imageFileType != "zip" && $imageFileType != "rar" ) {
        $uploadOk = 0;
    }

    $num_row2 = $pdo->query(" SELECT COUNT(*) FROM pengajuan WHERE id_mhs = '$id_akun' and (approved = 'new' or approved = 'yes')")->fetchColumn();
    if($num_row2 >= 2){
        echo "
        <script>
        $(document).ready(function() {
            $('#sa-error3r').click();
        });
    </script>";
    }else{
        //jika file yang dikirim salah maka file tidak di upload dan menampilkan pesan error
        if($uploadOk == 0){
            echo "
            <script>
            $(document).ready(function() {
                $('#sa-error').click();
            });
        </script>";

        //jika file yang dikirim benar maka file akan di proses
        }else{
        $stmt1 = $pdo->prepare("INSERT INTO pengajuan(id_spesial,id_mhs,nama,email,tel,semester,ipk,pilihan,berkas,approved) VALUES ('$id_spesial','$id_akun','$nama','$email','$hp',$semester,'$ipk','$pilihan','$newFileName','new')");
        //untuk memindahkan file yang diterima oleh server (dengan method post) ke dalam file server
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile) && $stmt1->execute()){
            echo "
            <script>
            $(document).ready(function() {
                $('#sa-success').click();
            });
        </script>";
        }else{
            echo "
            <script>
            $(document).ready(function() {
                $('#sa-error2r').click();
            });
        </script>";
        }
        }
    }
}
?>

                    <script>

        document.getElementById('semester').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const ipk = selectedOption.getAttribute('data-ipk');
            document.getElementById('ipk').value = ipk ? ipk : '';
        });
</script>
<?=template_footer()?>