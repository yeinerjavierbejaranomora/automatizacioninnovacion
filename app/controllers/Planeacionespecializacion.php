<?php

class Planeacionespecializacion extends Controller{

    private $model;

    public function __construct()
    {
        $this->model = $this->model('PlaneacionEspecializacionModel');
    }

    public function inicio(){
        echo "Planeacion especializacion";
    }
}