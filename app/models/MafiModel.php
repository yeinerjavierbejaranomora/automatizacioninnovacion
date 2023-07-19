<?php

class MafiModel{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    function dataMafi(){
        try {
            $consulta = $this->db->connect()->prepare('SELECT * FROM `datosMafi`');
            $consulta->execute();
            return $consulta;
        } catch (PDOException $e) {
            return false;
        }
    }
}