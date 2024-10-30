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
   $row = $stmt->fetch(PDO::FETCH_ASSOC);
$photos = $row['foto'];
$num_row2 = $pdo->query(" SELECT COUNT(*) FROM pengajuan WHERE id_mhs = '$id_akun' ")->fetchColumn();
$total_pendaftaran = $pdo->query(" SELECT COUNT(*) FROM pengajuan ")->fetchColumn();

$today = $pdo->query(" SELECT COUNT(*) FROM pengajuan WHERE DATE(timestamp) = CURDATE() ")->fetchColumn();
$ditolak = $pdo->query(" SELECT COUNT(*) FROM pengajuan WHERE approved = 'no' and DATE(updated_timestamp) = CURDATE() ")->fetchColumn();
$diterima = $pdo->query(" SELECT COUNT(*) FROM pengajuan WHERE approved = 'yes' and DATE(updated_timestamp) = CURDATE() ")->fetchColumn();
$comparison1 = $pdo->query("SELECT 
ROUND(
    IF(count_yesterday > 0, 
        ((count_today - count_yesterday) / count_yesterday) * 100,
        IF(count_today > 0, 100, 0) 
    ),
    2
) AS percentage_change
FROM (
SELECT 
    (SELECT COUNT(*) FROM pengajuan WHERE DATE(timestamp) = CURDATE()) AS count_today,
    (SELECT COUNT(*) FROM pengajuan WHERE DATE(timestamp) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)) AS count_yesterday
) AS counts;

")->fetchColumn();

$arrow_icon1 = ($comparison1 > 0) ? '<h6 class="m-0 float-right text-center text-success"> <i class="mdi mdi-arrow-up"></i>' : '<h6 class="m-0 float-right text-center text-danger"> <i class="mdi mdi-arrow-down"></i>';

$comparison2 = $pdo->query("SELECT 
ROUND(
    IF(count_yesterday > 0, 
        ((count_today - count_yesterday) / count_yesterday) * 100,
        IF(count_today > 0, 100, 0) 
    ),
    2
) AS percentage_change
FROM (
SELECT 
    (SELECT COUNT(*) FROM pengajuan WHERE approved = 'no' AND DATE(updated_timestamp) = CURDATE()) AS count_today,
    (SELECT COUNT(*) FROM pengajuan WHERE approved = 'no' AND DATE(updated_timestamp) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)) AS count_yesterday
) AS counts;


")->fetchColumn();

$arrow_icon2 = ($comparison2 > 0) ? '<h6 class="m-0 float-right text-center text-success"> <i class="mdi mdi-arrow-up"></i>' : '<h6 class="m-0 float-right text-center text-danger"> <i class="mdi mdi-arrow-down"></i>';

$comparison3 = $pdo->query("SELECT 
ROUND(
    IF(count_yesterday > 0, 
        ((count_today - count_yesterday) / count_yesterday) * 100,
        IF(count_today > 0, 100, 0)  -- Jika count_today > 0 maka 100, jika tidak maka 0
    ),
    2
) AS percentage_change
FROM (
SELECT 
    (SELECT COUNT(*) FROM pengajuan WHERE approved = 'yes' AND DATE(updated_timestamp) = CURDATE()) AS count_today,
    (SELECT COUNT(*) FROM pengajuan WHERE approved = 'yes' AND DATE(updated_timestamp) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)) AS count_yesterday
) AS counts;

")->fetchColumn();

$arrow_icon3 = ($comparison3 > 0) ? '<h6 class="m-0 float-right text-center text-success"> <i class="mdi mdi-arrow-up"></i>' : '<h6 class="m-0 float-right text-center text-danger"> <i class="mdi mdi-arrow-down"></i>';

$year = date('Y');

$stmt_chart1 = $pdo->prepare("WITH months AS (
    SELECT '$year-01-01' AS month UNION ALL
    SELECT '$year-02-01' UNION ALL
    SELECT '$year-03-01' UNION ALL
    SELECT '$year-04-01' UNION ALL
    SELECT '$year-05-01' UNION ALL
    SELECT '$year-06-01' UNION ALL
    SELECT '$year-07-01' UNION ALL
    SELECT '$year-08-01' UNION ALL
    SELECT '$year-09-01' UNION ALL
    SELECT '$year-10-01' UNION ALL
    SELECT '$year-11-01' UNION ALL
    SELECT '$year-12-01'
)
SELECT 
    DATE_FORMAT(m.month, '%Y-%m-%d') AS month,
    COALESCE(SUM(CASE WHEN p.approved = 'new' THEN 1 ELSE 0 END), 0) AS a,
    COALESCE(SUM(CASE WHEN p.approved = 'no' THEN 1 ELSE 0 END), 0) AS b,
    COALESCE(SUM(CASE WHEN p.approved = 'yes' THEN 1 ELSE 0 END), 0) AS c
