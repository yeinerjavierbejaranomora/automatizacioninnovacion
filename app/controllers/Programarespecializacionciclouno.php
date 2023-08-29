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
            var_dump($estudiantes->fetchAll());die();
        else:
            echo " fuera de fecha";
        endif;
    }
}
?>