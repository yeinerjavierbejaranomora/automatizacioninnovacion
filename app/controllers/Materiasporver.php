<?php
class Materiasporver extends Controller{
    private $model;
    public function __construct()
    {
        $this->model = $this->model("MateriasPorVerModel");
    }

    public function inicio(){
        $periodos = $this->model->periodos();
        $marcaIngreso = "";
        foreach ($periodos as $periodo) {
            $marcaIngreso .= (int)$periodo['periodos'] . ",";
        }
        $marcaIngreso = trim($marcaIngreso, ",");
        $log = $this->model->logAplicacion('Insert-PrimerIngreso','materiasPorVer');
        if($log->rowCount() == 0):
            $offset =0;
        else:
            $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
        endif;
        $numEstudiantesPrimerIngreso = $this->model->falatntesPrimerIngreso($offset,$marcaIngreso);
        if($numEstudiantesPrimerIngreso->rowCount() != 0):
            $this->primeringreso($marcaIngreso);
        else:
            //echo "No hay estudiantes de primer ingreso <br>";
        endif;
        $log = $this->model->logAplicacion('Insert-Transferente','materiasPorVer');
        if($log->rowCount() == 0):
            $offset =0;
        else:
            $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
        endif;
        $limit = 800;
        $numEstudiantesTransferentes = $this->model->faltantesTransferentesNum($offset,$marcaIngreso);
        $divEstudiantesTransferentes = ceil($numEstudiantesTransferentes->rowCount()/$limit);
        for ($i=0; $i < $divEstudiantesTransferentes; $i++) {             
            $this->transferentes($limit,$marcaIngreso);
        }
        var_dump($divEstudiantesTransferentes);die();
        var_dump($marcaIngreso);die();
    }


