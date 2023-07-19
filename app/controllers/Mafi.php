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
            $datosMafiFetch = $datosMafi->fetch(PDO::FETCH_ASSOC);
            var_dump($datosMafiFetch);die();
            /*$numeroRegistros = 0;
            $primerId = $data[0][0]->id;
            $ultimoRegistroId = 0;
            $fechaInicio = date('Y-m-d H:i:s');*/
        else:
            echo "No Hay datos que registrar";
        endif;
    }
}