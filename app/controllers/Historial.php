<?php
class Historial extends Controller{
    private $model;
    public function __construct()
    {
        $this->model = $this->model("HistorialModel");
    }

    public function inicio() {
        $file = "../public/historialAcademico14-08.csv";
        $handle = fopen($file, "r");
        $lineNumber = 1;
        while (($raw_string = fgets($handle)) !== false) {
            $row = str_getcsv($raw_string);
            $fila = explode(";",$row[0]);
            //var_dump($fila);die();
            $codBanner = $fila[0];
            $nombre = $fila[1];
            $origen = $fila[2];
            $codPrograma = $fila[3];
            $programa = $fila[4];
            $codMateria = $fila[5];
            $nombreMateria = $fila[6];
            $nota = $fila[7];
            $save = $this->model->save($codBanner,$nombre,$origen,$codPrograma,$programa,$codMateria,$nombreMateria,$nota);
            if ($save):
                $fechaInicio = date('Y-m-d H:i:s');
                echo $codBanner." insertado a historial -- ". $fechaInicio;
            endif;
            $lineNumber++;
        }
        fclose($handle);
    }
}