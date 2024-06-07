<?php

namespace Core;

use Core\Database;
use Core\DataPenyakitJagung;
use Core\DataPenyakitGejalaJagung;

require_once '../function/init.php';

//start session
session_start();

//insialisasi database
$db = new database();

// Dapatkan daftar id_gejala dari sesi
$id_gejala_penyakit = $_SESSION['id_gejala_penyakit'] ?? [];

// Periksa apakah cf_values ada dalam sesi
if (!isset($_SESSION['cf_values']) || empty($_SESSION['cf_values'])) {
    echo "Tidak ada data gejala yang diproses.";
    exit;
}

// Hitung nilai CF akhir
$final_cf = array_reduce($_SESSION['cf_values'], function ($carry, $item) {
    return $carry + $item * (1 - $carry);
}, 0);

if (empty($id_gejala_penyakit)) {
    echo "<p>Tidak ada gejala yang diproses.</p>";
    session_destroy();
    exit;
}

//make variable for save the data from class
$data_penyakit = new DataPenyakitJagung();
$data_penyakit->penyakitJagung();
$penyakit_jagung = $data_penyakit->getPenyakit();

var_dump($penyakit_jagung);

//data penyakit gejala jagung
$data_penyakit_gejala = new DataPenyakitGejalaJagung();
$data_penyakit_gejala->DataPenyakitGejalaJagung();
$gejala_penyakit_jagung = $data_penyakit_gejala->getPenyakitGejala();

echo "</br>";
var_dump($id_gejala_penyakit);
echo "</br>";
echo "</br>";

// foreach ($id_gejala_penyakit as $id_gejala) {
//     foreach ($penyakit_jagung as $id_penyakit => $data) {
//         var_dump($data);
//         if ($data['id_penyakit'] === $id_gejala) {
//             // echo "Nama Penyakit: " . $data['nama_penyakit'] . "\n";
//             echo "halo";
//         }
//     }
// }

foreach ($id_gejala_penyakit as $id_gejala) {
    $sql = $db->getConnection()->prepare("SELECT tb_penyakit_jagung.id_penyakit, tb_penyakit_jagung.nama_penyakit, tb_penyakit_gejala.id_penyakit, tb_penyakit_gejala.id_gejala 
    FROM tb_penyakit_jagung 
    JOIN tb_penyakit_gejala 
    ON tb_penyakit_jagung.id_penyakit = tb_penyakit_gejala.id_penyakit
    WHERE tb_penyakit_gejala.id_gejala = ? ");
    $sql->bind_param('s', $id_gejala);
    $sql->execute();
    $result_penyakit = $sql->get_result();

    foreach ($id_gejala_penyakit as $id_gejala) {
        $sql = $db->getConnection()->prepare("
            SELECT tb_penyakit_jagung.nama_penyakit 
            FROM tb_penyakit_jagung 
            JOIN tb_penyakit_gejala 
            ON tb_penyakit_jagung.id_penyakit = tb_penyakit_gejala.id_penyakit
            WHERE tb_penyakit_gejala.id_gejala = ? 
        ");
        $sql->bind_param('s', $id_gejala);
        $sql->execute();
        $result_penyakit = $sql->get_result();
    
        if ($result_penyakit->num_rows > 0) {
            while ($row = $result_penyakit->fetch_assoc()) {
                echo "Nama Penyakit: " . $row['nama_penyakit'] . "<br>";
            }
        } else {
            echo "Tidak ditemukan penyakit untuk gejala: $id_gejala <br>";
        }
    }
    
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

</body>

</html>