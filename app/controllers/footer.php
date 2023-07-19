<?php
class Footer extends Controller{
    function __construct(){}

    public function render()
    {
        $datos = [];
        $this->view('layout/footer',$datos);
    }
}