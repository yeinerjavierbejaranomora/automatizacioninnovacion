<?php
class Programarprimerciclo extends Controller{

    private $model;

    public function __construct()
    {
        $this->model = $this->model("ProgramarPrimerCicloModel");
    }

    /*public function periodo(){
        $fechaActual = date('Y-m-d');
        $mesActual = date('m');
        $mesActual = 06;
        $periodo = $this->model->getPeriodo();
        foreach($periodo as $value):
            $ciclo1 = explode('-',$value['fechaInicioCiclo1']);
            $ciclo2 = explode('-',$value['fechaInicioCiclo2']);
            if(in_array($mesActual,$ciclo1) || in_array($mesActual,$ciclo2)):
                var_dump("SI");
            else:
                var_dump("No");
            endif;
        endforeach;
        die();
        return $mesActual;
    }*/

    public function primerciclo(){
        $periodos = $this->model->periodos();
        $marcaIngreso = "";
        foreach ($periodos as $periodo) {
            $marcaIngreso .= (int)$periodo['periodos'] . ",";
        }
        var_dump($marcaIngreso);die();
    }
}