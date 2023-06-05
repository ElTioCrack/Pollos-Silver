$(document).ready(() => {
  list_employees(1);
  $("#search_state").change(function () {
    list_employees(get_state($(this)));
  });

  $(".input_search input").on("input", function (event) {
    let search = $(this).val();
    if (search.length >= 1) {
      let state = get_state($("#search_state"));
      console.log("search: " + search);
      console.log("state: " + state);
      employee_list_with_search(state, search);
    } else {
      list_employees(get_state($("#search_state")));
    }
  });
});

var data_empleado = {};
function get_data_empleado(card) {
  data_empleado = {
    ci: card.find("#ci").val(),
    nombres: card.find("#nombres").val(),
    apellidos: card.find("#apellidos").val(),
    telefono: card.find("#telefono").val(),
    contrasena: card.find("#contrasena").val(),
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
// Listar empleados
function list_employees(state) {
  get_empleados(state)
    .then((empleados) => {
      return get_sucursales(1).then((sucursales) =>
        create_employee_card_list(empleados, sucursales)
      );
    })
    .catch((error) => {
      console.log(error);
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
      console.log(error);
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
    card.find("#contrasena").attr("value", empleado.usuario.contrasena);

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

  //Botones
  $(".btn_options").each(function () {
    $(this).find("div").eq(1).hide();
  });

  // Boton Editar
  $(".btn_options #edit").click(function (e) {
    let card = $(this).closest(".card");
    get_data_empleado(card);
    console.log("%c%s", "color: #00ff88", JSON.stringify(data_empleado));

    input_bool(card, false);

    card.find(".btn_options div").eq(0).hide();
    card.find(".btn_options div").eq(1).show();
  });

  // Boton Guardar
  $(".btn_options #save_edit").click(function (e) {
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
    ).then((response) => {
      if (response.success) {
        alert(response.message);
      } else {
        console.log("%c%s", "color: #ff0000", response.message);
      }
      input_bool(card, true);
      card.find(".btn_options div").eq(0).show();
      card.find(".btn_options div").eq(1).hide();

      // Si se editó el estado
      if (state_change) {
        console.log("se edito el estado");
        let state = Boolean(data_empleado.estado);
        $("#search_state").prop("checked", state);
        list_employees(get_state($("#search_state")));
      }
    });
  });

  // Boton Cancelar
  $(".btn_options #cancel_edit").click(function (e) {
    let card = $(this).closest(".card");
    card.find("#nombres").val(data_empleado.nombres);
    card.find("#apellidos").val(data_empleado.apellidos);
    card.find("#telefono").val(data_empleado.telefono);
    card.find("#contrasena").val(data_empleado.contrasena);
    // card.find("#sucursales_list").
    // card.find("#state").
    input_bool(card, true);
    card.find(".btn_options div").eq(0).show();
    card.find(".btn_options div").eq(1).hide();
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
            <input type="text" id="contrasena" class="input_text"  disabled required>
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
            <div>
              <button id="edit">Editar</button>
            </div>
            <div>
              <button id="save_edit">Guardar</button>
              <button id="cancel_edit">Cancelar</button>
            </div>
          </div>
        </div>
      </div>
    `);
}
