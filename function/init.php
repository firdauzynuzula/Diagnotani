<?php
require_once __DIR__ . '/Core/database/Database.php';
require_once __DIR__ . '/Core/Account.php';
require_once __DIR__ . '/Core/Question.php';
require_once __DIR__ . '/Core/ShowData.php';
require_once __DIR__ . '/Core/DataGejala.php';
require_once __DIR__ . '/Core/DataPenyakitGejalaJagung.php';
require_once __DIR__ . '/Core/DataPenyakitJagung.php';

use Core\Database;
use Core\Account;
use Core\Question;
use Core\ShowData;
use Core\DataGejala;

// Contoh penggunaan kelas
$datagejala = new DataGejala();
$datagejala->dataGejala();
$gejala = $datagejala->getGejala();

// var_dump($gejala); // Menampilkan seluruh data untuk debugging
