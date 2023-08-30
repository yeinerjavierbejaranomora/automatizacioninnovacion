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
        var_dump($marcaIngreso);
        $codPeriodo = substr($marcaIngreso,-2);
        $periodosEspecializacion = $this->model->periodosEspecializacion();
        foreach ($periodosEspecializacion as $periodo) {
            $codPeriodo = substr($periodo['periodos'],-2);
            if($codPeriodo >= 41 && $codPeriodo <=45):
                $marcaIngreso .= (int)$periodo['periodos'] . ",";
            endif;
        }
        var_dump($marcaIngreso);die();
        switch ($codPeriodo) {
            case 41:
                var_dump("ciclo 1, 41");die();
            case 42:
                var_dump("ciclo 2, 41 y 42");die();
            case 43:
                var_dump("ciclo 1, 42 y 43");die();
            case 44:
                var_dump("ciclo 2, 43 y 44");die();
            case 45:
                var_dump("ciclo 1, 45 y 44, y solo ciclo 2, 45");die();
                break;
            
            default:
                # code...
                break;
        }
        if($fechaActual > $fechaInicioProgramacion && $fechaActual <= $fechaInicioCiclo1):
            //echo "detro de la fecha";die();
            $log = $this->model->logAplicacion('Insert-ProgramacionPrimerCicloEspecializacion', 'programacion');
            if ($log->rowCount() == 0) :
                $offset = 0;
            else :
                $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
            endif;
            //var_dump($offset);die();
            $estudiantes = $this->model->getEstudiantesNum($offset,$marcaIngreso);
            $limit = 500;
            $numEstudinates = ceil($estudiantes->rowCount()/$limit);
            for ($i=0; $i < $numEstudinates; $i++) { 
                //sleep(10);
                //$this->primerciclo($limit,$marcaIngreso);
            }
        else:
            echo " fuera de fecha";
        endif;
    }
}
?>