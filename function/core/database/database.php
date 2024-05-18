<?php 

class database
{
    private $host = "localhost"; // Host database
    private $db_name = "db_diagnotani"; // Nama database
    private $username = "root"; // Username database
    private $password = ""; // Password database
    private $conn;

    // Method untuk membuat koneksi ke database
    public function getConnection()
    {
        $this->conn = null; // Inisialisasi koneksi sebagai null

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            // Set charset koneksi ke utf8
            $this->conn->set_charset("utf8");
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

        return $this->conn; // Mengembalikan objek koneksi
    }

    public function getLastUserID()
    {
        $this->conn = $this->getConnection();
        $result = $this->conn->query("SELECT id_user FROM tb_account ORDER BY id_user DESC LIMIT 1");
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lastID = $row['id_user'];
            return intval(substr($lastID, 3)); // Mengembalikan hanya bagian numerik dari ID
        } else {
            return 0; // Jika tidak ada entri, mulai dari 0
        }
    }
}