    public function primeringreso($marcaIngreso){
        
        $log = $this->model->logAplicacion('Insert-PrimerIngreso','materiasPorVer');
        if($log->rowCount() == 0):
            $offset =0;
        else:
            $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
        endif;
        $primerIngreso = $this->model->falatntesPrimerIngreso($offset,$marcaIngreso);
        //var_dump($primerIngreso->fetchAll());die();
        if($primerIngreso->rowCount() != false):
            $fechaInicio = date('Y-m-d H:i:s');
            $registroMPV = 0;
            $primerId = $this->model->falatntesPrimerIngreso($offset,$marcaIngreso)->fetch(PDO::FETCH_ASSOC)['id'];
            $ultimoRegistroId = 0;
            foreach($primerIngreso as $estudiante):
                $marcaIngreso = $estudiante['marca_ingreso'];
                $codBanner = $estudiante['homologante'];
                $programa = $estudiante['programa'];
                $periodo = substr($marcaIngreso,-2);
                $mallaCurricular = $this->model->baseAcademica($codBanner,$programa,$periodo,$marcaIngreso);
                //var_dump($mallaCurricular);die();
                $insertMateriaPorVer = $this->model->insertMateriaPorVer($mallaCurricular);
                $registroMPV = $registroMPV + $insertMateriaPorVer;
                if(count($mallaCurricular) == $insertMateriaPorVer):
                    $updateEstudiantePI = $this->model->updateEstudiante($estudiante['id'],$codBanner);
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

    public function transferentes($limit,$marcaIngreso){
        $log = $this->model->logAplicacion('Insert-Transferente','materiasPorVer');
        if($log->rowCount() == 0):
            $offset =0;
        else:
            $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
        endif;
        //$limit = 800;
        $transferentes = $this->model->faltantesTransferentes($offset,$limit,$marcaIngreso);
        if($transferentes->rowCount() != false):
            $fechaInicio = date('Y-m-d H:i:s');
            $registroMPV = 0;
            $primerId = $this->model->faltantesTransferentes($offset,$limit,$marcaIngreso)->fetch(PDO::FETCH_ASSOC)['id'];
            $ultimoRegistroId = 0;
            foreach($transferentes as $estudiante):
                //var_dump($estudiante);die();
                $marcaIngreso = $estudiante['marca_ingreso'];
                $codBanner = $estudiante['homologante'];
                $programa = $estudiante['programa'];
                $periodo = substr($marcaIngreso,-2);
                $mallaCurricular = $this->model->baseAcademica($codBanner,$programa,$periodo,$marcaIngreso);
                var_dump($mallaCurricular);die();
                $historial = $this->model->historial($codBanner);
                $diff = array_udiff($mallaCurricular, $historial, function($a, $b) {
                    return $a['codMateria'] <=> $b['codMateria'];
                });
                //$historialMoodle = $this->model->historialMoodle($codBanner);
                //var_dump(count($historialMoodle));die();
                /*if(count($historialMoodle) > 0 ):
                    $diffMoodle = array_udiff($diff, $historialMoodle, function($a, $b) {
                        return $a['codMateria'] <=> $b['codMateria'];
                    });
                    $insertMateriaPorVer = $this->model->insertMateriaPorVer($diffMoodle);
                    $registroMPV = $registroMPV + $insertMateriaPorVer;
                    if(count($diffMoodle) == $insertMateriaPorVer):
                        $updateEstudianteT = $this->model->updateEstudiante($estudiante['id'],$codBanner);
                    endif;
                else:*/
                    $insertMateriaPorVer = $this->model->insertMateriaPorVer($diff);
                    $registroMPV = $registroMPV + $insertMateriaPorVer;
                    if(count($diff) == $insertMateriaPorVer):
                        $updateEstudianteT = $this->model->updateEstudiante($estudiante['id'],$codBanner);
                    endif;
                //endif;
                $ultimoRegistroId = $estudiante['id'];
                $idBannerUltimoRegistro = $estudiante['homologante'];
            endforeach;
            $fechaFin = date('Y-m-d H:i:s');
            $acccion = 'Insert-Transferente';
            $tablaAfectada = 'materiasPorVer';
            $descripcion = 'Se realizo la insercion en la tabla materiasPorVer insertando las materias por ver del estudiante transferente, iniciando en el id ' . $primerId . ' y terminando en el id ' . $ultimoRegistroId . ',insertando ' . $registroMPV . ' registros';
            $fecha = date('Y-m-d H:i:s');
            $insertarLogAplicacion = $this->model->insertarLogAplicacion($primerId,$ultimoRegistroId,$fechaInicio,$fechaFin,$acccion,$tablaAfectada,$descripcion);
            $insertIndiceCambio = $this->model->insertIndiceCambio($idBannerUltimoRegistro,$acccion,$descripcion,$fecha);
            echo $ultimoRegistroId."-".$registroMPV . "-Fecha Inicio: " . $fechaInicio . "Fecha Fin: " . $fechaFin . "<br>";
        else:
            echo "No hay estudiantes TRANSFERENTES <br>";die();
        endif;
    }
    
    public function estudiantesantiguos(){
        $log = $this->model->logAplicacion('Insert-EstudinatesAntiguos','materiasPorVer');
        if($log->rowCount() == 0):
            $offset =0;
        else:
            $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
        endif;
        $totalEstudiantesAntiguos = $this->model->totalEstudiantes($offset);
        if($totalEstudiantesAntiguos == 0):
            echo "No hay estudiantes ANTIGUOS <br>";die();
        else:
            //var_dump($totalEstudiantesAntiguos);die();
            $limit = 1000;
            $numDivEstudiantes = ceil($totalEstudiantesAntiguos/$limit);
            for ($i=0; $i < $numDivEstudiantes; $i++) :
                $this->antiguos($offset,$limit);
            endfor;
        endif;
    }

    public function antiguos($offset,$limit){
        /*$log = $this->model->logAplicacion('Insert-EstudinatesAntiguos','materiasPorVer');
        if($log->rowCount() == 0):
            $offset =0;
        else:
            $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
        endif;
        $limit = 1000;*/
        $estudiantesAntiguos = $this->model->faltantesAntiguos($offset,$limit);
        if($estudiantesAntiguos->rowCount() != false):
            foreach($estudiantesAntiguos as $estudiante):
                //var_dump($estudiante);die();
                $fechaInicio = date('Y-m-d H:i:s');
                $primerId = $this->model->faltantesAntiguos($offset,$limit)->fetch(PDO::FETCH_ASSOC)['id'];
                $ultimoRegistroId = 0;

                $marcaIngreso = $estudiante['marca_ingreso'];
                $codBanner = $estudiante['homologante'];
                $tipoEstudiante = $estudiante['tipo_estudiante'];
                $programa = $estudiante['programa'];
                $periodo = substr($marcaIngreso,-2);

                $mallaCurricular = $this->model->baseAcademica($codBanner,$programa,$periodo);
                $historial = $this->model->historial($codBanner);
                //$historialMoodle = $this->model->historialMoodle($codBanner);
                $diff = array_udiff($mallaCurricular, $historial, function($a, $b) {
                    return $a['codMateria'] <=> $b['codMateria'];
                });
                /*if(count($historialMoodle) > 0 ):
                    //var_dump($diff);die();
                    $diff = array_udiff($diff, $historialMoodle, function($a, $b) {
                        return $a['codMateria'] <=> $b['codMateria'];
                    });
                    //var_dump($diff);die();
                endif;*/
                $cantidadDiff = count($diff);
                if(count($diff) > 0):
                    $insertMateriaPorVer = $this->model->insertMateriaPorVer($diff);
                    if (count($diff) == $insertMateriaPorVer) :
                        $updateEstudianteT = $this->model->updateEstudiante($estudiante['id'], $codBanner);
                    endif;
                    $ultimoRegistroId = $estudiante['id'];
                    $idBannerUltimoRegistro = $estudiante[ 'homologante'];
                    $fechaFin = date('Y-m-d H:i:s');
                    $acccion = 'Insert-EstudinatesAntiguos';
                    $tablaAfectada = 'materiasPorVer';
                    $descripcion = 'Se realizo la insercion en la tabla materiasPorVer insertando las materias por ver del estudiante de primer ingreso, iniciando en el id ' . $primerId . ' y terminando en el id ' . $ultimoRegistroId . '.';
                    $fecha = date('Y-m-d H:i:s');
                    $insertarLogAplicacion = $this->model->insertarLogAplicacion($primerId, $ultimoRegistroId, $fechaInicio, $fechaFin, $acccion, $tablaAfectada, $descripcion);
                    $insertIndiceCambio = $this->model->insertIndiceCambio($idBannerUltimoRegistro, $acccion, $descripcion, $fecha);
                    echo $ultimoRegistroId . "-" . "-Fecha Inicio: " . $fechaInicio . "Fecha Fin: " . $fechaFin . "<br>";
                else :
                    $mensajeAlerta = 'El estudiante con idBanner' . $codBanner . ' es estudiante antiguo y ya vio todo.';
                    $insertarAlertaTemprana = $this->model->insertarAlerta($codBanner, $tipoEstudiante, $mensajeAlerta);
                    $updateEstudianteEA = $this->model->upateEstuianteAntiguo($estudiante['id'],$codBanner);
                    echo "estudiante vio todo". $codBanner."<br>";
                endif;
            endforeach;
        else:
            echo "No hay estudiantes ANTIGUOS,ni PSEUDO INGRESO O REINGRESO <br>";
        endif;
    }

    
}