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

    function dataMafi($offset){
        try {
            $consulta = $this->db->connect()->prepare("SELECT * FROM `datosMafi` WHERE `id` > $offset AND `estado` = 'Activo' AND `sello` IN ('TIENE RETENCION', 'TIENE SELLO FINANCIERO') ORDER BY `id` ASC");
            //$consulta->bindValue(1,$offset,PDO::PARAM_INT);
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

    public function insertLog($primerId,$ultimoRegistroId,$fechaInicio,$fechaFin,$accion,$tablaAfectada,$mensajeLog){
        try {
            $insertLog = $this->db->connect()->prepare("INSERT INTO `logAplicacion` SET   
                                                                    `idInicio` = ?, 
                                                                    `idFin` = ?, 
                                                                    `fechaInicio` = ?, 
                                                                    `fechaFin` = ?, 
                                                                    `accion` = ?, 
                                                                    `tabla_afectada` = ?, 
                                                                    `descripcion` = ?, 
                                                                    `created_at` = NOW(), 
                                                                    `updated_at` = NOW()");
            $insertLog->bindValue(1,$primerId,PDO::PARAM_INT);
            $insertLog->bindValue(2,$ultimoRegistroId,PDO::PARAM_INT);
            $insertLog->bindValue(3,$fechaInicio,PDO::PARAM_STR);
            $insertLog->bindValue(4,$fechaFin,PDO::PARAM_STR);
            $insertLog->bindValue(5,$accion,PDO::PARAM_STR);
            $insertLog->bindValue(6,$tablaAfectada,PDO::PARAM_STR);
            $insertLog->bindValue(7,$mensajeLog,PDO::PARAM_STR);
            $insertLog->execute();
            return $insertLog;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function insertIndice($idBannerUltimoRegistro,$accion,$mensajeLog){
        try {
            $insertIndice = $this->db->connect()->prepare("INSERT INTO `indice_cambios_mafi` SET 
                                                                        `idbanner` = ?, 
                                                                        `accion` = ?, 
                                                                        `descripcion` = ?, 
                                                                        `fecha` = NOW(), 
                                                                        `created_at` = NOW(), 
                                                                        `updated_at` = NOW()");
            $insertIndice->bindValue(1,$idBannerUltimoRegistro,PDO::PARAM_INT);
            $insertIndice->bindValue(2,$accion,PDO::PARAM_STR);
            $insertIndice->bindValue(3,$mensajeLog,PDO::PARAM_STR);
            $insertIndice->execute();
            return $insertIndice;
        } catch (PDOException $e) {
            return false;
        }
    }
}