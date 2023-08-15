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
            foreach($fila as $row):
                var_dump($row);
            endforeach;
        } catch (PDOException $e) {
            return false;
        }
    }
}