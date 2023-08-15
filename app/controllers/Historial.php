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
            var_dump($fila);die();
            $codBanner = $fila[0];
            $save = $this->model->save($codBanner);
            $lineNumber++;
        }
        fclose($handle);
    }
}