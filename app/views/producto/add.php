<div class="card mt-5">
    <div class="card-body">
        <div class="card-title">Añadir producto</div>
    </div>
    <div style="width: 80%;margin:10px auto">
        <form id="Form">
            <div class="mb-3" id="grupo_nombre">
                <label for="Nombre" class="form-label">Nombre Prodcuto</label>
                <input type="text" class="form-control formulario__input" id="nombre" name="nombre" >
                <small class="campo_obligatorio-error">Este campo es obligatorio</small>
            </div>
            <div class="mb-3" id="grupo_referencia">
                <label for="Referencia" class="form-label">Referencia</label>
                <input type="text" class="form-control formulario__input" id="referencia" name="referencia" >
                <small class="campo_obligatorio-error">Este campo es obligatorio</small>
            </div>
            <div class="mb-3" id="grupo_precio">
                <label for="Precio" class="form-label">Precio</label>
                <input type="number"  class="form-control formulario__input" id="precio" name="precio" >
                <small class="campo_obligatorio-error">Este campo es obligatorio</small>
            </div>
            <div class="mb-3" id="grupo_peso">
                <label for="Peso" class="form-label">Peso</label>
                <input type="number" class="form-control formulario__input" id="peso" name="peso" >
                <small class="campo_obligatorio-error">Este campo es obligatorio</small>
            </div>
            <div class="mb-3" id="grupo_categoria">
                <label for="Categoria" class="form-label">Categoría</label>
                <input type="text" class="form-control formulario__input" id="categoria" name="categoria" >
                <small class="campo_obligatorio-error">Este campo es obligatorio</small>
            </div>
            <div class="mb-3" id="grupo_cantidad">
                <label for="Cantidad" class="form-label">Cantidad</label>
                <input type="number" class="form-control formulario__input" id="cantidad" name="cantidad" >
                <small class="campo_obligatorio-error">Este campo es obligatorio</small>
            </div>
            <button type="submit" class="btn btn-primary">Añadir</button>
        </form>
    </div>
</div>
<script>
    const Form =document.getElementById('Form');
    const inputs = document.querySelectorAll('#Form input');

    Form.addEventListener('submit', (e) => {
        e.preventDefault();
        // console.log(inputs);

        nombre =        inputs[0].value;
        referencia =    inputs[1].value;
        precio = inputs[2].value;
        peso = inputs[3].value;
        categoria = inputs[4].value;
        cantidad = inputs[5].value;
        
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

        if (nombre != '' && referencia != '' && precio != '' && peso != '' && categoria != '' && cantidad != '') {
            formData = new FormData();
            formData.append('nombre',nombre);
            formData.append('referencia',referencia);
            formData.append('precio',precio);
            formData.append('peso',peso);
            formData.append('categoria',categoria);
            formData.append('cantidad',cantidad);
            saveProducto(formData);

            function saveProducto(formData) {
                $.ajax({
                    url: URL + "producto/saveproducto",
                    type: 'POST',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false
                }).done(function(data){
                    console.log(data);
                    if(data.save == true){
                        location.href = URL + "producto/inicio";
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