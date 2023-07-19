<?php
class Producto extends Controller{
    private $model;
    public $productos =[];

    function __construct()
    {
        $this->model = $this->model("ProductoModel");
    }

    public function productos()
    {
        $query = $this->model->productos();
        if($query->rowcount() > 0):
            foreach($query as $producto):
                $this->productos[] = [
                    'id' => $producto['id'],
                    'nombre' => $producto['nombre'],
                    'referencia' => $producto['referencia'],
                    'precio' => $producto['precio'],
                    'peso' => $producto['peso'],
                    'categoria' => $producto['categoria'],
                    'cantidad' => $producto['stock'],
                    'fecha_creacion' => $producto['fecha_creacion'],
                ];
            endforeach;
            var_dump($this->productos);
        endif;
    }

    public function inicio(){
        $query = $this->model->productos();
        if($query->rowcount() > 0):
            foreach($query as $producto):
                $productos[] = [
                    'id' => $producto['id'],
                    'nombre' => $producto['nombre'],
                    'referencia' => $producto['referencia'],
                    'precio' => $producto['precio'],
                    'peso' => $producto['peso'],
                    'categoria' => $producto['categoria'],
                    'cantidad' => $producto['stock'],
                    'fecha_creacion' => $producto['fecha_creacion'],
                ];
            endforeach;
            $datos=[
                'productos' => $productos,
            ];
        else:
            $datos = [];
        endif;
        $this->header('CRUD | Productos');
        $this->view('producto/index', $datos);
        $this->footer();
    }

    public function add()
    {
        $datos = [];
        $this->header('CRUD | Productos');
        $this->view('producto/add', $datos);
        $this->footer();
    }

    public function saveproducto()
    {
        $result = [];
        $nombre = $_POST['nombre'];
        $referencia = $_POST['referencia'];
        $precio = $_POST['precio'];
        $peso = $_POST['peso'];
        $categoria = $_POST['categoria'];
        $cantidad = $_POST['cantidad'];
        $fechaCreacion = date("Y-m-d");

        $query = $this->model->save($nombre, $referencia, $precio, $peso, $categoria, $cantidad,$fechaCreacion);
        if($query):
            $result['save'] =true;
        endif;
        header('Content-Type: application/json');

        echo json_encode($result);
        
    }

    public function producto($id)
    {
        $query = $this->model->producto($id);
        $queryFetch = $query->fetch(PDO::FETCH_ASSOC);
        $datos = [
            'id' => $queryFetch['id'],
            'nombre' => $queryFetch['nombre'],
            'referencia' => $queryFetch['referencia'],
            'precio' => $queryFetch['precio'],
            'peso' => $queryFetch['peso'],
            'categoria' => $queryFetch['categoria'],
            'cantidad' => $queryFetch['stock'],
            'fecha_creacion' => $queryFetch['fecha_creacion'],
            'fecha_ultima_venta' => $queryFetch['fecha_ultima_venta'],
        ];
        $this->header('CRUD | Productos');
        $this->view('producto/producto',$datos);
        $this->footer();
    }

    public function edit($id)
    {
        $query = $this->model->producto($id);
        $queryFetch = $query->fetch(PDO::FETCH_ASSOC);
        $datos = [
            'id' => $queryFetch['id'],
            'nombre' => $queryFetch['nombre'],
            'referencia' => $queryFetch['referencia'],
            'precio' => $queryFetch['precio'],
            'peso' => $queryFetch['peso'],
            'categoria' => $queryFetch['categoria'],
            'cantidad' => $queryFetch['stock'],
            'fecha_creacion' => $queryFetch['fecha_creacion'],
            'fecha_ultima_venta' => $queryFetch['fecha_ultima_venta'],
        ];

        $this->header('CRUD | Productos');
        $this->view('producto/edit',$datos);
        $this->footer();
    }

    public function editproducto()
    {
        //var_dump($_POST);die();
        $result = [];
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $referencia = $_POST['referencia'];
        $precio = $_POST['precio'];
        $peso = $_POST['peso'];
        $categoria = $_POST['categoria'];
        $cantidad = $_POST['cantidad'];

        $query = $this->model->edit($id, $nombre, $referencia, $precio, $peso, $categoria, $cantidad);
        if($query):
            $result['edit'] = true;
        endif;
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function eliminar()
    {
        $result = [];
        $id = $_POST['id'];
        $query = $this->model->delete($id);
        if($query):
            $result['delete'] = true;
        endif;
        header('Content-Type: application/json');
        echo json_encode($result);
    }
}