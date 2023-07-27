<?php
class ProgramarPrimerCicloModel{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function getPeriodo(){
        try {
            $consultaPeriodo = $this->db->connect()->prepare("SELECT * FROM `periodo`");
            $consultaPeriodo->execute();
            return $consultaPeriodo;
        } catch (PDOException $e) {
            return false;
        }
    }
}