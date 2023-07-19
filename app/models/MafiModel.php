<?php

class MafiModel{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function log($accion,$tabla){
        try {
            $consultaLog = $this->db->connect()->prepare("SELECT * FROM `logAplicacion` WHERE `accion` = ? AND `tabla_afectada` = ? ORDER BY `id` DESC LIMIT 1");
            $consultaLog->bindValue(1,$accion,PDO::PARAM_STR);
            $consultaLog->bindValue(2,$tabla,PDO::PARAM_STR);
            $consultaLog->execute();
            return $consultaLog;
        } catch (PDOException $e) {
            return false;
        }
    }

    function dataMafi(){
        try {
            $consulta = $this->db->connect()->prepare("SELECT * FROM `datosMafi` WHERE `estado` = 'Activo' AND `sello` IN ('TIENE RETENCION', 'TIENE SELLO FINANCIERO') ORDER BY `id` ASC");
            $consulta->execute();
            return $consulta;
        } catch (PDOException $e) {
            return false;
        }
    }
}