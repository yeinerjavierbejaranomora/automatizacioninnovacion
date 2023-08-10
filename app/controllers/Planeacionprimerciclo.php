<?php
class Planeacionprimerciclo extends Controller{
    private $model;
    public function __construct()
    {
        $this->model = $this->model('PlaneacionPrimerCicloModel');
    }

    public function inicio(){
        $log = $this->model->logAplicacion('Insert-PlaneacionPrimerCiclo', 'planeacion');
        if ($log->rowCount() == 0) :
            $offset = 0;
        else :
            $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
        endif;
        // $offset = 6013;
        $periodos = $this->model->periodos();
        $marcaIngreso = "";
        foreach ($periodos as $periodo) {
            $marcaIngreso .= (int)$periodo['periodos'] . ",";
        }
        $marcaIngreso = trim($marcaIngreso, ",");
        // var_dump($marcaIngreso);die();
        $estudiantes = $this->model->getEstudiantesNum($offset,$marcaIngreso);
        var_dump($estudiantes->rowCount());die();
        /*$limit = 500;
        $numEstudinates = ceil($estudiantes->rowCount()/$limit);
        for ($i=0; $i < $numEstudinates; $i++) { 
            //sleep(10);
            $this->primerciclo($limit,$marcaIngreso);
        }*/
    }
}