FROM 
    months m
LEFT JOIN 
    pengajuan p ON DATE_FORMAT(p.updated_timestamp, '%Y-%m') = DATE_FORMAT(m.month, '%Y-%m')
GROUP BY 
    m.month
ORDER BY 
    m.month;

");
   $stmt_chart1->execute();
   $rows_chart1 = $stmt_chart1->fetchAll(PDO::FETCH_ASSOC);

   $data = [];
   foreach ($rows_chart1 as $row) {
       $data[] = [
           'y' => $row['month'],
           'a' => (int)$row['a'],
           'b' => (int)$row['b'],
           'c' => (int)$row['c']
       ];
   }

   $stmt_chart2 = $pdo->prepare("WITH statuses AS (
    SELECT 'new' AS approved UNION ALL
    SELECT 'no' UNION ALL
    SELECT 'yes'
)
SELECT 
    CASE s.approved
        WHEN 'new' THEN 'Baru'
        WHEN 'no' THEN 'Ditolak'
        WHEN 'yes' THEN 'Diterima'
    END AS label,
    COALESCE(COUNT(p.approved), 0) AS value
FROM 
    statuses s
LEFT JOIN 
    pengajuan p ON p.approved = s.approved AND DATE(p.updated_timestamp) = CURDATE()
GROUP BY 
    s.approved
ORDER BY 
    s.approved;

");
   $stmt_chart2->execute();
   $rows_chart2 = $stmt_chart2->fetchAll(PDO::FETCH_ASSOC);
   $donutData = [];
   foreach($rows_chart2 as $row){
    $donutData[] = [
        'label' => $row['label'],
        'value' => (int)$row['value']
    ];
   }
?>

