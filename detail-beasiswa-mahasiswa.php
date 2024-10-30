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

$id = $_GET['id'];

$num_row2 = $pdo->query(" SELECT COUNT(*) FROM pengajuan WHERE id_mhs = '$id_akun' and id_spesial = '$id'")->fetchColumn();

if($num_row2 == 0){
    echo "<script type='text/javascript'> window.location.href='page-404.php'</script>";
}

$stmt = $pdo->prepare("SELECT * FROM akun WHERE username = '$user' ");
   $stmt->execute();
   // Fetch the records so we can display them in our template.
   $row = $stmt->fetch(PDO::FETCH_ASSOC);

   $stmt2 = $pdo->prepare("SELECT * FROM pengajuan WHERE id_mhs = '$id_akun' and id_spesial = '$id' ");
   $stmt2->execute();
   // Fetch the records so we can display them in our template.
   $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

$photos = $row['foto'];
$status = "";
if($row2['approved'] == 'new'){
    $status = "Belum diverifikasi.";
}elseif($row2['approved'] == 'no'){
    $status = "Ditolak.";
}elseif($row2['approved'] == 'yes'){
    $status = "Diterima.";
}
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
                                                    <li class="breadcrumb-item active">Detail Data Registrasi</li>
                                                </ol>
                                            </div>
                                            <h4 class="page-title">Detail Data Beasiswa</h4>
                                        </div>
                                    </div>
                                </div>
                                <!-- end page title end breadcrumb -->
                                <div class="row">
                                <div class="col-lg-12">
                                    <div class="card m-b-30">
                                        <div class="card-body">
            
                                            <h4 class="mt-0 header-title">Registrasi Beasiswa</h4>
                                            <p class="text-muted m-b-30 font-14">Silahkan ubah informasi data diri anda untuk mendaftar beasiswa TEL-U (bila perlu).</p>
            
                                            <form id="form_parsley" action="" enctype="multipart/form-data" method="post">
                                                <?php if($row2['approved'] == 'no' or $row2['approved'] == 'yes'){?>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Nama</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="nama" value="<?=$row2['nama']?>" class="form-control" placeholder="Masukkan nama anda..." disabled/>
                                                        <input type="text" name="id_spesial" value="<?=$row2['id_spesial']?>" hidden />
                                                    </div>
                                                </div>
            
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Email</label>
                                                    <div class="col-sm-10">
                                                        <input type="email" name="email" value="<?=$row2['email']?>" class="form-control" disabled
                                                                parsley-type="email" placeholder="Masukkan email anda..."/>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Nomor HP</label>
                                                    <div class="col-sm-10">
                                                        <input data-parsley-type="number" value="<?=$row2['tel']?>" name="hp" type="text"
                                                                class="form-control" disabled
                                                                placeholder="Masukkan Nomor Telepon"/>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Semester saat ini</label>
                                                <div class="col-sm-10">
                                                <select class="form-control" disabled id="semester" name="semester">
                                                    <option value="">Pilih</option>
                                                    <option value="1" <?php echo ($row2['semester'] == 1) ? 'selected' : ''; ?> data-ipk="3.98">1</option>
                                                    <option value="2" <?php echo ($row2['semester'] == 2) ? 'selected' : ''; ?> data-ipk="3.90">2</option>
                                                    <option value="3" <?php echo ($row2['semester'] == 3) ? 'selected' : ''; ?> data-ipk="3.89">3</option>
                                                    <option value="4" <?php echo ($row2['semester'] == 4) ? 'selected' : ''; ?> data-ipk="3.67">4</option>
                                                    <option value="5" <?php echo ($row2['semester'] == 5) ? 'selected' : ''; ?> data-ipk="3.95">5</option>
                                                    <option value="6" <?php echo ($row2['semester'] == 6) ? 'selected' : ''; ?> data-ipk="3.88">6</option>
                                                    <option value="7" <?php echo ($row2['semester'] == 7) ? 'selected' : ''; ?> data-ipk="3.72">7</option>
                                                    <option value="8" <?php echo ($row2['semester'] == 8) ? 'selected' : ''; ?> data-ipk="3.50">8</option>
                                                </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">IPK terakhir</label>
                                                <div class="col-sm-10">
                                                    <input  type="text" value="<?=$row2['ipk']?>" class="form-control" id="ipk" disabled name="ipk"/>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Pilih beasiswa</label>
                                                <div class="col-sm-10">
                                                    <select class="form-control" disabled name="pilihan">
                                                        <option value="">Pilih</option>
                                                        <option value="akademik" <?php echo ($row2['pilihan'] == 'akademik') ? 'selected' : ''; ?> >Akademik</option>
                                                        <option value="non-akademik" <?php echo ($row2['pilihan'] == 'non-akademik') ? 'selected' : ''; ?> >Non-Akademik</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Upload Berkas Syarat</label>
                                                <div class="col-sm-10">
                                                        <input type="file" name="file" class="dropify" accept=".jpg, .jpeg, .png, .pdf, .zip, .rar" data-default-file="berkas/<?=$row2['berkas']?>" disabled />
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Status</label>
                                                <div class="col-sm-10">
                                                    <input  type="text" value="<?=$status?>" class="form-control" disabled/>
                                                </div>
                                            </div>
                                            <?php if($row2['approved'] == 'no'){ ?>
                                                <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Alasan ditolak</label>
                                                <div class="col-sm-10">
                                                    <input  type="text" value="<?=$row2['alasan']?>" class="form-control" disabled/>
                                                </div>
                                            </div>
                                            <?php } ?>
                                                <div class="form-group row">
                                                <label class="col-sm-2 col-form-label"></label>
                                                    <div class="col-sm-10">
                                                        <a href="index-mahasiswa.php" class="btn btn-secondary waves-effect m-l-5">
                                                            Kembali
                                                        </a>
                                                    </div>
                                                </div>
                                                <?php }else{?>
                                                    <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Nama</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="nama" value="<?=$row2['nama']?>" class="form-control" required placeholder="Masukkan nama anda..." required />
                                                        <input type="text" name="id_spesial" value="<?=$row2['id_spesial']?>" hidden />
                                                    </div>
                                                </div>
            
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Email</label>
                                                    <div class="col-sm-10">
                                                        <input type="email" name="email" value="<?=$row2['email']?>" class="form-control" required
                                                                parsley-type="email" placeholder="Masukkan email anda..."/>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Nomor HP</label>
                                                    <div class="col-sm-10">
                                                        <input data-parsley-type="number" value="<?=$row2['tel']?>" name="hp" type="text"
                                                                class="form-control" required
                                                                placeholder="Masukkan Nomor Telepon"/>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Semester saat ini</label>
                                                <div class="col-sm-10">
                                                <select class="form-control" required id="semester" name="semester">
                                                    <option value="">Pilih</option>
                                                    <option value="1" <?php echo ($row2['semester'] == 1) ? 'selected' : ''; ?> data-ipk="3.98">1</option>
                                                    <option value="2" <?php echo ($row2['semester'] == 2) ? 'selected' : ''; ?> data-ipk="3.90">2</option>
                                                    <option value="3" <?php echo ($row2['semester'] == 3) ? 'selected' : ''; ?> data-ipk="3.89">3</option>
                                                    <option value="4" <?php echo ($row2['semester'] == 4) ? 'selected' : ''; ?> data-ipk="3.67">4</option>
                                                    <option value="5" <?php echo ($row2['semester'] == 5) ? 'selected' : ''; ?> data-ipk="3.95">5</option>
                                                    <option value="6" <?php echo ($row2['semester'] == 6) ? 'selected' : ''; ?> data-ipk="3.88">6</option>
                                                    <option value="7" <?php echo ($row2['semester'] == 7) ? 'selected' : ''; ?> data-ipk="3.72">7</option>
                                                    <option value="8" <?php echo ($row2['semester'] == 8) ? 'selected' : ''; ?> data-ipk="3.50">8</option>
                                                </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">IPK terakhir</label>
                                                <div class="col-sm-10">
                                                    <input  type="text" value="<?=$row2['ipk']?>" class="form-control" id="ipk" readonly name="ipk"/>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Pilih beasiswa</label>
                                                <div class="col-sm-10">
                                                    <select class="form-control" required name="pilihan">
                                                        <option value="">Pilih</option>
                                                        <option value="akademik" <?php echo ($row2['pilihan'] == 'akademik') ? 'selected' : ''; ?> >Akademik</option>
                                                        <option value="non-akademik" <?php echo ($row2['pilihan'] == 'non-akademik') ? 'selected' : ''; ?> >Non-Akademik</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Upload Berkas Syarat</label>
                                                <div class="col-sm-10">
                                                        <input type="file" name="file" class="dropify" accept=".jpg, .jpeg, .png, .pdf, .zip, .rar" data-default-file="berkas/<?=$row2['berkas']?>" />
                                                </div>
                                            </div>
                                                <div class="form-group row">
                                                <label class="col-sm-2 col-form-label"></label>
                                                    <div class="col-sm-10">
                                                        <input type="submit" name="submit" value="Ubah" class="btn btn-primary">
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

                    <button type="button" class="btn btn-primary waves-effect waves-light" style="display: none;" id="sa-success4">Click me</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" style="display: none;" id="sa-error4">Click me</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" style="display: none;" id="sa-error4e">Click me</button>

<?php
if(isset($_POST['submit'])){
    $id_spesial = isset($_POST['id_spesial']) ? $_POST['id_spesial'] : '';
    $nama = isset($_POST['nama']) ? $_POST['nama'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $hp = isset($_POST['hp']) ? $_POST['hp'] : '';
    $semester = isset($_POST['semester']) ? $_POST['semester'] : '';
    $ipk = isset($_POST['ipk']) ? $_POST['ipk'] : '';
    $pilihan = isset($_POST['pilihan']) ? $_POST['pilihan'] : '';

    $file = $_FILES["file"]["name"];

    if($file == ''){
        $stmt1 = $pdo->prepare("UPDATE pengajuan set nama = '$nama',email = '$email',tel = '$hp',semester = '$semester' ,ipk = '$ipk' ,pilihan = '$pilihan' where id_spesial = '$id_spesial'");
        if($stmt1->execute()){
            echo "
            <script>
            $(document).ready(function() {
                $('#sa-success4').click();
            });
        </script>";
        }else{
            echo "
            <script>
            $(document).ready(function() {
                $('#sa-error4e').click();
            });
        </script>";
        }
    }else{
    $newFileName = $id_akun."_".$nama."_".date("d-m-Y_H-i-s")."_".$file;
    $targetDir = "berkas/"; 
    $targetFile = $targetDir . $newFileName;

    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));

    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "pdf"
    && $imageFileType != "zip" && $imageFileType != "rar" ) {
        $uploadOk = 0;
    }

        if($uploadOk == 0){
            echo "
            <script>
            $(document).ready(function() {
                $('#sa-error4').click();
            });
        </script>";
        }else{
        $stmt1 = $pdo->prepare("UPDATE pengajuan set nama = '$nama',email = '$email',tel = '$hp',semester = '$semester' ,ipk = '$ipk' ,pilihan = '$pilihan',berkas = '$newFileName' where id_spesial = '$id_spesial'");
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile) && $stmt1->execute()){
            echo "
            <script>
            $(document).ready(function() {
                $('#sa-success4').click();
            });
        </script>";
        }else{
            echo "
            <script>
            $(document).ready(function() {
                $('#sa-error4e').click();
            });
        </script>";
        }
        }
    }
}
?>

                    <script>
        $('#sa-success4').click(function () {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data anda berhasil diubah.',
                icon: 'success',
                confirmButtonClass: 'btn btn-success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'detail-beasiswa-mahasiswa.php?id=<?=$id?>';
                }
            });
        });

        $('#sa-error4').click(function () {
            Swal.fire({
                title: 'Gagal!',
                text: 'File bukan bertipe gambar!',
                icon: 'error',
                confirmButtonClass: 'btn btn-danger',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'detail-beasiswa-mahasiswa.php?id=<?=$id?>';
                }
            });    
        });

        $('#sa-error4e').click(function () {
            Swal.fire({
                title: 'Gagal!',
                text: 'Terjadi kesalahan, silahkan coba lagi!',
                icon: 'error',
                confirmButtonClass: 'btn btn-danger',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'detail-beasiswa-mahasiswa.php?id=<?=$id?>';
                }
            });    
        });

        document.getElementById('semester').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const ipk = selectedOption.getAttribute('data-ipk');
            document.getElementById('ipk').value = ipk ? ipk : '';
        });
</script>
<?=template_footer()?>