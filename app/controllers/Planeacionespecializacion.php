<?php

class Planeacionespecializacion extends Controller{

    private $model;

    public function __construct()
    {
        $this->model = $this->model('PlaneacionEspecializacionModel');
    }

    public function inicio(){
        $periodos = $this->model->periodosEspecializacion();
        $marcaIngreso = "";
        foreach ($periodos as $periodo) {
            $codPeriodo = substr($periodo['periodos'],-2);
            if($codPeriodo >= 41 && $codPeriodo <=45):
                $marcaIngreso .= (int)$periodo['periodos'] . ",";
            endif;
        }
        $marcaIngreso = trim($marcaIngreso, ",");
        var_dump($marcaIngreso);die();
        $codPeriodo = substr($marcaIngreso,-2);
        $marcaIngreso = "";
        switch ($codPeriodo) {
            case 41:
                $log = $this->model->logAplicacion('Insert-planeacionPrimerCicloEspecializacion', 'planeacion');
                if ($log->rowCount() == 0) :
                    $offset = 0;
                else :
                    $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
                endif;
                // var_dump($offset);die();
                $periodosEspecializacion = $this->model->periodosEspecializacion();
                foreach ($periodosEspecializacion as $periodo) {
                    $codPeriodo2 = substr($periodo['periodos'], -2);
                    if ($codPeriodo2 == 41) :
                        $marcaIngreso .= (int)$periodo['periodos'] . ",";
                    endif;
                }
                $marcaIngreso = trim($marcaIngreso, ",");
                $estudiantes = $this->model->getEstudiantesNum($offset, $marcaIngreso);
                if ($estudiantes->rowCount() > 0) :
                    $limit = 500;
                    $numEstudinates = ceil($estudiantes->rowCount() / $limit);
                    for ($i = 0; $i < $numEstudinates; $i++) {
                        //sleep(10);
                        $this->primerciclo($limit, $marcaIngreso, $codPeriodo);
                    }
                else :
                    echo "No hay Estudiantes por planeacion especializacion";
                endif;
                break;
            case 42:
                $log = $this->model->logAplicacion('Insert-planeacionSegundoCicloEspecializacion', 'planeacion');
                if ($log->rowCount() == 0) :
                    $offset = 0;
                else :
                    $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
                endif;
                // var_dump($offset);die();
                $periodosEspecializacion = $this->model->periodosEspecializacion();
                foreach ($periodosEspecializacion as $periodo) {
                    $codPeriodo2 = substr($periodo['periodos'], -2);
                    if ($codPeriodo2 >= 41 && $codPeriodo2 <= 42) :
                        $marcaIngreso .= (int)$periodo['periodos'] . ",";
                    endif;
                }
                $marcaIngreso = trim($marcaIngreso, ",");
                $estudiantes = $this->model->getEstudiantesNum($offset, $marcaIngreso);
                if ($estudiantes->rowCount() > 0) :
                    $limit = 500;
                    $numEstudinates = ceil($estudiantes->rowCount() / $limit);
                    for ($i = 0; $i < $numEstudinates; $i++) {
                        //sleep(10);
                        $this->segundoCiclo($limit, $marcaIngreso, $codPeriodo);
                    }
                else :
                    echo "No hay Estudiantes por planeacion especializacion";
                endif;
                break;
            case 43:
                $log = $this->model->logAplicacion('Insert-planeacionPrimerCicloEspecializacion', 'planeacion');
                if ($log->rowCount() == 0) :
                    $offset = 0;
                else :
                    $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
                endif;
                // var_dump($offset);die();
                $periodosEspecializacion = $this->model->periodosEspecializacion();
                foreach ($periodosEspecializacion as $periodo) {
                    $codPeriodo2 = substr($periodo['periodos'], -2);
                    if ($codPeriodo2 >= 42 && $codPeriodo2 <= 43) :
                        $marcaIngreso .= (int)$periodo['periodos'] . ",";
                    endif;
                }
                $marcaIngreso = trim($marcaIngreso, ",");
                $estudiantes = $this->model->getEstudiantesNum($offset, $marcaIngreso);
                if ($estudiantes->rowCount() > 0) :
                    $limit = 500;
                    $numEstudinates = ceil($estudiantes->rowCount() / $limit);
                    for ($i = 0; $i < $numEstudinates; $i++) {
                        //sleep(10);
                        $this->primerciclo($limit, $marcaIngreso, $codPeriodo);
                    }
                else :
                    echo "No hay Estudiantes por planeacion especializacion";
                endif;
                break;
            case 44:
                $log = $this->model->logAplicacion('Insert-planeacionSegundoCicloEspecializacion', 'planeacion');
                if ($log->rowCount() == 0) :
                    $offset = 0;
                else :
                    $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
                endif;
                //var_dump($offset);die();
                $periodosEspecializacion = $this->model->periodosEspecializacion();
                foreach ($periodosEspecializacion as $periodo) {
                    $codPeriodo2 = substr($periodo['periodos'], -2);
                    if ($codPeriodo2 >= 43 && $codPeriodo2 <= 44) :
                        $marcaIngreso .= (int)$periodo['periodos'] . ",";
                    endif;
                }
                $marcaIngreso = trim($marcaIngreso, ",");
                $estudiantes = $this->model->getEstudiantesNum($offset, $marcaIngreso);
                if ($estudiantes->rowCount() > 0) :
                    $limit = 500;
                    $numEstudinates = ceil($estudiantes->rowCount() / $limit);
                    for ($i = 0; $i < $numEstudinates; $i++) {
                        $this->segundoCiclo($limit, $marcaIngreso, $codPeriodo);
                    }
                else :
                    echo "No hay Estudiantes por planeacion especializacion";
                endif;
                break;
            case 45:
                $log = $this->model->logAplicacion('Insert-planeacionPrimerCicloEspecializacion', 'planeacion');
                if ($log->rowCount() == 0) :
                    $offset = 0;
                else :
                    $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
                endif;
                // var_dump($offset);die();
                $periodosEspecializacion = $this->model->periodosEspecializacion();
                foreach ($periodosEspecializacion as $periodo) {
                    $codPeriodo2 = substr($periodo['periodos'], -2);
                    if ($codPeriodo2 >= 44 && $codPeriodo2 <= 45) :
                        $marcaIngreso .= (int)$periodo['periodos'] . ",";
                    endif;
                }
                $marcaIngreso = trim($marcaIngreso, ",");
                $estudiantes = $this->model->getEstudiantesNum($offset, $marcaIngreso);
                if ($estudiantes->rowCount() > 0) :
                    $limit = 500;
                    $numEstudinates = ceil($estudiantes->rowCount() / $limit);
                    for ($i = 0; $i < $numEstudinates; $i++) {
                        //sleep(10);
                        $this->primerciclo($limit, $marcaIngreso, $codPeriodo);
                    }
                else :
                    echo "No hay Estudiantes por planeacion especializacion";
                endif;
                break;

            default:
                # code...
                break;
        }
    }

