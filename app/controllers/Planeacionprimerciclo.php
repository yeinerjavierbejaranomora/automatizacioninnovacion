<?php
class Planeacionprimerciclo extends Controller{
    private $model;
    public function __construct()
    {
        $this->model = $this->model('PlaneacionPrimerCicloModel');
    }

    public function inicio(){
        echo "Planeacion Primer Ciclo";
    }
}