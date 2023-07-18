<?php
class Header extends Controller{

    function __construct(){}

    public function render($title)
    {
        $datos = [
            'title' => $title,
        ];
        $this->view('layout/header',$datos);
    }
}