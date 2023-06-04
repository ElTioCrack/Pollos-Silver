function session_data() {
  let url_php = "../../php/gu/session_data.php";

  return new Promise(function (resolve, reject) {
    $.ajax({
      url: url_php,
      method: "POST",
      dataType: "json",
      success: function (response) {
        resolve(response); // Resuelve la promesa con la respuesta
      },
      error: function (error) {
        reject(error); // Rechaza la promesa con el error
      },
    });
  });
}
