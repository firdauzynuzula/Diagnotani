<?php

namespace Core;

use Core\Question;

require_once 'database/database.php';

class ShowData
{
    private $id_gejala_user, $value_gejala_user, $combined_cf;

    public function __construct($id_gejala_user = null, $value_gejala_user = null, $combined_cf = null)
    {
        $this->id_gejala_user = $id_gejala_user;
        $this->value_gejala_user = $value_gejala_user;
        $this->combined_cf = $combined_cf;
    }

    public function question_process()
    {
        // Connection to the database
        $db = new Database();

        $sql = "SELECT pg.id_penyakit, p.nama_penyakit, pg.id_gejala, g.gejala, g.mb, g.md
                FROM tb_penyakit_gejala pg
                JOIN tb_penyakit_jagung p ON pg.id_penyakit = p.id_penyakit
                JOIN tb_gejala_jagung g ON pg.id_gejala = g.id_gejala
                WHERE pg.id_gejala = ?";

        $stmt = $db->getconnection()->prepare($sql);
        $stmt->bind_param("s", $this->id_gejala_user);
        $stmt->execute();
        $result = $stmt->get_result();

        // Array to hold data
        $data = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        $cf_values = [];

        // Process each item
        foreach ($data as $item) {
            $id_penyakit = $item['id_penyakit'];
            $nama_penyakit = $item['nama_penyakit'];
            $id_gejala = $item['id_gejala'];
            $gejala = $item['gejala'];
            $mb = $item['mb'];
            $md = $item['md'];

            // Instantiate the Question class
            $question = new Question($this->id_gejala_user, $this->value_gejala_user, $id_penyakit, $nama_penyakit, $id_gejala, $gejala, $mb, $md, null, null);
            $cf_user_value = $question->hitungNilai();

            if ($cf_user_value !== null) {
                $cf_values[] = $cf_user_value;
            }
        }

        // Combine CF values
        $this->combined_cf = $this->combineCF($cf_values);
        return $this->combined_cf;
    }

    public function combineCF($cf_values)
    {
        // if (count($cf_values) == 0) return 0;

        $this->combined_cf = array_shift($cf_values);
        foreach ($cf_values as $cf) {
            $this->combined_cf = $this->combined_cf + $cf * (1 - $this->combined_cf);
        }
        return $this->combined_cf;
    }

    public function getPenyakitByGejala()
    {
        // Connection to the database
        $db = new Database();

        // Dapatkan daftar id_gejala dari sesi
        $id_gejala_penyakit = $_SESSION['id_gejala_penyakit'] ?? [];

        // Periksa apakah cf_values ada dalam sesi
        if (!isset($_SESSION['cf_values']) || empty($_SESSION['cf_values'])) {
            echo "Tidak ada data gejala yang diproses.";
            exit;
        }

        // Use prepared statement to avoid SQL injection
        $sql = "SELECT pg.id_penyakit, p.nama_penyakit
                FROM tb_penyakit_gejala pg
                JOIN tb_penyakit_jagung p ON pg.id_penyakit = p.id_penyakit
                WHERE pg.id_gejala = ?";

        $stmt = $db->getconnection()->prepare($sql);
        $stmt->bind_param("s", $id_gejala_penyakit);
        $stmt->execute();
        $result = $stmt->get_result();

        // Array to hold data
        $penyakit = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $penyakit[] = $row;
            }
        }

        return $penyakit;
    }
}
