function logout() {
  let url_php = "../../php/gu/logout.php";

  let url_login = "../../views/gu/login.html";

  $.ajax({
    url: url_php,
    method: "POST",
    dataType: "json",
    success: function (response) {
      const session_start = response.session_start;

      if (session_start) {
        // La sesión fue cerrada
        alert("Sesión Cerrada");
        window.location.href = url_login;
      } else {
        // No se pudo cerrar la sesión o hubo un error inesperado
        alert("No se pudo cerrar sesión. Inténtelo nuevamente");
        window.location.reload();
      }
    },
    error: function (error) {
      console.log(error);
    },
  });
}


