<div class="card mt-5">
    <div class="card-body">
        <div class="card-title">Añadir producto</div>
    </div>
    <div class="card-title"><a href="<?= $_ENV['URL'] ?>producto/add" class="btn btn-success">Añadir</a></div>
    <div style="width: 90%;margin:10px auto">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">nombre</th>
                    <th scope="col">referencia</th>
                    <th scope="col">precio</th>
                    <th scope="col">cantidad</th>
                    <th scope="col">ver más</th>
                    <th scope="col">editar</th>
                    <th scope="col">eliminar</th>
                </tr>
            </thead>
            <tbody>
            <?php if($datos != null):?>
                <?php foreach ($datos['productos'] as $producto) : ?>
                    <tr>
                        <th scope="row"><?= $producto['nombre'] ?></th>
                        <td><?= $producto['referencia'] ?></td>
                        <td><?= $producto['precio'] ?></td>
                        <td><?= $producto['cantidad'] ?></td>
                        <td><a class="btn btn-sm btn-primary" href="<?= $_ENV['URL'] ?>producto/producto/<?= $producto['id'] ?>">ver más</a></td>
                        <td><a class="btn btn-sm btn-warning" href="<?= $_ENV['URL'] ?>producto/edit/<?= $producto['id'] ?>">editar</a></td>
                        <td><button class="btn btn-sm btn-danger" onclick="eliminar(<?= $producto['id'] ?>)">eliminar</button></td>
                    </tr>
                <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>

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
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'No se pudo eliminar el producto!',
                                showConfirmButton: true,
                            }).then((result)=>{
                                if (result.value) {
                                    location.reload();
                                }
                            });
                        }
                    },
                });
            }
        })
    }
</script>