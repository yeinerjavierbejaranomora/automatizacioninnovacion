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

    public function primeringreso(){
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
        var_dump($primerIngreso);die();
        if($primerIngreso):
            $fechaInicio = date('Y-m-d H:i:s');
            $registroMPV = 0;
            $primerId = $this->model->falatntesPrimerIngreso($offset)->fetch(PDO::FETCH_ASSOC)['id'];
            $ultimoRegistroId = 0;
            foreach($primerIngreso as $estudiante):
                $marcaIngreso = $estudiante['marca_ingreso'];
                $codBanner = $estudiante['homologante'];
                $programa = $estudiante['programa'];
                $periodo = substr($marcaIngreso,-2);
                $mallaCurricular = $this->model->baseAcademica($codBanner,$programa,$periodo);
                $insertMateriaPorVer = $this->model->insertMateriaPorVer($mallaCurricular);
                $registroMPV = $registroMPV + $insertMateriaPorVer;
                if(count($mallaCurricular) == $insertMateriaPorVer):
                    $updateEstudiantePC = $this->model->updateEstudiante($estudiante['id'],$codBanner);
                endif;
                $ultimoRegistroId = $estudiante['id'];
                $idBannerUltimoRegistro = $estudiante['homologante'];
            endforeach;
            $fechaFin = date('Y-m-d H:i:s');
            $acccion = 'Insert-PrimerIngreso';
            $tablaAfectada = 'materiasPorVer';
            $descripcion = 'Se realizo la insercion en la tabla materiasPorVer insertando las materias por ver del estudiante de primer ingreso, modificando el valor del campo materias_faltantes en la tabla estudiantes de NULL a "OK" en cada estudiante, iniciando en el id ' . $primerId . ' y terminando en el id ' . $ultimoRegistroId . ',insertando ' . $registroMPV . ' registros';
            $fecha = date('Y-m-d H:i:s');
            $insertarLogAplicacion = $this->model->insertarLogAplicacion($primerId,$ultimoRegistroId,$fechaInicio,$fechaFin,$acccion,$tablaAfectada,$descripcion);
            $insertIndiceCambio = $this->model->insertIndiceCambio($idBannerUltimoRegistro,$acccion,$descripcion,$fecha);
            echo $ultimoRegistroId."-".$registroMPV . "-Fecha Inicio: " . $fechaInicio . "Fecha Fin: " . $fechaFin . "<br>";
        else:
            echo "No hay estudiantes de primer ingreso <br>";die();
        endif;
    }
}