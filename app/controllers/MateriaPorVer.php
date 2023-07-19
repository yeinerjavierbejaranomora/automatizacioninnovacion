<?php
class MateriasPorVer extends Controller{
    private $model;
    public function __construct()
    {
        $this->model = $this->model("MateriasPorVerModel");
    }

    public function getEstudiantes(){
        $log = $this->model->logAplicacion('Insert-PrimerIngreso','materiasPorVer');
        var_dump($log->fetch(PDO::FETCH_ASSOC));die();
    }
}