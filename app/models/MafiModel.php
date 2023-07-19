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
            $consulta = $this->db->connect()->prepare("SELECT * FROM `datosMafi` WHERE `id` > 0 AND `estado` = 'Activo' AND `sello` IN ('TIENE RETENCION', 'TIENE SELLO FINANCIERO') ORDER BY `id` ASC");
            $consulta->execute();
            return $consulta;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function insertEstudiante($idBanner,$primerApellido,$programa,$codPrograma,$cadena,$periodo,$estado,$tipoEstudiante,$rutaAcademica,$sello,$operador,$autorizadoAsistir){
        try {
            $insertEstudiante= $this->db->connect()->prepare("INSERT INTO `datosMafiReplica` SET 
                                                                          `idbanner` = ?, 
                                                                          `primer_apellido` = ?, 
                                                                          `programa` = ?, 
                                                                          `codprograma` = ?, 
                                                                          `cadena` = ?, 
                                                                          `periodo` = ?, 
                                                                          `estado` = ?, 
                                                                          `tipoestudiante` = ?, 
                                                                          `ruta_academica` = ?, 
                                                                          `sello` = ?, 
                                                                          `operador` = ?, 
                                                                          `autorizado_asistir` = ?, 
                                                                          `created_at` = NOW(), 
                                                                          `updated_at` = NOW()");
            $insertEstudiante->bindValue(1,$idBanner,PDO::PARAM_INT);
            $insertEstudiante->bindValue(2,$primerApellido,PDO::PARAM_STR);
            $insertEstudiante->bindValue(3,$programa,PDO::PARAM_STR);
            $insertEstudiante->bindValue(4,$codPrograma,PDO::PARAM_STR);
            $insertEstudiante->bindValue(5,$cadena,PDO::PARAM_STR);
            $insertEstudiante->bindValue(6,$periodo,PDO::PARAM_STR);
            $insertEstudiante->bindValue(7,$estado,PDO::PARAM_STR);
            $insertEstudiante->bindValue(8,$tipoEstudiante,PDO::PARAM_STR);
            $insertEstudiante->bindValue(9,$rutaAcademica,PDO::PARAM_STR);
            $insertEstudiante->bindValue(10,$sello,PDO::PARAM_STR);
            $insertEstudiante->bindValue(11,$operador,PDO::PARAM_STR);
            $insertEstudiante->bindValue(12,$autorizadoAsistir,PDO::PARAM_STR);
            $insertEstudiante->execute();
            return $insertEstudiante;
        } catch (PDOException $e) {
            return false;
        }
    }
}