<?php

class HistorialModel{

    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function save($codBanner,$nombre,$origen,$codPrograma,$programa,$codMateria,$nombreMateria,$nota){
        var_dump($codBanner,$nombre,$origen,$codPrograma,$programa,$codMateria,$nombreMateria,$nota);die();
        try {
            var_dump($fila[2]);die();
        } catch (PDOException $e) {
            return false;
        }
    }
}