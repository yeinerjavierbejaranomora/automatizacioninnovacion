<?php
class ProgramarPrimerCicloModel{
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

    public function getEstudiantes($offset,$marcaIngreso,$limit){
        try {
            $consultaEstudiantes = $this->db->connect()->prepare("SELECT `id`,`homologante`,`programa`,`bolsa`,`tipo_estudiante` FROM `estudiantes` 
            WHERE `id` > ?
            AND `materias_faltantes` = 'OK' 
            AND `programado_ciclo1` IS NULL 
            AND `programado_ciclo2` IS NULL 
            AND `marca_ingreso` IN ($marcaIngreso) 
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

    public function materiasPorVer($codigoBanner,$ciclo,$programa){
        try {
            $consultaMateriasPorVer = $this->db->connect()->prepare("SELECT mpv.codBanner,mpv.codMateria,mpv.orden,m.creditos,m.ciclo FROM `materiasPorVer` mpv 
            INNER JOIN mallaCurricular m ON m.codigoCurso=mpv.codMateria
            WHERE mpv.codBanner = ?
            AND m.ciclo IN (1,12)
            AND mpv.codprograma = ?
            AND m.codprograma = ?
            ORDER BY mpv.orden ASC");
            $consultaMateriasPorVer->bindValue(1,$codigoBanner,PDO::PARAM_INT);
            $consultaMateriasPorVer->bindValue(2,$programa,PDO::PARAM_STR);
            $consultaMateriasPorVer->bindValue(3,$programa,PDO::PARAM_STR);
            $consultaMateriasPorVer->execute();
            return $consultaMateriasPorVer;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getCreditosPlaneados($codigoBanner){
        try {
            $consultaCreditosPlaneados = $this->db->connect()->prepare("SELECT `planeacion`.`codBanner`, SUM(mallaCurricular.creditos) AS `CreditosPlaneados` FROM `mallaCurricular` INNER JOIN `planeacion` ON `planeacion`.`codMateria` = `mallaCurricular`.`codigoCurso` WHERE `planeacion`.`codBanner` = ? GROUP BY `planeacion`.`codBanner` LIMIT 1");
            $consultaCreditosPlaneados->bindParam(1,$codigoBanner,PDO::PARAM_INT);
            $consultaCreditosPlaneados->execute();
            return $consultaCreditosPlaneados;
        } catch (PDOException $e) {
            return false;
        }
    }
}