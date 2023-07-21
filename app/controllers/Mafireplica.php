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
        var_dump($datosNumFetch['totalEstudiantes']);die();
        $datosMafi = $this->model->dataMafiReplica($offset);
        var_dump($datosMafi->rowCount(PDO::FETCH_ASSOC));die();
    }

    
}