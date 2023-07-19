<?php
class Mafi extends Controller{
    private $model = '';

    public function __construct()
    {
        $this->model = $this->model("MafiModel");
    }

    public function inicio() {
        echo "Hola";
    }
}