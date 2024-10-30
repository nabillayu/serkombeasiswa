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

$id = $_GET['id'];

$num_row2 = $pdo->query(" SELECT COUNT(*) FROM pengajuan WHERE id_spesial = '$id'")->fetchColumn();

if($num_row2 == 0){
    echo "<script type='text/javascript'> window.location.href='page-404.php'</script>";
}

$stmt = $pdo->prepare("SELECT * FROM akun WHERE username = '$user' ");
   $stmt->execute();
   // Fetch the records so we can display them in our template.
   $row = $stmt->fetch(PDO::FETCH_ASSOC);

   $stmt2 = $pdo->prepare("SELECT * FROM pengajuan WHERE id_spesial = '$id' ");
   $stmt2->execute();
   // Fetch the records so we can display them in our template.
   $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

$photos = $row['foto'];

?>
<?=template_header("List Pengajuan","admin",$id_role,$photos)?>
            <div class="page-content-wrapper ">

                        <div class="container-fluid">

                            <div class="row">
                                    <div class="col-sm-12">
                                        <div class="page-title-box">
                                            <div class="btn-group float-right">
                                                <ol class="breadcrumb hide-phone p-0 m-0">
                                                    <li class="breadcrumb-item"><a href="index-admin.php">Beasiswa TEL-U</a></li>
                                                    <li class="breadcrumb-item"><a href="list-beasiswa-admin.php">List Pengajuan</a></li>
                                                    <li class="breadcrumb-item active">Detail Data Pengajuan</li>
                                                </ol>
                                            </div>
                                            <h4 class="page-title">Detail Data Pengajuan Beasiswa TEL-U</h4>
                                        </div>
                                    </div>
                                </div>
                                <!-- end page title end breadcrumb -->
                                <div class="row">
                                <div class="col-lg-12">
                                    <div class="card m-b-30">
                                        <div class="card-body">
            
                                            <h4 class="mt-0 header-title">Data Pengajuan</h4>
                                            <p class="text-muted m-b-30 font-14">Mohon perhatikan data lebih teliti sebelum membuat keputusan approval dari beasiswa.</p>
            
                                            <form id="form_parsley" action="" enctype="multipart/form-data" method="post">
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Nama</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="nama" value="<?=$row2['nama']?>" class="form-control" placeholder="Masukkan nama anda..." readonly />
                                                        <input type="text" name="id_spesial" value="<?=$row2['id_spesial']?>" hidden />
                                                    </div>
                                                </div>
            
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Email</label>
                                                    <div class="col-sm-10">
                                                        <input type="email" name="email" value="<?=$row2['email']?>" class="form-control" readonly
                                                                parsley-type="email" placeholder="Masukkan email anda..."/>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Nomor HP</label>
                                                    <div class="col-sm-10">
                                                        <input data-parsley-type="number" value="<?=$row2['tel']?>" name="hp" type="text"
                                                                class="form-control" readonly
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
                                                    <input  type="text" value="<?=$row2['ipk']?>" class="form-control" id="ipk" readonly name="ipk"/>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Jenis Beasiswa</label>
                                                <div class="col-sm-10">
                                                    <select class="form-control" disabled name="pilihan">
                                                        <option value="">Pilih</option>
                                                        <option value="akademik" <?php echo ($row2['pilihan'] == 'akademik') ? 'selected' : ''; ?> >Akademik</option>
                                                        <option value="non-akademik" <?php echo ($row2['pilihan'] == 'non-akademik') ? 'selected' : ''; ?> >Non-Akademik</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Download Berkas Syarat</label>
                                                <div class="col-sm-10">
                                                        <a href="berkas/<?=$row2['berkas']?>" class="btn btn-dark">
                                                        Download
                                                        </a>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Status</label>
                                                    <div class="col-sm-10">
                                                        <input value="<?=$row2['approved']?>" type="text"
                                                                class="form-control" readonly/>
                                                    </div>
                                                </div>
                                                <?php if($row2['approved'] == 'no'){ ?>
                                                    <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">Alasan</label>
                                                    <div class="col-sm-10">
                                                        <input value="<?=$row2['alasan']?>" type="text"
                                                                class="form-control" readonly/>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                                <div class="form-group row">
                                                <label class="col-sm-2 col-form-label"></label>
                                                    <div class="col-sm-10">
                                                    <?php if($row2['approved'] == 'new'){ ?>
                                                    <button id="sa-approved" data-id="<?=$id?>" class="btn btn-primary" type="button">ACC Pengajuan</button>
                                                    <button id="sa-rejected" data-id="<?=$id?>" class="btn btn-danger" type="button">Tolak Pengajuan</button>
                                                        <a href="list-beasiswa-admin.php" class="btn btn-secondary waves-effect m-l-5">
                                                            Batal
                                                        </a>
                                                    <?php }elseif($row2['approved'] == 'no' or $row2['approved'] == 'yes'){?>
                                                        <a href="list-beasiswa-admin.php" class="btn btn-secondary waves-effect m-l-5">
                                                            Kembali
                                                        </a>
                                                    <?php }?>
                                                    </div>
                                                </div>
                                            </form>
            
                                        </div>
                                    </div>
                                </div> <!-- end col -->
                                </div>
                        </div><!-- container -->

                    </div> <!-- Page content Wrapper -->

                    <script>

        $('#sa-approved').click(function () {
            event.preventDefault();
            var id_spesial = $(this).data('id');

            Swal.fire({
                title: 'Apakah kamu yakin ingin meng-ACC pengajuan ini?',
                text: "Tindakan ini tidak bisa dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger m-l-10',
                confirmButtonText: 'Ya!'
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'approved.php',
                        type: 'POST',
                        data: {
                            id_spesial: id_spesial
                        },
                        success: function (response) {
                            Swal.fire('Berhasil!', 'Pengajuan berhasil diterima.', 'success').then(function () {
                                location.reload();
                            });
                        },
                        error: function (xhr, status, error) {
                            Swal.fire('Galat!', 'Terjadi kesalahan.', 'error').then(function () {
                                location.reload();
                            });
                        }
                    });
                } else {
                }
            });
        });

        $('#sa-rejected').click(function () {
            event.preventDefault();
            var id_spesial = $(this).data('id');

            Swal.fire({
                title: 'Apakah kamu yakin ingin menolak pengajuan ini?',
                text: "Tindakan ini tidak bisa dibatalkan!",
                icon: 'warning', 
                input: 'text',  
                inputPlaceholder: 'Masukkan alasan penolakan...',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Alasan penolakan harus diisi!';
                    }
                },
                showCancelButton: true,
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger m-l-10',
                confirmButtonText: 'Ya, tolak!'
            }).then(function (result) {
                if (result.isConfirmed && result.value) {
                    var alasan = result.value;
                    
                    $.ajax({
                        url: 'rejected.php',
                        type: 'POST',
                        data: {
                            id_spesial: id_spesial,
                            alasan: alasan    
                        },
                        success: function (response) {
                            Swal.fire('Ditolak!', 'Pengajuan berhasil ditolak.', 'success')
                            .then(function () {
                                location.reload();
                            });
                        },
                        error: function (xhr, status, error) {
                            Swal.fire('Galat!', 'Terjadi kesalahan.', 'error').then(function () {
                                location.reload();
                            });
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire('Dibatalkan', 'Pengajuan batal ditolak.', 'info');
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