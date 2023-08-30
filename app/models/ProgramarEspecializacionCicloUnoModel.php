<?php
class ProgramarEspecializacionCicloUnoModel {
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
            $consultaEstudiantes = $this->db->connect()->prepare("SELECT `id`,`homologante`,`programa`,`bolsa`,`tipo_estudiante`,`marca_ingreso` FROM `estudiantes` 
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
}