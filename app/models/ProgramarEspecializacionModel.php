<?php
class ProgramarEspecializacionModel {
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function periodos(){
        try {
            $consultaPeriodo = $this->db->connect()->prepare("SELECT `periodos` FROM `periodo` WHERE `activoCiclo1` = 1");
            $consultaPeriodo->execute();
            return $consultaPeriodo;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function periodosEspecializacion(){
        try {
            $consultaPeriodo = $this->db->connect()->prepare("SELECT `periodos` FROM `periodo` WHERE `periodoActivo` = 1");
            $consultaPeriodo->execute();
            return $consultaPeriodo;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function logAplicacion($accion,$tabla){
        try {
            $consultaLog = $this->db->connect()->prepare("SELECT `idFin` FROM `logAplicacion` WHERE `accion` = ? AND `tabla_afectada` = ? ORDER BY `id` DESC LIMIT 1");
            $consultaLog->bindValue(1,$accion,PDO::PARAM_STR);
            $consultaLog->bindValue(2,$tabla,PDO::PARAM_STR);
            $consultaLog->execute();
            return $consultaLog;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getEstudiantesNum($offset,$marcaIngreso){
        //var_dump($marcaIngreso);die();
        try {
            $consultaEstudiantes = $this->db->connect()->prepare("SELECT `id`,`homologante` FROM `estudiantes` 
            WHERE `id` > ?
            AND `materias_faltantes` = 'OK'
            /*AND `planeado_ciclo1` = 'OK' */
            /*AND `planeado_ciclo2` = 'OK'  
            AND `programado_ciclo1` IS NULL 
            AND `programado_ciclo2` IS NULL */
            AND `marca_ingreso` IN ($marcaIngreso) 
            /*AND `programa` != 'PPSV'*/ 
            ORDER BY `id` ASC");
            $consultaEstudiantes->bindParam(1,$offset,PDO::PARAM_INT);
            $consultaEstudiantes->execute();
            return $consultaEstudiantes;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getEstudiantes($offset,$marcaIngreso,$limit){
        try {
            $consultaEstudiantes = $this->db->connect()->prepare("SELECT `id`,`homologante`,`programa`,`marca_ingreso`,`tipo_estudiante` FROM `estudiantes` 
            WHERE `id` > ?
            AND `materias_faltantes` = 'OK'
            /*AND `planeado_ciclo1` = 'OK' */
            /*AND `planeado_ciclo2` = 'OK'  
            AND `programado_ciclo1` IS NULL 
            AND `programado_ciclo2` IS NULL */
            AND `marca_ingreso` IN ($marcaIngreso) 
            /*AND `programa` != 'PPSV'*/ 
            ORDER BY `id` ASC
            LIMIT ?");
            $consultaEstudiantes->bindParam(1,$offset,PDO::PARAM_INT);
            $consultaEstudiantes->bindParam(2,$limit,PDO::PARAM_INT);
            $consultaEstudiantes->execute();
            return $consultaEstudiantes;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function consultaSemestre($codigoBanner, $programa){
        try {
            $consultaSemetre = $this->db->connect()->prepare("SELECT h.`codBanner`,h.`codMateria`,m.semestre FROM `historialAcademico` h
            INNER JOIN mallaCurricular m ON m.codigoCurso=h.codMateria
            WHERE h.`codBanner` = ? AND h.`codprograma` = ? ORDER BY h.`codMateria` DESC LIMIT 1");
            $consultaSemetre->bindParam(1,$codigoBanner,PDO::PARAM_INT);
            $consultaSemetre->bindParam(2,$programa,PDO::PARAM_STR);
            $consultaSemetre->execute();
            return $consultaSemetre;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function materiasPorVer($codigoBanner,$programa,$ciclo,$semestre){
        //var_dump($codigoBanner,$programa,$ciclo,$semestre);die();
        try {
            $consultaMateriasPorVer = $this->db->connect()->prepare("SELECT mpv.codBanner,mpv.codMateria,mpv.orden,m.semestre,m.creditos,m.ciclo,m.prerequisito FROM `materiasPorVer` mpv 
            INNER JOIN `mallaCurricular` m ON m.codigoCurso=mpv.codMateria
            WHERE mpv.codBanner = ?  
            AND m.ciclo IN ($ciclo)
            AND m.semestre = ?
            AND mpv.codprograma = ?
            AND m.codprograma = ?
            ORDER BY mpv.orden ASC");
            $consultaMateriasPorVer->bindValue(1,$codigoBanner,PDO::PARAM_INT);
            $consultaMateriasPorVer->bindValue(2,$semestre,PDO::PARAM_INT);
            $consultaMateriasPorVer->bindValue(3,$programa,PDO::PARAM_STR);
            $consultaMateriasPorVer->bindValue(4,$programa,PDO::PARAM_STR);
            $consultaMateriasPorVer->execute();
            return $consultaMateriasPorVer;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function insertarAlerta($codigoBanner,$marca_ingreso,$programa,$tipoAlerta, $tipoEstudiante, $mensajeAlerta){
        try {
            $insertAlerta = $this->db->connect()->prepare("INSERT INTO `alertas_tempranas` SET `idbanner` = ?, `tipo_estudiante` = ?, `codprograma` = ?, `periodo` = ?, `activo` = ?,`tipo` = ?, `desccripcion` = ?, `created_at` = NOW(), `updated_at` = NOW()");
            $insertAlerta->bindValue(1,$codigoBanner,PDO::PARAM_INT);
            $insertAlerta->bindValue(2,$tipoEstudiante,PDO::PARAM_STR);
            $insertAlerta->bindValue(3,$programa,PDO::PARAM_STR);
            $insertAlerta->bindValue(4,$marca_ingreso,PDO::PARAM_STR);
            $insertAlerta->bindValue(5,1,PDO::PARAM_INT);
            $insertAlerta->bindValue(6,$tipoAlerta,PDO::PARAM_STR);
            $insertAlerta->bindValue(7,$mensajeAlerta,PDO::PARAM_STR);
            $insertAlerta->execute();
            return $insertAlerta;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function insertarProgramacion($codBanner,$codMateria,$orden,$semestre,$programada,$programa,$marca_ingreso){
        try {
            $fecha = date('Y-m-d H:i:s');
            $insertProgramacion = $this->db->connect()->prepare("INSERT INTO `programacion` SET 
            `codBanner`= ?, 
            `codMateria`= ?, 
            `orden`= ?, 
            `semestre`= ?, 
            `programada`= ?, 
            `codprograma`= ?,
            `periodo` = ?, 
            `fecha_registro` = ?");
            $insertProgramacion->bindValue(1,$codBanner,PDO::PARAM_INT);
            $insertProgramacion->bindValue(2,$codMateria,PDO::PARAM_STR);
            $insertProgramacion->bindValue(3,$orden,PDO::PARAM_INT);
            $insertProgramacion->bindValue(4,$semestre,PDO::PARAM_INT);
            $insertProgramacion->bindValue(5,$programada,PDO::PARAM_STR);
            $insertProgramacion->bindValue(6,$programa,PDO::PARAM_STR);
            $insertProgramacion->bindValue(7,$marca_ingreso,PDO::PARAM_STR);
            $insertProgramacion->bindValue(8,$fecha,PDO::PARAM_STR);
            $insertProgramacion->execute();
            if($insertProgramacion):
                return true;
            endif;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function insertarLogAplicacion($primerID,$ultimoRegistroId,$fechaInicio,$fechaFin,$acccion,$tablaAfectada,$descripcion){
        try {
            $fecha = date('Y-m-d H:i:s');
            $insertarLog = $this->db->connect()->prepare("INSERT INTO `logAplicacion` SET
                                                                        `idInicio` = ?, 
                                                                        `idFin` = ?, 
                                                                        `fechaInicio` = ?, 
                                                                        `fechaFin` = ?, 
                                                                        `accion` = ?, 
                                                                        `tabla_afectada` = ?, 
                                                                        `descripcion` = ?, 
                                                                        `created_at` = ?, 
                                                                        `updated_at` = ?");
            $insertarLog->bindValue(1,$primerID,PDO::PARAM_INT);
            $insertarLog->bindValue(2,$ultimoRegistroId,PDO::PARAM_STR);
            $insertarLog->bindValue(3,$fechaInicio,PDO::PARAM_STR);
            $insertarLog->bindValue(4,$fechaFin,PDO::PARAM_STR);
            $insertarLog->bindValue(5,$acccion,PDO::PARAM_STR);
            $insertarLog->bindValue(6,$tablaAfectada,PDO::PARAM_STR);
            $insertarLog->bindValue(7,$descripcion,PDO::PARAM_STR);
            $insertarLog->bindValue(8,$fecha,PDO::PARAM_STR);
            $insertarLog->bindValue(9,$fecha,PDO::PARAM_STR);
            $insertarLog->execute();
            return $insertarLog;
        } catch (PDOException $e) {
            return false;
        }
    }
}