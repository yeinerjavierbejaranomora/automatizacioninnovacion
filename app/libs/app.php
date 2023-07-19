<?php
/**
 * 
 */
class App{
    private $controller = 'producto';
    private $method = 'inicio';
    private $parameters = [];
    function __construct()
    {
        date_default_timezone_set("America/Bogota");
        $url = $this->separarURL();
        if($url != '' && file_exists("../app/controllers/".ucwords($url[0]).".php")):
            $this->controller = ucwords($url[0]);
            unset($url[0]);
        endif;

        require_once("../app/controllers/".ucwords($this->controller).".php");
        $this->controller = new $this->controller;

        if(isset($url[1])):
            if(method_exists($this->controller,$url[1])):
                $this->method = $url[1];
                unset($url[1]);
            endif;
        endif;

        $this->parameters = $url?array_values($url):[];

        call_user_func_array([$this->controller,$this->method],$this->parameters);
    }

    public function separarURL()
    {
        $url = "";
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'],'/');
            $url = filter_var($url,FILTER_SANITIZE_URL);
            $url = explode("/",$url);
        }
        return $url;
    }
}