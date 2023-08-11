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
        var_dump($marcaIngreso);die();
        $log = $this->model->logAplicacion('Insert-ProgramacionSegundoCiclo', 'programacion');
        if ($log->rowCount() == 0) :
            $offset = 0;
        else :
            $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
        endif;
        $limit = 2;
        $estudiantes = $this->model->getEstudiantesNum($offset,$marcaIngreso);
        $numEstudiantes = $estudiantes->rowCount();
        $divEstudiantes = ceil($numEstudiantes/$limit);
        var_dump($numEstudiantes);die();
        for ($i=0; $i < $divEstudiantes; $i++) {
            $this->segundociclo($marcaIngreso,$limit);
        }
    }


    public function segundociclo($marcaIngreso,$limit){

        $log = $this->model->logAplicacion('Insert-ProgramacionSegundoCiclo', 'programacion');
        if ($log->rowCount() == 0) :
            $offset = 0;
        else :
            $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
        endif;
        // $limit = 20;
        $estudiantes = $this->model->getEstudiantes($offset,$marcaIngreso,$limit);
        var_dump($estudiantes->fetchAll());die();
        if($estudiantes->rowCount() > 0):
            foreach ($estudiantes as $key => $estudiante) {
                $programaHomologante = $estudiante['programa'];
                if ($programaHomologante != 'PPSV') :

                    //var_dump($estudiante);die();
                    /*$fechaInicio = date('Y-m-d H:i:s');
                    $primerId = $estudiante['id'];
                    $ultimoRegistroId = 0;
                    $idHomologante = $estudiante['id'];
                    $codHomologante = $estudiante['homologante'];
                    $programaHomologante = $estudiante['programa'];
                    $tipoEstudiante = $estudiante['tipo_estudiante'];
                    $materiasProgramadas = $this->model->materiasProgramadas($codHomologante, $programaHomologante);
                    $materias_programadas = '';
                    foreach ($materiasProgramadas as $materia) :
                        $codmateria = $materia['codMateria'];
                        $materias_programadas = $materias_programadas . "'" . $codmateria . "',";
                    endforeach;
                    $materias_programadas = substr($materias_programadas, 0, -1);
                    $materias_programadas = ($materias_programadas == '') ? "'n-a'" : $materias_programadas;
                    $materiasMoodleConsulta = $this->model->materiasMoodle($codHomologante);
                    $materias_moodle = "";
                    if ($materiasMoodleConsulta->rowCount() == 0) :
                        $materias_moodle = '""';
                    else :
                        foreach ($materiasMoodleConsulta as $materia) {
                            $materias_moodle .= '"' . $materia['codigomateria'] . '",';
                        }
                    endif;
                    $materias_moodle = trim($materias_moodle, ",");
                    //var_dump($materias_programadas,"--",$materias_moodle);die();
                    $consultaMateriasPorVer = $this->model->materiasPorVer($codHomologante, $programaHomologante, $materias_programadas, $materias_moodle);
                    //var_dump($consultaMateriasPorVer->fetchAll());die();
                    $numeroCreditos = $this->model->getCreditosplaneados($codHomologante);
                    $numeroCreditos = $numeroCreditos->rowCount() == 0 ? 0 : $numeroCreditos->fetch(PDO::FETCH_ASSOC)['CreditosPlaneados'];
                    //var_dump($numeroCreditos);die();
                    $numeroMateriasPorVer = $consultaMateriasPorVer->rowCount();

                    $ruta = $estudiante['bolsa'];
                    if ($ruta != '') :
                        $ruta = 1;
                    else :
                        $ruta = 0;
                    endif;
                    $tipoEstudiante = $estudiante['tipo_estudiante'];

                    switch ($tipoEstudiante) {
                        case str_contains($tipoEstudiante, 'TRANSFERENTE'):
                            $tipoEstudiante = 'TRANSFERENTE';
                            break;
                        case str_contains($tipoEstudiante, 'ESTUDIANTE ANTIGUO'):
                            $tipoEstudiante = 'ESTUDIANTE ANTIGUO';
                            break;
                        case str_contains($tipoEstudiante, 'PRIMER INGRESO'):
                            $tipoEstudiante = 'PRIMER INGRESO';
                            break;
                        case str_contains($tipoEstudiante, 'PSEUDO ACTIVOS'):
                            $tipoEstudiante = 'ESTUDIANTE ANTIGUO';
                            break;
                        case str_contains($tipoEstudiante, 'REINGRESO'):
                            $tipoEstudiante = 'ESTUDIANTE ANTIGUO';
                            break;
                        case str_contains($tipoEstudiante, 'INGRESO SINGULAR'):
                            $tipoEstudiante = 'PRIMER INGRESO';
                            break;

                        default:
                            # code...
                            break;
                    }
                    $cicloReglaNegocio = 2;
                    $reglasNegocioConsulta = $this->model->getReglasNegocio($programaHomologante, $ruta, $tipoEstudiante, $cicloReglaNegocio);
                    $reglasNegocio = $reglasNegocioConsulta->fetch(PDO::FETCH_ASSOC);

                    $numeroCreditosPermitidos = $reglasNegocio['creditos'];
                    $numeroMateriasPermitidos = (int)$reglasNegocio['materiasPermitidas'];
                    $orden2 = 1;

                    if ($numeroMateriasPorVer == 0) :
                    /*$mensajeAlerta = 'El estudiante con idBanner' . $codHomologante . ' no tiene materias por ver, segundo ciclo.';
                    $insertarAlertaTemprana = $this->model->insertarAlerta($codHomologante, $tipoEstudiante, $mensajeAlerta);
                    $updateEstudinate = $this->model->updateEstudinate($idHomologante,$codHomologante);
                    $ultimoRegistroId = $estudiante['id'];
                    $idBannerUltimoRegistro = $estudiante['homologante'];
                    $fechaFin = date('Y-m-d H:i:s');
                    $acccion = 'Insert-ProgramacionSegundoCiclo';
                    $tablaAfectada = 'programacion';
                    $descripcion = 'Se realizo la insercion en la tabla programacion insertando las materias del segundo ciclo del estudiante ' . $codHomologante . ', iniciando en el id ' . $primerId . ' y terminando en el id ' . $ultimoRegistroId . '.';
                    $fecha = date('Y-m-d H:i:s');
                    $insertarLogAplicacion = $this->model->insertarLogAplicacion($primerId, $ultimoRegistroId, $fechaInicio, $fechaFin, $acccion, $tablaAfectada, $descripcion);
                    //$insertIndiceCambio = $this->model->insertIndiceCambio($idBannerUltimoRegistro, $acccion, $descripcion, $fecha);
                    echo "Sin  Materias : " . $codHomologante . "<br />";*/
                    /*else :
                        //var_dump($consultaMateriasPorVer->fetchAll());die();
                        foreach ($consultaMateriasPorVer as $materia) :
                            $codBanner = $materia['codBanner'];
                            $codMateria = $materia['codMateria'];
                            $creditoMateria = $materia['creditos'];
                            $ciclo = $materia['ciclo'];
                            /*$consultaPrerequisitos = $this->model->consultaPrerequisitos($codMateria,$programaHomologante);
                        $fetchPrerequisistos = $consultaPrerequisitos->fetch(PDO::FETCH_ASSOC);
                        $prerequisitos =  $fetchPrerequisistos['prerequisito'];*/
                            /*$prerequisitos = $materia['prerequisito'];

                            $numeroCreditosTemp = $numeroCreditos + $creditoMateria;
                            if ($numeroCreditosTemp >= $numeroCreditosPermitidos) :
                                break;
                            endif;
                            //var_dump($codMateria,$prerequisitos,$numeroCreditosTemp,$numeroCreditosPermitidos,$ciclo);die();
                            if ($prerequisitos == '' && $numeroCreditosTemp <= $numeroCreditosPermitidos && $ciclo == 2) :
                                $consultaestaProgramacion = $this->model->estaProgramacion($codMateria, $codBanner);
                                $fetchestaProgramacion = $consultaestaProgramacion->fetchAll();
                                $codBanner = $codBanner;
                                $planeada = $fetchestaProgramacion['codMateria'];

                                if ($planeada == '' && $numeroCreditos < $numeroCreditosPermitidos && $numeroCreditosTemp <= $numeroCreditosPermitidos) :
                                    $numeroCreditos = $numeroCreditos + $creditoMateria;
                                    $semestre = 1;
                                    $programada = '';
                                    $insertPlaneada = $this->model->insertProgramacion($codBanner, $codMateria, $orden2, $semestre, $programada, $programaHomologante);
                                //echo $insertPlaneada . "<br />";
                                endif;
                            else :
                                $prerequisitos = trim($prerequisitos, '"');
                                $prerequisitos = '"' . $prerequisitos . '"';
                                $consultaestaProgramacion = $this->model->estaProgramacionPrerequisitos($prerequisitos, $codBanner);
                                $fetchestaProgramacion = $consultaestaProgramacion->fetch(PDO::FETCH_ASSOC);
                                $preprogramado = $fetchestaProgramacion['codMateria'];
                                $consultaEstaPorVer = $this->model->estaPorVer($prerequisitos, $codBanner);
                                $fetchEstaPorVer = $consultaEstaPorVer->fetch(PDO::FETCH_ASSOC);
                                $estaPorVer = $fetchEstaPorVer['codMateria'];
                                if ($preprogramado == '' && $estaPorVer == '' && $numeroCreditosTemp <= $numeroCreditosPermitidos && $ciclo == 2 && $numeroCreditos < $numeroCreditosPermitidos) :
                                    $numeroCreditos = $numeroCreditos + $creditoMateria;
                                    $semestre = 1;
                                    $programada = '';
                                    $insertPlaneada = $this->model->insertProgramacion($codBanner, $codMateria, $orden2, $semestre, $programada, $programaHomologante);
                                //echo $insertPlaneada . "<br />";
                                endif;
                            endif;
                        endforeach;
                    // die();
                    /*$orden2++;
                    $updateEstudinate = $this->model->updateEstudinate($idHomologante,$codHomologante);
                    $ultimoRegistroId = $estudiante['id'];
                    $idBannerUltimoRegistro = $estudiante['homologante'];
                    $fechaFin = date('Y-m-d H:i:s');
                    $acccion = 'Insert-ProgramacionSegundoCiclo';
                    $tablaAfectada = 'programacion';
                    $descripcion = 'Se realizo la insercion en la tabla programacion insertando las materias del segundo ciclo del estudiante ' . $codBanner . ', iniciando en el id ' . $primerId . ' y terminando en el id ' . $ultimoRegistroId . '.';
                    $fecha = date('Y-m-d H:i:s');
                    $insertarLogAplicacion = $this->model->insertarLogAplicacion($primerId, $ultimoRegistroId, $fechaInicio, $fechaFin, $acccion, $tablaAfectada, $descripcion);
                    //$insertIndiceCambio = $this->model->insertIndiceCambio($idBannerUltimoRegistro, $acccion, $descripcion, $fecha);
                    echo $ultimoRegistroId . "-Fecha Inicio: " . $fechaInicio . "Fecha Fin: " . $fechaFin . "<br>";
                    // echo "Planeación realizada para : " . $codBanner . " y " . $codMateria . "-".$fechaInicio."-".$fechaFin. "<br />";
                    endif;*/
                else :
                    var_dump($estudiante);die();
                    //$this->programarOrden($estudiante);
                endif;
            }
        else :
            echo "No hay estudiantes de segundo ciclo para programar <br>";
        endif;
    }

    public function programarOrden($estudiante){
        var_dump($estudiante);die();
        $fechaInicio = date('Y-m-d H:i:s');
        $primerId = $estudiante['id'];
        $ultimoRegistroId = 0;
        $idHomologante = $estudiante['id'];
        $codHomologante = $estudiante['homologante'];
        $programaHomologante = $estudiante['programa'];
        $tipoEstudiante = $estudiante['tipo_estudiante'];
        $materiasProgramadas = $this->model->materiasProgramadas($codHomologante, $programaHomologante);
        $materias_programadas = '';
        foreach ($materiasProgramadas as $materia) :
            $codmateria = $materia['codMateria'];
            $materias_programadas = $materias_programadas . "'" . $codmateria . "',";
        endforeach;
        $materias_programadas = substr($materias_programadas, 0, -1);
        $materias_programadas = ($materias_programadas == '') ? "'n-a'" : $materias_programadas;
        $materiasMoodleConsulta = $this->model->materiasMoodle($codHomologante);
        $materias_moodle = "";
        if ($materiasMoodleConsulta->rowCount() == 0) :
            $materias_moodle = '""';
        else :
            foreach ($materiasMoodleConsulta as $materia) {
                $materias_moodle .= '"' . $materia['codigomateria'] . '",';
            }
        endif;
        $materias_moodle = trim($materias_moodle, ",");
        var_dump($materias_moodle,$materias_programadas);die();
        $consultaMateriasPorVer = $this->model->materiasPorVer($codHomologante,$programaHomologante,$materias_programadas);
        $numeroCreditos = $this->model->getCreditosplaneados($codHomologante);
        $numeroCreditos = $numeroCreditos->rowCount() == 0 ? 0 : $numeroCreditos->fetch(PDO::FETCH_ASSOC)['CreditosPlaneados'];
        $numeroMateriasPorVer = $consultaMateriasPorVer->rowCount();
        $ruta = $estudiante['bolsa'];
        if ($ruta != '') :
            $ruta = 1;
        else :
            $ruta = 0;
        endif;
        $tipoEstudiante = $estudiante['tipo_estudiante'];

        switch ($tipoEstudiante) {
            case str_contains($tipoEstudiante, 'TRANSFERENTE'):
                $tipoEstudiante = 'TRANSFERENTE';
                break;
            case str_contains($tipoEstudiante, 'ESTUDIANTE ANTIGUO'):
                $tipoEstudiante = 'ESTUDIANTE ANTIGUO';
                break;
            case str_contains($tipoEstudiante, 'PRIMER INGRESO'):
                $tipoEstudiante = 'PRIMER INGRESO';
                break;
            case str_contains($tipoEstudiante, 'PSEUDO ACTIVOS'):
                $tipoEstudiante = 'ESTUDIANTE ANTIGUO';
                break;
            case str_contains($tipoEstudiante, 'REINGRESO'):
                $tipoEstudiante = 'ESTUDIANTE ANTIGUO';
                break;
            case str_contains($tipoEstudiante, 'INGRESO SINGULAR'):
                $tipoEstudiante = 'PRIMER INGRESO';
                break;

            default:
                # code...
                break;
        }
        $cicloReglaNegocio = 2;
        $reglasNegocioConsulta = $this->model->getReglasNegocio($programaHomologante, $ruta, $tipoEstudiante, $cicloReglaNegocio);
        $reglasNegocio = $reglasNegocioConsulta->fetch(PDO::FETCH_ASSOC);
        $numeroCreditosPermitidos = $reglasNegocio['creditos'];
        $numeroMateriasPermitidos = (int)$reglasNegocio['materiasPermitidas'];
        $orden2 = 1;
        if ($numeroMateriasPorVer == 0) :
            $mensajeAlerta = 'El estudiante con idBanner' . $codHomologante . ', perteneciente al programa '.$programaHomologante.', no tiene materias por ver, segundo ciclo.';
            $insertarAlertaTemprana = $this->model->insertarAlerta($codHomologante, $tipoEstudiante, $mensajeAlerta);
            $updateEstudinate = $this->model->updateEstudinate($idHomologante, $codHomologante);
            $ultimoRegistroId = $estudiante['id'];
            $idBannerUltimoRegistro = $estudiante['homologante'];
            $fechaFin = date('Y-m-d H:i:s');
            $acccion = 'Insert-ProgramacionSegundoCiclo';
            $tablaAfectada = 'programacion';
            $descripcion = 'Se realizo la insercion en la tabla programacion insertando las materias del segundo ciclo del estudiante ' . $codHomologante . ', iniciando en el id ' . $primerId . ' y terminando en el id ' . $ultimoRegistroId . '.';
            $fecha = date('Y-m-d H:i:s');
            $insertarLogAplicacion = $this->model->insertarLogAplicacion($primerId, $ultimoRegistroId, $fechaInicio, $fechaFin, $acccion, $tablaAfectada, $descripcion);
            //$insertIndiceCambio = $this->model->insertIndiceCambio($idBannerUltimoRegistro, $acccion, $descripcion, $fecha);
            echo "Sin  Materias : " . $codHomologante . "<br />";
        else :
            foreach ($consultaMateriasPorVer as $materia) :
                $codBanner = $materia['codBanner'];
                $codMateria = $materia['codMateria'];
                $creditoMateria = $materia['creditos'];
                $ciclo = $materia['ciclo'];
                $prerequisitos = $materia['prerequisito'];
                $numeroCreditosTemp = $numeroCreditos + $creditoMateria;
                if ($numeroCreditosTemp>=$numeroCreditosPermitidos) :
                    break;
                endif;
                if($prerequisitos == '' && $numeroCreditosTemp<=$numeroCreditosPermitidos):
                    $consultaestaProgramacion = $this->model->estaProgramacion($codMateria,$codBanner);
                    $fetchestaProgramacion = $consultaestaProgramacion->fetchAll();
                    $codBanner = $codBanner;
                    $planeada = $fetchestaProgramacion['codMateria'];
                    if($planeada == '' && $numeroCreditos < $numeroCreditosPermitidos && $numeroCreditosTemp<=$numeroCreditosPermitidos):
                        $numeroCreditos = $numeroCreditos + $creditoMateria;
                        $semestre = 1;
                        $programada = '';
                        $insertPlaneada = $this->model->insertProgramacion($codBanner,$codMateria,$orden2,$semestre,$programada,$programaHomologante);
                        //echo $insertPlaneada . "<br />";
                    endif;
                else:
                    $prerequisitos = trim($prerequisitos,'"');
                    $prerequisitos = '"' . $prerequisitos . '"';
                    $consultaestaProgramacion = $this->model->estaProgramacionPrerequisitos($prerequisitos, $codBanner);
                    $fetchestaProgramacion = $consultaestaProgramacion->fetch(PDO::FETCH_ASSOC);
                    $preprogramado = $fetchestaProgramacion['codMateria'];
                    $consultaEstaPorVer = $this->model->estaPorVer($prerequisitos, $codBanner);
                    $fetchEstaPorVer = $consultaEstaPorVer->fetch(PDO::FETCH_ASSOC);
                    $estaPorVer = $fetchEstaPorVer['codMateria'];
                    if($preprogramado == '' && $estaPorVer == '' && $numeroCreditosTemp<=$numeroCreditosPermitidos &&  $numeroCreditos < $numeroCreditosPermitidos):
                        $numeroCreditos = $numeroCreditos + $creditoMateria;
                        $semestre = 1;
                        $programada = '';
                        $insertPlaneada = $this->model->insertProgramacion($codBanner,$codMateria,$orden2,$semestre,$programada,$programaHomologante);
                        //echo $insertPlaneada . "<br />";
                    endif;
                endif;
            endforeach;
            /*$orden2++;
            $updateEstudinate = $this->model->updateEstudinate($idHomologante, $codHomologante);
            $ultimoRegistroId = $estudiante['id'];
            $idBannerUltimoRegistro = $estudiante['homologante'];
            $fechaFin = date('Y-m-d H:i:s');
            $acccion = 'Insert-ProgramacionSegundoCiclo';
            $tablaAfectada = 'programacion';
            $descripcion = 'Se realizo la insercion en la tabla programacion insertando las materias del segundo ciclo del estudiante ' . $codBanner . ', perteneciente al programa '.$programaHomologante.', iniciando en el id ' . $primerId . ' y terminando en el id ' . $ultimoRegistroId . '.';
            $fecha = date('Y-m-d H:i:s');
            $insertarLogAplicacion = $this->model->insertarLogAplicacion($primerId, $ultimoRegistroId, $fechaInicio, $fechaFin, $acccion, $tablaAfectada, $descripcion);
            //$insertIndiceCambio = $this->model->insertIndiceCambio($idBannerUltimoRegistro, $acccion, $descripcion, $fecha);
            echo $ultimoRegistroId . "-Fecha Inicio: " . $fechaInicio . "Fecha Fin: " . $fechaFin . "<br>";
                    // echo "Planeación realizada para : " . $codBanner . " y " . $codMateria . "-".$fechaInicio."-".$fechaFin. "<br />";*/
        endif;
    }
}