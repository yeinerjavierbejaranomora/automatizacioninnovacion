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
        var_dump(!empty($logFecth));die();
        if(!empty($logFecth)):
            $offset = $logFecth['idFin'];
        else:
            $offset = 0;
        endif;
        /*$datosMafi = $this->model->dataMafi();
        var_dump($datosMafi->fetch(PDO::FETCH_ASSOC));die();*/
    }
}