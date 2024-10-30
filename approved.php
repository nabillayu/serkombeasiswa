<?php
include 'functions.php';
$pdo = pdo_connect_mysql();

$response = array();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Gunakan fungsi filter_var untuk memvalidasi dan membersihkan input ID spesial
    $id_spesial = isset($_POST['id_spesial']) ? filter_var($_POST['id_spesial'], FILTER_SANITIZE_STRING) : '';

    // Pastikan untuk menggunakan parameter binding untuk menghindari SQL Injection
    $stmt = $pdo->prepare("UPDATE pengajuan SET approved = 'yes' WHERE id_spesial = :id_spesial");
    $stmt->bindParam(':id_spesial', $id_spesial, PDO::PARAM_STR);

    if($stmt->execute()){
        $response = array(
            'status' => 'success',
            'message' => 'Pengajuan berhasil diterima.'
        );
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Terjadi kesalahan dalam memproses pengajuan.'
        );
    }
} else {
    $response = array(
        'status' => 'error',
        'message' => 'Data pengajuan tidak diterima.'
    );
}

// Mengirim respons kembali ke JavaScript
header('Content-Type: application/json');
echo json_encode($response);
?>
