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
                        $codPeriodo = substr($periodo['periodos'], -2);
                        if ($codPeriodo == 41) :
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
                    var_dump($marcaIngreso);die();
                case 42:
                    $periodosEspecializacion = $this->model->periodosEspecializacion();
                    foreach ($periodosEspecializacion as $periodo) {
                        $codPeriodo = substr($periodo['periodos'], -2);
                        if ($codPeriodo >= 41 && $codPeriodo <= 42) :
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
                    var_dump($marcaIngreso);die();
                case 43:
                    $periodosEspecializacion = $this->model->periodosEspecializacion();
                    foreach ($periodosEspecializacion as $periodo) {
                        $codPeriodo = substr($periodo['periodos'], -2);
                        if ($codPeriodo >= 42 && $codPeriodo <= 43) :
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
                    var_dump($marcaIngreso);die();
                case 44:
                    $periodosEspecializacion = $this->model->periodosEspecializacion();
                    foreach ($periodosEspecializacion as $periodo) {
                        $codPeriodo = substr($periodo['periodos'], -2);
                        if ($codPeriodo >= 43 && $codPeriodo <= 44) :
                            $marcaIngreso .= (int)$periodo['periodos'] . ",";
                        endif;
                    }
                    $marcaIngreso = trim($marcaIngreso, ",");
                    $estudiantes = $this->model->getEstudiantesNum($offset, $marcaIngreso);
                    // var_dump($estudiantes->rowCount());die();
                    $limit = 500;
                    $numEstudinates = ceil($estudiantes->rowCount() / $limit);
                    for ($i = 0; $i < $numEstudinates; $i++) {
                        $this->primerCiclo($limit,$marcaIngreso);
                    }
                case 45:
                    $periodosEspecializacion = $this->model->periodosEspecializacion();
                    foreach ($periodosEspecializacion as $periodo) {
                        $codPeriodo = substr($periodo['periodos'], -2);
                        if ($codPeriodo >= 44 && $codPeriodo <= 45) :
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
                    var_dump($marcaIngreso);die();
                    break;
                
                default:
                    # code...
                    break;
            }
            
        else:
            echo " fuera de fecha";
        endif;
    }

    public function primerCiclo($limit,$marcaIngreso){
        $log = $this->model->logAplicacion('Insert-ProgramacionPrimerCicloEspecializacion', 'programacion');
        if ($log->rowCount() == 0) :
            $offset = 0;
        else :
            $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
        endif;

        $estudiantes = $this->model->getEstudiantes($offset,$marcaIngreso,$limit);
        if($estudiantes->rowCount() > 0):
            else:
            echo "No hay estudiantes de especialización para programar <br>";
        endif;
        var_dump($estudiantes->fetchAll());die();
    }

    public function segundoCiclo($limit,$marcaIngreso){
        $log = $this->model->logAplicacion('Insert-ProgramacionPrimerCicloEspecializacion', 'programacion');
        if ($log->rowCount() == 0) :
            $offset = 0;
        else :
            $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
        endif;

        $estudiantes = $this->model->getEstudiantes($offset,$marcaIngreso,$limit);
        if($estudiantes->rowCount() > 0):
            else:
            echo "No hay estudiantes de especialización para programar <br>";
        endif;
        var_dump($estudiantes->fetchAll());die();
    }
}
?>