<?php
namespace Core;
// require 'database/database.php';

class ShowData
{
    private $id_gejala_user, $value_gejala_user;

    public function __construct($id_gejala_user, $value_gejala_user){
        $this->id_gejala_user = $id_gejala_user;
        $this->value_gejala_user = $value_gejala_user;  
    }

    public function question_process()
    {
        // Connection to the database
        $db = new database();

        // Use prepared statement to avoid SQL injection
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

        if ($result->num_rows > 0) 
        {
            while ($row = $result->fetch_assoc()) 
            {
                $data[] = $row;
            }
        }

        $cf_values = []; // Initialize CF values array

        // Process each item
        foreach ($data as $item)
        {
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
        $combined_cf = $this->combineCF($cf_values);

        return $combined_cf;
    }
    
    private function combineCF($cf_values)
    {
        if (count($cf_values) == 0) return 0;

        $combined_cf = array_shift($cf_values);
        foreach ($cf_values as $cf)
        {
            $combined_cf = $combined_cf + $cf * (1 - $combined_cf);
        }
        return $combined_cf;
    }
}

class Question
{
    private $id_gejala_user, $value_gejala_user;
    protected $id_penyakit, $nama_penyakit, $id_gejala, $gejala, $mb, $md;
    protected $cf_value, $CF_H_e;

    public function __construct($id_gejala_user, $value_gejala_user, $id_penyakit, $nama_penyakit, $id_gejala, $gejala, $mb, $md, $cf_value, $CF_H_e)
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

    public function hitungNilai() {
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

// Usage example
// $showData = new ShowData('G001', 0.8); 
$combined_cf = $showData->question_process();
echo 'Combined CF: ' . ($combined_cf * 100) . '%';

var_dump($combined_cf);
