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
            echo "Hay datos que registrar";
            $datosMafi = $this->model->dataMafiReplica($offset);
            var_dump($datosMafi->fetch(PDO::FETCH_ASSOC));die();
        else:
            echo "No Hay datos que registrar";
        endif;
    }

    
}