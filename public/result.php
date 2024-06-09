<?php

namespace Core;

use Core\Question;
use Core\ShowData;
use Core\Database;
use Core\DataPenyakitJagung;
use Core\DataPenyakitGejalaJagung;

require_once '../function/init.php';

//start session
session_start();

//insialisasi database
$db = new database();

// Periksa apakah combined_data ada dalam sesi
if (!isset($_SESSION['combined_data']) || empty($_SESSION['combined_data'])) {
    echo "Tidak ada data gejala yang diproses.";
    exit;
}

// Hitung nilai CF akhir
$final_cf = array_reduce($_SESSION['combined_data'], function ($carry, $item) {
    $combined_cf = $item['combined_cf'];
    $carry = $carry + $combined_cf * (1 - $carry);
    return $carry;
}, 0);

$data_penyakit = $_SESSION['combined_data'];

$filtered_gejala = array_filter($data_penyakit, function ($item) {
    return $item['nilai_gejala'] > 0;
});

var_dump($filtered_gejala);
echo "</br>";

$id_gejala = array_column($filtered_gejala, 'id_gejala');

var_dump($id_gejala);

if (!empty($id_gejala)) {
    $placeholders = implode(',', array_fill(0, count($id_gejala), '?'));
    $sql_penyakit = "SELECT pg.id_penyakit, p.nama_penyakit
    FROM tb_penyakit_gejala pg
    JOIN tb_penyakit_jagung p ON pg.id_penyakit = p.id_penyakit
    WHERE pg.id_gejala IN ($placeholders)";

    $sql_penyakit = $db->getConnection()->prepare($sql_penyakit);
    $types = str_repeat('s', count($id_gejala)); 
    $sql_penyakit->bind_param($types, ...$id_gejala);
    $sql_penyakit->execute();
    $result = $sql_penyakit->get_result();

    // Variable untuk menyimpan data penyakit
    $penyakit_counts = [];
    while ($row = $result->fetch_assoc()) {
        $nama_penyakit = $row['nama_penyakit'];
        if (!isset($penyakit_counts[$nama_penyakit])) {
            $penyakit_counts[$nama_penyakit] = 0;
        }
        $penyakit_counts[$nama_penyakit]++;
    }

    // $sql_penyakit = $db->getConnection()->prepare($sql_penyakit);
    // $sql_penyakit->bind_param("s", $id_gejala);
    // $sql_penyakit->execute();
    // $result = $sql_penyakit->get_result();

    //variable untuk menyimpan data penyakit
    // while ($row = $result->fetch_assoc()) {
    //     $nama_penyakit = $row['nama_penyakit']; 
        // if (!isset($penyakit_counts[$nama_penyakit])) {
        //     $penyakit_counts[$nama_penyakit] = 0;
        // }
        // $penyakit_counts[$nama_penyakit]++;
    // }

    // echo "<hr>";
    // echo "</br>";  
    // echo $nama_penyakit;
    // echo "</br>";

    echo "<pre>";
    print_r($penyakit_counts);
    echo "</pre>";
}


// // Dapatkan daftar id_gejala dari sesi
// $id_gejala_penyakit = $_SESSION['id_gejala_penyakit'] ?? [];

// // Periksa apakah cf_values ada dalam sesi
// if (!isset($_SESSION['cf_values']) || empty($_SESSION['cf_values'])) {
//     echo "Tidak ada data gejala yang diproses.";
//     exit;
// }

// // Hitung nilai CF akhir
// $final_cf = array_reduce($_SESSION['cf_values'], function ($carry, $item) {
//     $hasil = $carry + $item * (1 - $carry);
//     return $hasil;
// }, 0);


// if (empty($id_gejala_penyakit)) {
//     echo "<p>Tidak ada gejala yang diproses.</p>";
//     session_destroy();
//     exit;
// }

$final_cf_percentage = $final_cf * 100;
echo $final_cf_percentage . '%';

// $data_penyakit = new ShowData();
// $penyakit = $data_penyakit->question_process();

// var_dump($penyakit);
//make variable for save the data from class
// $data_penyakit = new DataPenyakitJagung();
// $data_penyakit->penyakitJagung();
// $penyakit_jagung = $data_penyakit->getPenyakit();
// echo "</br>"; 
// var_dump($penyakit_jagung);

// //data penyakit gejala jagung
// $data_penyakit_gejala = new DataPenyakitGejalaJagung();
// $data_penyakit_gejala->DataPenyakitGejalaJagung();
// $gejala_penyakit_jagung = $data_penyakit_gejala->getPenyakitGejala();

// echo "</br>";
// var_dump($id_gejala_penyakit);
// echo "</br>";
// echo "</br>";

// // foreach ($id_gejala_penyakit as $id_gejala) {
// //     foreach ($penyakit_jagung as $id_penyakit => $data) {
// //         var_dump($data);
// //         if ($data['id_penyakit'] === $id_gejala) {
// //             // echo "Nama Penyakit: " . $data['nama_penyakit'] . "\n";
// //             echo "halo";
// //         }
// //     }
// // }

// foreach ($id_gejala_penyakit as $id_gejala) {
//     $sql = $db->getConnection()->prepare("SELECT tb_penyakit_jagung.id_penyakit, tb_penyakit_jagung.nama_penyakit, tb_penyakit_gejala.id_penyakit, tb_penyakit_gejala.id_gejala 
//     FROM tb_penyakit_jagung 
//     JOIN tb_penyakit_gejala 
//     ON tb_penyakit_jagung.id_penyakit = tb_penyakit_gejala.id_penyakit
//     WHERE tb_penyakit_gejala.id_gejala = ? ");
//     $sql->bind_param('s', $id_gejala);
//     $sql->execute();
//     $result_penyakit = $sql->get_result();

//     foreach ($id_gejala_penyakit as $id_gejala) {
//         $sql = $db->getConnection()->prepare("
//             SELECT tb_penyakit_jagung.nama_penyakit 
//             FROM tb_penyakit_jagung 
//             JOIN tb_penyakit_gejala 
//             ON tb_penyakit_jagung.id_penyakit = tb_penyakit_gejala.id_penyakit
//             WHERE tb_penyakit_gejala.id_gejala = ? 
//         ");
//         $sql->bind_param('s', $id_gejala);
//         $sql->execute();
//         $result_penyakit = $sql->get_result();

//         if ($result_penyakit->num_rows > 0) {
//             while ($row = $result_penyakit->fetch_assoc()) {
//                 echo "Nama Penyakit: " . $row['nama_penyakit'] . "<br>";
//             }
//         } else {
//             echo "Tidak ditemukan penyakit untuk gejala: $id_gejala <br>";
//         }
//     }

// }


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