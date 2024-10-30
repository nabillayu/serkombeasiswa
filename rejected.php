<?php
include 'functions.php';
$pdo = pdo_connect_mysql();

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mengambil data dari permintaan POST
    $id_spesial = isset($_POST['id_spesial']) ? filter_var($_POST['id_spesial'], FILTER_SANITIZE_STRING) : '';
    $alasan = isset($_POST['alasan']) ? filter_var($_POST['alasan'], FILTER_SANITIZE_STRING) : '';

    if ($id_spesial && $alasan) {
        // Gunakan parameter binding untuk menghindari SQL Injection
        $stmt = $pdo->prepare("UPDATE pengajuan SET approved = 'no', alasan = :alasan WHERE id_spesial = :id_spesial");
        $stmt->bindParam(':id_spesial', $id_spesial, PDO::PARAM_STR);
        $stmt->bindParam(':alasan', $alasan, PDO::PARAM_STR);

        if ($stmt->execute()) {
            //ini array untuk menyimpan respon yang akan diterima oleh user
            $response = array(
                'status' => 'success',
                'message' => 'Pengajuan berhasil ditolak.'
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Terjadi kesalahan dalam memproses penolakan.'
            );
        }
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Data pengajuan atau alasan tidak valid.'
        );
    }
} else {
    $response = array(
        'status' => 'error',
        'message' => 'Metode permintaan tidak valid.'
    );
}

// Mengirim respons kembali ke JavaScript
header('Content-Type: application/json');
echo json_encode($response);
?>
