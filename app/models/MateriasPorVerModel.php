<?php

class MateriasPorVerModel{

    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    /*public function getPeriodo(){
        try {
            $consultaPeriodo = $this->db->connect()->prepare("SELECT * FROM `periodo`");
            $consultaPeriodo->execute();
            return $consultaPeriodo;
        } catch (PDOException $e) {
            return false;
        }
    }*/

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

    public function falatntesPrimerIngreso($offset){
        try {
            $consultaEstPrimerIngreso = $this->db->connect()->prepare("SELECT * FROM `estudiantes` WHERE `id` > ? AND `tipo_estudiante` LIKE 'PRIMER%' AND `programaActivo` IS NULL AND `materias_faltantes` IS NULL OR `tipo_estudiante` LIKE 'INGRESO%' AND `programaActivo` IS NULL AND `materias_faltantes` IS NULL");
            $consultaEstPrimerIngreso->bindParam(1,$offset,PDO::PARAM_INT);
            $consultaEstPrimerIngreso->execute();
            return $consultaEstPrimerIngreso;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function baseAcademica($codBanner,$programa,$periodo){
        //var_dump($codBanner,$programa,$periodo);die();
        try {
            $data = [];
            $consultaBaseAcademica = $this->db->connect()->prepare("SELECT m.codigoCurso,m.orden,m.codprograma FROM `mallaCurricular` m 
                                                                    INNER JOIN programas p ON p.codprograma=m.codprograma
                                                                    INNER JOIN programasPeriodos pp ON pp.codPrograma=m.codprograma
                                                                    WHERE m.codprograma = ?
                                                                    AND pp.periodo = ?
                                                                    ORDER BY semestre ASC, orden ASC
                                                                    ");
            $consultaBaseAcademica->bindParam(1,$programa,PDO::PARAM_STR);
            $consultaBaseAcademica->bindParam(2,$periodo,PDO::PARAM_INT);
            $consultaBaseAcademica->execute();
            $orden = 1;
            foreach ($consultaBaseAcademica as $key => $value) :
                $data[] = [
                    'codBanner' => $codBanner,
                    'codMateria' => $value['codigoCurso'],
                    'orden' => $orden,
                    'codprograma' => $value['codprograma'],
                    /*'created_at' => now(),
                    'updated_at' => now(),*/
                ];
                $orden++;
            endforeach;
            //var_dump($data);die();
            return $data;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function insertMateriaPorVer($mallaCurricular){
        try {
            $numInsert= 0;
            foreach($mallaCurricular as $malla):
                $insertMateriaPorVer = $this->db->connect()->prepare("INSERT INTO `materiasPorVer` SET 
                                                                                    `codBanner` = ?, 
                                                                                    `codMateria` = ?, 
                                                                                    `orden` = ?, 
                                                                                    `codprograma` = ?, 
                                                                                    `created_at` = NOW(), 
                                                                                    `updated_at` = NOW()");
                $insertMateriaPorVer->bindValue(1,$malla['codBanner'],PDO::PARAM_INT);
                $insertMateriaPorVer->bindValue(2,$malla['codMateria'],PDO::PARAM_INT);
                $insertMateriaPorVer->bindValue(3,$malla['orden'],PDO::PARAM_INT);
                $insertMateriaPorVer->bindValue(4,$malla['codprograma'],PDO::PARAM_INT);
                $insertMateriaPorVer->execute();
                $numInsert++;
            endforeach;
            if($numInsert == count($mallaCurricular)):
                return $numInsert;
            endif;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateEstudiante($estudianteId,$codBanner){
        try {
            $udpateEstudiante = $this->db->connect()->prepare("UPDATE `estudiantes` SET `materias_faltantes`='OK' WHERE `id` = ? AND `homologante` = ? ");
            $udpateEstudiante->bindParam(1,$estudianteId,PDO::PARAM_INT);
            $udpateEstudiante->bindParam(2,$codBanner,PDO::PARAM_INT);
            $udpateEstudiante->execute();
            if($udpateEstudiante):
                return true;
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

    public function faltantesTransferentes($offset){
        try {
            $consultaEstTransferente = $this->db->connect()->prepare("SELECT * FROM `estudiantes`
                                                                        WHERE `id` > ?
                                                                        AND `tipo_estudiante` like 'TRANSFERENTE%'
                                                                        AND `programaActivo` IS NULL
                                                                        AND `tiene_historial` IS NULL
                                                                        AND `materias_faltantes` IS NULL
                                                                        AND `programa` != 'MED'");
            $consultaEstTransferente->bindParam(1,$offset,PDO::PARAM_INT);
            $consultaEstTransferente->execute();
            return $consultaEstTransferente;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function historial($codBanner){
        try {
            $data=[];
            $consultaHistorial = $this->db->connect()->prepare("SELECT `codMateria`,`codBanner` FROM `historialAcademico` WHERE `codBanner` = ? AND `codMateria` != 'na'");
            $consultaHistorial->bindParam(1,$codBanner,PDO::PARAM_INT);
            $consultaHistorial->execute();
            foreach($consultaHistorial as $historial):
                $data[] = [
                    'codMateria'=>$historial['codMateria'],
                    'codprograma'=>$historial['codprograma'],
                ];
            endforeach;
            var_dump($data);die();
            return $consultaHistorial;
        } catch (PDOException $e) {
            return false;
        }
    }
}