    public function primerCiclo($limit,$marcaIngreso,$codPeriodo){
        $log = $this->model->logAplicacion('Insert-PlaneacionPrimerCicloEspecializacion', 'planeacion');
        if ($log->rowCount() == 0) :
            $offset = 0;
        else :
            $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
        endif;

        $estudiantes = $this->model->getEstudiantes($offset,$marcaIngreso,$limit);
        if($estudiantes->rowCount() > 0):
            foreach ($estudiantes as $estudiante) :
                //var_dump($estudiante);die();
                $idEstudiante = $estudiante['id'];
                $codigoBanner = $estudiante['homologante'];
                $marca_ingreso = $estudiante['marca_ingreso'];
                $programa = $estudiante['programa'];
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
                $consultaSemestre = $this->model->consultaSemestre($codigoBanner, $programa);
                $semestre = $consultaSemestre->fetch(PDO::FETCH_ASSOC)['semestre'];

                if (substr($marca_ingreso, -2) < $codPeriodo) :
                    //echo "202343";
                    $ciclo = 1 . "," . 12;
                    $materiasPorVer = $this->model->materiasPorVer($codigoBanner, $programa, $ciclo, $semestre);
                    $orden = 1;
                    foreach ($materiasPorVer as $materia) :
                        $fechaInicio = date('Y-m-d H:i:s');
                        $primerId = $estudiante['id'];
                        $ultimoRegistroId = 0;
                        $codBanner = $materia['codBanner'];
                        $codMateria = $materia['codMateria'];
                        $creditoMateria = $materia['creditos'];
                        $ciclo = $materia['ciclo'];
                        $semestre = $materia['semestre'];
                        if ($ciclo == 12) :
                            /**creo alerta de materia de ciclo completo*/
                            $orden--;
                                $tipoAlerta = 'Materia ciclo completo';
                                $mensajeAlerta = 'El estudiante con idBanner ' . $codigoBanner . ', No se le puede programar la materia ' . $materia['codMateria'] . ' ya que es de ciclo completo, y el estudiante es antiguo y se le esta programando primer ciclo';
                                $insertarAlertaTemprana = $this->model->insertarAlerta($codigoBanner,$marca_ingreso,$programa,$tipoAlerta, $tipoEstudiante, $mensajeAlerta);
                            else :
                                /**programo las materias insertando en planeacion */
                            $programada = '';
                            $insertPlaneada = $this->model->insertarPlaneacion($codBanner, $codMateria, $orden, $semestre, $programada, $programa, $marca_ingreso);
                        endif;
                        $orden++;
                    endforeach;
                    $ultimoRegistroId = $estudiante['id'];
                    $idBannerUltimoRegistro = $estudiante['homologante'];
                    $fechaFin = date('Y-m-d H:i:s');
                    $acccion = 'Insert-PlaneacionPrimerCicloEspecializacion';
                    $tablaAfectada = 'planeacion';
                    $descripcion = 'Se realizo la insercion en la tabla planeacion insertando las materias del primer ciclo del estudiante ' . $codigoBanner . ', iniciando en el id ' . $primerId . ' y terminando en el id ' . $ultimoRegistroId . '.';
                    $fecha = date('Y-m-d H:i:s');
                    $insertarLogAplicacion = $this->model->insertarLogAplicacion($primerId, $ultimoRegistroId, $fechaInicio, $fechaFin, $acccion, $tablaAfectada, $descripcion);
                    echo $ultimoRegistroId . "-Fecha Inicio: " . $fechaInicio . "Fecha Fin: " . $fechaFin . "<br>";
                else :
                    //echo "202344";
                    if ($semestre == NULL) :
                        $semestre = 1;
                    endif;
                    $ciclo = 1 . "," . 12;
                    $materiasPorVer = $this->model->materiasPorVer($codigoBanner, $programa, $ciclo, $semestre);
                    //var_dump($materiasPorVer->fetchAll());die();
                    $orden = 1;
                    foreach ($materiasPorVer as $materia) :
                        $fechaInicio = date('Y-m-d H:i:s');
                        $primerId = $estudiante['id'];
                        $ultimoRegistroId = 0;
                        $codBanner = $materia['codBanner'];
                        $codMateria = $materia['codMateria'];
                        $creditoMateria = $materia['creditos'];
                        $ciclo = $materia['ciclo'];
                        $semestre = $materia['semestre'];
                        $programada = '';
                        $insertPlaneada = $this->model->insertarPlaneacion($codBanner, $codMateria, $orden, $semestre, $programada, $programa, $marca_ingreso);
                        $orden++;
                    endforeach;
                    $ultimoRegistroId = $estudiante['id'];
                    $idBannerUltimoRegistro = $estudiante['homologante'];
                    $fechaFin = date('Y-m-d H:i:s');
                    $acccion = 'Insert-PlaneacionPrimerCicloEspecializacion';
                    $tablaAfectada = 'planeacion';
                    $descripcion = 'Se realizo la insercion en la tabla planeacion insertando las materias del primer ciclo del estudiante ' . $codigoBanner . ', iniciando en el id ' . $primerId . ' y terminando en el id ' . $ultimoRegistroId . '.';
                    $fecha = date('Y-m-d H:i:s');
                    $insertarLogAplicacion = $this->model->insertarLogAplicacion($primerId, $ultimoRegistroId, $fechaInicio, $fechaFin, $acccion, $tablaAfectada, $descripcion);
                    echo $ultimoRegistroId . "-Fecha Inicio: " . $fechaInicio . "Fecha Fin: " . $fechaFin . "<br>";
                endif;
                
            endforeach;
            else:
            echo "No hay estudiantes de especialización para programar <br>";
        endif;
    }

