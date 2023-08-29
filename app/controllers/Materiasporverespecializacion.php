<?php

class Materiasporverespecializacion extends Controller {
    private $model;
    public function __construct()
    {
        $this->model = $this->model('MateriasPorVerEspecializacionModel');
    }

    public function inicio(){
        $periodos = $this->model->periodos();
        $marcaIngreso = "";
        foreach ($periodos as $periodo) {
            $marcaIngreso .= (int)$periodo['periodos'] . ",";
        }
        $marcaIngreso = trim($marcaIngreso, ",");
        var_dump($marcaIngreso);die();
    }
}