<div class="card mt-5">
    <div class="card-body">
        <h4 class="card-title text-center"><?= $datos['nombre'] ?></h4>
    </div>
    
    <div style="width: 70%;margin:10px auto">
        <div class="car-title"><strong>Nombre:</strong> <?= $datos['nombre'] ?></div>
        <div class="car-title"><strong>Referencia:</strong> <?= $datos['referencia'] ?></div>
        <div class="car-title"><strong>Precio:</strong> <?= $datos['precio'] ?></div>
        <div class="car-title"><strong>Peso:</strong> <?= $datos['peso'] ?></div>
        <div class="car-title"><strong>Categoria:</strong> <?= $datos['categoria'] ?></div>
        <div class="car-title"><strong>Cantidad:</strong> <?= $datos['cantidad'] ?></div>
        <div class="car-title"><strong>Fecha Creacion:</strong> <?= $datos['fecha_creacion'] ?></div>
        <div class="car-title"><strong>Ultima Venta:</strong> <?= $datos['fecha_ultima_venta'] ?></div>
        <div class="container text-center">
            <div class="row">
                <div class="col">
                    <a href="<?=$_ENV['URL']?>producto/edit/<?=$datos['id']?>" class="btn btn-warning">Editar</a>
                </div>
                <div class="col">
                    <button class="btn btn-danger" onclick="eliminar(<?= $datos['id'] ?>)">eliminar</button>
                </div>
                <div class="col">
                    <a href="<?=$_ENV['URL']?>producto/inicio" class="btn btn-primary">Volver</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function eliminar(id) {
        Swal.fire({
            icon: 'info',
            title: 'Eliminar el producto',
            text: '¿Esta seguro de eliminar el producto?',
            showCancelButton: true,
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#e03616',
            cancelButtonColor: '#17a2b8',
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                formData = new FormData();
                formData.append('id',id);

                $.ajax({
                    type: 'POST',
                    url: URL + 'producto/eliminar',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        if (data.delete) {
                            Swal.fire({
                                icon:'success',
                                title: 'El producto ha sido eliminado!',
                                showConfirmButton: true,
                            }).then((result)=>{
                                if (result.value) {
                                    location.href = URL + "producto/inicio"; 
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'No se pudo eliminar el producto!',
                                showConfirmButton: true,
                            }).then((result)=>{
                                if (result.value) {
                                    location.href = URL + "producto/inicio";
                                }
                            });
                        }
                    },
                });
            }
        })
    }
</script>