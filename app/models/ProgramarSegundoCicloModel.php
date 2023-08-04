<?php
class ProgramarSegundoCicloModel{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function periodos(){
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
        try {
            $consultaEstudiantes = $this->db->connect()->prepare("SELECT id, homologante, programa,tipo_estudiante FROM estudiantes 
            WHERE `id` > ?
            AND materias_faltantes='OK' 
            AND programado_ciclo1='OK' 
            AND programado_ciclo2 IS NULL 
            AND marca_ingreso IN (202305,202312,202332,202342,202352,202306,202313,202333,202343,202353) 
            ORDER BY id ASC");
            $consultaEstudiantes->bindParam(1,$offset,PDO::PARAM_INT);
            $consultaEstudiantes->execute();
            return $consultaEstudiantes;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getEstudiantes($offset,$marcaIngreso,$limit){
        try {
            $consultaEstudiantes = $this->db->connect()->prepare("SELECT id, homologante, programa,tipo_estudiante FROM estudiantes 
            WHERE `id` > ?
            AND materias_faltantes='OK' 
            AND programado_ciclo1='OK' 
            AND programado_ciclo2 IS NULL 
            AND marca_ingreso IN (202305,202312,202332,202342,202352,202306,202313,202333,202343,202353) 
            ORDER BY id ASC 
            LIMIT ?");
            $consultaEstudiantes->bindParam(1,$offset,PDO::PARAM_INT);
            $consultaEstudiantes->bindParam(2,$limit,PDO::PARAM_INT);
            $consultaEstudiantes->execute();
            return $consultaEstudiantes;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function materiasPlaneadas($codHomologante,$programaHomologante){
        try {
            $consultaMateriasplaneadas = $this->db->connect()->prepare("SELECT p.codBanner, p.codMateria FROM planeacion p INNER JOIN mallaCurricular mc ON p.codMateria=mc.codigoCurso WHERE codBanner=? AND p.codprograma = ? AND mc.codprograma = ?");
            $consultaMateriasplaneadas->bindValue(1,$codHomologante,PDO::PARAM_INT);
            $consultaMateriasplaneadas->bindValue(2,$programaHomologante,PDO::PARAM_STR);
            $consultaMateriasplaneadas->bindValue(3,$programaHomologante,PDO::PARAM_STR);
            $consultaMateriasplaneadas->execute();
            return $consultaMateriasplaneadas;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function materiasPorVer($codHomologante,$programaHomologante,$materias_planeadas){
        try {
            $consultaMateriasPorVer = $this->db->connect()->prepare("SELECT mv.codBanner, mv.codMateria, mv.orden, mc.creditos, mc.ciclo,mc.prerequisito  FROM materiasPorVer mv INNER JOIN mallaCurricular mc ON mv.codMateria=mc.codigoCurso WHERE codBanner=? AND mv.codprograma = ? AND mc.codprograma = ? AND mv.codMateria NOT IN ($materias_planeadas) ORDER BY mv.orden ASC");
            $consultaMateriasPorVer->bindParam(1,$codHomologante,PDO::PARAM_INT);
            $consultaMateriasPorVer->bindParam(2,$programaHomologante,PDO::PARAM_STR);
            $consultaMateriasPorVer->bindParam(3,$programaHomologante,PDO::PARAM_STR);
            $consultaMateriasPorVer->execute();
            return $consultaMateriasPorVer;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getCreditosplaneados($codHomologante){
        try {
            $consultaCreditosplaneados = $this->db->connect()->prepare("SELECT p.codBanner, SUM(mc.creditos) AS CreditosPlaneados FROM mallaCurricular mc INNER JOIN planeacion p ON mc.codigoCurso=p.codMateria WHERE p.codBanner=? AND mc.codprograma=p.codprograma group by p.codbanner");
            $consultaCreditosplaneados->bindValue(1,$codHomologante,PDO::PARAM_INT);
            $consultaCreditosplaneados->execute();
            return $consultaCreditosplaneados;
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

    public function updateEstudinate($idHomologante,$codHomologante){
        try {
            $updateEstudiante = $this->db->connect()->prepare("UPDATE `estudiantes` SET `programado_ciclo2`='OK' WHERE `id` = ? AND `homologante` = ?");
            $updateEstudiante->bindParam(1,$idHomologante,PDO::PARAM_INT);
            $updateEstudiante->bindParam(2,$codHomologante,PDO::PARAM_INT);
            $updateEstudiante->execute();
            if ($updateEstudiante == true):
                return true;
            endif;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function consultaPrerequisitos($codMateria,$programaHomologante){
        try {
            $consultaPrerequisitos = $this->db->connect()->prepare("SELECT prerequisito FROM mallaCurricular WHERE codigoCurso=? AND codprograma = ?");
            $consultaPrerequisitos->bindParam(1,$codMateria,PDO::PARAM_STR);
            $consultaPrerequisitos->bindParam(2,$programaHomologante,PDO::PARAM_STR);
            $consultaPrerequisitos->execute();
            return $consultaPrerequisitos;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getReglasNegocio($programa,$ruta,$tipoEstudiante,$cicloReglaNegocio){
        try {
            $consultaReglasNegocio = $this->db->connect()->prepare("SELECT `creditos`,`materiasPermitidas` FROM `reglasNegocio` WHERE `programa` = ? AND `ruta` = ? AND `tipoEstudiante` = ? AND `ciclo` =? AND `activo` = 1");
            $consultaReglasNegocio->bindParam(1,$programa,PDO::PARAM_INT);
            $consultaReglasNegocio->bindParam(2,$ruta,PDO::PARAM_INT);
            $consultaReglasNegocio->bindParam(3,$tipoEstudiante,PDO::PARAM_STR);
            $consultaReglasNegocio->bindParam(4,$cicloReglaNegocio,PDO::PARAM_INT);
            $consultaReglasNegocio->execute();
            return $consultaReglasNegocio;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function estaPlaneacion($codMateria,$codBanner){
        try {
            $consultaEstaPlaneacion= $this->db->connect()->prepare("SELECT codMateria FROM planeacion WHERE codMateria=? AND codBanner=?");
            $consultaEstaPlaneacion->bindValue(1,$codMateria,PDO::PARAM_STR);
            $consultaEstaPlaneacion->bindValue(2,$codBanner,PDO::PARAM_INT);
            $consultaEstaPlaneacion->execute();
            return $consultaEstaPlaneacion;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function insertarPlaneacion($codBanner,$codMateria,$orden2,$semestre,$programada,$programaHomologante){
        
        try {
            $fecha = date('Y-m-d H:i:s');
            $insertPlaneacion = $this->db->connect()->prepare("INSERT INTO `planeacion` SET 
            `codBanner`= ?, 
            `codMateria`= ?, 
            `orden`= ?, 
            `semestre`= ?, 
            `programada`= ?, 
            `codprograma`= ?, 
            `fecha_registro` = ?");
            $insertPlaneacion->bindValue(1,$codBanner,PDO::PARAM_INT);
            $insertPlaneacion->bindValue(2,$codMateria,PDO::PARAM_STR);
            $insertPlaneacion->bindValue(3,$orden2,PDO::PARAM_INT);
            $insertPlaneacion->bindValue(4,$semestre,PDO::PARAM_INT);
            $insertPlaneacion->bindValue(5,$programada,PDO::PARAM_STR);
            $insertPlaneacion->bindValue(6,$programaHomologante,PDO::PARAM_STR);
            $insertPlaneacion->bindValue(7,$fecha,PDO::PARAM_STR);
            $insertPlaneacion->execute();
            if($insertPlaneacion):
                return true;
            endif;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function estaPlaneacionPrerequisitos($prerequisitos,$codBanner){
        try {
            $consultaEstaPlaneacionPrerequisitos = $this->db->connect()->prepare("SELECT `codMateria` FROM `planeacion` WHERE `codMateria` IN ($prerequisitos)  AND `codBanner` = ?");
            $consultaEstaPlaneacionPrerequisitos->bindParam(1,$codBanner,PDO::PARAM_STR);
            $consultaEstaPlaneacionPrerequisitos->execute();
            return $consultaEstaPlaneacionPrerequisitos;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function estaPorVer($prerequisitos,$codBanner){
        try {
            $consultaEstaPorVer = $this->db->connect()->prepare("SELECT `codMateria` FROM `materiasPorVer` WHERE `codMateria` IN ($prerequisitos)  AND `codBanner` = ? ORDER BY `id` ASC LIMIT 1");
            $consultaEstaPorVer->bindParam(1,$codBanner,PDO::PARAM_STR);
            $consultaEstaPorVer->execute();
            return $consultaEstaPorVer;
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

    public function insertIndiceCambio($idBannerUltimoRegistro,$acccion,$descripcion,$fecha){
        try {
            $fecha = date('Y-m-d H:i:s');
            $insertIndiceCambios = $this->db->connect()->prepare("INSERT INTO `indice_cambios_mafi` SET  
                                                                                `idbanner` = ?, 
                                                                                `accion` = ?, 
                                                                                `descripcion` = ?, 
                                                                                `fecha` = ?, 
                                                                                `created_at` = ?, 
                                                                                `updated_at` = ?");
            $insertIndiceCambios->bindValue(1,$idBannerUltimoRegistro,PDO::PARAM_INT);
            $insertIndiceCambios->bindValue(2,$acccion,PDO::PARAM_STR);
            $insertIndiceCambios->bindValue(3,$descripcion,PDO::PARAM_STR);
            $insertIndiceCambios->bindValue(4,$fecha,PDO::PARAM_STR);
            $insertIndiceCambios->bindValue(5,$fecha,PDO::PARAM_STR);
            $insertIndiceCambios->bindValue(6,$fecha,PDO::PARAM_STR);
            $insertIndiceCambios->execute();
            return $insertIndiceCambios;
        } catch (PDOException $e) {
            return false;
        }
    }
}