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

