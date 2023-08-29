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
            var_dump(substr($periodo,2));
            $marcaIngreso .= (int)$periodo['periodos'] . ",";
        }
        die();
        $marcaIngreso = trim($marcaIngreso, ",");
        $log = $this->model->logAplicacion('Insert-especializacion','materiasPorVer');
        if($log->rowCount() == 0):
            $offset =0;
        else:
            $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
        endif;
        $estudiantesEspecializaciones = $this->model->estudiantesEspecializacion($offset,$marcaIngreso);
        var_dump($estudiantesEspecializaciones);die();
    }
}