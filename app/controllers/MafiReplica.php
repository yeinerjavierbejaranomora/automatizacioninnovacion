<?php
class MafiReplica  extends Controller{

    private $model;
    public function __construct()
    {
        $this->model = $this->model("MafiReplicaModel");
    }

    public function dataMafiReplica(){
        $log = $this->model->log('Insert','estudiantes');
        $logFecth = $log->fetch(PDO::FETCH_ASSOC);
        if(!empty($logFecth)):
            $offset = $logFecth['idFin'];
        else:
            $offset = 0;
        endif;
        $datosMafi = $this->model->dataMafiReplica($offset);
        if($datosMafi):
            $numeroRegistros = 0;
            $numeroRegistrosAlertas = 0;
            $primerID = $this->model->dataMafiReplica($offset)->fetch(PDO::FETCH_ASSOC)['id'];
            $ultimoRegistroId = 0;
            $fechaInicio = date('Y-m-d H:i:s');
            echo $fechaInicio;
            $cont = 0;
            
            foreach ($datosMafi as $estudiante) :
                //var_dump($estudiante);die();
                $codigoBanner = $estudiante['idbanner'];
                $nombre = $estudiante['primer_apellido'];
                $programa = $estudiante['programa'];
                $bolsa = $estudiante['ruta_academica'];
                $operador = $estudiante['operador'];
                $nodo = 'nodo';
                $tipoEstudiante = $estudiante['tipoestudiante'];
                $marcaIngreso = $estudiante['periodo'];
                $tieneHistorial = NULL;
                $programaAbrio = NULL;
                if(str_contains($tipoEstudiante,'TRANSFERENTE')):
                    $historial = $this->model->historialEstudiante($codigoBanner);
                    $historialCount = $historial->fetch(PDO::FETCH_ASSOC)['historial'];
                    if($historialCount == 0):
                        if($estudiante['programaActivo'] < 1):
                            $tieneHistorial = 'SIN HISTORIAL';
                            $programaAbrio = 'NO SE ABRIO PROGRAMA';
                            $insertarEstudiante = $this->model->insertarEstudiante($codigoBanner,$nombre,$programa,$bolsa,$operador,$nodo,$tipoEstudiante,$tieneHistorial,$programaAbrio,$marcaIngreso);
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
                            $insertarEstudiante = $this->model->insertarEstudiante($codigoBanner,$nombre,$programa,$bolsa,$operador,$nodo,$tipoEstudiante,$tieneHistorial,$programaAbrio,$marcaIngreso);
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
                        if($estudiante['programaActivo'] > 0):
                            $insertarEstudiante = $this->model->insertarEstudiante($codigoBanner,$nombre,$programa,$bolsa,$operador,$nodo,$tipoEstudiante,$tieneHistorial,$programaAbrio,$marcaIngreso);
                        else:
                            $programaAbrio = 'NO SE ABRIO PROGRAMA';
                            $insertarEstudiante = $this->model->insertarEstudiante($codigoBanner,$nombre,$programa,$bolsa,$operador,$nodo,$tipoEstudiante,$tieneHistorial,$programaAbrio,$marcaIngreso);
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
                    if($estudiante['programaActivo'] > 0):
                        $insertarEstudiante = $this->model->insertarEstudiante($codigoBanner,$nombre,$programa,$bolsa,$operador,$nodo,$tipoEstudiante,$tieneHistorial,$programaAbrio,$marcaIngreso);
                    else:
                        $programaAbrio = 'NO SE ABRIO PROGRAMA';
                        $insertarEstudiante = $this->model->insertarEstudiante($codigoBanner,$nombre,$programa,$bolsa,$operador,$nodo,$tipoEstudiante,$tieneHistorial,$programaAbrio,$marcaIngreso);
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
                $ultimoRegistroId = $estudiante['id'];
                $idBannerUltimoRegistro = $estudiante['idbanner'];
                $cont++;
                echo $cont;
            endforeach;
            $fechaFin = date('Y-m-d H:i:s');
            $acccion = 'Insert';
            $tablaAfectada = 'estudiantes';
            $descripcion = 'Se realizo la insercion en la tabla estudiantes desde la tabla datosMafiReplica, iniciando en el id ' . $primerID . ' y terminando en el id ' . $ultimoRegistroId . ',insertando ' . $numeroRegistros . ' registros';
            $fecha = date('Y-m-d H:i:s');
            $insertarLogAplicacion = $this->model->insertarLogAplicacion($primerID,$ultimoRegistroId,$fechaInicio,$fechaFin,$acccion,$tablaAfectada,$descripcion);
            $insertIndiceCambio = $this->model->insertIndiceCambio($idBannerUltimoRegistro,$acccion,$descripcion,$fecha);
            echo  "Numero de registros: " . $numeroRegistros . "=> primer id registrado: " . $primerID . ', Ultimo id registrado ' . $ultimoRegistroId .
                "<br> Numero de registrosen alertas: " . $numeroRegistrosAlertas .
                "<br> inicio:" . $fechaInicio . "-- Fin:" . $fechaFin;
            if ($cont >= 50) :
                die();
            endif;
        else:
            echo "No hay registros para replicar <br>";
        endif;
    }

    
}