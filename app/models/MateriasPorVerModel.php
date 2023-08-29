<?php

class MateriasPorVerModel{

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
            $consultaLog = $this->db->connect()->prepare("SELECT * FROM `logAplicacion` WHERE `accion` = ? AND `tabla_afectada` = ? ORDER BY `id` DESC LIMIT 1");
            $consultaLog->bindValue(1,$accion,PDO::PARAM_STR);
            $consultaLog->bindValue(2,$tabla,PDO::PARAM_STR);
            $consultaLog->execute();
            return $consultaLog;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function falatntesPrimerIngreso($offset,$marcaIngreso){
        try {
            $consultaEstPrimerIngreso = $this->db->connect()->prepare("SELECT `id`,`homologante`,`programa`,`marca_ingreso` FROM `estudiantes` 
            WHERE `id` > ? 
            AND `programa` NOT IN ('MED')
            /*AND `programa` NOT IN ('MED','EFCC','EAU','EFAC','EASV','EGSV','ESST','EGFV','EAGV','EGYV','EMDV','EDIV','EDIA','ENEV','EABV')*/
            AND `tipo_estudiante` LIKE 'PRIMER%'  
            AND `programaActivo` IS NULL 
            AND `materias_faltantes` IS NULL
            AND `observacion` IS NULL
            AND `marca_ingreso` IN ($marcaIngreso)
            OR `id` > ?  
            AND `programa` NOT IN ('MED')
            /*AND `programa` NOT IN ('MED','EFCC','EAU','EFAC','EASV','EGSV','ESST','EGFV','EAGV','EGYV','EMDV','EDIV','EDIA','ENEV','EABV')*/
            AND `tipo_estudiante` LIKE 'INGRESO%' 
            AND `programaActivo` IS NULL 
            AND `materias_faltantes` IS NULL
            AND `observacion` IS NULL
            AND `marca_ingreso` IN ($marcaIngreso)");
            $consultaEstPrimerIngreso->bindParam(1,$offset,PDO::PARAM_INT);
            $consultaEstPrimerIngreso->bindParam(2,$offset,PDO::PARAM_INT);
            $consultaEstPrimerIngreso->execute();
            return $consultaEstPrimerIngreso;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function baseAcademica($codBanner,$programa,$periodo,$marcaIngreso){
        //var_dump($codBanner,$programa,$periodo,$marcaIngreso);die();
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
                    'periodo' => $marcaIngreso,
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
        //var_dump($mallaCurricular);die();
        try {
            $numInsert= 0;
            foreach($mallaCurricular as $malla):
                $fecha = date('Y-m-d H:i:s');
                $insertMateriaPorVer = $this->db->connect()->prepare("INSERT INTO `materiasPorVer` SET 
                                                                                    `codBanner` = ?, 
                                                                                    `codMateria` = ?, 
                                                                                    `orden` = ?, 
                                                                                    `codprograma` = ?,
                                                                                    `periodo` = ?, 
                                                                                    `created_at` = ?, 
                                                                                    `updated_at` = ?");
                $insertMateriaPorVer->bindValue(1,$malla['codBanner'],PDO::PARAM_INT);
                $insertMateriaPorVer->bindValue(2,$malla['codMateria'],PDO::PARAM_INT);
                $insertMateriaPorVer->bindValue(3,$malla['orden'],PDO::PARAM_INT);
                $insertMateriaPorVer->bindValue(4,$malla['codprograma'],PDO::PARAM_INT);
                $insertMateriaPorVer->bindValue(5,$malla['periodo'],PDO::PARAM_STR);
                $insertMateriaPorVer->bindValue(6,$fecha,PDO::PARAM_STR);
                $insertMateriaPorVer->bindValue(7,$fecha,PDO::PARAM_STR);
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

    public function faltantesTransferentesNum($offset,$marcaIngreso){
        try {
            $consultaEstTransferente = $this->db->connect()->prepare("SELECT * FROM `estudiantes`
            WHERE `id` > ?
            AND `tipo_estudiante` like 'TRANSFERENTE%'
            AND `programaActivo` IS NULL
            AND `tiene_historial` IS NULL
            AND `materias_faltantes` IS NULL
            AND `programa` NOT IN ('MED')
            /*AND `programa` NOT IN ('MED','EFCC','EAU','EFAC','EASV','EGSV','ESST','EGFV','EAGV','EGYV','EMDV','EDIV','EDIA','ENEV','EABV')*/
            AND `observacion` IS NULL
            AND `marca_ingreso` IN ($marcaIngreso)");
            $consultaEstTransferente->bindParam(1,$offset,PDO::PARAM_INT);
            $consultaEstTransferente->execute();
            return $consultaEstTransferente;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function faltantesTransferentes($offset,$limit,$marcaIngreso){
        try {
            $consultaEstTransferente = $this->db->connect()->prepare("SELECT * FROM `estudiantes`
            WHERE `id` > ?
            AND `tipo_estudiante` like 'TRANSFERENTE%'
            AND `programaActivo` IS NULL
            AND `tiene_historial` IS NULL
            AND `materias_faltantes` IS NULL
            AND `programa` NOT IN ('MED')
            /*AND `programa` NOT IN ('MED','EFCC','EAU','EFAC','EASV','EGSV','ESST','EGFV','EAGV','EGYV','EMDV','EDIV','EDIA','ENEV','EABV')*/
            AND `observacion` IS NULL
            AND `marca_ingreso` IN ($marcaIngreso)
            LIMIT ?");
            $consultaEstTransferente->bindParam(1,$offset,PDO::PARAM_INT);
            $consultaEstTransferente->bindParam(2,$limit,PDO::PARAM_INT);
            $consultaEstTransferente->execute();
            return $consultaEstTransferente;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function historial($codBanner,$programa){
        try {
            $data=[];
            $consultaHistorial = $this->db->connect()->prepare("SELECT `codMateria`,`codprograma`,`nota` FROM `historialAcademico` WHERE `codBanner` = ? AND `codprograma` = ? AND `codMateria` != 'na' GROUP BY `codMateria`");
            $consultaHistorial->bindParam(1,$codBanner,PDO::PARAM_INT);
            $consultaHistorial->bindParam(2,$programa,PDO::PARAM_STR);
            $consultaHistorial->execute();
            foreach($consultaHistorial as $historial):
                $data[] = [
                    'codMateria'=>$historial['codMateria'],
                    'codprograma'=>$historial['codprograma'],
                    'nota'=>$historial['nota'],
                ];
            endforeach;
            return $data;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function historialMoodle($codBanner){
        try {
            $data=[];
            $consultaHistorial = $this->db->connect()->prepare("SELECT `codigomateria`,`Grupo`,`Nota_Acumulada` FROM `datos_moodle` WHERE `Id_Banner` = ?");
            $consultaHistorial->bindParam(1,$codBanner,PDO::PARAM_INT);
            $consultaHistorial->execute();
            foreach($consultaHistorial as $historial):
                $programa = explode('_',$historial['Grupo']);
                $codprograma = $programa[1];
                //if ($historial['Nota_Acumulada'] >= 3 && $historial['Nota_Acumulada'] != 'Sin Actividad') :
                $data[] = [
                    'codMateria' => $historial['codigomateria'],
                    'codprograma' => $codprograma,
                    'nota' => $historial['Nota_Acumulada'],
                ];
                //endif;
            endforeach;
            return $data;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function totalEstudiantes($offset,$marcaIngreso){
        try {
            $consultaTotalEstudiantes = $this->db->connect()->prepare("SELECT COUNT(`id`) AS `total` FROM `estudiantes`
            WHERE `id` > ?
            AND `tipo_estudiante` LIKE 'ESTUDIANTE ANTIGUO%'
            AND `programaActivo` IS NULL
            AND `materias_faltantes` IS NULL
            AND `programa` NOT IN ('MED','EFCC','EAU','EFAC','EASV','EGSV','ESST','EGFV','EAGV','EGYV','EMDV','EDIV','EDIA','ENEV','EABV')
            AND `observacion` IS NULL
            AND `marca_ingreso` IN ($marcaIngreso)
            OR `id` > ?
            AND `tipo_estudiante` LIKE 'PSEUDO ACTIVOS%'
            AND `programaActivo` IS NULL
            AND `materias_faltantes` IS NULL
            AND `programa` NOT IN ('MED','EFCC','EAU','EFAC','EASV','EGSV','ESST','EGFV','EAGV','EGYV','EMDV','EDIV','EDIA','ENEV','EABV')
            AND `observacion` IS NULL
            AND `marca_ingreso` IN ($marcaIngreso)
            OR `id` > ? 
            AND `tipo_estudiante` = 'REINGRESO'
            AND `programaActivo` IS NULL
            AND `materias_faltantes` IS NULL
            AND `programa` NOT IN ('MED','EFCC','EAU','EFAC','EASV','EGSV','ESST','EGFV','EAGV','EGYV','EMDV','EDIV','EDIA','ENEV','EABV')
            AND `observacion` IS NULL
            AND `marca_ingreso` IN ($marcaIngreso)
            ORDER BY `id` ASC");
            $consultaTotalEstudiantes->bindParam(1,$offset,PDO::PARAM_INT);
            $consultaTotalEstudiantes->bindParam(2,$offset,PDO::PARAM_INT);
            $consultaTotalEstudiantes->bindParam(3,$offset,PDO::PARAM_INT);
            $consultaTotalEstudiantes->execute();
            return $consultaTotalEstudiantes->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (PDOException $e) {
            return false;
        }
    }

    public function faltantesAntiguos($offset,$limit,$marcaIngreso){
        try{
            $consultaEstudiantesAntiguos = $this->db->connect()->prepare("SELECT * FROM `estudiantes`
            WHERE `id` > ?
            AND `tipo_estudiante` LIKE 'ESTUDIANTE ANTIGUO%'
            AND `programaActivo` IS NULL
            AND `materias_faltantes` IS NULL
            AND `programa` NOT IN ('MED','EFCC','EAU','EFAC','EASV','EGSV','ESST','EGFV','EAGV','EGYV','EMDV','EDIV','EDIA','ENEV','EABV')
            AND `observacion` IS NULL
            AND `marca_ingreso` IN ($marcaIngreso)
            OR `id` > ? AND `tipo_estudiante` LIKE 'PSEUDO ACTIVOS%'
            AND `programaActivo` IS NULL
            AND `materias_faltantes` IS NULL
            AND `programa` NOT IN ('MED','EFCC','EAU','EFAC','EASV','EGSV','ESST','EGFV','EAGV','EGYV','EMDV','EDIV','EDIA','ENEV','EABV')
            AND `observacion` IS NULL
            AND `marca_ingreso` IN ($marcaIngreso)
            OR `id` > ? AND `tipo_estudiante` = 'REINGRESO'
            AND `programaActivo` IS NULL
            AND `materias_faltantes` IS NULL
            AND `programa` NOT IN ('MED','EFCC','EAU','EFAC','EASV','EGSV','ESST','EGFV','EAGV','EGYV','EMDV','EDIV','EDIA','ENEV','EABV')
            AND `observacion` IS NULL
            AND `marca_ingreso` IN ($marcaIngreso)
            ORDER BY `id` ASC
            LIMIT ?");
            $consultaEstudiantesAntiguos->bindParam(1,$offset,PDO::PARAM_INT);
            $consultaEstudiantesAntiguos->bindParam(2,$offset,PDO::PARAM_INT);
            $consultaEstudiantesAntiguos->bindParam(3,$offset,PDO::PARAM_INT);
            $consultaEstudiantesAntiguos->bindParam(4,$limit,PDO::PARAM_INT);
            $consultaEstudiantesAntiguos->execute();
            return $consultaEstudiantesAntiguos;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function upateEstuianteAntiguo($estudianteId,$codBanner){
        try {
            $udpateEstudiante = $this->db->connect()->prepare("UPDATE `estudiantes` SET `materias_faltantes`='YA VIO TODO',`tiene_historial`='COMPLETO' WHERE `id` = ? AND `homologante` = ? ");
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
}