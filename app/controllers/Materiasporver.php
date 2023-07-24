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