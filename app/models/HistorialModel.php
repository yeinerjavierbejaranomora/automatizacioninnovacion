<?php

class HistorialModel{

    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function save($codBanner){
        var_dump($codBanner);die();
        try {
            var_dump($fila[2]);die();
        } catch (PDOException $e) {
            return false;
        }
    }
}