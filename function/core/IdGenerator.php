<?php
require_once '/database/database.php';
class IDGeneratorAccount
{
    private $lastID;

    public function __construct($lastID = 0)
    {
        $this->lastID = $lastID;
    }

    public function generateID()
    {
        $this->lastID++;
        return "USR" . str_pad($this->lastID, 3, "0", STR_PAD_LEFT);
    }

}

