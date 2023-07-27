<?php
class Programarprimerciclo extends Controller{

    private $model;

    public function __construct()
    {
        $this->model = $this->model("ProgramarPrimerCicloModel");
    }

    /*public function periodo(){
        $fechaActual = date('Y-m-d');
        $mesActual = date('m');
        $mesActual = 06;
        $periodo = $this->model->getPeriodo();
        foreach($periodo as $value):
            $ciclo1 = explode('-',$value['fechaInicioCiclo1']);
            $ciclo2 = explode('-',$value['fechaInicioCiclo2']);
            if(in_array($mesActual,$ciclo1) || in_array($mesActual,$ciclo2)):
                var_dump("SI");
            else:
                var_dump("No");
            endif;
        endforeach;
        die();
        return $mesActual;
    }*/

    public function primerciclo(){
        $periodos = $this->model->periodos();
        $marcaIngreso = "";
        foreach ($periodos as $periodo) {
            $marcaIngreso .= (int)$periodo['periodos'] . ",";
        }
        $marcaIngreso = trim($marcaIngreso, ",");
        // Dividir la cadena en elementos individuales
        /*$marcaIngreso = explode(",", $marcaIngreso);
        // Convertir cada elemento en un número
        $marcaIngreso = array_map('intval', $marcaIngreso);*/
        //var_dump($marcaIngreso);die();
        $log = $this->model->logAplicacion('Insert-PlaneacionPrimerCiclo', 'planeacion');
        if (!$log) :
            $offset = 0;
        else :
            $offset = $log->fetch(PDO::FETCH_ASSOC)['idFin'];
        endif;
        $limit = 1000;
        $estudiantes = $this->model->getEstudiantes($offset,$marcaIngreso,$limit);
        if($estudiantes->rowCount() > 0):
            foreach($estudiantes as $estudiante):
                $fechaInicio = date('Y-m-d H:i:s');
                $primerId = $estudiante['id'];
                $ultimoRegistroId = 0;
                $idEstudiante = $estudiante['id'];
                $codigoBanner = $estudiante['homologante'];
                $programa = $estudiante['programa'];
                $ruta = $estudiante['bolsa'];
                if ($ruta != '') :
                    $ruta = 1;
                else:
                    $ruta = 0;
                endif;
                $tipoEstudiante = $estudiante['tipo_estudiante'];

                switch ($tipoEstudiante) {
                    case str_contains($tipoEstudiante, 'TRANSFERENTE'):
                        $tipoEstudiante = 'TRANSFERENTE';
                        break;
                    case str_contains($tipoEstudiante, 'ESTUDIANTE ANTIGUO'):
                        $tipoEstudiante = 'ESTUDIANTE ANTIGUO';
                        break;
                    case str_contains($tipoEstudiante, 'PRIMER INGRESO'):
                        $tipoEstudiante = 'PRIMER INGRESO';
                        break;
                    case str_contains($tipoEstudiante, 'PSEUDO ACTIVOS'):
                        $tipoEstudiante = 'ESTUDIANTE ANTIGUO';
                        break;
                    case str_contains($tipoEstudiante, 'REINGRESO'):
                        $tipoEstudiante = 'ESTUDIANTE ANTIGUO';
                        break;
                    case str_contains($tipoEstudiante, 'INGRESO SINGULAR'):
                        $tipoEstudiante = 'PRIMER INGRESO';
                        break;

                    default:
                        # code...
                        break;
                }
                $ciclo = [1, 12];
                $materiasPorVer = $this->model->materiasPorVer($codigoBanner,$ciclo,$programa);
                $numeroCreditos = $this->model->getCreditosPlaneados($codigoBanner);
                $numeroCreditos = $numeroCreditos->rowCount() == 0 ? 0 : $numeroCreditos->fetch(PDO::FETCH_ASSOC)['CreditosPlaneados'];
                $numeroCreditosC1 = $this->model->getCreditosCicloUno($codigoBanner);
                $sumaCreditosCiclo1 = $numeroCreditosC1->fetch(PDO::FETCH_ASSOC)['screditos'];
                $sumaCreditosCiclo1 = $sumaCreditosCiclo1 == '' ? 0 : $sumaCreditosCiclo1;
                $cuentaCursosCiclo1 = $numeroCreditosC1->fetch(PDO::FETCH_ASSOC)['ccursos'];
                $cuentaCursosCiclo1 = $cuentaCursosCiclo1 == 0 ? 0 : $cuentaCursosCiclo1;
                $cicloReglaNegocio = 1;
                $reglasNegocioConsulta = $this->model->getReglasNegocio($programa,$ruta,$tipoEstudiante,$cicloReglaNegocio);
                $reglasNegocio = $reglasNegocioConsulta->fetchAll(PDO::FETCH_ASSOC);
                $numeroCreditosPermitidos = $reglasNegocio['creditos'];
                $numeroMateriasPermitidos = $reglasNegocio['materiasPermitidas'];
                $orden = 1;
                //var_dump($programa,$materiasPorVer->fetchAll());die();

                foreach($materiasPorVer as $materia):
                    if ($cuentaCursosCiclo1 >= $numeroMateriasPermitidos) :
                        break;
                    endif;
                    $codBanner = $materia['codBanner'];
                    $codMateria = $materia['codMateria'];
                    $creditoMateria = $materia['creditos'];
                    $ciclo = $materia['ciclo'];
                    $prerequisitosConsulta = $this->model->prerequisitos($codMateria,$programa);
                    var_dump($prerequisitosConsulta->rowCount());
                endforeach;
                die();
            endforeach;
        else:
            echo "No hay estudiantes de primer ciclo para programar <br>";
        endif;
    }
}