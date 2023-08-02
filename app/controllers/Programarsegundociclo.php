<?php
class Programarsegundociclo extends Controller{

    private $model;

    public function __construct()
    {
        $this->model = $this->model("ProgramarSegundoCicloModel");
    }

    public function inicio(){
        $periodos = $this->model->periodos();
        $marcaIngreso = "";
        foreach ($periodos as $periodo) {
            $marcaIngreso .= (int)$periodo['periodos'] . ",";
        }
        $marcaIngreso = trim($marcaIngreso, ",");

        $log = $this->model->logAplicacion('Insert-PlaneacionSegundoCiclo', 'planeacion');
        if ($log->rowCount() == 0) :
            $offset = 0;
        else :
            $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
        endif;
        $limit = 500;
        $estudiantes = $this->model->getEstudiantes($offset,$marcaIngreso,$limit);
        if($estudiantes->rowCount() > 0):
            foreach ($estudiantes as $key => $estudiante) {
                $idHomologante = $estudiante['id'];
                $codHomologante = $estudiante['homologante'];
                $programaHomologante = $estudiante['programa'];
                var_dump($idHomologante,$codHomologante,$programaHomologante);die();
                # code...
            }
        else:
            echo "No hay estudiantes de segundo ciclo para programar <br>";
        endif;
    }
}