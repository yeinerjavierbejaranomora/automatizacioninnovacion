<?php
class Programarprimerciclo extends Controller{

    private $model;

    public function __construct()
    {
        $this->model = $this->model("ProgramarPrimerCicloModel");
    }


    public function inicio(){
        $log = $this->model->logAplicacion('Insert-ProgramacionPrimerCiclo', 'programacion');
        if ($log->rowCount() == 0) :
            $offset = 0;
        else :
            $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
        endif;
        // $offset = 6013;
        $periodos = $this->model->periodos();
        $marcaIngreso = "";
        foreach ($periodos as $periodo) {
            $marcaIngreso .= (int)$periodo['periodos'] . ",";
        }
        $marcaIngreso = trim($marcaIngreso, ",");
        //var_dump($marcaIngreso);die();
        $estudiantes = $this->model->getEstudiantesNum($offset,$marcaIngreso);
        //var_dump($estudiantes->rowCount());die();
        $limit = 500;
        $numEstudinates = ceil($estudiantes->rowCount()/$limit);
        for ($i=0; $i < $numEstudinates; $i++) { 
            //sleep(10);
            $this->primerciclo($limit,$marcaIngreso);
        }
    }

    public function primerciclo($limit,$marcaIngreso,){

        $log = $this->model->logAplicacion('Insert-ProgramacionPrimerCiclo', 'programacion');
        if ($log->rowCount() == 0) :
            $offset = 0;
        else :
            $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
        endif;
        //$limit = 50;
        $estudiantes = $this->model->getEstudiantes($offset,$marcaIngreso,$limit);
        //var_dump($estudiantes->fetchAll());die();
        
        if($estudiantes->rowCount() > 0):
            foreach ($estudiantes as $estudiante) :
                //var_dump($estudiante);die();
                $programa = $estudiante['programa'];
                if ($programa != 'PPSV') :
                    //echo "No es PPSV <br>";

                    $fechaInicio = date('Y-m-d H:i:s');
                    $primerId = $estudiante['id'];
                    $ultimoRegistroId = 0;
                    $idEstudiante = $estudiante['id'];
                    //$codigoBanner = $estudiante['homologante'];
                    $codigoBanner = 100053582;
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
                    $ciclo = [1, 12];
                    $materiasMoodleConsulta = $this->model->materiasMoodle($codigoBanner);
                    $materias_moodle = "";
                    foreach ($materiasMoodleConsulta as $materia) {
                        $materias_moodle .= $materia['codigomateria'] . ",";
                    }
                    $materias_moodle = trim($materias_moodle, ",");
                    var_dump($materias_moodle);die();
                    $materiasPorVer = $this->model->materiasPorVer($codigoBanner, $ciclo, $programa);
                    $numeroCreditos = $this->model->getCreditosPlaneados($codigoBanner);
                    $numeroCreditos = $numeroCreditos->rowCount() == 0 ? 0 : $numeroCreditos->fetch(PDO::FETCH_ASSOC)['CreditosPlaneados'];
                    $numeroCreditosC1 = $this->model->getCreditosCicloUno($codigoBanner);
                    $sumaCreditosCiclo1 = $numeroCreditosC1->fetch(PDO::FETCH_ASSOC)['screditos'];
                    $sumaCreditosCiclo1 = $sumaCreditosCiclo1 == '' ? 0 : $sumaCreditosCiclo1;
                    $cuentaCursosCiclo1 = $numeroCreditosC1->fetch(PDO::FETCH_ASSOC)['ccursos'];
                    $cuentaCursosCiclo1 = $cuentaCursosCiclo1 == 0 ? 0 : (int)$cuentaCursosCiclo1;
                    $cicloReglaNegocio = 1;
                    $reglasNegocioConsulta = $this->model->getReglasNegocio($programa, $ruta, $tipoEstudiante, $cicloReglaNegocio);
                    $reglasNegocio = $reglasNegocioConsulta->fetch(PDO::FETCH_ASSOC);
                    $numeroCreditosPermitidos = $reglasNegocio['creditos'];
                    $numeroMateriasPermitidos = (int)$reglasNegocio['materiasPermitidas'];
                    $orden = 1;
                    //var_dump($materiasPorVer->fetchAll());die();

                    foreach ($materiasPorVer as $materia) :
                        if ($cuentaCursosCiclo1 >= $numeroMateriasPermitidos) :
                            break;
                        endif;
                        
                        $codBanner = $materia['codBanner'];
                        $codMateria = $materia['codMateria'];
                        $creditoMateria = $materia['creditos'];
                        $ciclo = $materia['ciclo'];
                        $prerequisitos = $materia['prerequisito'];
                        //$prerequisitosConsulta = $this->model->prerequisitos($codMateria, $programa);
                        //$prerequisitos = $prerequisitosConsulta->fetch(PDO::FETCH_ASSOC)['prerequisito'];
                        //echo $codMateria."-". $prerequisitos."<br>";
                        //var_dump($prerequisitos,"<br>");
                        if ($prerequisitos == '' && $ciclo != 2 && $cuentaCursosCiclo1 < $numeroMateriasPermitidos) :
                            $estaPlaneacion = $this->model->estaPlaneacion($codMateria, $codBanner);
                            if ($estaPlaneacion->rowCount() == 0  && $numeroCreditos < $numeroCreditosPermitidos) :
                                $numeroCreditos = $numeroCreditos + $creditoMateria;
                                $semestre = 1;
                                $programada = '';
                                $insertarPlaneacion = $this->model->insertarPlaneacion($codBanner, $codMateria, $orden, $semestre, $programada, $programa);
                                $cuentaCursosCiclo1++;
                            endif;
                        else :
                            //var_dump("Cp",$numeroCreditos,$creditoMateria,$prerequisitos);die();
                            $prerequisitos = trim($prerequisitos,'"');
                            $prerequisitos = '"' . $prerequisitos . '"';$estaPlaneacion = $this->model->estaPlaneacionPrerequisitos($prerequisitos, $codBanner);
                            $estaPorVer = $this->model->estaPorVer($prerequisitos, $codBanner);
                            if ($estaPlaneacion->rowCount() == 0  && $estaPorVer->rowCount() == 0  && $cuentaCursosCiclo1 < $numeroMateriasPermitidos) :
                                $numeroCreditos = $numeroCreditos + $creditoMateria;
                                $semestre = 1;
                                $programada = '';
                                $insertarPlaneacion = $this->model->insertarPlaneacion($codBanner, $codMateria, $orden, $semestre, $programada, $programa);
                                $cuentaCursosCiclo1++;
                            endif;
                        endif;
                    endforeach;
                    $updateEstudiante = $this->model->updateEstudiante($estudiante['id'], $codBanner);
                    $ultimoRegistroId = $estudiante['id'];
                    $idBannerUltimoRegistro = $estudiante['homologante'];
                    $fechaFin = date('Y-m-d H:i:s');
                    $acccion = 'Insert-ProgramacionPrimerCiclo';
                    $tablaAfectada = 'planeacion';
                    $descripcion = 'Se realizo la insercion en la tabla planeacion insertando las materias del primer ciclo del estudiante ' . $codBanner . ', iniciando en el id ' . $primerId . ' y terminando en el id ' . $ultimoRegistroId . '.';
                    $fecha = date('Y-m-d H:i:s');
                    $insertarLogAplicacion = $this->model->insertarLogAplicacion($primerId, $ultimoRegistroId, $fechaInicio, $fechaFin, $acccion, $tablaAfectada, $descripcion);
                    //$insertIndiceCambio = $this->model->insertIndiceCambio($idBannerUltimoRegistro, $acccion, $descripcion, $fecha);
                    echo $ultimoRegistroId . "-Fecha Inicio: " . $fechaInicio . "Fecha Fin: " . $fechaFin . "<br>";
                else :
                    //$this->programarOrden($estudiante);
                endif;
                die();
            endforeach;
        else:
            echo "No hay estudiantes de primer ciclo para programar <br>";
        endif;
    }

