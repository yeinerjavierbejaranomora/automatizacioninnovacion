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

    public function getEstudiantes($marcaIngreso){
        //var_dump($marcaIngreso);die();
        //try {
            $consultaEstudiantes = $this->db->connect()->prepare("SELECT `id`,`homologante`,`programa`,`bolsa`,`tipo_estudiante` FROM `estudiantes` 
            WHERE `materias_faltantes` = 'OK' 
            AND `programado_ciclo1` IS NULL
            AND `programado_ciclo2` IS NULL
            AND `marca_ingreso` IN (?)
            ORDER BY `id` ASC");
            $consultaEstudiantes->bindValue(1,$marcaIngreso,PDO::PARAM_STR);
            $consultaEstudiantes->execute();
            var_dump($consultaEstudiantes);die();
            /*return $consultaEstudiantes;
        } catch (PDOException $e) {
            return false;
        }*/
    }
}