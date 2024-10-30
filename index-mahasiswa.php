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
$num_row2 = $pdo->query(" SELECT COUNT(*) FROM pengajuan WHERE id_mhs = '$id_akun' ")->fetchColumn();

$stmt2 = $pdo->prepare("SELECT * FROM pengajuan WHERE id_mhs = '$id_akun' ");
   $stmt2->execute();
   // Fetch the records so we can display them in our template.
   $rows2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>
<?=template_header("Dashboard","mahasiswa",$id_role,$photos)?>
            <div class="page-content-wrapper ">

                        <div class="container-fluid">

                            <div class="row">
                                    <div class="col-sm-12">
                                        <div class="page-title-box">
                                            <div class="btn-group float-right">
                                                <ol class="breadcrumb hide-phone p-0 m-0">
                                                    <li class="breadcrumb-item"><a href="index-mahasiswa.php">Beasiswa TEL-U</a></li>
                                                    <li class="breadcrumb-item active">Dashboard</li>
                                                </ol>
                                            </div>
                                            <h4 class="page-title">Info Penerimaan Beasiswa TEL-U</h4>
                                        </div>
                                    </div>
                                </div>
                                <!-- end page title end breadcrumb -->
                                <div class="col-lg-12">
                                        <div class="card m-b-30">
                                            <div class="card-body">
                                            <h4 class="mt-0 header-title">Halo <?=$row['nama']?></h4>
                                                <p class="text-muted m-b-30 font-14">NIM <?=$row['nim']?>.</p>
                                                <?php if($num_row2 == 0){ ?>
                
                                                <div class="">
                                                    <div class="alert alert-info" role="alert">
                                                        <h4 class="alert-heading font-18">Tidak ditemukan pengajuan beasiswa!</h4>
                                                        <p>Silahkan daftarkan diri anda untuk mendapatkan beasiswa TEL-U</p>
                                                        <p class="mb-0">klik <a style="color: blue;" href="registrasi-mahasiswa.php">disini</a> untuk daftar atau klik menu 'Registrasi' yang ada kiri halaman.</p>
                                                    </div>
                                                </div>

                                                
                                                <?php }else{
                                                    foreach($rows2 as $row2){
                                                     if($row2['approved'] == 'yes') {?>
                                                <div class="">
                                                    <div class="alert alert-success" role="alert">
                                                        <h4 class="alert-heading font-18">Diterima! - <span style="color:blue">beasiswa <?=$row2['pilihan']?></h4>
                                                        <p>Pengajuan beasiswa <?=$row2['pilihan']?> anda diterima!</p>
                                                        <p class="mb-0">Untuk selanjutnya silahkan ....</p>
                                                    </div>
                                                </div>
                                                <?php }elseif($row2['approved'] == 'no'){ ?>
                                                <div class="">
                                                    <div class="alert alert-danger" role="alert">
                                                    <h4 class="alert-heading font-18">Ditolak! - <span style="color:blue">beasiswa <?=$row2['pilihan']?></h4>
                                                        <p>Mohon maaf pengajuan beasiswa <?=$row2['pilihan']?> anda ditolak dengan alasan :</p>
                                                        <p style="color:blue" class="mb-0"><?=$row2['alasan']?></p><br>
                                                        <a class="btn btn-primary" href="detail-beasiswa-mahasiswa.php?id=<?=$row2['id_spesial']?>"><p class="mb-0">Lihat data anda</p></a>
                                                    </div>
                                                </div>
                                                <?php }elseif($row2['approved'] == 'new'){?>
                                                <div class="">
                                                    <div class="alert alert-info" role="alert">
                                                    <h4 class="alert-heading font-18">Belum di verifikasi! - <span style="color:blue">beasiswa <?=$row2['pilihan']?></span></h4>
                                                        <p>Beasiswa <?=$row2['pilihan']?> menunggu persetujuan. Silahkan tunggu sampai dengan 3 hari kerja. Anda masih bisa mengubah data diri sebelum di periksa oleh petugas.</p>
                                                        <a class="btn btn-primary" href="detail-beasiswa-mahasiswa.php?id=<?=$row2['id_spesial']?>"><p class="mb-0">Lihat data anda</p></a>
                                                    </div>
                                                </div>
                                                <?php } } }?>
                                            </div>
                                        </div>
                                    </div>
                        </div><!-- container -->

                    </div> <!-- Page content Wrapper -->
<?=template_footer()?>