    public function programarOrden($estudiante){
        $fechaInicio = date('Y-m-d H:i:s');
        $primerId = $estudiante['id'];
        $ultimoRegistroId = 0;
        $idEstudiante = $estudiante['id'];
        $codigoBanner = $estudiante['homologante'];
        $programa = $estudiante['programa'];
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
        $materiasPorVer = $this->model->materiasPorVerOrden($codigoBanner, $programa);
        //var_dump($materiasPorVer->fetchAll());die();
        $numeroCreditos = $this->model->getCreditosPlaneados($codigoBanner);
        $numeroCreditos = $numeroCreditos->rowCount() == 0 ? 0 : $numeroCreditos->fetch(PDO::FETCH_ASSOC)['CreditosPlaneados'];
        $numeroCreditosC1 = $this->model->getCreditosCicloUno($codigoBanner);
        $sumaCreditosCiclo1 = $numeroCreditosC1->fetch(PDO::FETCH_ASSOC)['screditos'];
        $sumaCreditosCiclo1 = $sumaCreditosCiclo1 == '' ? 0 : $sumaCreditosCiclo1;
        $cuentaCursosCiclo1 = $numeroCreditosC1->fetch(PDO::FETCH_ASSOC)['ccursos'];
        $cuentaCursosCiclo1 = $cuentaCursosCiclo1 == 0 ? 0 : (int)$cuentaCursosCiclo1;
        $cicloReglaNegocio = 1;
        $reglasNegocioConsulta = $this->model->getReglasNegocio($programa, $ruta, $tipoEstudiante, $cicloReglaNegocio);
        $reglasNegocio = $reglasNegocioConsulta->fetch(PDO::FETCH_ASSOC);
        $numeroCreditosPermitidos = $reglasNegocio['creditos'];
        $numeroMateriasPermitidos = (int)$reglasNegocio['materiasPermitidas'];
        $orden = 1;
        foreach ($materiasPorVer as $materia) :
           //var_dump($materia,"<br>");
            $codBanner = $materia['codBanner'];
            $codMateria = $materia['codMateria'];
            $creditoMateria = $materia['creditos'];
            $ciclo = $materia['ciclo'];
            $prerequisitos = $materia['prerequisito'];
            if($prerequisitos == '' && $cuentaCursosCiclo1 < $numeroMateriasPermitidos):
                $estaPlaneacion = $this->model->estaPlaneacion($codMateria, $codBanner);
                if ($estaPlaneacion->rowCount()  == 0  && $numeroCreditos < $numeroCreditosPermitidos) :
                    $numeroCreditos = $numeroCreditos + $creditoMateria;
                    $semestre = 1;
                    $programada = '';
                    $insertarPlaneacion = $this->model->insertarPlaneacion($codBanner, $codMateria, $orden, $semestre, $programada, $programa);
                    $cuentaCursosCiclo1++;
                endif;
            else:
                $prerequisitos = trim($prerequisitos,'"');
                $prerequisitos = '"' . $prerequisitos . '"';
                $estaPlaneacion = $this->model->estaPlaneacionPrerequisitos($prerequisitos, $codBanner);
                $estaPorVer = $this->model->estaPorVer($prerequisitos, $codBanner);
                if ($estaPlaneacion->rowCount() == 0  && $estaPorVer->rowCount() == 0  && $cuentaCursosCiclo1 < $numeroMateriasPermitidos) :
                    $numeroCreditos = $numeroCreditos + $creditoMateria;
                    $semestre = 1;
                    $programada = '';
                    $insertarPlaneacion = $this->model->insertarPlaneacion($codBanner, $codMateria, $orden, $semestre, $programada, $programa);
                    $cuentaCursosCiclo1++;
                endif;
            endif;
        endforeach;
        $updateEstudiante = $this->model->updateEstudiante($estudiante['id'], $codBanner);
        $ultimoRegistroId = $estudiante['id'];
        $idBannerUltimoRegistro = $estudiante['homologante'];
        $fechaFin = date('Y-m-d H:i:s');
        $acccion = 'Insert-ProgramacionPrimerCiclo';
        $tablaAfectada = 'planeacion';
        $descripcion = 'Se realizo la insercion en la tabla planeacion insertando las materias del primer ciclo del estudiante ' . $codBanner . ', perteneciente al programa '.$programa.', iniciando en el id ' . $primerId . ' y terminando en el id ' . $ultimoRegistroId . '.';
        $fecha = date('Y-m-d H:i:s');
        $insertarLogAplicacion = $this->model->insertarLogAplicacion($primerId, $ultimoRegistroId, $fechaInicio, $fechaFin, $acccion, $tablaAfectada, $descripcion);
        //$insertIndiceCambio = $this->model->insertIndiceCambio($idBannerUltimoRegistro, $acccion, $descripcion, $fecha);
        echo $ultimoRegistroId . "-Fecha Inicio: " . $fechaInicio . "Fecha Fin: " . $fechaFin . "<br>";
    }

}