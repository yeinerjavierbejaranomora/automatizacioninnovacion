<?php
class Materiasporver extends Controller{
    private $model;
    public function __construct()
    {
        $this->model = $this->model("MateriasPorVerModel");
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

    public function getEstudiantes(){
        /*$programado_ciclo1 = NULL;
        $periodo = $this->periodo();
        var_dump($periodo);die();
        $marcaIngreso = "";
        foreach ($periodo as $key => $value) {
            $marcaIngreso .= (int)$value->periodos . ",";
        }

        // para procesasr las marcas de ingreso en los periodos
        $marcaIngreso = trim($marcaIngreso, ",");
        // Dividir la cadena en elementos individuales
        $marcaIngreso = explode(",", $marcaIngreso);
        // Convertir cada elemento en un número
        $marcaIngreso = array_map('intval', $marcaIngreso);*/
        $log = $this->model->logAplicacion('Insert-PrimerIngreso','materiasPorVer');
        //$estudiantes = $this->model->numeroEstudiantes();
        if(!$log):
            $offset =0;
        else:
            $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
        endif;
        $primerIngreso = $this->model->falatntesPrimerIngreso($offset);
        if($primerIngreso):
            $fechaInicio = date('Y-m-d H:i:s');
            $registroMPV = 0;
            $primerId = $primerIngreso->fetch(PDO::FETCH_ASSOC)['id'];
            $ultimoRegistroId = 0;
            var_dump($primerId);die();
            echo "Hay estudiantes de primer ingreso <br>";die();
        else:
            echo "No hay estudiantes de primer ingreso <br>";die();
        endif;
    }
}