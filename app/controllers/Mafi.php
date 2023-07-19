<?php
class Mafi extends Controller{
    private $model = '';

    public function __construct()
    {
        $this->model = $this->model("MafiModel");
    }
    

    public function inicio() {
        
        $log = $this->model->log('Insert','datosMafiReplica');
        var_dump($log);die();
        /*$datosMafi = $this->model->dataMafi();
        var_dump($datosMafi->fetch(PDO::FETCH_ASSOC));die();*/
    }
}