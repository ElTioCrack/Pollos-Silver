
let id = $("input[name*='id_sucursal']")
id.attr("readonly","readonly");


$(".btnedit").click( e =>{
    let textvalues = displayData(e);
        let coordenada= $("input[name*='id_coordenada']");
        let ciudad = $("input[name*='id_ciudad']");
        let nombre = $("input[name*='nombre']");
        let direccion = $("input[name*='direccion']");
        let estado = $("input[name*='estado']");   
        let telefono = $("input[name*='telefono']"); 
        let apertura = $("input[name*='hora_apertura']");   
        let cierre = $("input[name*='hora_cierre']"); 
        
        
        
    let bookname = $("input[name*='book_name']");
    let bookpublisher = $("input[name*='book_publisher']");
    let bookprice = $("input[name*='book_price']");

    id.val(textvalues[0]);
    coordenada.val(textvalues[1]);
    ciudad.val(textvalues[2]);
    nombre.val(textvalues[3]);
    direccion.val(textvalues[4]);
    estado.val(textvalues[5]);
    telefono.val(textvalues[6]);
    apertura.val(textvalues[7]);
    cierre.val(textvalues[8]);
});


function displayData(e) {
    let id = 0;
    const td = $("#tbody tr td");
    let textvalues = [];

    for (const value of td){
        if(value.dataset.id == e.target.dataset.id){
           textvalues[id++] = value.textContent;
        }
    }
    return textvalues;

}