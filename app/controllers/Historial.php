<?php
class Historial extends Controller{
    private $model;
    public function __construct()
    {
        $this->model = $this->model("HistorialModel");
    }

    public function inicio() {
        $file = "../public/historialAcademico14-08.csv";
        $openfile = fopen($file, "r");
        $cont = fread($openfile, filesize($file));
        echo $cont;
    }
}