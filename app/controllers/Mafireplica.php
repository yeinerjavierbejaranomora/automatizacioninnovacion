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
            var_dump($datosMafi->fetch(PDO::FETCH_ASSOC));die();
        else:
            echo "No Hay datos que registrar";
        endif;
    }

    
}