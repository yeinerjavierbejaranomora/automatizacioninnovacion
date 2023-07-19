<?php

class MateriasPorVerModel{

    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function logAplicacion($accion,$tabla){
        try {
            $consultaLog = $this->db->connect()->prepare("SELECT * FROM `logaplicacion` WHERE `accion` = ? AND `tabla_afectada` = ? ORDER BY `id` DESC LIMIT 1");
            $consultaLog->bindValue(1,$accion,PDO::PARAM_STR);
            $consultaLog->bindValue(2,$tabla,PDO::PARAM_STR);
            $consultaLog->execute();
            return $consultaLog;
        } catch (PDOException $e) {
            return false;
        }
    }
}