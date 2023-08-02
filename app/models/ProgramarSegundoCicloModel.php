<?php
class ProgramarSegundoCicloModel{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function periodos(){
        try {
            $consultaPeriodo = $this->db->connect()->prepare("SELECT * FROM `periodo` WHERE `periodoActivo` = 1");
            $consultaPeriodo->execute();
            return $consultaPeriodo;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function logAplicacion($accion,$tabla){
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
            $consultaMateriasPorVer = $this->db->connect()->prepare("SELECT mv.codBanner, mv.codMateria, mv.orden, mc.creditos, mc.ciclo FROM materiasPorVer mv INNER JOIN mallaCurricular mc ON mv.codMateria=mc.codigoCurso WHERE codBanner=? AND mv.codprograma = ? AND mc.codprograma = ? AND mv.codMateria NOT IN ($materias_planeadas) ORDER BY mv.orden ASC");
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
}