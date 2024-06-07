<?php

namespace Core;

require_once '../function/init.php';

$data_penyakit = new ShowData();
$penyakit_with_values = $data_penyakit->getPenyakitByGejala();

print_r($penyakit_with_values); // Tampilkan hasil

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