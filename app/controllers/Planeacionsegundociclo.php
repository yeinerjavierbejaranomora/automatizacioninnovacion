<?php
class Planeacionsegundociclo extends Controller{
    private $model;
    public function __construct(){
        $this->model= $this->model('PlaneacionSegundoCicloModel');
    }


    public function inicio(){
        $periodos = $this->model->periodos();
        $marcaIngreso = "";
        foreach ($periodos as $periodo) {
            $marcaIngreso .= (int)$periodo['periodos'] . ",";
        }
        $marcaIngreso = trim($marcaIngreso, ",");
        // var_dump($marcaIngreso);die();
        $log = $this->model->logAplicacion('Insert-PlaneacionSegundoCiclo', 'planeacion');
        if ($log->rowCount() == 0) :
            $offset = 0;
        else :
            $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
        endif;
        $limit = 500;
        $estudiantes = $this->model->getEstudiantesNum($offset,$marcaIngreso);
        $numEstudiantes = $estudiantes->rowCount();
        $divEstudiantes = ceil($numEstudiantes/$limit);
        var_dump($numEstudiantes);die();
        for ($i=0; $i < $divEstudiantes; $i++) {
            $this->segundociclo($marcaIngreso,$limit);
        }
    }
}