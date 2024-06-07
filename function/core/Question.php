<?php

namespace Core;
require_once 'database/database.php';
class Question
{
    private $id_gejala_user, $value_gejala_user;
    protected $id_penyakit, $nama_penyakit, $id_gejala, $gejala, $mb, $md;
    protected $cf_value, $CF_H_e;

    public function __construct($id_gejala_user, $value_gejala_user, $id_penyakit, $nama_penyakit, $id_gejala, $gejala, $mb, $md, $cf_value = null, $CF_H_e = null)
    {
        $this->id_gejala_user = $id_gejala_user;
        $this->value_gejala_user = $value_gejala_user;
        $this->id_penyakit = $id_penyakit;
        $this->nama_penyakit = $nama_penyakit;
        $this->id_gejala = $id_gejala;
        $this->gejala = $gejala;
        $this->mb = $mb;
        $this->md = $md;
        $this->cf_value = $cf_value;
        $this->CF_H_e = $CF_H_e;
    }

    public function hitungNilai()
    {
        if ($this->id_gejala_user == $this->id_gejala) {
            // Hitung nilai CF pakar
            $this->cf_value = $this->mb - $this->md;
            // Hitung nilai CF user 
            $this->CF_H_e = $this->cf_value * $this->value_gejala_user;
            return $this->CF_H_e;
        }
        return null;
    }

    public function getCFValue()
    {
        return $this->cf_value;
    }


}

class ShowData
{
    private $id_gejala_user, $value_gejala_user, $combined_cf;
    private $db;

    public function __construct($id_gejala_user = null, $value_gejala_user = null,  $combined_cf = null){
        $this->id_gejala_user = $id_gejala_user;
        $this->value_gejala_user = $value_gejala_user;  
        $this->combined_cf = $combined_cf;  

        // Inisialisasi database
        $this->db = new Database();

        // session_start();
        // // Periksa dan proses data dari sesi
        // $this->processSessionData();
    }

    // private function processSessionData()
    // {
    //     // Dapatkan daftar id_gejala dari sesi
    //     $id_gejala_penyakit = $_SESSION['id_gejala_penyakit'] ?? [];

    //     // Periksa apakah cf_values ada dalam sesi
    //     if (!isset($_SESSION['cf_values']) || empty($_SESSION['cf_values'])) {
    //         echo "Tidak ada data gejala yang diproses.";
    //         exit;
    //     }

    //     // Hitung nilai CF akhir
    //     $final_cf = array_reduce($_SESSION['cf_values'], function ($carry, $item) {
    //         return $carry + $item * (1 - $carry);
    //     }, 0);

    //     if (empty($id_gejala_penyakit)) {
    //         echo "<p>Tidak ada gejala yang diproses.</p>";
    //         session_destroy();
    //         exit;
    //     }

    //     $this->combined_cf = $final_cf;
    // }

    public function question_process()
    {
        $sql = "SELECT pg.id_penyakit, p.nama_penyakit, pg.id_gejala, g.gejala, g.mb, g.md
                FROM tb_penyakit_gejala pg
                JOIN tb_penyakit_jagung p ON pg.id_penyakit = p.id_penyakit
                JOIN tb_gejala_jagung g ON pg.id_gejala = g.id_gejala
                WHERE pg.id_gejala = ?";
        
        $stmt = $this->db->getconnection()->prepare($sql);
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
        if (count($cf_values) == 0) return 0;

        $this->combined_cf = array_shift($cf_values);
        foreach ($cf_values as $cf) {
            $this->combined_cf = $this->combined_cf + $cf * (1 - $this->combined_cf);
        }
        return $this->combined_cf;
    }

    public function getCombinedCF() {
        return $this->combined_cf;
    }

    public function getPenyakitByGejala()
    {
        $sql = "SELECT pg.id_penyakit, p.nama_penyakit
                FROM tb_penyakit_gejala pg
                JOIN tb_penyakit_jagung p ON pg.id_penyakit = p.id_penyakit
                WHERE pg.id_gejala = ?";
        
        $stmt = $this->db->getconnection()->prepare($sql);
        $stmt->bind_param("s", $this->id_gejala_user);
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

    // public function getPenyakitWithValues()
    // {
    //     // Ambil penyakit berdasarkan gejala
    //     $penyakit = $this->getPenyakitByGejala();

    //     // Periksa apakah cf_values ada dalam sesi
    //     if (!isset($_SESSION['cf_values']) || empty($_SESSION['cf_values'])) {
    //         echo "Tidak ada data gejala yang diproses.";
    //         exit;
    //     }

    //     // Buat array untuk menggabungkan penyakit dengan nilai CF
    //     $penyakit_with_values = [];

    //     // Gabungkan penyakit dengan nilai CF
    //     foreach ($penyakit as $key => $item) {
    //         $penyakit_with_values[] = [
    //             'id_penyakit' => $item['id_penyakit'],
    //             'nama_penyakit' => $item['nama_penyakit'],
    //             'cf_value' => $_SESSION['cf_values'][$key] ?? 0
    //         ];
    //     }

    //     return $penyakit_with_values;
    // }
}
