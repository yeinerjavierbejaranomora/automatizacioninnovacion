<?php
class Mafi extends Controller{
    private $model = '';

    public function __construct()
    {
        $this->model = $this->model("MafiModel");
    }
    

    public function inicio() {
        
        $log = $this->model->log('Insert','datosMafiReplica');
        $logFecth = $log->fetch(PDO::FETCH_ASSOC);
        if(!empty($logFecth)):
            $offset = $logFecth['idFin'];
        else:
            $offset = 0;
        endif;
        $datosNum = $this->model->numeroDatosMafi($offset);
        $datosNumFetch = $datosNum->fetch(PDO::FETCH_ASSOC);
        $datosMafi = $this->model->dataMafi2();
        var_dump($datosMafi->fetch(PDO::FETCH_ASSOC));die();
        if($datosNumFetch['total'] > 0):
            // $datosMafi = $this->model->dataMafi($offset);
            $numeroRegistros = 0;
            $primerId = $this->model->dataMafi()->fetch(PDO::FETCH_ASSOC)['id'];
            $ultimoRegistroId = 0;
            $fechaInicio = date('Y-m-d H:i:s');
            foreach ($datosMafi as $estudiante) :
                $idBanner = $estudiante['idbanner'];
                $primerApellido = $estudiante['primer_apellido'];
                $programa = $estudiante['programa'];
                $codPrograma = $estudiante['codprograma'];
                $cadena = $estudiante['cadena'];
                $periodo = $estudiante['periodo'];
                $estado = $estudiante['estado'];
                $tipoEstudiante = $estudiante['tipoestudiante'];
                $rutaAcademica = $estudiante['ruta_academica'];
                $sello = $estudiante['sello'];
                $operador = $estudiante['operador'];
                $autorizadoAsistir = $estudiante['autorizado_asistir'];
                if ($sello == 'TIENE RETENCION' && ($autorizadoAsistir == 'ACTIVO EN PLATAFORMA' || $autorizadoAsistir == 'ACTIVO EN PLATAFORMA ICETEX')) :
                    $insertEstudiante = $this->model->insertEstudiante($idBanner, $primerApellido, $programa, $codPrograma, $cadena, $periodo, $estado, $tipoEstudiante, $rutaAcademica, $sello, $operador, $autorizadoAsistir);
                    $numeroRegistros++;
                elseif ($sello == 'TIENE SELLO FINANCIERO') :
                    $insertEstudiante = $this->model->insertEstudiante($idBanner, $primerApellido, $programa, $codPrograma, $cadena, $periodo, $estado, $tipoEstudiante, $rutaAcademica, $sello, $operador, $autorizadoAsistir);
                    $numeroRegistros++;
                endif;
                $ultimoRegistroId = $estudiante['id'];
                $idBannerUltimoRegistro = $idBanner;
            endforeach;
            $fechaFin = date('Y-m-d H:i:s');
            $accion = 'Insert';
            $tablaAfectada = 'datosMafiReplica';
            $mensajeLog = 'Se realizo la insercion en la tabla datosMafiRelica desde la tabla datosMafi, iniciando en el id ' . $primerId . ' y terminando en el id ' . $ultimoRegistroId . ',insertando ' . $numeroRegistros . ' registros';
            $insertarLog = $this->model->insertLog($primerId, $ultimoRegistroId, $fechaInicio, $fechaFin, $accion, $tablaAfectada, $mensajeLog);
            $insertIndice = $this->model->insertIndice($idBannerUltimoRegistro, $accion, $mensajeLog);
            if ($insertarLog && $insertIndice) :
                echo "Fecha inicio: " . $fechaInicio . ', Fecha Fin ' . $fechaFin;
                echo "Numero de registros: '$numeroRegistros'=> primer id registrado: " . $primerId . ', Ultimo id registrado ' . $ultimoRegistroId;
            endif;
        else:
            echo "No Hay datos que registrar";
        endif;
    }
}