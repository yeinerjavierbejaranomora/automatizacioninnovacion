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

        $log = $this->model->logAplicacion('Insert-PlaneacionSegundoCiclo', 'planeacion');
        if ($log->rowCount() == 0) :
            $offset = 0;
        else :
            $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
        endif;
        $limit = 50;
        $estudiantes = $this->model->getEstudiantes($offset,$marcaIngreso,$limit);
        if($estudiantes->rowCount() > 0):
            foreach ($estudiantes as $key => $estudiante) {
                $fechaInicio = date('Y-m-d H:i:s');
                $primerId = $estudiante['id'];
                $ultimoRegistroId = 0;
                $idHomologante = $estudiante['id'];
                $codHomologante = $estudiante['homologante'];
                $programaHomologante = $estudiante['programa'];
                $tipoEstudiante = $estudiante['tipo_estudiante'];
                $materiasPlaneadas = $this->model->materiasPlaneadas($codHomologante,$programaHomologante);
                $materias_planeadas='';
                foreach($materiasPlaneadas as $materia):
                    $codmateria= $materia['codMateria'];
                    $materias_planeadas = $materias_planeadas . "'" . $codmateria . "',";
                endforeach;
                $materias_planeadas = substr($materias_planeadas, 0, -1);
	            $materias_planeadas = ($materias_planeadas=='') ? "'n-a'" : $materias_planeadas;
                $consultaMateriasPorVer = $this->model->materiasPorVer($codHomologante,$programaHomologante,$materias_planeadas);
                $numeroCreditos = $this->model->getCreditosplaneados($codHomologante);
                $numeroCreditos = $numeroCreditos->rowCount() == 0 ? 0 : $numeroCreditos->fetch(PDO::FETCH_ASSOC)['CreditosPlaneados'];
                $numeroMateriasPorVer = $consultaMateriasPorVer->rowCount();
                $ruta = $estudiante['bolsa'];
                if ($ruta != '') :
                    $ruta = 1;
                else:
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
                $reglasNegocioConsulta = $this->model->getReglasNegocio($programaHomologante,$ruta,$tipoEstudiante,$cicloReglaNegocio);
                $reglasNegocio = $reglasNegocioConsulta->fetch(PDO::FETCH_ASSOC);
                $numeroCreditosPermitidos = $reglasNegocio['creditos'];
                $numeroMateriasPermitidos = (int)$reglasNegocio['materiasPermitidas'];
                $orden2 = 1;

                if ($numeroMateriasPorVer == 0) :
                    $mensajeAlerta = 'El estudiante con idBanner' . $codHomologante . ' no tiene materias por ver, segundo ciclo.';
                    $insertarAlertaTemprana = $this->model->insertarAlerta($codHomologante, $tipoEstudiante, $mensajeAlerta);
                    $updateEstudinate = $this->model->updateEstudinate($idHomologante,$codHomologante);
                    $ultimoRegistroId = $estudiante['id'];
                    $idBannerUltimoRegistro = $estudiante['homologante'];
                    $fechaFin = date('Y-m-d H:i:s');
                    $acccion = 'Insert-PlaneacionSegundoCiclo';
                    $tablaAfectada = 'planeacion';
                    $descripcion = 'Se realizo la insercion en la tabla planeacion insertando las materias del segundo ciclo del estudiante ' . $codBanner . ', iniciando en el id ' . $primerId . ' y terminando en el id ' . $ultimoRegistroId . '.';
                    $fecha = date('Y-m-d H:i:s');
                    $insertarLogAplicacion = $this->model->insertarLogAplicacion($primerId, $ultimoRegistroId, $fechaInicio, $fechaFin, $acccion, $tablaAfectada, $descripcion);
                    $insertIndiceCambio = $this->model->insertIndiceCambio($idBannerUltimoRegistro, $acccion, $descripcion, $fecha);
                    echo "Sin  Materias : " . $codHomologante . "<br />";
                else:
                    foreach($consultaMateriasPorVer as $materia):
                        $codBanner = $materia['codBanner'];
                        $codMateria = $materia['codMateria'];
                        $creditoMateria = $materia['creditos'];
                        $ciclo = $materia['ciclo'];
                        $consultaPrerequisitos = $this->model->consultaPrerequisitos($codMateria,$programaHomologante);
                        $fetchPrerequisistos = $consultaPrerequisitos->fetch(PDO::FETCH_ASSOC);
                        $prerequisitos =  $fetchPrerequisistos['prerequisito'];

                        $numeroCreditosTemp = $numeroCreditos + $creditoMateria;
                        if($prerequisitos == '' && $numeroCreditosTemp<=$numeroCreditosPermitidos && $ciclo == 2):
                            $consultaEstaPlaneacion = $this->model->estaPlaneacion($codMateria,$codBanner);
                            $fetchEstaPlaneacion = $consultaEstaPlaneacion->fetchAll();
                            $codBanner=$codBanner;
                            $planeada = $fetchEstaPlaneacion['codMateria'];

                            if($planeada == '' && $numeroCreditos < $numeroCreditosPermitidos && $numeroCreditosTemp<=$numeroCreditosPermitidos):
                                $numeroCreditos = $numeroCreditos + $creditoMateria;
                                $semestre = 1;
                                $programada = '';
                                $insertPlaneada = $this->model->insertarPlaneacion($codBanner,$codMateria,$orden2,$semestre,$programada,$programaHomologante);
                                echo $insertPlaneada . "<br />";
                            endif;
                        else:
                            $prerequisitos = '"'.$prerequisitos.'"';
                            $consultaEstaPlaneacion = $this->model->estaPlaneacionPrerequisitos($prerequisitos,$codBanner);
                            $fetchEstaPlaneacion = $consultaEstaPlaneacion->fetch(PDO::FETCH_ASSOC);
                            $preprogramado = $fetchEstaPlaneacion['codMateria'];
                            $consultaEstaPorVer = $this->model->estaPorVer($prerequisitos,$codBanner);
                            $fetchEstaPorVer = $consultaEstaPorVer->fetch(PDO::FETCH_ASSOC);
                            $estaPorVer = $fetchEstaPorVer['codMateria'];
                            if($preprogramado == '' && $estaPorVer == '' && $numeroCreditosTemp<=$numeroCreditosPermitidos && $ciclo == 2 && $numeroCreditos < $numeroCreditosPermitidos):
                                $numeroCreditos = $numeroCreditos + $creditoMateria;
                                $semestre = 1;
                                $programada = '';
                                $insertPlaneada = $this->model->insertarPlaneacion($codBanner,$codMateria,$orden2,$semestre,$programada,$programaHomologante);
                                echo $insertPlaneada . "<br />";
                            endif;
                        endif;
                    endforeach;
                    $orden2++;
                    $updateEstudinate = $this->model->updateEstudinate($idHomologante,$codHomologante);
                    $ultimoRegistroId = $estudiante['id'];
                    $idBannerUltimoRegistro = $estudiante['homologante'];
                    $fechaFin = date('Y-m-d H:i:s');
                    $acccion = 'Insert-PlaneacionSegundoCiclo';
                    $tablaAfectada = 'planeacion';
                    $descripcion = 'Se realizo la insercion en la tabla planeacion insertando las materias del segundo ciclo del estudiante ' . $codBanner . ', iniciando en el id ' . $primerId . ' y terminando en el id ' . $ultimoRegistroId . '.';
                    $fecha = date('Y-m-d H:i:s');
                    $insertarLogAplicacion = $this->model->insertarLogAplicacion($primerId, $ultimoRegistroId, $fechaInicio, $fechaFin, $acccion, $tablaAfectada, $descripcion);
                    $insertIndiceCambio = $this->model->insertIndiceCambio($idBannerUltimoRegistro, $acccion, $descripcion, $fecha);
                    echo "Planeación realizada para : " . $codBanner . " y " . $codMateria . "<br />";
                endif;

            }
        else:
            echo "No hay estudiantes de segundo ciclo para programar <br>";
        endif;
    }
}