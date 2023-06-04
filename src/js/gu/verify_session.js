function verify_session() {
    let url_php = "../../php/gu/verify_session.php";
  
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
  