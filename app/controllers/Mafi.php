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
        $datosMafi = $this->model->dataMafi();
        if($datosMafi):
            $numeroRegistros = 0;
            $primerId = $this->model->dataMafi()->fetch(PDO::FETCH_ASSOC)['id'];
            $ultimoRegistroId = 0;
            $fechaInicio = date('Y-m-d H:i:s');
            foreach($datosMafi as $estudiante):
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
                if($sello == 'TIENE RETENCION' && ($autorizadoAsistir == 'ACTIVO EN PLATAFORMA' || $autorizadoAsistir == 'ACTIVO EN PLATAFORMA ICETEX')):
                    //$insertEstudiante = $this->model->insertEstudiante($idBanner,$primerApellido,$programa,$codPrograma,$cadena,$periodo,$estado,$tipoEstudiante,$rutaAcademica,$sello,$operador,$autorizadoAsistir);
                    $numeroRegistros++;
                else:
                    //$insertEstudiante = $this->model->insertEstudiante($idBanner,$primerApellido,$programa,$codPrograma,$cadena,$periodo,$estado,$tipoEstudiante,$rutaAcademica,$sello,$operador,$autorizadoAsistir);
                    $numeroRegistros++;
                endif;
            endforeach;

            echo $numeroRegistros;
        else:
            echo "No Hay datos que registrar";
        endif;
    }
}