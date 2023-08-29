<?php

class Materiasporverespecializacion extends Controller {
    private $model;
    public function __construct()
    {
        $this->model = $this->model('MateriasPorVerEspecializacionModel');
    }

    public function inicio(){
        echo "Hola matrias especializacion";
    }
}