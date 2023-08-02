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
                $tipoEstudiante = $estudiante['tipo_estudiante'];
                $materiasPlaneadas = $this->model->materiasPlaneadas($codHomologante,$programaHomologante);
                $materias_planeadas='';
                foreach($materiasPlaneadas as $materia):
                    $codmateria= $materia['codMateria'];
                    $materias_planeadas = $materias_planeadas . "'" . $codmateria . "',";
                endforeach;
                $materias_planeadas = substr($materias_planeadas, 0, -1);
	            $materias_planeadas = ($materias_planeadas=='') ? "'n-a'" : $materias_planeadas;
                $consultaMateriasPorVer = $this->model->materiasPorVer($codHomologante,$programaHomologante,$materias_planeadas);
                $numeroCreditos = $this->model->getCreditosplaneados($codHomologante);
                $numeroCreditos = $numeroCreditos->rowCount() == 0 ? 0 : $numeroCreditos->fetch(PDO::FETCH_ASSOC)['CreditosPlaneados'];
                $numeroMateriasPorVer = $consultaMateriasPorVer->rowCount();
                $orden2=1;

                if($numeroMateriasPorVer == 0):
                    var_dump($codHomologante);die();
                    $mensajeAlerta = 'El estudiante con idBanner' . $codHomologante . ' no tiene materias por ver.';
                    $insertarAlertaTemprana = $this->model->insertarAlerta($codHomologante, $tipoEstudiante, $mensajeAlerta);
                    $updateEstudinate = $this->model->updateEstudinate($idHomologante,$codHomologante);
                    echo "Sin  Materias : " . $codHomologante . "<br />";
                else:
                    //echo "Con  Materias : " . $codHomologante . "<br />";
                endif;
                //var_dump($numeroMateriasPorVer);die();
                # code...
            }
        else:
            echo "No hay estudiantes de segundo ciclo para programar <br>";
        endif;
    }
}