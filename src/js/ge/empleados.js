$(document).ready(() => {
  // Gestion de roles
  if (tipo_usuario == "Empleado") {
    $(".input_create").hide();
  }

  list_employees(1);

  //Funcion de switch para listar emmpleados
  $("#search_state").change(function () {
    $("#btn_create").show();
    list_employees(get_state($(this)));
  });

  // Boton para crear empleado
  $("#btn_create").click(function () {
    $(this).hide();
    create_empleado();
  });

  // Campo de texto para la busqueda
  $(".input_search input").on("input", function (event) {
    let search = $(this).val();
    if (search.length >= 1) {
      let state = get_state($("#search_state"));
      employee_list_with_search(state, search);
    } else {
      list_employees(get_state($("#search_state")));
    }
  });
});

var data_empleado = {};
var default_password = "***************";
function get_data_empleado(card) {
  data_empleado = {
    ci: card.find("#ci").val(),
    nombres: card.find("#nombres").val(),
    apellidos: card.find("#apellidos").val(),
    telefono: card.find("#telefono").val(),
    //contrasena: card.find("#contrasena").val(),
    contrasena: card.find("#contrasena").data("contrasena"),
    id_sucursal: card.find("#sucursales_list").val(),
    estado: card.find("#state").is(":checked") ? 1 : 0,
    // "estado": Boolean(card.find("#state").is(":checked")),
  };
}

function get_state(switch_state) {
  if (switch_state.is(":checked")) {
    return 1;
  } else {
    return 0;
  }
}
/* -------------------------------------------------------------------------- */
// Obtener Sucursales
function get_sucursales(state) {
  let url_php = "../../php/ge/get_sucursales.php";

  return new Promise(function (resolve, reject) {
    $.ajax({
      url: url_php,
      method: "post",
      dataType: "json",
      data: { state: state },
      success: function (response) {
        resolve(response);
      },
      error: function (error) {
        reject(error);
      },
    });
  });
}

// Obtener Empleados
function get_empleados(state) {
  let url_php = "../../php/ge/get_empleados.php";

  return new Promise(function (resolve, reject) {
    $.ajax({
      url: url_php,
      method: "post",
      dataType: "json",
      data: { state: state },
      success: function (response) {
        resolve(response);
      },
      error: function (error) {
        reject(error);
      },
    });
  });
}

// Buscar empleados
function get_empleados_with_search(state, search) {
  let url_php = "../../php/ge/search_empleados.php";

  return new Promise(function (resolve, reject) {
    $.ajax({
      url: url_php,
      method: "post",
      dataType: "json",
      data: {
        state,
        search,
      },
      success: function (response) {
        resolve(response);
      },
      error: function (error) {
        reject(error);
      },
    });
  });
}

