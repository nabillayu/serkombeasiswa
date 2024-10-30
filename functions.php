<?php
//ini fungsi
function pdo_connect_mysql() {
	$DATABASE_HOST = "localhost";
    $DATABASE_USER = "root";
    $DATABASE_PASS = "";
    $DATABASE_NAME = "beasiswa_serkom";
    try {
    	return new PDO("mysql:host=$DATABASE_HOST;port=3306;dbname=$DATABASE_NAME", $DATABASE_USER, $DATABASE_PASS);
    } catch (PDOException $exception) {
    	exit('Failed to connect to database!');
    }
}

//ini contoh prosedur
function template_header($title,$role,$id_role,$photos) {
    $pdo = pdo_connect_mysql();

    $stmt_hmenu = $pdo->prepare("SELECT * FROM header_menu WHERE role = '$id_role' order by posisi ASC");
    $stmt_hmenu->execute();
    $row_hmenu = $stmt_hmenu->fetchAll(PDO::FETCH_ASSOC);

 echo <<<EOT
 <!DOCTYPE html>
 <html>
     <head>
         <meta charset="utf-8" />
         <meta http-equiv="X-UA-Compatible" content="IE=edge">
         <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
         <title>$title</title>
         <meta content="Admin Dashboard" name="description" />
         <meta content="Mannatthemes" name="author" />
         <meta http-equiv="X-UA-Compatible" content="IE=edge" />
 
         <link rel="shortcut icon" href="assets/images/favicon.png">
 
         <link href="assets/plugins/morris/morris.css" rel="stylesheet">
         <link href="https://cdn.datatables.net/v/bs5/jszip-2.5.0/dt-1.13.4/b-2.3.6/b-colvis-2.3.6/b-html5-2.3.6/b-print-2.3.6/datatables.min.css" rel="stylesheet"/>
         <!-- Sweet Alert -->
         <script src='https://cdn.jsdelivr.net/npm/sweetalert2@9'></script>

         <!-- Dropzone css -->
         <link href="assets/plugins/dropzone/dist/dropzone.css" rel="stylesheet" type="text/css">
         <link href="assets/plugins/dropify/css/dropify.min.css" rel="stylesheet">

         <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
         <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
         <link href="assets/css/icons.css" rel="stylesheet" type="text/css">
         <link href="assets/css/style.css" rel="stylesheet" type="text/css">
 
     </head>
 
 
     <body class="fixed-left">
 
         <!-- Loader -->
         <div id="preloader"><div id="status"><div class="spinner"></div></div></div>
 
         <!-- Begin page -->
         <div id="wrapper">
 
             <!-- ========== Left Sidebar Start ========== -->
             <div class="left side-menu">
                 <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
                     <i class="ion-close"></i>
                 </button>
 
                 <!-- LOGO -->
                 <div class="topbar-left">
                     <div class="text-center">
                         <a href="index-$role.php" class="logo"><img src="assets/images/ittp_logo.png" height="30" alt="logo"> Beasiswa TEL-U</a>
                         <!-- <a href="index-$role.php" class="logo"><img src="assets/images/logo.png" height="24" alt="logo"></a> -->
                     </div>
                 </div>
 
                 <div class="sidebar-inner slimscrollleft">
 
                     <div id="sidebar-menu">
                         <ul>
EOT;
 foreach ($row_hmenu as $hmenu){
    $id_header = $hmenu['id'];
    $nama_header = $hmenu['nama'];
    echo <<<EOT
    <li class="menu-title">$nama_header</li>
    EOT;

    $stmt_menu = $pdo->prepare("SELECT * FROM menu WHERE role = '$id_role' and id_header = '$id_header' order by posisi ASC");
    $stmt_menu->execute();
    // Fetch the records so we can display them in our template.
    $row_menu = $stmt_menu->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($row_menu as $menu){
    $link_menu = $menu['link'];
    $nama_menu = $menu['nama'];
    $icon = $menu['icon'];
    if($title == $nama_menu){
        echo <<<EOT
                             <li>
                                 <a href="$link_menu" class="waves-effect">
                                     <i class="$icon"></i>
                                     <span> $nama_menu </span>
                                 </a>
                             </li>
        EOT;
    }else{
    echo <<<EOT
                             <li>
                                <a href="$link_menu" class="waves-effect"><i class="$icon"></i><span> $nama_menu </span></a>
                            </li>
    EOT;
    }
 }
}
                            echo <<<EOT
                         </ul>
                     </div>
                     <div class="clearfix"></div>
                 </div> <!-- end sidebarinner -->
             </div>
             <!-- Left Sidebar End -->
 
             <!-- Start right Content here -->
 
             <div class="content-page">
                 <!-- Start content -->
                 <div class="content">
 
                     <!-- Top Bar Start -->
                     <div class="topbar">
 
                         <nav class="navbar-custom">
 
                             <ul class="list-inline float-right mb-0">

                                 <li class="list-inline-item dropdown notification-list">
                                     <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button"
                                        aria-haspopup="false" aria-expanded="false">
                                         <img src="assets/images/users/$photos" alt="user" class="rounded-circle">
                                     </a>
                                     <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                                         <!-- item-->
                                         <div class="dropdown-item noti-title">
                                             <h5>Welcome</h5>
                                         </div>
                                         <a class="dropdown-item" href="profile-$role.php"><i class="mdi mdi-account-circle m-r-5 text-muted"></i> Profile</a>
                                         <div class="dropdown-divider"></div>
                                         <a class="dropdown-item" href="logout.php"><i class="mdi mdi-logout m-r-5 text-muted"></i> Logout</a>
                                     </div>
                                 </li>
 
                             </ul>
 
                             <ul class="list-inline menu-left mb-0">
                                 <li class="float-left">
                                     <button class="button-menu-mobile open-left waves-light waves-effect">
                                         <i class="mdi mdi-menu"></i>
                                     </button>
                                 </li>
                             </ul>
 
                             <div class="clearfix"></div>
 
                         </nav>
 
                     </div>
                     <!-- Top Bar End -->
 
 EOT;
}

