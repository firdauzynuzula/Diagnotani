<?php 

namespace core;

class DataPenyakitGejalaJagung
{
    private $data_penyakit = [];
    
    public function __construct($data_penyakit = []) {
        $this->data_penyakit = $data_penyakit;
    }

    public function DataPenyakitGejalaJagung() 
    {
        //insialisasi database
        $db = new database();
        $sql_penyakit = "SELECT * FROM tb_penyakit_gejala";
        $result = $db->getconnection()->query($sql_penyakit);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id_gejala_penyakit = $row['id_gejala_penyakit'];
                $id_penyakit = $row['id_penyakit'];
                $id_gejala = $row['id_gejala'];

            if(!isset($this->data_penyakit[$id_gejala_penyakit])) {
                $this->data_penyakit[$id_gejala_penyakit] = [
                    'id_gejala_penyakit' => $id_gejala_penyakit, 
                    'id_penyakit' => $id_penyakit,
                    'id_gejala' => $id_gejala
                ];
            }
        }
        }
    }

    public function getPenyakitGejala()
    {
        return $this->data_penyakit;
    }
}