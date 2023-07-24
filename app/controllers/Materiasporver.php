<?php
class Materiasporver extends Controller{
    private $model;
    public function __construct()
    {
        $this->model = $this->model("MateriasPorVerModel");
    }

    public function periodo(){
        $fechaActual = date('Y-m-d');
        $mesActual = date('m');
        $mesActual = 06;
        $periodo = $this->model->getPeriodo();
        foreach($periodo as $value):
            $ciclo1 = explode('-',$value['fechaInicioCiclo1']);
            $ciclo2 = explode('-',$value['fechaInicioCiclo2']);
            if(in_array($mesActual,$ciclo1) || in_array($mesActual,$ciclo2)):
                var_dump("SI");die();
            else:
                var_dump("No");die();
            endif;
        endforeach;
        return $mesActual;
    }

    public function getEstudiantes(){
        $programado_ciclo1 = NULL;
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
        $marcaIngreso = array_map('intval', $marcaIngreso);
        $log = $this->model->logAplicacion('Insert-PrimerIngreso','materiasPorVer');
        $estudiantes = $this->model->numeroEstudiantes();
        var_dump($log->fetch(PDO::FETCH_ASSOC));die();
    }
}