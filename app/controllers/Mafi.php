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
                if($estudiante['sello'] == 'TIENE RETENCION' && ($estudiante['autorizado_asistir'] == 'ACTIVO EN PLATAFORMA' || $estudiante['autorizado_asistir'] == 'ACTIVO EN PLATAFORMA ICETEX')):
                    var_dump($estudiante);die();
                else:
                endif;
            endforeach;
        else:
            echo "No Hay datos que registrar";
        endif;
    }
}