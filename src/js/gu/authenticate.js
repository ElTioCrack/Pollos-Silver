$(document).ready(function () {
  $("form").submit(function (e) {
    e.preventDefault();
    const ci = $("#ci").val();
    const password = $("#password").val();
    const system = "Webapp";
    login(ci, password, system);
  });
});

function authenticate(ci, password) {
  const url_php = "../../php/gu/authenticate.php";

  return new Promise(function (resolve, reject) {
    $.ajax({
      url: url_php,
      method: "post",
      dataType: "json",
      data: {
        ci: ci,
        password: password,
      },
      success: (response) => resolve(response),
      error: (error) => reject(error),
    });
  });
}

function login_empleado(ci, password, system) {
  const url_php = "../../php/gu/login_empleado.php";

  return new Promise(function (resolve, reject) {
    $.ajax({
      url: url_php,
      method: "post",
      dataType: "json",
      data: {
        ci: ci,
        password: password,
        system: system,
      },
      success: (response) => resolve(response),
      error: (error) => reject(error),
    });
  });
}
/* -------------------------------------------------------------------------- */
function handleResponse(response) {
  if (response.hasOwnProperty("error") && response.error !== null) {
    alert(response.error);
    throw new Error(response.error);
  }
}

function login(ci, password, system) {
  authenticate(ci, password)
    .then((authentication) => {
      handleResponse(authentication);
      return login_empleado(ci, password, system).then((response) => {
        handleResponse(response);
        console.log(JSON.stringify(response));
        console.log(response.estado);
        if (response.estado === 1)
          window.location.href = "../../views/db/dashboard.html";
        // alert()
        else alert("Acceso no Autorizado!");
      });
    })
    .catch((error) => {
      if (typeof error === "object") error = JSON.stringify(error);
      console.log("%c%s", "color: #ff0000", error);
    });
}
