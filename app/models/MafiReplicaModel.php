<?php
class MafiReplicaModel{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function numerodatosMafi($offset){
        try {
            $consultaDataMafiReplica =$this->db->connect()->prepare("SELECT COUNT(`idbanner`) as `totalEstudiantes` FROM `datosmafireplica` dmr INNER JOIN programas p ON p.codprograma=dmr.programa INNER JOIN periodo pe ON pe.periodos=dmr.periodo WHERE dmr.id > ? AND pe.periodoActivo = 1 ORDER BY dmr.id ASC");
            $consultaDataMafiReplica->bindValue(1,$offset,PDO::PARAM_INT);
            $consultaDataMafiReplica->execute();
            return $consultaDataMafiReplica;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function log($accion,$tabla){
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

    function dataMafiReplica($offset){
        try {
            $consultaDataMafiReplica = $this->db->connect()->prepare("SELECT dmr.*,p.activo AS programaActivo FROM `datosmafireplica` dmr INNER JOIN programas p ON p.codprograma=dmr.programa INNER JOIN periodo pe ON pe.periodos=dmr.periodo WHERE dmr.id > ? AND pe.periodoActivo = 1 ORDER BY dmr.id ASC LIMIT 100");
            $consultaDataMafiReplica->bindValue(1,$offset,PDO::PARAM_INT);
            $consultaDataMafiReplica->execute();
            return $consultaDataMafiReplica;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function historialEstudiante($idBanner){
        try {
            $consultaHistorial = $this->db->connect()->prepare("SELECT COUNT(ha.codMateria) AS `historial` FROM `datosMafiReplica` dmr INNER JOIN historialAcademico ha ON ha.codBanner=dmr.idbanner WHERE dmr.idbanner = ?");
            $consultaHistorial->bindValue(1,$idBanner,PDO::PARAM_INT);
            $consultaHistorial->execute();
            return $consultaHistorial;
        }catch (PDOException $e) {
            return false;
        }
    }

    public function insertarEstudiante($codigoBanner,$nombre,$programa,$bolsa,$operador,$nodo,$tipoEstudiante,$tieneHistorial,$programaAbrio,$marcaIngreso){
        //var_dump($codigoBanner,$nombre,$programa,$bolsa,$operador,$nodo,$tipoEstudiante,$tieneHistorial,$programaAbrio,$marcaIngreso);
        try {
            $insertarEstudiante = $this->db->connect()->prepare("INSERT INTO `estudiantes` SET 
                                                                            `homologante` = ?, 
                                                                            `nombre` = ?, 
                                                                            `programa` = ?, 
                                                                            `bolsa` = ?, 
                                                                            `operador` = ?, 
                                                                            `nodo` = ?, 
                                                                            `tipo_estudiante` = ?,  
                                                                            `tiene_historial` = ?, 
                                                                            `programaActivo` = ?, 
                                                                            `marca_ingreso` = ?, 
                                                                            `created_at` = NOW(), 
                                                                            `updated_at` = NOW()");
            $insertarEstudiante->bindValue(1,$codigoBanner,PDO::PARAM_INT);
            $insertarEstudiante->bindValue(2,$nombre,PDO::PARAM_STR);
            $insertarEstudiante->bindValue(3,$programa,PDO::PARAM_STR);
            $insertarEstudiante->bindValue(4,$bolsa,PDO::PARAM_STR);
            $insertarEstudiante->bindValue(5,$operador,PDO::PARAM_STR);
            $insertarEstudiante->bindValue(6,$nodo,PDO::PARAM_STR);
            $insertarEstudiante->bindValue(7,$tipoEstudiante,PDO::PARAM_STR);
            $insertarEstudiante->bindValue(8,$tieneHistorial,PDO::PARAM_STR);
            $insertarEstudiante->bindValue(9,$programaAbrio,PDO::PARAM_STR);
            $insertarEstudiante->bindValue(10,$marcaIngreso,PDO::PARAM_STR);
            $insertarEstudiante->execute();
            //var_dump($insertarEstudiante);die();
            return $insertarEstudiante;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function insertarLogAplicacion($primerID,$ultimoRegistroId,$fechaInicio,$fechaFin,$acccion,$tablaAfectada,$descripcion){
        try {
            $insertarLog = $this->db->connect()->prepare("INSERT INTO `logaplicacion` SET
                                                                        `idInicio` = ?, 
                                                                        `idFin` = ?, 
                                                                        `fechaInicio` = ?, 
                                                                        `fechaFin` = ?, 
                                                                        `accion` = ?, 
                                                                        `tabla_afectada` = ?, 
                                                                        `descripcion` = ?, 
                                                                        `created_at` = NOW(), 
                                                                        `updated_at` = NOW()");
            $insertarLog->bindValue(1,$primerID,PDO::PARAM_INT);
            $insertarLog->bindValue(2,$ultimoRegistroId,PDO::PARAM_STR);
            $insertarLog->bindValue(3,$fechaInicio,PDO::PARAM_STR);
            $insertarLog->bindValue(4,$fechaFin,PDO::PARAM_STR);
            $insertarLog->bindValue(5,$acccion,PDO::PARAM_STR);
            $insertarLog->bindValue(6,$tablaAfectada,PDO::PARAM_STR);
            $insertarLog->bindValue(7,$descripcion,PDO::PARAM_STR);
            $insertarLog->execute();
            return $insertarLog;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function insertIndiceCambio($idBannerUltimoRegistro,$acccion,$descripcion,$fecha){
        try {
            $insertIndiceCambios = $this->db->connect()->prepare("INSERT INTO `indece_cambios_mafi` SET  
                                                                                `idbanner` = ?, 
                                                                                `accion` = ?, 
                                                                                `descripcion` = ?, 
                                                                                `fecha` = ?, 
                                                                                `created_at` = NOW(), 
                                                                                `updated_at` = NOW()");
            $insertIndiceCambios->bindValue(1,$idBannerUltimoRegistro,PDO::PARAM_INT);
            $insertIndiceCambios->bindValue(2,$acccion,PDO::PARAM_STR);
            $insertIndiceCambios->bindValue(3,$descripcion,PDO::PARAM_STR);
            $insertIndiceCambios->bindValue(4,$fecha,PDO::PARAM_STR);
            $insertIndiceCambios->execute();
            return $insertIndiceCambios;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function insertarAlerta($codigoBanner,$tipoEstudiante,$mensajeAlerta){
        try {
            $insertAlerta = $this->db->connect()->prepare("INSERT INTO `alertas_tempranas` SET `idbanner` = ?, `tipo_estudiante` = ?, `desccripcion` = ?, `created_at` = NOW(), `updated_at` = NOW()");
            $insertAlerta->bindValue(1,$codigoBanner,PDO::PARAM_INT);
            $insertAlerta->bindValue(2,$tipoEstudiante,PDO::PARAM_STR);
            $insertAlerta->bindValue(3,$mensajeAlerta,PDO::PARAM_STR);
            $insertAlerta->execute();
            return $insertAlerta;
        } catch (PDOException $e) {
            return false;
        }
    }
}