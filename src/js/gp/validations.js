$(document).ready(function() {
  $('#productName').on('keydown', function(e) {
    var key = e.keyCode || e.which;
    var inputValue = String.fromCharCode(key);
    var pattern = /^[a-zA-Z\s!"#$%&'()*+,-./:;<=>?@[\]^_`{|}~\b]+$/;
    if (key >= 96 && key <= 105) {
      e.preventDefault();
      return;
    }
    if (!pattern.test(inputValue)) {
      e.preventDefault();
    }
  });
  //VALIDACION CAMPO PRECIOS
  
  var priceInput = $('#productPrice');
    priceInput.on('input', function() {
      var value = $(this).val();
      var newValue = value.replace(/\D/g, '');
      newValue = newValue.slice(0, 3);
      if (parseInt(newValue) > 100) {
        newValue = '100';
      }
      $(this).val(newValue);
    });
    
    //Text Area (Descripccion)
    $('#productDescription').on('input', function() {
    var maxLength = 126;
    var text = $(this).val();
    
    if (text.length > maxLength) {
      $(this).val(text.substring(0, maxLength));
    }
  });
});