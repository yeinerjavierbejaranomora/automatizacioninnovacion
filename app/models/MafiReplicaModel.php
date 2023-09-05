<?php
class MafiReplicaModel{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function numeroDatosMafi($offset){
        try {
            $consultaDataMafiReplica =$this->db->connect()->prepare("SELECT COUNT(`idbanner`) as `totalEstudiantes` FROM `datosMafiReplica` dmr INNER JOIN periodo pe ON pe.periodos=dmr.periodo WHERE dmr.id > ? AND pe.periodoActivo = 1 ORDER BY dmr.id ASC");
            $consultaDataMafiReplica->bindValue(1,$offset,PDO::PARAM_INT);
            $consultaDataMafiReplica->execute();
            return $consultaDataMafiReplica;
        } catch (PDOException $e) {
            return false;
        }
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

    function dataMafiReplica($offset,$limit){
        //var_dump($offset);die();
        try {
            $consultaDataMafiReplica = $this->db->connect()->prepare("SELECT dmr.*,p.nivelFormacion FROM `datosMafiReplica` dmr 
            INNER JOIN periodo pe ON pe.periodos=dmr.periodo 
            INNER JOIN programas p ON p.codprograma=dmr.codprograma 
            WHERE dmr.id > ? 
            AND pe.periodoActivo = 1 
            ORDER BY dmr.id ASC  
            limit ?");
            $consultaDataMafiReplica->bindValue(1,$offset,PDO::PARAM_INT);
            $consultaDataMafiReplica->bindValue(2,$limit,PDO::PARAM_INT);
            $consultaDataMafiReplica->execute();
            //var_dump($consultaDataMafiReplica);die();
            return $consultaDataMafiReplica;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function programaActivo($codigoBanner,$periodo){
        try {
            $consultaProgramaActivo = $this->db->connect()->prepare("SELECT pp.estado AS `programaActivo` FROM `datosMafiReplica` dmr INNER JOIN programasPeriodos pp ON pp.codPrograma=dmr.codprograma  WHERE dmr.idbanner = ? AND pp.periodo = ? ORDER BY pp.`id` ASC LIMIT 1");
            $consultaProgramaActivo->bindParam(1,$codigoBanner,PDO::PARAM_INT);
            $consultaProgramaActivo->bindParam(2,$periodo,PDO::PARAM_INT);
            $consultaProgramaActivo->execute();
            return $consultaProgramaActivo;
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

    public function insertarEstudiante($codigoBanner,$nombre,$programa,$bolsa,$operador,$nodo,$tipoEstudiante,$tieneHistorial,$programaAbrio,$marcaIngreso,$observacion,$sello,$autorizadoAsistir){
        //var_dump($codigoBanner,$nombre,$programa,$bolsa,$operador,$nodo,$tipoEstudiante,$tieneHistorial,$programaAbrio,$marcaIngreso,$observacion);die();
        try {
            $insertarEstudiante = $this->db->connect()->prepare("INSERT INTO `estudiantes` SET 
                                                                            `homologante` = ?, 
                                                                            `nombre` = ?, 
                                                                            `programa` = ?, 
                                                                            `bolsa` = ?, 
                                                                            `operador` = ?, 
                                                                            `nodo` = ?, 
                                                                            `tipo_estudiante` = ?, 
                                                                            `sello` = ? , 
                                                                            `autorizado_asistir` = ?,
                                                                            `tiene_historial` = ?, 
                                                                            `programaActivo` = ?, 
                                                                            `observacion` = ?, 
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
            $insertarEstudiante->bindValue(8,$sello,PDO::PARAM_STR);
            $insertarEstudiante->bindValue(9,$autorizadoAsistir,PDO::PARAM_STR);
            $insertarEstudiante->bindValue(10,$tieneHistorial,PDO::PARAM_STR);
            $insertarEstudiante->bindValue(11,$programaAbrio,PDO::PARAM_STR);
            $insertarEstudiante->bindValue(12,$observacion,PDO::PARAM_STR);
            $insertarEstudiante->bindValue(13,$marcaIngreso,PDO::PARAM_STR);
            $insertarEstudiante->execute();
            //var_dump($insertarEstudiante);die();
            return $insertarEstudiante;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function insertHistorial($arrayhistorial){
        //var_dump(count($historial));die();
        try {
            $numInsert= 0;
            foreach($arrayhistorial as $historial):
                //var_dump($historial);die();
                $insertHistorial = $this->db->connect()->prepare("INSERT INTO `historialAcademico2` SET 
                `codBanner` = ?, 
                `nombreEst` = ?, 
                `institucionOrigen` = ?, 
                `codprograma` = ?, 
                `programa` = ?, 
                `codMateria` = ?, 
                `nombreMat` = ?, 
                `nota` = ?");
                $insertHistorial->bindValue(1,$historial['codBanner'],PDO::PARAM_INT);
                $insertHistorial->bindValue(2,$historial['nombreEst'],PDO::PARAM_STR);
                $insertHistorial->bindValue(3,$historial['institucionOrigen'],PDO::PARAM_STR);
                $insertHistorial->bindValue(4,$historial['codprograma'],PDO::PARAM_STR);
                $insertHistorial->bindValue(5,$historial['programa'],PDO::PARAM_STR);
                $insertHistorial->bindValue(6,$historial['codMateria'],PDO::PARAM_STR);
                $insertHistorial->bindValue(7,$historial['nombreMat'],PDO::PARAM_STR);
                $insertHistorial->bindValue(8,$historial['nota'],PDO::PARAM_STR);
                $insertHistorial->execute();
                $numInsert++;
                //var_dump($insertHistorial);die();
            endforeach;
            if($numInsert == count($arrayhistorial)):
                return $numInsert;
            endif;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function insertarLogAplicacion($primerID,$ultimoRegistroId,$fechaInicio,$fechaFin,$acccion,$tablaAfectada,$descripcion){
        try {
            $insertarLog = $this->db->connect()->prepare("INSERT INTO `logAplicacion` SET
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
            $insertIndiceCambios = $this->db->connect()->prepare("INSERT INTO `indice_cambios_mafi` SET  
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

    public function insertarAlerta($codigoBanner,$marcaIngreso,$programa,$tipoEstudiante,$mensajeAlerta){
        try {
            $insertAlerta = $this->db->connect()->prepare("INSERT INTO `alertas_tempranas` SET `idbanner` = ?, `tipo_estudiante` = ?, `codprograma` = ?, `periodo` = ?, `activo` = ?, `desccripcion` = ?, `created_at` = NOW(), `updated_at` = NOW()");
            $insertAlerta->bindValue(1,$codigoBanner,PDO::PARAM_INT);
            $insertAlerta->bindValue(2,$tipoEstudiante,PDO::PARAM_STR);
            $insertAlerta->bindValue(3,$programa,PDO::PARAM_STR);
            $insertAlerta->bindValue(4,$marcaIngreso,PDO::PARAM_STR);
            $insertAlerta->bindValue(5,0,PDO::PARAM_INT);
            $insertAlerta->bindValue(6,$mensajeAlerta,PDO::PARAM_STR);
            $insertAlerta->execute();
            return $insertAlerta;
        } catch (PDOException $e) {
            return false;
        }
    }
}