function template_footer() {
    echo <<<EOT
        </div> <!-- content -->

                    <footer class="footer">
                        © 2024 Beasiswa.
                    </footer>

                </div>
                <!-- End Right content here -->

            </div>
            <!-- END wrapper -->


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

            <script type="text/javascript" src="assets/plugins/parsleyjs/parsley.min.js"></script>
            <!-- Sweet-Alert  -->
            <script src="assets/plugins/sweet-alert2/sweetalert2.min.js"></script>
            <script src="assets/pages/sweet-alert.init.js"></script> 

            <script type="text/javascript">
            $(document).ready(function() {
                $('#form_parsley').parsley();
            });
        </script>

        <!-- Dropzone js -->
        <script src="assets/plugins/dropzone/dist/dropzone.js"></script>
        <script src="assets/plugins/dropify/js/dropify.min.js"></script>

            <!-- App js -->
            <script src="assets/js/app.js"></script>

            <script>
            $(document).ready(function(){
                // Basic
                $('.dropify').dropify();

                // Translated
                $('.dropify-fr').dropify({
                    messages: {
                        default: 'Glissez-déposez un fichier ici ou cliquez',
                        replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                        remove:  'Supprimer',
                        error:   'Désolé, le fichier trop volumineux'
                    }
                });

                // Used events
                var drEvent = $('#input-file-events').dropify();

                drEvent.on('dropify.beforeClear', function(event, element){
                    return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
                });

                drEvent.on('dropify.afterClear', function(event, element){
                    alert('File deleted');
                });

                drEvent.on('dropify.errors', function(event, element){
                    console.log('Has Errors');
                });

                var drDestroy = $('#input-file-to-destroy').dropify();
                drDestroy = drDestroy.data('dropify')
                $('#toggleDropify').on('click', function(e){
                    e.preventDefault();
                    if (drDestroy.isDropified()) {
                        drDestroy.destroy();
                    } else {
                        drDestroy.init();
                    }
                })
            });
        </script>

        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    
        <script src="https://cdn.datatables.net/fixedheader/3.3.2/js/dataTables.fixedHeader.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap.min.js"></script>
    
        <script>
        $(document).ready(function () {
            var table = $('#table').DataTable({
                scrollX: true,
                autoWidth: false // Menonaktifkan penyesuaian otomatis lebar kolom
            });
        });        
      </script>   

      <script>
      $(document).ready(function () {
          var table = $('#table2').DataTable({
              scrollX: true,
              autoWidth: false // Menonaktifkan penyesuaian otomatis lebar kolom
          });
      });        
    </script>  

        </body>
    </html>
    EOT;
   }
?>