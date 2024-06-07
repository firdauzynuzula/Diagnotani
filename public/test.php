<?php

namespace Core;

session_start();

require_once '../function/init.php';

use Core\DataGejala;
use Core\Question;
use Core\ShowData;

// Inisialisasi objek DataGejala
$datagejala = new DataGejala();

// Periksa apakah pertanyaan saat ini sudah ada dalam sesi
if (!isset($_SESSION['current_question'])) {
    // Jika belum ada, ambil pertanyaan pertama dari objek DataGejala
    $datagejala->dataGejala();
    $gejala = $datagejala->getGejala();

    // Simpan semua gejala dalam sesi untuk akses berikutnya
    $_SESSION['gejala'] = $gejala;

    // Periksa apakah ada pertanyaan
    if (!empty($gejala)) {
        // Jika ada, simpan pertanyaan pertama dalam sesi
        $_SESSION['current_question_index'] = 0;
        $_SESSION['current_question'] = $gejala[0];
    }
}

// Ambil pertanyaan yang akan ditampilkan
$current_question_index = $_SESSION['current_question_index'] ?? 0;
$gejala = $_SESSION['gejala'] ?? [];
$current_question = $gejala[$current_question_index] ?? null;

// Tangani pengiriman jawaban jika formulir disubmit
if (isset($_POST['submit'])) {
    $id_gejala_user = $_POST['id_gejala'];
    $value_gejala_user = $_POST['nilai_gejala'];

    // Pastikan cf_values disimpan dalam sesi dan adalah array
    if (!isset($_SESSION['cf_values'])) {
        $_SESSION['cf_values'] = [];
    }

    if (!isset($_SESSION['id_gejala_penyakit'])) {
        $_SESSION['id_gejala_penyakit'] = [];
    }

    // Simpan id_gejala ke dalam array dalam sesi
    $_SESSION['id_gejala_penyakit'][] = $id_gejala_user;

    // Instantiate ShowData and process the question
    $sql_data = new ShowData($id_gejala_user, $value_gejala_user);
    $combined_cf = $sql_data->question_process();

    // Gabungkan cf_values yang baru dengan yang ada di sesi
    $_SESSION['cf_values'][] = $combined_cf;
    var_dump($_SESSION['cf_values']);

    // Pindah ke pertanyaan berikutnya
    $current_question_index++;
    if ($current_question_index < count($gejala)) {
        $_SESSION['current_question_index'] = $current_question_index;
        $_SESSION['current_question'] = $gejala[$current_question_index];
    } else {
        // Jika tidak ada pertanyaan berikutnya, hapus sesi pertanyaan saat ini
        unset($_SESSION['current_question']);
        unset($_SESSION['current_question_index']);
    }

    // Redirect to result page if all questions have been answered
    if (!isset($_SESSION['current_question'])) {
        header('Location: result.php');
        exit;
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
    <?php if (!empty($gejala)) : ?>
    <?php endif; ?>

    <?php if (isset($current_question)) : ?>
        <form action="" method="post">
            <p>Apakah <?= $current_question['gejala'] ?>?</p>
            <input type="hidden" name="id_gejala" value="<?= $current_question['id_gejala'] ?>">
            <input type="radio" name="nilai_gejala" value="1.0"> Sangat Yakin
            <input type="radio" name="nilai_gejala" value="0.8"> Yakin
            <input type="radio" name="nilai_gejala" value="0.6"> Cukup Yakin
            <input type="radio" name="nilai_gejala" value="0.4"> Sedikit Yakin
            <input type="radio" name="nilai_gejala" value="0.2"> Tidak Tahu
            <input type="radio" name="nilai_gejala" value="0.0"> Tidak
            <br>
            <button type="submit" name="submit">Selanjutnya</button>
        </form>
    <?php else : ?>
        <p>Tidak ada data gejala.</p>
    <?php endif; ?>
</body>

</html>