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
        if(!empty($logFecth)):
            $offset = $logFecth['idFin'];
        else:
            $offset = 0;
        endif;
        $datosNum = $this->model->numeroDatosMafi($offset);
        $datosNumFetch = $datosNum->fetch(PDO::FETCH_ASSOC);
        if ($datosNumFetch['totalEstudiantes'] > 0) :
            $datosMafi = $this->model->dataMafiReplica($offset);
            $numeroRegistros = 0;
            $primerId = $this->model->datamafireplica($offset)->fetch(PDO::FETCH_ASSOC)['id'];
            $ultimoRegistroId = 0;
            $fechaInicio = date('Y-m-d H:i:s');
            foreach($datosMafi as $estudiante):
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
                    var_dump($codigoBanner,$programa,$tipoEstudiante,"TRANSFERENTE");die();
                else:
                    var_dump($codigoBanner,$programa,$tipoEstudiante,"NO TRANSFERENTE");die();
                endif;
                var_dump($codigoBanner,$programa,$tipoEstudiante);die();
            endforeach;
        else:
            echo "No Hay datos que registrar";
        endif;
    }

    
}