// Editar empleado
function edit_empleado(
  ci,
  nombres,
  apellidos,
  telefono,
  contrasena,
  id_sucursal,
  estado
) {
  let url_php = "../../php/ge/edit_empleado.php";

  return new Promise(function (resolve, reject) {
    $.ajax({
      url: url_php,
      method: "post",
      dataType: "json",
      data: {
        ci: ci,
        nombres: nombres,
        apellidos: apellidos,
        telefono: telefono,
        contrasena: contrasena,
        id_sucursal: id_sucursal,
        estado: estado,
      },
      success: function (response) {
        resolve(response);
      },
      error: function (error) {
        reject(error);
      },
    });
  });
}
/* -------------------------------------------------------------------------- */
//Crear Empleado
function create_empleado() {
  get_sucursales(1)
    .then((sucursales) => {
      let card = create_card();
      card.find("#ci").prop("disabled", false);
      card.find("#nombres").prop("disabled", false);
      card.find("#apellidos").prop("disabled", false);
      card.find("#telefono").prop("disabled", false);
      card.find("#contrasena").prop("disabled", false);
      card.find("#contrasena").attr("value", "");

      card.find("#sucursales_list").prop("disabled", false);
      card.find("#sucursales_list").empty();
      let sucursales_list = "";
      sucursales.forEach((sucursal) => {
        sucursales_list += `<option value="${sucursal.id}">${sucursal.nombre}</option>`;
      });
      card.find("#sucursales_list").html(sucursales_list);

      card.find("#state").prop("disabled", false);
      card.find("#state").attr("checked", true);

      card.find(".btn_options .options:nth-child(1)").hide();
      card.find(".btn_options .options:nth-child(2)").html(`
        <button id="create" class="confirm button">Guardar</button>
        <button id="cancel_create" class="cancel button">Cancelar</button>
      `);

      $(".list-cards .container").html(card.prop("outerHTML"));

      $("#ci").focus();

      // Modificar css
      $(".card").css("height", "480px");
      $(".card .card-title").css("top", "-35px");
      $(".card .content").css({
        top: "50px",
        height: "430px",
      });
      //
      $("#contrasena")
        .off("focus")
        .focus(function () {
          $(this).val("");
        });

      $("#contrasena")
        .off("blur")
        .blur(function () {
          let contrasena = $(this).val();
          if (contrasena.length > 0) {
            $(this).data("contrasena", contrasena);
          }
          $(this).val(default_password);
        });
      //
      $("#create")
        .off("click")
        .click(function () {
          //llamar al procedimiendo crear empleado
          let card = $(this).closest(".card");
          get_data_empleado(card);

          $.ajax({
            url: "../../php/ge/create_empleado.php",
            type: "post",
            dataType: "json",
            data: {
              ci: data_empleado.ci,
              contrasena: data_empleado.contrasena,
              id_sucursal: data_empleado.id_sucursal,
              nombres: data_empleado.nombres,
              apellidos: data_empleado.apellidos,
              telefono: data_empleado.telefono,
              estado: data_empleado.estado,
            },
            success: function (response) {
              alert(response.mensaje);
              if (response.creado) {
                list_employees(data_empleado.estado);
                $("#search_state").attr(
                  "checked",
                  Boolean(data_empleado.estado)
                );
                $("#btn_create").show();
              }
            },
            error: (error) => {
              if (typeof error === "object") error = JSON.stringify(error);
              console.log("%c%s", "color: #ff0000", error);
            },
          });
        });

      $("#cancel_create")
        .off("click")
        .click(function () {
          list_employees(get_state($("#search_state")));
          $("#btn_create").show();
        });
    })
    .catch((error) => {
      console.log("%c%s", "color: #ff0000", error);
    });
}
/* -------------------------------------------------------------------------- */
// Listar empleados
function list_employees(state) {
  get_empleados(state)
    .then((empleados) => {
      return get_sucursales(1).then((sucursales) =>
        create_employee_card_list(empleados, sucursales)
      );
    })
    .catch((error) => {
      console.log("%c%s", "color: #ff0000", error);
    });
}

// Buscar y listar Empleados
function employee_list_with_search(search, state) {
  get_empleados_with_search(search, state)
    .then((empleados) => {
      return get_sucursales(1).then((sucursales) =>
        create_employee_card_list(empleados, sucursales)
      );
    })
    .catch((error) => {
      console.log("%c%s", "color: #ff0000", error);
    });
}

