<?php
class ProgramarPrimerCicloModel{
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

    public function getEstudiantes($offset,$marcaIngreso,$limit){
        try {
            $consultaEstudiantes = $this->db->connect()->prepare("SELECT `id`,`homologante`,`programa`,`bolsa`,`tipo_estudiante`,`marca_ingreso` FROM `estudiantes` 
            WHERE `id` > ?
            AND `materias_faltantes` = 'OK' 
            AND `planeado_ciclo1` = 'OK' 
            AND `planeado_ciclo2` = 'OK'  
            AND `programado_ciclo1` IS NULL 
            AND `programado_ciclo2` IS NULL 
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
    
    public function getEstudiantesNum($offset,$marcaIngreso){
        //var_dump($marcaIngreso);die();
        try {
            $consultaEstudiantes = $this->db->connect()->prepare("SELECT `id`,`homologante` FROM `estudiantes` 
            WHERE `id` > ?
            AND `materias_faltantes` = 'OK'
            AND `planeado_ciclo1` = 'OK' 
            AND `planeado_ciclo2` = 'OK'  
            AND `programado_ciclo1` IS NULL 
            AND `programado_ciclo2` IS NULL 
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

    public function materiasMoodle($codBanner){
        try {
            $consultaMateriaMoodle = $this->db->connect()->prepare("SELECT `Id_Banner`,`Tipo_Estudiante`,`codigomateria`,`Nota_Acumulada` FROM `datos_moodle` WHERE `Id_Banner`= ? AND`Nota_Acumulada` >= '3' AND `Nota_Acumulada` != 'Sin Actividad'");
            $consultaMateriaMoodle->bindValue(1,$codBanner,PDO::PARAM_INT);
            $consultaMateriaMoodle->execute();
            return $consultaMateriaMoodle;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function materiasPorVer($codigoBanner,$programa,$materias_moodle){
        //var_dump($codigoBanner,$programa);die();
        try {
            $consultaMateriasPorVer = $this->db->connect()->prepare("SELECT mpv.codBanner,mpv.codMateria,mpv.orden,m.creditos,m.ciclo,m.prerequisito FROM `materiasPorVer` mpv 
            INNER JOIN `mallaCurricular` m ON m.codigoCurso=mpv.codMateria
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
            $consultaCreditosPlaneados = $this->db->connect()->prepare("SELECT `programacion`.`codBanner`, SUM(mallaCurricular.creditos) AS `CreditosPlaneados` FROM `mallaCurricular` INNER JOIN `programacion` ON `programacion`.`codMateria` = `mallaCurricular`.`codigoCurso` WHERE `programacion`.`codBanner` = ? GROUP BY `programacion`.`codBanner` LIMIT 1");
            $consultaCreditosPlaneados->bindParam(1,$codigoBanner,PDO::PARAM_INT);
            $consultaCreditosPlaneados->execute();
            return $consultaCreditosPlaneados;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getCreditosCicloUno($codigoBanner){
        try {
            $consultaCreditosCicloUno = $this->db->connect()->prepare("SELECT SUM(`mallaCurricular`.`creditos`) AS `screditos`,COUNT(`mallaCurricular`.`creditos`) AS `ccursos` FROM `mallaCurricular` INNER JOIN `programacion` ON `programacion`.`codMateria` = `mallaCurricular`.`codigoCurso` WHERE `programacion`.`codBanner` = ? AND `mallaCurricular`.`ciclo` IN (1,12) LIMIT 1");
            $consultaCreditosCicloUno->bindParam(1,$codigoBanner,PDO::PARAM_INT);
            $consultaCreditosCicloUno->execute();
            return $consultaCreditosCicloUno;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function getCreditosCicloUnoOrden($codigoBanner){
        try {
            $consultaCreditosCicloUno = $this->db->connect()->prepare("SELECT SUM(`mallaCurricular`.`creditos`) AS `screditos`,COUNT(`mallaCurricular`.`creditos`) AS `ccursos` FROM `mallaCurricular` INNER JOIN `programacion` ON `programacion`.`codMateria` = `mallaCurricular`.`codigoCurso` WHERE `programacion`.`codBanner` = ?  LIMIT 1");
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

    public function prerequisitos($codMateria,$programa){
        try {
            $consultaPrerequisitos = $this->db->connect()->prepare("SELECT `prerequisito` FROM `mallaCurricular` WHERE `codigoCurso` = ? AND `codprograma` = ? LIMIT 1");
            $consultaPrerequisitos->bindParam(1,$codMateria,PDO::PARAM_STR);
            $consultaPrerequisitos->bindParam(2,$programa,PDO::PARAM_STR);
            $consultaPrerequisitos->execute();
            return $consultaPrerequisitos;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function estaProgramacion($codMateria,$codBanner){
        try {
            $consultaestaProgramacion = $this->db->connect()->prepare("SELECT `codMateria` FROM `programacion` WHERE `codMateria` = ?  AND `codBanner` = ?");
            $consultaestaProgramacion->bindParam(1,$codMateria,PDO::PARAM_STR);
            $consultaestaProgramacion->bindParam(2,$codBanner,PDO::PARAM_INT);
            $consultaestaProgramacion->execute();
            return $consultaestaProgramacion;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function estaProgramacionPrerequisitos($prerequisitos,$codBanner){
        try {
            $consultaestaProgramacionPrerequisitos = $this->db->connect()->prepare("SELECT `codMateria` FROM `programacion` WHERE `codMateria` IN ($prerequisitos)  AND `codBanner` = ?");
            $consultaestaProgramacionPrerequisitos->bindParam(1,$codBanner,PDO::PARAM_STR);
            $consultaestaProgramacionPrerequisitos->execute();
            return $consultaestaProgramacionPrerequisitos;
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

    public function insertarProgramacion($codBanner,$codMateria,$orden,$semestre,$programada,$programa){
        
        try {
            $fecha = date('Y-m-d H:i:s');
            $insertProgramacion = $this->db->connect()->prepare("INSERT INTO `programacion` SET 
            `codBanner`= ?, 
            `codMateria`= ?, 
            `orden`= ?, 
            `semestre`= ?, 
            `programada`= ?, 
            `codprograma`= ?, 
            `fecha_registro` = ?");
            $insertProgramacion->bindValue(1,$codBanner,PDO::PARAM_INT);
            $insertProgramacion->bindValue(2,$codMateria,PDO::PARAM_STR);
            $insertProgramacion->bindValue(3,$orden,PDO::PARAM_INT);
            $insertProgramacion->bindValue(4,$semestre,PDO::PARAM_INT);
            $insertProgramacion->bindValue(5,$programada,PDO::PARAM_STR);
            $insertProgramacion->bindValue(6,$programa,PDO::PARAM_STR);
            $insertProgramacion->bindValue(7,$fecha,PDO::PARAM_STR);
            $insertProgramacion->execute();
            if($insertProgramacion):
                return true;
            endif;
        } catch (PDOException $e) {
            return false;
        }
    }


    public function updateEstudiante($estudianteId,$codBanner){
        try {
            $udpateEstudiante = $this->db->connect()->prepare("UPDATE `estudiantes` SET `programado_ciclo1`='OK' WHERE `id` = ? AND `homologante` = ?");
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