<?=template_header("Dashboard","admin",$id_role,$photos)?>
            <div class="page-content-wrapper ">

                        <div class="container-fluid">

                            <div class="row">
                                    <div class="col-sm-12">
                                        <div class="page-title-box">
                                            <div class="btn-group float-right">
                                                <ol class="breadcrumb hide-phone p-0 m-0">
                                                    <li class="breadcrumb-item"><a href="index-admin.php">Admin Beasiswa TEL-U</a></li>
                                                    <li class="breadcrumb-item active">Dashboard</li>
                                                </ol>
                                            </div>
                                            <h4 class="page-title">Dashboard Beasiswa TEL-U</h4>
                                        </div>
                                    </div>
                                </div>
                                <!-- end page title end breadcrumb -->

                                <div class="row">
                                <!-- Column -->
                                <div class="col-md-6 col-lg-6 col-xl-3">
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <div class="d-flex flex-row">
                                                <div class="col-3 align-self-center">
                                                    <div class="round">
                                                        <i class="mdi mdi-database"></i>
                                                    </div>
                                                </div>
                                                <div class="col-6 align-self-center text-center">
                                                    <div class="m-l-10">
                                                        <h5 class="mt-0 round-inner"><?=$total_pendaftaran?></h5>
                                                        <p class="mb-0 text-muted">Total pendaftaran</p>                                                                 
                                                    </div>
                                                </div>
                                                <div class="col-3 align-self-end align-self-center">
                                                     
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Column -->
                                <!-- Column -->
                                <div class="col-md-6 col-lg-6 col-xl-3">
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <div class="d-flex flex-row">
                                                <div class="col-3 align-self-center">
                                                    <div class="round">
                                                        <i class="mdi mdi-playlist-plus"></i>
                                                    </div>
                                                </div>
                                                <div class="col-6 text-center align-self-center">
                                                    <div class="m-l-10 ">
                                                        <h5 class="mt-0 round-inner"><?=$today?></h5>
                                                        <p class="mb-0 text-muted">Pendaftaran hari ini</p>
                                                    </div>
                                                </div>
                                                <div class="col-3 align-self-end align-self-center">
                                                    <?=$arrow_icon1?> <span><?=$comparison1?>%</span></h6>
                                                </div>                                                        
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Column -->
                                <!-- Column -->
                                <div class="col-md-6 col-lg-6 col-xl-3">
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <div class="d-flex flex-row">
                                                <div class="col-3 align-self-center">
                                                    <div class="round ">
                                                        <i class="mdi mdi-playlist-remove"></i>
                                                    </div>
                                                </div>
                                                <div class="col-6 align-self-center text-center">
                                                    <div class="m-l-10 ">
                                                        <h5 class="mt-0 round-inner"><?=$ditolak?></h5>
                                                        <p class="mb-0 text-muted">Pendaftaran ditolak</p>
                                                    </div>
                                                </div>
                                                <div class="col-3 align-self-end align-self-center">
                                                    <?=$arrow_icon2?> <span><?=$comparison2?>%</span></h6>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Column -->
                                <!-- Column -->
                                <div class="col-md-6 col-lg-6 col-xl-3">
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <div class="d-flex flex-row">
                                                <div class="col-3 align-self-center">
                                                    <div class="round">
                                                        <i class="mdi mdi-playlist-check"></i>
                                                    </div>
                                                </div>
                                                <div class="col-6 align-self-center text-center">
                                                    <div class="m-l-10 ">
                                                        <h5 class="mt-0 round-inner"><?=$diterima?></h5>
                                                        <p class="mb-0 text-muted">Pendaftaran diterima</p>
                                                    </div>
                                                </div>
                                                <div class="col-3 align-self-end align-self-center">
                                                    <?=$arrow_icon3?> <span><?=$comparison3?>%</span></h6>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Column -->
                            </div>

                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-xl-8">
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <h5 class="header-title pb-3 mt-0">Data Beasiswa Perbulan</h5>
                                            <div id="multi-line-chart" style="height:400px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12 col-xl-4">
                                    <div class="card m-b-30">
                                        <div class="card-body">
                                            <a href="list-beasiswa-admin.php" class="btn btn-primary btn-sm float-right">More Info</a>
                                            <h5 class="header-title mt-0 pb-3">Data Beasiswa Hari ini</h5>
                                                                                            
                                            <ul class="list-unstyled list-inline text-center">
                                                <li class="list-inline-item">
                                                    <p><i class="mdi mdi-checkbox-blank-circle text-info mr-2"></i>Baru</p> 
                                                </li>
                                                <li class="list-inline-item">
                                                    <p><i class="mdi mdi-checkbox-blank-circle text-danger mr-2"></i>Ditolak</p>
                                                </li>
                                                <li class="list-inline-item">
                                                    <p><i class="mdi mdi-checkbox-blank-circle text-success mr-2"></i>Diterima</p>    
                                                </li>
                                            </ul> 
                                            <div id="morris-donut-chart" style="height:345px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div><!-- container -->

                    </div> <!-- Page content Wrapper -->

        <script src="assets/plugins/morris/morris.min.js"></script>
        <script src="assets/plugins/raphael/raphael-min.js"></script>

<script>
    !function($) {
    "use strict";

    var Dashboard = function() {};

    //creates line chart
    Dashboard.prototype.createLineChart = function(element, data, xkey, ykeys, labels, lineColors) {
        Morris.Line({
          element: element,
          data: data,
          xkey: xkey,
          ykeys: ykeys,
          labels: labels,
          hideHover: 'auto',
          gridLineColor: '#eef0f2',
          resize: true, //defaulted to true
          lineColors: lineColors
        });
    },


    //creates Donut chart
    Dashboard.prototype.createDonutChart = function(element, data, colors) {
        Morris.Donut({
            element: element,
            data: data,
            resize: true,
            colors: colors
        });
    },
    
    Dashboard.prototype.init = function() {

        var $data = <?php echo json_encode($data); ?>;
        var $donutData = <?php echo json_encode($donutData); ?>;
        this.createLineChart('multi-line-chart', $data, 'y', ['a', 'b', 'c'], ['Baru', 'Ditolak', 'Diterima'], ['#007BFF', '#FF0000', '#00FF00']);

        //creating donut chart
        this.createDonutChart('morris-donut-chart', $donutData, ['#007BFF', '#FF0000', '#00FF00']);
    },
    //init
    $.Dashboard = new Dashboard, $.Dashboard.Constructor = Dashboard
}(window.jQuery),

//initializing 
function($) {
    "use strict";
    $.Dashboard.init();
}(window.jQuery);
</script>

<?=template_footer()?>