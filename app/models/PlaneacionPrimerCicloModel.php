<?php 
class PlaneacionPrimerCicloModel{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
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

    public function periodos(){
        try {
            $consultaPeriodo = $this->db->connect()->prepare("SELECT `periodos` FROM `periodo` WHERE `activoCiclo1` = 1");
            $consultaPeriodo->execute();
            return $consultaPeriodo;
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
            AND `planeado_ciclo1` IS NULL 
            AND `planeado_ciclo2` IS NULL 
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
            AND `planeado_ciclo1` IS NULL 
            AND `planeado_ciclo2` IS NULL 
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

    public function materiasMoodle($codBanner){
        try {
            $consultaMateriaMoodle = $this->db->connect()->prepare("SELECT `Id_Banner`,`Tipo_Estudiante`,`codigomateria`,`Nota_Acumulada` FROM `datos_moodle` WHERE `Id_Banner`= ?");
            $consultaMateriaMoodle->bindValue(1,$codBanner,PDO::PARAM_INT);
            $consultaMateriaMoodle->execute();
            return $consultaMateriaMoodle;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function materiasPorVer($codigoBanner,$ciclo,$programa,$materias_moodle){
        try {
            $consultaMateriasPorVer = $this->db->connect()->prepare("SELECT mpv.codBanner,mpv.codMateria,mpv.orden,m.creditos,m.ciclo,m.prerequisito FROM `materiasPorVer` mpv 
            INNER JOIN mallaCurricular m ON m.codigoCurso=mpv.codMateria
            WHERE mpv.codBanner = ?
            AND m.ciclo IN (1,12)
            AND mpv.codprograma = ?
            AND m.codprograma = ?
            AND mpv.codMateria NOT IN ($materias_moodle)
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

    public function materiasPorVerOrden($codigoBanner,$programa,$materias_moodle){
        try {
            $consultaMateriasPorVer = $this->db->connect()->prepare("SELECT mpv.codBanner,mpv.codMateria,mpv.orden,m.creditos,m.ciclo,m.prerequisito FROM `materiasPorVer` mpv 
            INNER JOIN mallaCurricular m ON m.codigoCurso=mpv.codMateria
            WHERE mpv.codBanner = ?
            /*AND m.ciclo IN (1,12)*/
            AND mpv.codprograma = ?
            AND m.codprograma = ?
            AND mpv.codMateria NOT IN ($materias_moodle)
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

    public function getCreditosCicloUno($codigoBanner){
        try {
            $consultaCreditosCicloUno = $this->db->connect()->prepare("SELECT SUM(`mallaCurricular`.`creditos`) AS `screditos`,COUNT(`mallaCurricular`.`creditos`) AS `ccursos` FROM `mallaCurricular` INNER JOIN `planeacion` ON `planeacion`.`codMateria` = `mallaCurricular`.`codigoCurso` WHERE `planeacion`.`codBanner` = ? AND `mallaCurricular`.`ciclo` IN (1,12) LIMIT 1");
            $consultaCreditosCicloUno->bindParam(1,$codigoBanner,PDO::PARAM_INT);
            $consultaCreditosCicloUno->execute();
            return $consultaCreditosCicloUno;
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
            $consultaEstaPlaneacion = $this->db->connect()->prepare("SELECT `codMateria` FROM `planeacion` WHERE `codMateria` = ?  AND `codBanner` = ?");
            $consultaEstaPlaneacion->bindParam(1,$codMateria,PDO::PARAM_STR);
            $consultaEstaPlaneacion->bindParam(2,$codBanner,PDO::PARAM_INT);
            $consultaEstaPlaneacion->execute();
            return $consultaEstaPlaneacion;
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

    public function insertarPlaneacion($codBanner,$codMateria,$orden,$semestre,$programada,$programa,$marca_ingreso){
        
        try {
            $fecha = date('Y-m-d H:i:s');
            $insertPlaneacion = $this->db->connect()->prepare("INSERT INTO `planeacion` SET 
            `codBanner`= ?, 
            `codMateria`= ?, 
            `orden`= ?, 
            `semestre`= ?, 
            `programada`= ?, 
            `codprograma`= ?,
            `periodo` = ?, 
            `fecha_registro` = ?");
            $insertPlaneacion->bindValue(1,$codBanner,PDO::PARAM_INT);
            $insertPlaneacion->bindValue(2,$codMateria,PDO::PARAM_STR);
            $insertPlaneacion->bindValue(3,$orden,PDO::PARAM_INT);
            $insertPlaneacion->bindValue(4,$semestre,PDO::PARAM_INT);
            $insertPlaneacion->bindValue(5,$programada,PDO::PARAM_STR);
            $insertPlaneacion->bindValue(6,$programa,PDO::PARAM_STR);
            $insertPlaneacion->bindValue(7,$marca_ingreso,PDO::PARAM_STR);
            $insertPlaneacion->bindValue(8,$fecha,PDO::PARAM_STR);
            $insertPlaneacion->execute();
            if($insertPlaneacion):
                return true;
            endif;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateEstudiante($estudianteId,$codBanner){
        try {
            $udpateEstudiante = $this->db->connect()->prepare("UPDATE `estudiantes` SET `planeado_ciclo1`='OK' WHERE `id` = ? AND `homologante` = ?");
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