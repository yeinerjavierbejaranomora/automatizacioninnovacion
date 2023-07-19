<?php
class Mafireplica  extends Controller{

    private $model;
    public function __construct()
    {
        $this->model = $this->model("MafiReplicaModel");
    }

    public function datamafireplica(){
        echo "Hola";
    }

    
}