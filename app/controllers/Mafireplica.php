<?php
class Mafireplica  extends Controller{

    private $model;
    public function __construct()
    {
        $this->model = $this->model("MafiReplicaModel");
    }

    public function datamafireplica(){
        $log = $this->model->log('Insert','estudiantes');
        $logFecth = $log->fetch(PDO::FETCH_ASSOC);
        if($log->rowCount() > 0):
            $offset = $logFecth['idFin'];
        else:
            $offset = 0;
        endif;
        // var_dump($offset);die();
        $limit = 6000;
        $datosNum = $this->model->numeroDatosMafi($offset);
        var_dump($datosNum->fetchAll());die();
        $datosNumFetch = $datosNum->fetch(PDO::FETCH_ASSOC);
        if ($datosNumFetch['totalEstudiantes'] > 0) :
            $datosMafi = $this->model->dataMafiReplica($offset,$limit);
            //var_dump($datosMafi->fetchAll());die();
            $numeroRegistros = 0;
            $numeroRegistrosAlertas = 0;
            //$primerId = $this->model->datamafireplica($offset,$limit)->fetch(PDO::FETCH_ASSOC)['id'];
            $ultimoRegistroId = 0;
            $fechaInicio = date('Y-m-d H:i:s');
            foreach($datosMafi as $estudiante):
                $primerId = $estudiante['id'];
                $codigoBanner = $estudiante['idbanner'];
                $nombre = $estudiante['primer_apellido'];
                $programa = $estudiante['codprograma'];
                $bolsa = $estudiante['ruta_academica'];
                $operador = $estudiante['operador'];
                $nodo = 'nodo';
                $tipoEstudiante = $estudiante['tipoestudiante'];
                $marcaIngreso = $estudiante['periodo'];
                $nivelFormacion = $estudiante['nivelFormacion'];
                $sello = $estudiante['sello'];
                $autorizadoAsistir = $estudiante['autorizado_asistir'];
                //if($marcaIngreso == '')
                $periodo = substr($marcaIngreso, -2);
                //$codigoBanner = 100153752;
                
                //var_dump($historialCount);die();
                /*if (!empty($historial)) :
                    var_dump($historial);
                    die();
                else :
                    echo "No tiene historial";
                    die();
                endif;*/
                $programaActivoConsulta = $this->model->programaActivo($codigoBanner,$periodo);
                $programaActivo = $programaActivoConsulta->fetch(PDO::FETCH_ASSOC)["programaActivo"];
                $tieneHistorial = NULL;
                $programaAbrio = NULL;
                $observaciones = NULL;
                if($nivelFormacion == 'PROFESIONAL'):
                    if(str_contains($tipoEstudiante,'TRANSFERENTE')):
                        /*$historial = $this->model->historialEstudiante($codigoBanner);
                        $historialCount =$historial->fetch(PDO::FETCH_ASSOC)['historial'];*/
                        $url = "https://services.ibero.edu.co/utilitary/v1/MoodleAulaVirtual/GetPersonByIdBannerQuery/" . $codigoBanner;
                        $historial = json_decode(file_get_contents($url), true);
                        $historialCount =count($historial);
                        if($historialCount == 0):
                            if($programaActivo < 1):
                                $tieneHistorial = 'SIN HISTORIAL';
                                $programaAbrio = 'NO SE ABRIO PROGRAMA';
                                $insertarEstudiante = $this->model->insertarEstudiante($codigoBanner,$nombre,$programa,$bolsa,$operador,$nodo,$tipoEstudiante,$tieneHistorial,$programaAbrio,$marcaIngreso,$observaciones,$sello,$autorizadoAsistir);
                                $mensajeAlerta = 'El estudiante con idBanner' . $codigoBanner . ' es "TRANSFERENTE" y no tiene historial academico';
                                $insertarAlertaTemprana = $this->model->insertarAlerta($codigoBanner,$tipoEstudiante,$mensajeAlerta);
                                    if($insertarAlertaTemprana):
                                    $numeroRegistrosAlertas++;
                                endif;
                                $mensajeAlerta = 'NO SE ABRIO PROGRAMA ' . $programa;
                                $insertarAlertaTemprana = $this->model->insertarAlerta($codigoBanner,$tipoEstudiante,$mensajeAlerta);
                                if($insertarAlertaTemprana):
                                    $numeroRegistrosAlertas++;
                                endif;
                            else:
                                $tieneHistorial = 'SIN HISTORIAL';
                                $insertarEstudiante = $this->model->insertarEstudiante($codigoBanner,$nombre,$programa,$bolsa,$operador,$nodo,$tipoEstudiante,$tieneHistorial,$programaAbrio,$marcaIngreso,$observaciones,$sello,$autorizadoAsistir);
                                $mensajeAlerta = 'El estudiante con idBanner' . $codigoBanner . ' es "TRANSFERENTE" y no tiene historial academico';
                                $insertarAlertaTemprana = $this->model->insertarAlerta($codigoBanner,$tipoEstudiante,$mensajeAlerta);
                                if($insertarAlertaTemprana):
                                    $numeroRegistrosAlertas++;
                                endif;
                            endif;
                            if($insertarEstudiante):
                                $numeroRegistros++;
                            endif;
                        else:
                            if($programaActivo > 0):
                                $insertarEstudiante = $this->model->insertarEstudiante($codigoBanner,$nombre,$programa,$bolsa,$operador,$nodo,$tipoEstudiante,$tieneHistorial,$programaAbrio,$marcaIngreso,$observaciones,$sello,$autorizadoAsistir);
                            else:
                                $programaAbrio = 'NO SE ABRIO PROGRAMA';
                                $insertarEstudiante = $this->model->insertarEstudiante($codigoBanner,$nombre,$programa,$bolsa,$operador,$nodo,$tipoEstudiante,$tieneHistorial,$programaAbrio,$marcaIngreso,$observaciones,$sello,$autorizadoAsistir);
                                $mensajeAlerta = 'NO SE ABRIO PROGRAMA ' . $programa;
                                $insertarAlertaTemprana = $this->model->insertarAlerta($codigoBanner,$tipoEstudiante,$mensajeAlerta);
                                if($insertarAlertaTemprana):
                                    $numeroRegistrosAlertas++;
                                endif;
                            endif;
                            if($insertarEstudiante):
                                $numeroRegistros++;
                            endif;
                        endif;
                    else:
                        if($programaActivo > 0):
                            $insertarEstudiante = $this->model->insertarEstudiante($codigoBanner,$nombre,$programa,$bolsa,$operador,$nodo,$tipoEstudiante,$tieneHistorial,$programaAbrio,$marcaIngreso,$observaciones,$sello,$autorizadoAsistir);
                        else:
                            $programaAbrio = 'NO SE ABRIO PROGRAMA';
                            $insertarEstudiante = $this->model->insertarEstudiante($codigoBanner,$nombre,$programa,$bolsa,$operador,$nodo,$tipoEstudiante,$tieneHistorial,$programaAbrio,$marcaIngreso,$observaciones,$sello,$autorizadoAsistir);
                            $mensajeAlerta = 'NO SE ABRIO PROGRAMA ' . $programa;
                            $insertarAlertaTemprana = $this->model->insertarAlerta($codigoBanner,$tipoEstudiante,$mensajeAlerta);
                            if($insertarAlertaTemprana):
                                $numeroRegistrosAlertas++;
                            endif;
                        endif;
                        if($insertarEstudiante):
                            $numeroRegistros++;
                        endif;
                    endif;
                else:
                        $observaciones = "Nivel de formación " . $nivelFormacion;
                        $insertarEstudiante = $this->model->insertarEstudiante($codigoBanner, $nombre, $programa, $bolsa, $operador, $nodo, $tipoEstudiante, $tieneHistorial, $programaAbrio, $marcaIngreso, $observaciones,$sello,$autorizadoAsistir);
                        if($insertarEstudiante):
                            $numeroRegistros++;
                        endif;
                        $mensajeAlerta = 'El ' . $codigoBanner . ' es tipo de estudiante ' . $tipoEstudiante . ', programa' . $programa;
                        $insertarAlertaTemprana = $this->model->insertarAlerta($codigoBanner, $tipoEstudiante, $mensajeAlerta);
                        if ($insertarAlertaTemprana) :
                            $numeroRegistrosAlertas++;
                        endif;
                endif;
                $ultimoRegistroId = $estudiante['id'];
                $idBannerUltimoRegistro = $estudiante['idbanner'];
                $fechaFin = date('Y-m-d H:i:s');
                $acccion = 'Insert';
                $tablaAfectada = 'estudiantes';
                $descripcion = 'Se realizo la insercion en la tabla estudiantes, del estudiante con codigo banner'. $codigoBanner;
                $fecha = date('Y-m-d H:i:s');
                $insertarLogAplicacion = $this->model->insertarLogAplicacion($primerId, $ultimoRegistroId, $fechaInicio, $fechaFin, $acccion, $tablaAfectada, $descripcion);
                $insertIndiceCambio = $this->model->insertIndiceCambio($idBannerUltimoRegistro, $acccion, $descripcion, $fecha);
                /*echo  "Numero de registros: " . $numeroRegistros . "=> primer id registrado: " . $primerId . ', Ultimo id registrado ' . $ultimoRegistroId .
                "<br> Numero de registrosen alertas: " . $numeroRegistrosAlertas .
                "<br> inicio:" . $fechaInicio . "-- Fin:" . $fechaFin;*/
                echo $ultimoRegistroId . "--" . $codigoBanner . "-Fecha Inicio: " . $fechaInicio . "Fecha Fin: " . $fechaFin . "<br>";
            endforeach;
            
        else:
            echo "No Hay datos que registrar";
        endif;
    }

    
}