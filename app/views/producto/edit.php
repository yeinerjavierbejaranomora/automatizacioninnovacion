<div class="card mt-5">
    <div class="card-body">
        <div class="card-title">Editar producto</div>
    </div>
    <div style="width: 80%;margin:10px auto">
        <form id="Form">
            <input type="hidden" name="id" value="<?=$datos['id']?>">
            <div class="mb-3" id="grupo_nombre">
                <label for="Nombre" class="form-label">Nombre Prodcuto</label>
                <input type="text" class="form-control formulario__input" id="nombre" name="nombre" value="<?=$datos['nombre']?>">
                <small class="campo_obligatorio-error">Este campo es obligatorio</small>
            </div>
            <div class="mb-3" id="grupo_referencia">
                <label for="Referencia" class="form-label">Referencia</label>
                <input type="text" class="form-control formulario__input" id="referencia" name="referencia" value="<?=$datos['referencia']?>">
                <small class="campo_obligatorio-error">Este campo es obligatorio</small>
            </div>
            <div class="mb-3" id="grupo_precio">
                <label for="Precio" class="form-label">Precio</label>
                <input type="number" class="form-control formulario__input" id="precio" name="precio" value="<?=$datos['precio']?>">
                <small class="campo_obligatorio-error">Este campo es obligatorio</small>
            </div>
            <div class="mb-3" id="grupo_peso">
                <label for="Peso" class="form-label">Peso</label>
                <input type="number" class="form-control formulario__input" id="peso" name="peso" value="<?=$datos['peso']?>">
                <small class="campo_obligatorio-error">Este campo es obligatorio</small>
            </div>
            <div class="mb-3" id="grupo_categoria">
                <label for="Categoria" class="form-label">Categoría</label>
                <input type="text" class="form-control formulario__input" id="categoria" name="categoria" value="<?=$datos['categoria']?>">
                <small class="campo_obligatorio-error">Este campo es obligatorio</small>
            </div>
            <div class="mb-3" id="grupo_cantidad">
                <label for="Cantidad" class="form-label">Cantidad</label>
                <input type="number" class="form-control formulario__input" id="cantidad" name="cantidad" value="<?=$datos['cantidad']?>">
                <small class="campo_obligatorio-error">Este campo es obligatorio</small>
            </div>
            <button type="submit" class="btn btn-warning">Editar</button>
            <a href="<?=$_ENV['URL']?>producto/inicio" class="btn btn-danger">Volver</a>
        </form>
    </div>
