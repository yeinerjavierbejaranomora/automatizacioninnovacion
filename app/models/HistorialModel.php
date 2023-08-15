<?php

class HistorialModel{

    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function save($codBanner,$nombre,$origen,$codPrograma,$programa,$codMateria,$nombreMateria,$nota){
        //var_dump($codBanner,$nombre,$origen,$codPrograma,$programa,$codMateria,$nombreMateria,$nota);die();
        //try {
            $save = $this->db->connect()->prepare("INSERT INTO `historialAcademico` SET 
            `codBanner` = $codBanner, 
            ");
            /*$save->bindValue(1,$codBanner,PDO::PARAM_INT);
            $save->bindValue(2,$nombre,PDO::PARAM_STR);
            $save->bindValue(3,$origen,PDO::PARAM_STR);
            $save->bindValue(4,$codPrograma,PDO::PARAM_STR);
            $save->bindValue(5,$programa,PDO::PARAM_STR);
            $save->bindValue(6,$codMateria,PDO::PARAM_STR);
            $save->bindValue(7,$nombreMateria,PDO::PARAM_STR);
            $save->bindValue(8,$nota,PDO::PARAM_STR);*/
            //$save->execute();
            var_dump($save);die();
            /*return $save;
        } catch (PDOException $e) {
            return false;
        }*/
    }
}