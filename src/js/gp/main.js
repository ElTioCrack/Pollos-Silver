//console.log("HOLA MUNDO");
$(document).ready(function(e) {

  /*VARIABLES */
  let editar = false;
  /*OBTENER FECHA Y HORA ACTUAL*/
  const fechaActual = moment();
  const fechaFormateada = fechaActual.format('YYYY-MM-DD HH:mm:ss');
  const fechaFormateadaString = fechaFormateada.toString();

  /*MOSTRAR PRODUCTOS AL CARGAR LA PAGINA */
  getProdtducts();

  /*MOSTRAR PRODUCTOS ACTIVOS O INACTIVOS*/
  $('#productStateView').change(function() {
    if ($(this).is(':checked')) {
      //console.log('El checkbox está marcado');
      getProdtductsInactives()
    } else {
      //console.log('El checkbox está desmarcado');
      getProdtducts();
    }
  });

  /*BUSQUEDA DE PRODUCTOS */
  $('#search').keyup(function() {
      let search = $(this).val();
      if (search) {
        $.ajax({
          url: '../../php/gp/product-search.php',
          data: {search: search},
          type: 'POST',
          success: function (response) {
            if (!response.error) {
              let products = JSON.parse(response);
              let template = '';
              products.forEach(product => {
                template += `
                  <div class="AP-List AP-productEdit" productId="${product.id_producto}">
                    <div class="AP-imgBx">
                      <img src="${product.imagen_producto}">
                    </div>
                    <div class="AP-content">
                      <h2 class="AP-rank"><small>#</small>${product.id_producto}</h2>
                      <h4>${product.nombre}</h4>
                      <p>Bs. ${product.precio_unitario}</p>
                    </div>
                  </div>
                `;
              });
              $('#productsContainerList').html(template);
            }
          }
        });
      }else {
        if ($('#search').val() == '') {
          getProdtducts(); // Llamar a la función getProducts() para obtener todos los productos si el campo de búsqueda está vacío
        }
      }
  });
  /*CREAR O EDITAR EL PRODUCTO*/
  $('#createProduct').submit(function(e) {
        e.preventDefault();
        
        var valor=$('#productCategory').is(':checked')? 2 : 1;
        var estadoProducto = $('#productState').is(':checked')? 0 : 1;
        const postDataProducts={
          id_producto: $('#productId').val(),
          id_categoria: valor,
          id_sucursal: 1,
          nombre: $('#productName').val(),
          descripcion: $('#productDescription').val(),
         
          //imagen_producto: $('#filenameP').text(),
          imagen_producto: $('#imagenProducto').attr('src'),
          precio_total: $('#productPrice').val(),
          precio_unitario: $('#productPrice').val(),
          cantidad: 1,
          estado: estadoProducto,
          fecha_hora: fechaFormateadaString,
        };
        let url=  editar === false ? '../../php/gp/product-add.php' : '../../php/gp/product-edit.php';
        let message = editar === false ? 'Producto Creado':'Producto Actualizado';
        //console.log(formData);
        $.post(url, postDataProducts,function (response) {
          console.log(response);
          limpiarCampos();
          getProdtducts();
          $('#productStateView').prop('checked',false)
          alert(message);
          editar=false
        });
  })
  /*OBTENER LISTA DE PRODUCTOS*/
  function getProdtducts() {
      $.ajax({
        url: '../../php/gp/product-list.php',
        type: 'GET',
        success: function (response) {
          //console.log(response);
          let productos = JSON.parse(response);
          let template =''
          productos.forEach(producto =>{
            if (producto.estado === "1") {
              template += `
                <div class="AP-List AP-productEdit" productId="${producto.id_producto}">
                  <div class="AP-imgBx">
                    <img src="${producto.imagen_producto}">
                  </div>
                  <div class="AP-content">
                    <h2 class="AP-rank"><small>#</small>${producto.id_producto}</h2>
                    <h4>${producto.nombre}</h4>
                    <p>Bs. ${producto.precio_unitario}</p>
                  </div>
                </div>
              `;
            }
          });
          $('#productsContainerList').html(template)
        }
      })
  }
  /*OBTENER LISTA DE PRODUCTOS INACTIVOS */
  function getProdtductsInactives() {
      $.ajax({
        url: '../../php/gp/product-list.php',
        type: 'GET',
        success: function (response) {
          //console.log(response);
          let productos = JSON.parse(response);
          //console.log(productos);
          let template =''
          productos.forEach(producto =>{
            if (producto.estado === "0") {
              template += `
                <div class="AP-List AP-productEdit" productId="${producto.id_producto}">
                  <div class="AP-imgBx">
                    <img src="${producto.imagen_producto}">
                  </div>
                  <div class="AP-content">
                    <h2 class="AP-rank"><small>#</small>${producto.id_producto}</h2>
                    <h4>${producto.nombre}</h4>
                    <p>Bs. ${producto.precio_unitario}</p>
                  </div>
                </div>
              `;
            }
          });
          $('#productsContainerList').html(template)
        }
      })
  }
  /*CARGAR UN PRODUCTO SELECCIONADO EN EL VIEW*/
  $(document).on('click', '.AP-productEdit', function () {
      let element = $(this)[0];
      let id= $(element).attr('productId');
      $('.Ap-course2').removeClass('active')
      $.post('../../php/gp/product-selected.php',{id},function (response) {
        let productos = JSON.parse(response);
        let checkValue = productos.id_categoria == 1 ?  false : true
        let checkState = productos.estado == 1 ?  false : true
        limpiarCampos()
        let imgTag = `<img src="${productos.imagen_producto}" alt="" id="imagenProducto">`; //creando una etiqueta img y pasando la fuente de archivo seleccionada por el usuario dentro del atributo src
        $('#productId').val(productos.id_producto)
        $('#dragA').html(imgTag);
        $('#productName').val(productos.nombre)
        $('#productPrice').val(productos.precio_unitario)
        $('#productDescription').text(productos.descripcion)
        $('#productCategory').prop('checked',checkValue)
        $('#productState').prop('checked',checkState)
        editar = true
      });
  })
  /*LIMPIAR FORMULARIO PARA CREAR UN NUEVO PRODUCTO */
  $('#crear').click(function() {
      limpiarCampos()
      editar = false
  });
  /*LIMPIAR CAMPOS DEL FORMULARIO (NO TOCAR POR QUE FUNCIONA XD) */
  function limpiarCampos() {
      let dragArea= `<header>Arrastra tu</header><span>imagen</span>`
      $('#createProduct').trigger('reset');
      $('#productDescription').text('');
      $('#productImage').trigger('reset');
      $('#dragA').html(dragArea)
      $('#dragA').removeClass('active')
      $('#filenameP').html('')
  }
});
