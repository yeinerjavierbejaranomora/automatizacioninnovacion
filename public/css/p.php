public function dataMafiReplica(){

$log = $this->model->log('Insert', 'estudiantes');
$logFecth = $log->fetch(PDO::FETCH_ASSOC);
if (!empty($logFecth)) :
    $offset = $logFecth['idFin'];
else :
    $offset = 0;
endif;
$numerodatosMafi = $this->model->numerodatosMafi($offset);
$numeroDatos = $numerodatosMafi->fetch(PDO::FETCH_ASSOC)['totalEstudiantes'];
for ($i = 0; $i < $numeroDatos; $i++) :
    $log = $this->model->log('Insert', 'estudiantes');
    $logFecth = $log->fetch(PDO::FETCH_ASSOC);
    if (!empty($logFecth)) :
        $offset = $logFecth['idFin'];
    else :
        $offset = 0;
    endif;
    $datosMafi = $this->model->dataMafiReplica($offset);
    if ($datosMafi) :
        $datosMafiFetch = $datosMafi->fetch(PDO::FETCH_ASSOC);
        $numeroRegistros = 0;
        $numeroRegistrosAlertas = 0;
        $primerID = $this->model->dataMafiReplica($offset)->fetch(PDO::FETCH_ASSOC)['id'];
        $ultimoRegistroId = 0;
        $fechaInicio = date('Y-m-d H:i:s');
        echo $fechaInicio;
        $codigoBanner = $datosMafiFetch['idbanner'];
        $nombre = $datosMafiFetch['primer_apellido'];
        $programa = $datosMafiFetch['programa'];
        $bolsa = $datosMafiFetch['ruta_academica'];
        $operador = $datosMafiFetch['operador'];
        $nodo = 'nodo';
        $tipoEstudiante = $datosMafiFetch['tipoestudiante'];
        $marcaIngreso = $datosMafiFetch['periodo'];
        $tieneHistorial = NULL;
        $programaAbrio = NULL;
        if (str_contains($tipoEstudiante, 'TRANSFERENTE')) :
            $historial = $this->model->historialEstudiante($codigoBanner);
            $historialCount = $historial->fetch(PDO::FETCH_ASSOC)['historial'];
            if ($historialCount == 0) :
                if ($datosMafiFetch['programaActivo'] < 1) :
                    $tieneHistorial = 'SIN HISTORIAL';
                    $programaAbrio = 'NO SE ABRIO PROGRAMA';
                    $insertarEstudiante = $this->model->insertarEstudiante($codigoBanner, $nombre, $programa, $bolsa, $operador, $nodo, $tipoEstudiante, $tieneHistorial, $programaAbrio, $marcaIngreso);
                    $mensajeAlerta = 'El estudiante con idBanner' . $codigoBanner . ' es "TRANSFERENTE" y no tiene historial academico';
                    $insertarAlertaTemprana = $this->model->insertarAlerta($codigoBanner, $tipoEstudiante, $mensajeAlerta);
                    if ($insertarAlertaTemprana) :
                        $numeroRegistrosAlertas++;
                    endif;
                    $mensajeAlerta = 'NO SE ABRIO PROGRAMA ' . $programa;
                    $insertarAlertaTemprana = $this->model->insertarAlerta($codigoBanner, $tipoEstudiante, $mensajeAlerta);
                    if ($insertarAlertaTemprana) :
                        $numeroRegistrosAlertas++;
                    endif;
                else :
                    $tieneHistorial = 'SIN HISTORIAL';
                    $insertarEstudiante = $this->model->insertarEstudiante($codigoBanner, $nombre, $programa, $bolsa, $operador, $nodo, $tipoEstudiante, $tieneHistorial, $programaAbrio, $marcaIngreso);
                    $mensajeAlerta = 'El estudiante con idBanner' . $codigoBanner . ' es "TRANSFERENTE" y no tiene historial academico';
                    $insertarAlertaTemprana = $this->model->insertarAlerta($codigoBanner, $tipoEstudiante, $mensajeAlerta);
                    if ($insertarAlertaTemprana) :
                        $numeroRegistrosAlertas++;
                    endif;
                endif;
                if ($insertarEstudiante) :
                    $numeroRegistros++;
                endif;
            else :
                if ($datosMafiFetch['programaActivo'] > 0) :
                    $insertarEstudiante = $this->model->insertarEstudiante($codigoBanner, $nombre, $programa, $bolsa, $operador, $nodo, $tipoEstudiante, $tieneHistorial, $programaAbrio, $marcaIngreso);
                else :
                    $programaAbrio = 'NO SE ABRIO PROGRAMA';
                    $insertarEstudiante = $this->model->insertarEstudiante($codigoBanner, $nombre, $programa, $bolsa, $operador, $nodo, $tipoEstudiante, $tieneHistorial, $programaAbrio, $marcaIngreso);
                    $mensajeAlerta = 'NO SE ABRIO PROGRAMA ' . $programa;
                    $insertarAlertaTemprana = $this->model->insertarAlerta($codigoBanner, $tipoEstudiante, $mensajeAlerta);
                    if ($insertarAlertaTemprana) :
                        $numeroRegistrosAlertas++;
                    endif;
                endif;
                if ($insertarEstudiante) :
                    $numeroRegistros++;
                endif;
            endif;
        else :
            if ($datosMafiFetch['programaActivo'] > 0) :
                $insertarEstudiante = $this->model->insertarEstudiante($codigoBanner, $nombre, $programa, $bolsa, $operador, $nodo, $tipoEstudiante, $tieneHistorial, $programaAbrio, $marcaIngreso);
            else :
                $programaAbrio = 'NO SE ABRIO PROGRAMA';
                $insertarEstudiante = $this->model->insertarEstudiante($codigoBanner, $nombre, $programa, $bolsa, $operador, $nodo, $tipoEstudiante, $tieneHistorial, $programaAbrio, $marcaIngreso);
                $mensajeAlerta = 'NO SE ABRIO PROGRAMA ' . $programa;
                $insertarAlertaTemprana = $this->model->insertarAlerta($codigoBanner, $tipoEstudiante, $mensajeAlerta);
                if ($insertarAlertaTemprana) :
                    $numeroRegistrosAlertas++;
                endif;
            endif;
            if ($insertarEstudiante) :
                $numeroRegistros++;
            endif;
        endif;
        $ultimoRegistroId = $datosMafiFetch['id'];
        $idBannerUltimoRegistro = $datosMafiFetch['idbanner'];

        $fechaFin = date('Y-m-d H:i:s');
        $acccion = 'Insert';
        $tablaAfectada = 'estudiantes';
        $descripcion = 'Se realizo la insercion en la tabla estudiantes desde la tabla datosMafiReplica, iniciando en el id ' . $primerID . ' y terminando en el id ' . $ultimoRegistroId . ',insertando ' . $numeroRegistros . ' registros';
        $fecha = date('Y-m-d H:i:s');
        $insertarLogAplicacion = $this->model->insertarLogAplicacion($primerID, $ultimoRegistroId, $fechaInicio, $fechaFin, $acccion, $tablaAfectada, $descripcion);
        $insertIndiceCambio = $this->model->insertIndiceCambio($idBannerUltimoRegistro, $acccion, $descripcion, $fecha);
        echo  "Numero de registros: " . $numeroRegistros . "=> primer id registrado: " . $primerID . ', Ultimo id registrado ' . $ultimoRegistroId .
            "<br> Numero de registrosen alertas: " . $numeroRegistrosAlertas .
            "<br> inicio:" . $fechaInicio . "-- Fin:" . $fechaFin;
    else :
        echo "No hay registros para replicar <br>";
    endif;
endfor;
}