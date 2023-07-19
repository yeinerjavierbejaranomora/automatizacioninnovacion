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
        $datosMafi = $this->model->dataMafiReplica($offset);
        var_dump($datosMafi->fetch(PDO::FETCH_ASSOC));die();
    }

    
}