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
function login(ci, password, system) {
  authenticate(ci, password)
    .then((authentication) => {
      return login_empleado(ci, password, system).then((response) => {
        console.log(response.estado);
        if (response.estado === 1) {
          window.location.href = "../../views/db/dashboard.html";
        } else {
          alert("Acceso no Autorizado!");
        }
      });
    })
    .catch((error) => handleRequestError(error));
}

function handleRequestError(error) {
  let errorMessage = "Acceso no Autorizado!";

  if (error.responseJSON && error.responseJSON.errorMessage) {
    errorMessage = error.responseJSON.errorMessage;
  }

  alert(`Error: ${error.status}\n${errorMessage}`);
  console.log("%c%s", "color: #ff0000", JSON.stringify(error));
}
