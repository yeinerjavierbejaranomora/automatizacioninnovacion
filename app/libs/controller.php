<?php
class Controller{

    function __construct(){}

    public function model($modelo = '')
    {
        require_once '../app/models/'. $modelo.'.php';
        return new $modelo();
    }

    public function view($view = '', $datos =[])
    {
        //$view = explode("/",$view);
        if(file_exists('../app/views/'. $view.'.php')):
            require_once '../app/views/'. $view.'.php';
        else:
            die("La vista ". $view." no existe");
        endif;
    }

    public function header($title)
    {
        include_once '../app/controllers/header.php';
        $header = new Header();
        $header->render($title);
    }

    public function footer()
    {
        include_once '../app/controllers/footer.php';
        $header = new Footer();
        $header->render();
    }
}