    public function segundoCiclo($limit,$marcaIngreso,$codPeriodo){
        $log = $this->model->logAplicacion('Insert-PlaneacionSegundoCicloEspecializacion', 'planeacion');
        if ($log->rowCount() == 0) :
            $offset = 0;
        else :
            $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
        endif;

        $estudiantes = $this->model->getEstudiantes($offset,$marcaIngreso,$limit);
        if($estudiantes->rowCount() > 0):
            foreach ($estudiantes as $estudiante) :
                // var_dump($estudiante);die();
                $idEstudiante = $estudiante['id'];
                $codigoBanner = $estudiante['homologante'];
                $marca_ingreso = $estudiante['marca_ingreso'];
                $programa = $estudiante['programa'];
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
                $consultaSemestre = $this->model->consultaSemestre($codigoBanner, $programa);
                $semestre = $consultaSemestre->fetch(PDO::FETCH_ASSOC)['semestre'];
                if(substr($marca_ingreso,-2) < $codPeriodo):
                    // echo "202343";
                    $ciclo = 2 .",". 12;
                    $materiasPorVer = $this->model->materiasPorVer($codigoBanner, $programa,$ciclo,$semestre);
                    $orden = 1;
                    foreach($materiasPorVer as $materia):
                        $fechaInicio = date('Y-m-d H:i:s');
                        $primerId = $estudiante['id'];
                        $ultimoRegistroId = 0;
                        $codBanner = $materia['codBanner'];
                        $codMateria = $materia['codMateria'];
                        $creditoMateria = $materia['creditos'];
                        $ciclo = $materia['ciclo'];
                        $semestre = $materia['semestre'];
                        if($ciclo == 12):
                            /*creo alerta de materia de ciclo completo*/
                            $orden--;
                            $tipoAlerta = 'Materia ciclo completo';
                            $mensajeAlerta = 'El estudiante con idBanner ' . $codigoBanner . ', No se le puede programar la materia '.$materia['codMateria'] .' ya que es de ciclo completo, y el estudiante es antiguo y se le esta programando segundo ciclo';
                            $insertarAlertaTemprana = $this->model->insertarAlerta($codigoBanner,$marca_ingreso,$programa,$tipoAlerta, $tipoEstudiante, $mensajeAlerta);
                        else:
                            /**programo las materias insertando en planeacion */
                            $programada = '';
                            $insertPlaneada = $this->model->insertarPlaneacion($codBanner, $codMateria, $orden, $semestre, $programada, $programa,$marca_ingreso);
                        endif;                        
                        $orden++;
                    endforeach;
                    $ultimoRegistroId = $estudiante['id'];
                    $idBannerUltimoRegistro = $estudiante['homologante'];
                    $fechaFin = date('Y-m-d H:i:s');
                    $acccion = 'Insert-PlaneacionSegundoCicloEspecializacion';
                    $tablaAfectada = 'planeacion';
                    $descripcion = 'Se realizo la insercion en la tabla planeacion insertando las materias del segundo ciclo del estudiante ' . $codigoBanner . ', iniciando en el id ' . $primerId . ' y terminando en el id ' . $ultimoRegistroId . '.';
                    $fecha = date('Y-m-d H:i:s');
                    $insertarLogAplicacion = $this->model->insertarLogAplicacion($primerId, $ultimoRegistroId, $fechaInicio, $fechaFin, $acccion, $tablaAfectada, $descripcion);
                    echo $ultimoRegistroId . "-Fecha Inicio: " . $fechaInicio . "Fecha Fin: " . $fechaFin . "<br>";
                else:
                    // echo "202344";
                    if($semestre == NULL):
                        $semestre = 1;
                    endif;
                    $semestre++;
                    //var_dump($semestre);die();
                    $ciclo = 2 .",". 12;
                    $materiasPorVer = $this->model->materiasPorVer($codigoBanner, $programa,$ciclo,$semestre);
                    //var_dump($materiasPorVer->fetchAll());die();
                    $orden = 1;
                    foreach($materiasPorVer as $materia):
                        $fechaInicio = date('Y-m-d H:i:s');
                        $primerId = $estudiante['id'];
                        $ultimoRegistroId = 0;
                        $codBanner = $materia['codBanner'];
                        $codMateria = $materia['codMateria'];
                        $creditoMateria = $materia['creditos'];
                        $ciclo = $materia['ciclo'];
                        $semestre = $materia['semestre'];
                        $programada = '';
                        $insertPlaneada = $this->model->insertarPlaneacion($codBanner, $codMateria, $orden, $semestre, $programada, $programa,$marca_ingreso);
                        $orden++;
                    endforeach;
                    $ultimoRegistroId = $estudiante['id'];
                    $idBannerUltimoRegistro = $estudiante['homologante'];
                    $fechaFin = date('Y-m-d H:i:s');
                    $acccion = 'Insert-PlaneacionSegundoCicloEspecializacion';
                    $tablaAfectada = 'planeacion';
                    $descripcion = 'Se realizo la insercion en la tabla planeacion insertando las materias del segundo ciclo del estudiante ' . $codigoBanner . ', iniciando en el id ' . $primerId . ' y terminando en el id ' . $ultimoRegistroId . '.';
                    $fecha = date('Y-m-d H:i:s');
                    $insertarLogAplicacion = $this->model->insertarLogAplicacion($primerId, $ultimoRegistroId, $fechaInicio, $fechaFin, $acccion, $tablaAfectada, $descripcion);
                    echo $ultimoRegistroId . "-Fecha Inicio: " . $fechaInicio . "Fecha Fin: " . $fechaFin . "<br>";
                endif;
            endforeach;
            else:
            echo "No hay estudiantes de especialización para programar <br>";
        endif;
    }
}