<?php 

namespace core;

class DataPenyakitJagung 
{
    private $penyakit = [];
    
    public function __construct($penyakit = []) {
        $this->penyakit = $penyakit;
    }

    public function penyakitJagung() 
    {
        //inisialisasi database
        $db = new database();
        $sql_penyakit = "SELECT * FROM tb_penyakit_jagung";
        $result = $db->getconnection()->query($sql_penyakit);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id = $row['id_penyakit'];
                $nama_penyakit = $row['nama_penyakit'];

                // Cek apakah id_penyakit sudah ada dalam array untuk menghindari duplikasi
                if(!isset($this->penyakit[$id])) {
                    $this->penyakit[$id] = [
                        'id_penyakit' => $id,
                        'nama_penyakit' => $nama_penyakit
                    ];
                }
            }
        }
    }

    public function getPenyakit()
    {
        return $this->penyakit;
    }
}
