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
        //try {
            $data = [];
            $consultaBaseAcademica = $this->db->connect()->prepare("SELECT m.codigoCurso,m.orden,m.codprograma FROM `mallaCurricular` m 
                                                                    INNER JOIN programas p ON p.codprograma=m.codprograma
                                                                    INNER JOIN programasPeriodos pp ON pp.codPrograma=m.codprograma
                                                                    WHERE m.codprograma = ?
                                                                    AND pp.periodo = ?
                                                                    ORDER BY semestre ASC, orden ASC");
            $consultaBaseAcademica->bindParam(1,$programa,PDO::PARAM_STR);
            $consultaBaseAcademica->bindParam(2,$periodo,PDO::PARAM_INT);
            $consultaBaseAcademica->execute();
            $orden = 1;
            foreach($consultaBaseAcademica as $key => $value):
                var_dump($value);DIE();
                $data[] = [
                    'codBanner' => $codBanner,
                    'codMateria' => $value['codigoCurso'],
                    'orden' => $orden,
                    'codprograma' => $value['codprograma'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $orden++;
            endforeach;
        /*} catch (PDOException $e) {
            return false;
        }*/
    }
}