<?php
class Programarprimerciclo extends Controller{

    private $model;

    public function __construct()
    {
        $this->model = $this->model("ProgramarPrimerCiclo");
    }

    public function primerciclo(){
        echo "Hola";
    }
}