</div>
<script>
    const Form =document.getElementById('Form');
    const inputs = document.querySelectorAll('#Form input');

    Form.addEventListener('submit', (e) => {
        e.preventDefault();
        id= inputs[0].value
        nombre =        inputs[1].value;
        referencia =    inputs[2].value;
        precio = inputs[3].value;
        peso = inputs[4].value;
        categoria = inputs[5].value;
        cantidad = inputs[6].value;
        
        const validarVacio = (input) => {
            switch (input.name) {
                case 'nombre':
                        validarCampoVacio(input,input.name);
                    break;
                case 'referencia':
                        validarCampoVacio(input,input.name);
                    break;
                case 'precio':
                        validarCampoVacio(input,input.name);
                    break;
                case 'peso':
                        validarCampoVacio(input,input.name);
                    break;
                case 'categoria':
                        validarCampoVacio(input,input.name);
                    break;
                case 'cantidad':
                        validarCampoVacio(input,input.name);
                    break;
            
                default:
                    break;
            }
        }

        const validarCampoVacio = (input,campo) => {
            if (input.value == '' || input.value ==0) {
                document.getElementById(`grupo_${campo}`).classList.add('formulario__grupo-incorrecto');
                document.querySelector(`#grupo_${campo} .campo_obligatorio-error`).classList.add('campo_obligatorio-error-activo');
            }
        }

        const validarPrueba = (e) =>{
            switch (e.target.name) {
                case 'nombre':
                        if(e.target.value != '' || e.target.value != 0){
                            document.getElementById(`grupo_${e.target.name}`).classList.remove('formulario__grupo-incorrecto');
                            document.querySelector(`#grupo_${e.target.name} .campo_obligatorio-error`).classList.remove('campo_obligatorio-error-activo');
                        } 
                        if(e.target.value == '' || e.target.value == 0){
                            document.getElementById(`grupo_${e.target.name}`).classList.add('formulario__grupo-incorrecto');
                            document.querySelector(`#grupo_${e.target.name} .campo_obligatorio-error`).classList.add('campo_obligatorio-error-activo');
                        }
                    break;
                case 'referencia':
                        if(e.target.value != '' || e.target.value != 0){
                            document.getElementById(`grupo_${e.target.name}`).classList.remove('formulario__grupo-incorrecto');
                            document.querySelector(`#grupo_${e.target.name} .campo_obligatorio-error`).classList.remove('campo_obligatorio-error-activo');
                        } 
                        if(e.target.value == '' || e.target.value == 0){
                            document.getElementById(`grupo_${e.target.name}`).classList.add('formulario__grupo-incorrecto');
                            document.querySelector(`#grupo_${e.target.name} .campo_obligatorio-error`).classList.add('campo_obligatorio-error-activo');
                        }
                    break;
                case 'precio':
                        if(e.target.value != '' || e.target.value != 0){
                            document.getElementById(`grupo_${e.target.name}`).classList.remove('formulario__grupo-incorrecto');
                            document.querySelector(`#grupo_${e.target.name} .campo_obligatorio-error`).classList.remove('campo_obligatorio-error-activo');
                        } 
                        if(e.target.value == '' || e.target.value == 0){
                            document.getElementById(`grupo_${e.target.name}`).classList.add('formulario__grupo-incorrecto');
                            document.querySelector(`#grupo_${e.target.name} .campo_obligatorio-error`).classList.add('campo_obligatorio-error-activo');
                        }
                    break;
                case 'peso':
                        if(e.target.value != '' || e.target.value != 0){
                            document.getElementById(`grupo_${e.target.name}`).classList.remove('formulario__grupo-incorrecto');
                            document.querySelector(`#grupo_${e.target.name} .campo_obligatorio-error`).classList.remove('campo_obligatorio-error-activo');
                        } 
                        if(e.target.value == '' || e.target.value == 0){
                            document.getElementById(`grupo_${e.target.name}`).classList.add('formulario__grupo-incorrecto');
                            document.querySelector(`#grupo_${e.target.name} .campo_obligatorio-error`).classList.add('campo_obligatorio-error-activo');
                        }
                    break;
                case 'categoria':
                        if(e.target.value != '' || e.target.value != 0){
                            document.getElementById(`grupo_${e.target.name}`).classList.remove('formulario__grupo-incorrecto');
                            document.querySelector(`#grupo_${e.target.name} .campo_obligatorio-error`).classList.remove('campo_obligatorio-error-activo');
                        } 
                        if(e.target.value == '' || e.target.value == 0){
                            document.getElementById(`grupo_${e.target.name}`).classList.add('formulario__grupo-incorrecto');
                            document.querySelector(`#grupo_${e.target.name} .campo_obligatorio-error`).classList.add('campo_obligatorio-error-activo');
                        }
                    break;
                case 'cantidad':
                        if(e.target.value != '' || e.target.value != 0){
                            document.getElementById(`grupo_${e.target.name}`).classList.remove('formulario__grupo-incorrecto');
                            document.querySelector(`#grupo_${e.target.name} .campo_obligatorio-error`).classList.remove('campo_obligatorio-error-activo');
                        } 
                        if(e.target.value == '' || e.target.value == 0){
                            document.getElementById(`grupo_${e.target.name}`).classList.add('formulario__grupo-incorrecto');
                            document.querySelector(`#grupo_${e.target.name} .campo_obligatorio-error`).classList.add('campo_obligatorio-error-activo');
                        }
                    break;
            
                default:
                    break;
            }
        }

        if (id!= '' && nombre != '' && referencia != '' && precio != '' && peso != '' && categoria != '' && cantidad != '') {
            formData = new FormData();
            formData.append('id',id);
            formData.append('nombre',nombre);
            formData.append('referencia',referencia);
            formData.append('precio',precio);
            formData.append('peso',peso);
            formData.append('categoria',categoria);
            formData.append('cantidad',cantidad);
            editProducto(formData);

            function editProducto(formData) {
                $.ajax({
                    url: URL + "producto/editproducto",
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false
                }).done(function(data){
                    console.log(data);
                    if(data.edit == true){
                        location.href = URL + "producto/edit/"+id;
                    }
                })
            }
        } else {
            inputs.forEach((input) =>{
                validarVacio(input);
                input.addEventListener('keyup',validarPrueba);
            })
        }
    })

</script>