// Generar lista de tarjetas de empleados
function create_employee_card_list(empleados, sucursales) {
  let card_list = "";
  empleados.forEach((empleado) => {
    let card = create_card();
    card.find("#ci").attr("value", empleado.usuario.ci);
    card.find("#nombres").attr("value", empleado.nombres);
    card.find("#apellidos").attr("value", empleado.apellidos);
    card.find("#telefono").attr("value", empleado.telefono);

    // card.find("#contrasena").attr("value", empleado.usuario.contrasena);
    card.find("#contrasena").data("contrasena", empleado.usuario.contrasena);
    // console.log(card.find("#contrasena").data("contrasena"));

    card
      .find("#contrasena")
      .attr("data-contrasena", empleado.usuario.contrasena);
    // console.log('%c%s', 'color: #00ff2a', card.find("#contrasena").attr('data-contrasena'));

    card.find("#sucursales_list").empty();
    let sucursales_list = "";
    sucursales.forEach((sucursal) => {
      sucursales_list += `<option value="${sucursal.id}" ${
        sucursal.id === empleado.sucursal.id_sucursal ? "selected" : ""
      }>${sucursal.nombre}</option>`;
    });
    card.find("#sucursales_list").html(sucursales_list);

    card.find("#state").attr("checked", Boolean(empleado.estado));

    card_list += card.prop("outerHTML");
  });

  $(".list-cards .container").html(card_list);

  // Gestion de roles
  if (tipo_usuario == "Empleado") {
    $(".btn_options").hide();
  }

  //Botones
  $(".btn_options .options:nth-child(2)").hide();

  // Boton Editar
  $(".btn_options #edit")
    .off("click")
    .click(function () {
      const card = $(this).closest(".card");
      const contrasenaInput = card.find("#contrasena");

      get_data_empleado(card);

      input_bool(card, false);

      contrasenaInput.off("focus").focus(function () {
        $(this).val("");
      });

      contrasenaInput.off("blur").blur(function () {
        const contrasena = $(this).val().trim();
        if (contrasena.length > 0) {
          $(this).data("contrasena", contrasena);
        }
        $(this).val(default_password);
      });

      card.find(".btn_options .options").eq(0).hide();
      card.find(".btn_options .options").eq(1).show();
    });

  // Boton Guardar
  $(".btn_options #save_edit")
    .off("click")
    .click(function (e) {
      let card = $(this).closest(".card");
      let state_change =
        Boolean(data_empleado.estado) !==
        Boolean(card.find("#state").is(":checked"));

      get_data_empleado(card);

      edit_empleado(
        data_empleado.ci,
        data_empleado.nombres,
        data_empleado.apellidos,
        data_empleado.telefono,
        data_empleado.contrasena,
        data_empleado.id_sucursal,
        data_empleado.estado
      )
        .then((response) => {
          if (response.success) {
            alert(response.message);
          } else {
            console.log("%c%s", "color: #ff0000", response.message);
          }
          input_bool(card, true);
          card.find(".btn_options .options").eq(0).show();
          card.find(".btn_options .options").eq(1).hide();

          // Si se editó el estado
          if (state_change) {
            let state = Boolean(data_empleado.estado);
            $("#search_state").prop("checked", state);
            list_employees(get_state($("#search_state")));
          }
        })
        .catch((error) => {
          if (typeof error === "object") error = JSON.stringify(error);
          console.log("%c%s", "color: #ff0000", error);
        });
    });

  // Boton Cancelar
  $(".btn_options #cancel_edit")
    .off("click")
    .click(function (e) {
      let card = $(this).closest(".card");
      card.find("#nombres").val(data_empleado.nombres);
      card.find("#apellidos").val(data_empleado.apellidos);
      card.find("#telefono").val(data_empleado.telefono);
      card.find("#contrasena").val(default_password);
      card.find("#sucursales_list").val(data_empleado.id_sucursal);
      card.find("#state").prop("checked", data_empleado.estado);

      input_bool(card, true);

      card.find(".btn_options .options").eq(0).show();
      card.find(".btn_options .options").eq(1).hide();
    });
}

function input_bool(card, bool) {
  card.find("#nombres").prop("disabled", bool);
  card.find("#apellidos").prop("disabled", bool);
  card.find("#telefono").prop("disabled", bool);
  card.find("#contrasena").prop("disabled", bool);
  card.find("#sucursales_list").prop("disabled", bool);
  card.find("#state").prop("disabled", bool);
  $(".btn_options #edit").prop("disabled", !bool);
}

function create_card() {
  return $(`
      <div class="card">
        <div class="card-title">
          <div class="input_container">
            <input type="text" id="ci" class="input_text" disabled required>
            <span class="highlight"></span>
            <span class="bar"></span>
            <label class="label_text">CI:</label>
          </div>
        </div>
        <div class="content">
          <div class="input_container">
            <input type="text" id="nombres" class="input_text" disabled required>
            <span class="highlight"></span>
            <span class="bar"></span>
            <label class="label_text">Nombres:</label>
          </div>
          <div class="input_container">
            <input type="text" id="apellidos" class="input_text" disabled required>
            <span class="highlight"></span>
            <span class="bar"></span>
            <label class="label_text">Apellidos:</label>
          </div>
          <div class="input_container">
            <input type="text" id="telefono" class="input_text"  disabled required>
            <span class="highlight"></span>
            <span class="bar"></span>
            <label class="label_text">Telefono:</label>
          </div>
          <div class="input_container">
            <input type="text" id="contrasena" class="input_text" value="${default_password}" data-contrasena="${default_password}"disabled required>
            <span class="highlight"></span>
            <span class="bar"></span>
            <label class="label_text">Contraseña:</label>
          </div>
          <select id="sucursales_list" disabled required>
            <option value="1">1</option>
            <option value="2">2</option>
          </select>
          <div class="swich_state">
            <p>Inactivo</p>
            <div>
              <input type="checkbox" id="state" disabled>
            </div>
            <p>Activo</p>
          </div>
          <div class="btn_options">
            <div class="options">
              <button id="edit" class="button">Editar</button>
            </div>
            <div class="options">
              <button id="save_edit" class="confirm button">Guardar</button>
              <button id="cancel_edit" class="cancel button">Cancelar</button>
            </div>
          </div>
        </div>
      </div>
    `);
}
