<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
session_start();

if(!isset($_SESSION['user_admin'])){
    header('location:index.php');
}

$user = $_SESSION['user_admin'];
$id_akun = $_SESSION['id_akun'];
$id_role = $_SESSION['id_role'];

$stmt = $pdo->prepare("SELECT * FROM akun WHERE username = '$user' ");
   $stmt->execute();
   // Fetch the records so we can display them in our template.
   $row = $stmt->fetch(PDO::FETCH_ASSOC);
$photos = $row['foto'];

$stmts = $pdo->prepare("SELECT * FROM pengajuan where approved = 'new' order by timestamp ASC ");
   $stmts->execute();
   // Fetch the records so we can display them in our template.
   $rows = $stmts->fetchAll(PDO::FETCH_ASSOC);

   $stmts2 = $pdo->prepare("SELECT * FROM pengajuan where approved !='new' order by timestamp DESC ");
   $stmts2->execute();
   // Fetch the records so we can display them in our template.
   $rows2 = $stmts2->fetchAll(PDO::FETCH_ASSOC);

   $months = array(
    'January' => 'Januari',
    'February' => 'Februari',
    'March' => 'Maret',
    'April' => 'April',
    'May' => 'Mei',
    'June' => 'Juni',
    'July' => 'Juli',
    'August' => 'Agustus',
    'September' => 'September',
    'October' => 'Oktober',
    'November' => 'November',
    'December' => 'Desember'
);
?>
<?=template_header("List Pengajuan","admin",$id_role,$photos)?>
            <div class="page-content-wrapper ">

                        <div class="container-fluid">

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="page-title-box">
                                            <div class="btn-group float-right">
                                                <ol class="breadcrumb hide-phone p-0 m-0">
                                                    <li class="breadcrumb-item"><a href="index-admin.php">Admin Beasiswa TEL-U</a></li>
                                                    <li class="breadcrumb-item active">List Pengajuan</li>
                                                </ol>
                                            </div>
                                            <h4 class="page-title">List Pengajuan Beasiswa menunggu verifikasi TEL-U</h4>
                                        </div>
                                    </div>
                                </div>
                                <!-- end page title end breadcrumb -->

                                <div class="row">
                                <div class="col-12">
                                <div class="card mb-2">
                                    <div class="card-body text-left table-responsive">

                                        <h4 class="mt-0 header-title">Daftar pengajuan menunggu verifikasi</h4>

                                    <table class="table table-borderless nowrap" id="table">
                                    <thead>
                                    <tr>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Semester</th>
                                    <th scope="col">Jenis</th>
                                    <th scope="col">Waktu</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <?php foreach($rows as $row){ 
                                            $date_string = $row['timestamp'];
                                            $date = new DateTime($date_string);
                                            $formatted_date = $date->format('d F Y \P\u\k\u\l H:i');
                                            $formatted_date = str_replace(array_keys($months), array_values($months), $formatted_date);

                                            $status = "";
                                            if($row['approved'] == 'new'){
                                                $status = "Belum Diverifikasi";
                                            }elseif($row['approved'] == 'no'){
                                                $status = "Ditolak";
                                            }elseif($row['approved'] == 'yes'){
                                                $status = "Diterima";
                                            }
                                            ?>
                                            <tr>
                                        <td><?=$row['nama']?> </td>
                                        <td><?=$row['email']?> </td>
                                        <td><?=$row['semester']?> </td>
                                        <td><?=$row['pilihan']?> </td>
                                        <td><?=$formatted_date?> </td>
                                        <td><?=$status?> </td>
                                        <td>
                                        <a href="detail-list-beasiswa-admin.php?id=<?=$row['id_spesial']?>" class="btn btn-primary"><i class='fa fa-edit'></i></a>
                                        </td>
                                        </tr>
                                        <?php } ?>

                                    </tbody>
                                    </table>
                                    </div>
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->

                            <div class="row">
                                    <div class="col-sm-12">
                                        <div class="page-title-box">
                                            <div class="btn-group float-right">
                                                <ol class="breadcrumb hide-phone p-0 m-0">
                                                </ol>
                                            </div>
                                            <h4 class="page-title">List seluruh Pengajuan Beasiswa TEL-U</h4>
                                        </div>
                                    </div>
                                </div>

                            <div class="row">
                                <div class="col-12">
                                <div class="card mb-2">
                                    <div class="card-body text-left table-responsive">

                                        <h4 class="mt-0 header-title">Daftar seluruh pengajuan beasiswa</h4>

                                    <table class="table table-borderless nowrap" id="table2">
                                    <thead>
                                    <tr>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Semester</th>
                                    <th scope="col">Jenis</th>
                                    <th scope="col">Waktu</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <?php foreach($rows2 as $row){ 
                                            $date_string = $row['timestamp'];
                                            $date = new DateTime($date_string);
                                            $formatted_date = $date->format('d F Y \P\u\k\u\l H:i');
                                            $formatted_date = str_replace(array_keys($months), array_values($months), $formatted_date);

                                            $status = "";
                                            if($row['approved'] == 'new'){
                                                $status = "Baru";
                                            }elseif($row['approved'] == 'no'){
                                                $status = "Ditolak";
                                            }elseif($row['approved'] == 'yes'){
                                                $status = "Diterima";
                                            }
                                            ?>
                                            <tr>
                                        <td><?=$row['nama']?> </td>
                                        <td><?=$row['email']?> </td>
                                        <td><?=$row['semester']?> </td>
                                        <td><?=$row['pilihan']?> </td>
                                        <td><?=$formatted_date?> </td>
                                        <td><?=$status?> </td>
                                        <td>
                                        <a href="detail-list-beasiswa-admin.php?id=<?=$row['id_spesial']?>" class="btn btn-primary"><i class='fa fa-edit'></i></a>
                                        </td>
                                        </tr>
                                        <?php } ?>

                                    </tbody>
                                    </table>
                                    </div>
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->

                        </div><!-- container -->

                    </div> <!-- Page content Wrapper -->



<?=template_footer()?>