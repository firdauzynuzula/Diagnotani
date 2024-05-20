<?php

require_once '../function/init.php';

use Core\DataGejala;
use Core\Question;
use Core\ShowData;

// Mulai sesi untuk menyimpan informasi pertanyaan yang sedang ditampilkan
session_start();

// Inisialisasi objek DataGejala
$datagejala = new DataGejala();

// Periksa apakah pertanyaan saat ini sudah ada dalam sesi
if (!isset($_SESSION['current_question'])) {
    // Jika belum ada, ambil pertanyaan pertama dari objek DataGejala
    $datagejala->dataGejala();
    $gejala = $datagejala->getGejala();

    // Periksa apakah ada pertanyaan
    if (!empty($gejala)) {
        // Jika ada, simpan pertanyaan pertama dalam sesi
        $_SESSION['current_question'] = $gejala[0];
    }
}

// Ambil pertanyaan yang akan ditampilkan
$current_question = $_SESSION['current_question'];

// Tangani pengiriman jawaban jika formulir disubmit
if (isset($_POST['submit'])) {
    // Peroleh id_gejala yang diajukan pada form
    $id_gejala_user = $current_question['id_gejala'];
    // Peroleh nilai yang diberikan oleh user untuk gejala tersebut
    $value_gejala_user = $_POST['nilai_gejala'];

    $sql = new Question($id_gejala_user, $value_gejala_user, null, null, null, null, null, null, null, null);
    $sql_data = new ShowData($id_gejala_user, $value_gejala_user);

    $sql->hitungNilai();

    // Pindah ke pertanyaan berikutnya
    // Temukan index pertanyaan yang sedang ditampilkan dalam array $gejala
    $current_question_index = array_search($current_question, $gejala);
    // Pilih pertanyaan berikutnya jika masih ada
    if ($current_question_index !== false && isset($gejala[$current_question_index + 1])) {
        $_SESSION['current_question'] = $gejala[$current_question_index + 1];
    } else {
        // Jika tidak ada pertanyaan berikutnya, hapus sesi pertanyaan saat ini 
        unset($_SESSION['current_question']);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
</head>

<body>
    <?php if (isset($current_question)) : ?>
        <form action="" method="post">
            <p>Apakah <?= $current_question['gejala'] ?>?</p>
            <input type="hidden" name="id_gejala" value="<?= $current_question['id_gejala'] ?>">
            <input type="radio" name="nilai_gejala" value="1.0"> Sangat Yakin;
            <input type="radio" name="nilai_gejala" value="0.8"> Yakin;
            <input type="radio" name="nilai_gejala" value="0.6"> Cukup Yakin;
            <input type="radio" name="nilai_gejala" value="0.4"> Sedikit Yakin;
            <input type="radio" name="nilai_gejala" value="0.2"> Tidak Tahu;
            <input type="radio" name="nilai_gejala" value="0.0"> Tidak;
            <br>
            <button type="submit" name="submit">Selanjutnya</button>
        </form>
    <?php else : ?>
        <p>Tidak ada data gejala.</p>
    <?php endif; ?>
</body>

</html>
