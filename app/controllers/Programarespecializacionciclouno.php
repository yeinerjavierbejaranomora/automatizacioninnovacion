<?php
class Programarespecializacionciclouno extends Controller{

    private $model;
    public function __construct()
    {
        $this->model = $this->model('ProgramarEspecializacionCicloUnoModel');
    }

    public function inicio(){
        $fechaActual = date('Y-m-d');
        $dias_a_restar = 7;
        $periodos = $this->model->periodos();
        // $fechaInicioCiclo1 = $periodos->fetch(PDO::FETCH_ASSOC)['fechaInicioCiclo1'];
        $fechaInicioCiclo1 = '2023-08-30';
        /*echo $fechaInicioCiclo1,"<br>";
        echo date("Y-m-d",strtotime($fechaInicioCiclo1."- 1 week"));*/
        $fechaInicioProgramacion = date("Y-m-d",strtotime($fechaInicioCiclo1."- 1 week"));
        $marcaIngreso = "";
        foreach ($periodos as $periodo) {
            $codPeriodo = substr($periodo['periodos'],-2);
            if($codPeriodo >= 41 && $codPeriodo <=45):
                $marcaIngreso .= (int)$periodo['periodos'] . ",";
            endif;
        }
        $marcaIngreso = trim($marcaIngreso, ",");
        // var_dump($marcaIngreso);
        $codPeriodo = substr($marcaIngreso,-2);
        $marcaIngreso = "";
        // var_dump($marcaIngreso);die();
        
        if($fechaActual > $fechaInicioProgramacion && $fechaActual <= $fechaInicioCiclo1):
            //echo "detro de la fecha";die();
            
            $log = $this->model->logAplicacion('Insert-ProgramacionPrimerCicloEspecializacion', 'programacion');
            if ($log->rowCount() == 0) :
                $offset = 0;
            else :
                $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
            endif;
            switch ($codPeriodo) {
                case 41:
                    $periodosEspecializacion = $this->model->periodosEspecializacion();
                    foreach ($periodosEspecializacion as $periodo) {
                        $codPeriodo2 = substr($periodo['periodos'], -2);
                        if ($codPeriodo2 == 41) :
                            $marcaIngreso .= (int)$periodo['periodos'] . ",";
                        endif;
                    }
                    $marcaIngreso = trim($marcaIngreso, ",");
                    $estudiantes = $this->model->getEstudiantesNum($offset, $marcaIngreso);
                    $limit = 500;
                    $numEstudinates = ceil($estudiantes->rowCount() / $limit);
                    for ($i = 0; $i < $numEstudinates; $i++) {
                        //sleep(10);
                        //$this->primerciclo($limit,$marcaIngreso);
                    }
                case 42:
                    $periodosEspecializacion = $this->model->periodosEspecializacion();
                    foreach ($periodosEspecializacion as $periodo) {
                        $codPeriodo2 = substr($periodo['periodos'], -2);
                        if ($codPeriodo2 >= 41 && $codPeriodo2 <= 42) :
                            $marcaIngreso .= (int)$periodo['periodos'] . ",";
                        endif;
                    }
                    $marcaIngreso = trim($marcaIngreso, ",");
                    $estudiantes = $this->model->getEstudiantesNum($offset, $marcaIngreso);
                    $limit = 500;
                    $numEstudinates = ceil($estudiantes->rowCount() / $limit);
                    for ($i = 0; $i < $numEstudinates; $i++) {
                        //sleep(10);
                        //$this->primerciclo($limit,$marcaIngreso);
                    }
                case 43:
                    $periodosEspecializacion = $this->model->periodosEspecializacion();
                    foreach ($periodosEspecializacion as $periodo) {
                        $codPeriodo2 = substr($periodo['periodos'], -2);
                        if ($codPeriodo2 >= 42 && $codPeriodo2 <= 43) :
                            $marcaIngreso .= (int)$periodo['periodos'] . ",";
                        endif;
                    }
                    $marcaIngreso = trim($marcaIngreso, ",");
                    $estudiantes = $this->model->getEstudiantesNum($offset, $marcaIngreso);
                    $limit = 500;
                    $numEstudinates = ceil($estudiantes->rowCount() / $limit);
                    for ($i = 0; $i < $numEstudinates; $i++) {
                        //sleep(10);
                        //$this->primerciclo($limit,$marcaIngreso);
                    }
                case 44:
                    $periodosEspecializacion = $this->model->periodosEspecializacion();
                    foreach ($periodosEspecializacion as $periodo) {
                        $codPeriodo2 = substr($periodo['periodos'], -2);
                        if ($codPeriodo2 >= 43 && $codPeriodo2 <= 44) :
                            $marcaIngreso .= (int)$periodo['periodos'] . ",";
                        endif;
                    }
                    $marcaIngreso = trim($marcaIngreso, ",");
                    $estudiantes = $this->model->getEstudiantesNum($offset, $marcaIngreso);
                    // var_dump($estudiantes->rowCount());die();
                    $limit = 500;
                    $numEstudinates = ceil($estudiantes->rowCount() / $limit);
                    for ($i = 0; $i < $numEstudinates; $i++) {
                        $this->segundoCiclo($limit,$marcaIngreso,$codPeriodo);
                    }
                case 45:
                    $periodosEspecializacion = $this->model->periodosEspecializacion();
                    foreach ($periodosEspecializacion as $periodo) {
                        $codPeriodo2 = substr($periodo['periodos'], -2);
                        if ($codPeriodo2 >= 44 && $codPeriodo2 <= 45) :
                            $marcaIngreso .= (int)$periodo['periodos'] . ",";
                        endif;
                    }
                    $marcaIngreso = trim($marcaIngreso, ",");
                    $estudiantes = $this->model->getEstudiantesNum($offset, $marcaIngreso);
                    var_dump($estudiantes->rowCount());die();
                    $limit = 500;
                    $numEstudinates = ceil($estudiantes->rowCount() / $limit);
                    for ($i = 0; $i < $numEstudinates; $i++) {
                        //sleep(10);
                        //$this->primerciclo($limit,$marcaIngreso);
                    }
                    break;
                
                default:
                    # code...
                    break;
            }
            
        else:
            echo " fuera de fecha";
        endif;
    }

