<?php
class ProductoModel
{

    public $db;
    private $nombre;
    private $referencia;
    private $precio;
    private $peso;
    private $categoria;
    private $cantidad;

    function __construct()
    {
        $this->db = new Database();
    }

    public function productos()
    {
        try {
            $query = $this->db->connect()->prepare("SELECT * FROM  productos");
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function save($nombre, $referencia, $precio, $peso, $categoria, $cantidad,$fechaCreacion)
    {
        try {
            $query = $this->db->connect()->prepare("INSERT INTO `productos` SET 
                                                                `nombre` = ?, 
                                                                `referencia` = ?, 
                                                                `precio` = ?, 
                                                                `peso` = ?, 
                                                                `categoria` = ?, 
                                                                `stock` = ?, 
                                                                `fecha_creacion` = ?");
            $query->bindValue(1,$nombre,PDO::PARAM_STR);
            $query->bindValue(2,$referencia,PDO::PARAM_STR);
            $query->bindValue(3,$precio,PDO::PARAM_INT);
            $query->bindValue(4,$peso,PDO::PARAM_INT);
            $query->bindValue(5,$categoria,PDO::PARAM_STR);
            $query->bindValue(6,$cantidad,PDO::PARAM_INT);
            $query->bindValue(7,$fechaCreacion,PDO::PARAM_STR);
            $query->execute();
            return $this->db->insert_id();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function producto($id)
    {
        try {
            $query = $this->db->connect()->prepare("SELECT `id`, `nombre`, `referencia`, `precio`, `peso`, `categoria`, `stock`, `fecha_creacion`, `fecha_ultima_venta` FROM `productos` WHERE `id` = ?");
            $query->bindValue(1,$id,PDO::PARAM_INT);
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function edit($id, $nombre, $referencia, $precio, $peso, $categoria, $cantidad)
    {
        try {
            $query = $this->db->connect()->prepare("UPDATE `productos` SET 
                                                            `nombre`= ?,
                                                            `referencia`= ?,
                                                            `precio`= ?,
                                                            `peso`= ?,
                                                            `categoria`= ?,
                                                            `stock`= ?
                                                            WHERE `id` = ?");
            $query->bindValue(1,$nombre,PDO::PARAM_STR);
            $query->bindValue(2,$referencia,PDO::PARAM_STR);
            $query->bindValue(3,$precio,PDO::PARAM_INT);
            $query->bindValue(4,$peso,PDO::PARAM_INT);
            $query->bindValue(5,$categoria,PDO::PARAM_STR);
            $query->bindValue(6,$cantidad,PDO::PARAM_INT);
            $query->bindValue(7,$id,PDO::PARAM_INT);
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $query = $this->db->connect()->prepare("DELETE FROM `productos` WHERE `id` = ?");
            $query->bindValue(1,$id,PDO::PARAM_INT);
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            return false;
        }
    }
}
