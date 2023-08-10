<?php
class Planeacionsegundociclo extends Controller{
    private $model;
    public function __construct(){
        $this->model= $this->model('PlaneacionSegundoCicloModel');
    }

    public function inicio(){
        echo "Planeacion Segundo Ciclo ";
    }
}