    public function primerCiclo($limit,$marcaIngreso,$codPeriodo){
        $log = $this->model->logAplicacion('Insert-ProgramacionPrimerCicloEspecializacion', 'programacion');
        if ($log->rowCount() == 0) :
            $offset = 0;
        else :
            $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
        endif;

        $estudiantes = $this->model->getEstudiantes($offset,$marcaIngreso,$limit);
        if($estudiantes->rowCount() > 0):
            foreach ($estudiantes as $estudiante) :
                $idEstudiante = $estudiante['id'];
                $codigoBanner = $estudiante['homologante'];
                $ciclo = [1, 12];
                $marca_ingreso = $estudiante['marca_ingreso'];
                $programa = $estudiante['programa'];
                if(substr($marca_ingreso,-2) < $codPeriodo):
                    //echo "202343";
                    $ciclo = 2;
                    $materiasPorVer = $this->model->materiasPorVer($codigoBanner, $programa,$ciclo);
                    var_dump($materiasPorVer->fetchAll());die();
                else:
                    //echo "202344";
                    $ciclo = 2 .",". 12;
                    $materiasPorVer = $this->model->materiasPorVer($codigoBanner, $programa,$ciclo);
                    var_dump($materiasPorVer->fetchAll());die();
                endif;
                die();
            endforeach;
            else:
            echo "No hay estudiantes de especialización para programar <br>";
        endif;
        var_dump($estudiantes->fetchAll());die();
    }

    public function segundoCiclo($limit,$marcaIngreso,$codPeriodo){
        $log = $this->model->logAplicacion('Insert-ProgramacionPrimerCicloEspecializacion', 'programacion');
        if ($log->rowCount() == 0) :
            $offset = 0;
        else :
            $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
        endif;

        $estudiantes = $this->model->getEstudiantes($offset,$marcaIngreso,$limit);
        if($estudiantes->rowCount() > 0):
            foreach ($estudiantes as $estudiante) :
                $idEstudiante = $estudiante['id'];
                $codigoBanner = $estudiante['homologante'];
                $marca_ingreso = $estudiante['marca_ingreso'];
                $programa = $estudiante['programa'];
                if(substr($marca_ingreso,-2) < $codPeriodo):
                    //echo "202343";
                    $ciclo = 2;
                    $materiasPorVer = $this->model->materiasPorVer($codigoBanner, $programa,$ciclo);
                    var_dump($materiasPorVer->fetchAll());die();
                else:
                    //echo "202344";
                    $ciclo = 2 .",". 12;
                    $materiasPorVer = $this->model->materiasPorVer($codigoBanner, $programa,$ciclo);
                    var_dump($materiasPorVer->fetchAll());die();
                endif;
                    die();
            endforeach;
            else:
            echo "No hay estudiantes de especialización para programar <br>";
        endif;
        var_dump($estudiantes->fetchAll());die();
    }
}
?>