<?php

class HistorialModel{

    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function save($fila){
        //var_dump($fila);die();
        try {
            var_dump($fila[0]);die();
        } catch (PDOException $e) {
            return false;
        }
    }
}