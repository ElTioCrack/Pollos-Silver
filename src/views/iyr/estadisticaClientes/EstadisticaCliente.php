<html>
    <head>
        <title>Estadistica</title>
        <meta charset="UTF-8">
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/chartJS/Chart.min.js"></script>
        <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    </head>
    <style>
            body{
           background-color: #f5ebdc;
        }
    .boton-descarga {
    display: inline-block;
    margin-top: 20px;
    cursor: pointer;
    padding: 20px 40px;
    background-color: #ff8a3b;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
    text-align: center;
    transition: background-color 0.3s ease;
}
        .caja{
             background-color: black;
            margin: auto;
            max-width: 250px;
            padding: 20px;
            border: 1px solid #BDBDBD;
        }
        .caja select{
            width: 100%;
            font-size: 16px;
            padding: 5px;
        }
        .resultados{
      
            
            margin-top: 40px;
            width: 600px;
        }
          .container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .chart-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chart {
            width: 100%;
            max-width: 400px;
        }

        .explanation {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color:rgb(224, 198, 180);
            padding: 20px;
        }
    </style>
    <body> 



<div  id="myDiv">
            <center>
        </br>
         <h1>Incremento de clientes por mes, cada año</h1>
                
         </center>
        <div class="caja">
       
            <select onChange="mostrarResultados(this.value);">
                <?php
                    for($i=2000;$i<2024;$i++){
                        if($i == 2015){
                            echo '<option value="'.$i.'" selected>'.$i.'</option>';
                        }else{
                            echo '<option value="'.$i.'">'.$i.'</option>';
                        }
                    }
                ?>
            </select>
        </div>
<div class="container">
        <div class="resultados">
            <canvas id="grafico" ></canvas>
        </div>
    
    
    
    <div class="explanation">
       <p>En el presente grafico, tenemos la representacion grafica de la evolucion de clientes totales durante cada mes. Si presionamos el cuadro relacionado a los años, tendremos una representacion grafica del crecimiento de clientes referentes al año seleccionado.</p>
     </div>
</div>
</div>





        <center>
 <button class="boton-descarga" onclick="captureAndSave()">Guardar</button>
</center>
    </body>
    <script>
            $(document).ready(mostrarResultados(2015));  
                function mostrarResultados(year){
                    $('.resultados').html('<canvas id="grafico"></canvas>');
                    $.ajax({
                        type: 'POST',
                        url: 'php/procesar.php',
                        data: 'year='+year,
                        dataType: 'JSON',
                        success:function(response){
                            var Datos = {
                                    labels : ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                                    datasets : [
                                        {
                                            fillColor : 'rgba(255, 137, 69, 0.6)', //COLOR DE LAS BARRAS
                                            strokeColor : 'rgba(255, 115, 0, 0.7)', //COLOR DEL BORDE DE LAS BARRAS
                                            highlightFill : 'rgba(255, 115, 0, 0.6)', //COLOR "HOVER" DE LAS BARRAS
                                            highlightStroke : 'rgba(255, 115, 0, 0.6)', //COLOR "HOVER" DEL BORDE DE LAS BARRAS
                                            data : response
                                        }
                                    ]
                                }
                            var contexto = document.getElementById('grafico').getContext('2d');
                            window.Barra = new Chart(contexto).Bar(Datos, { responsive : true });
                            Barra.clear();
                        }
                    });
                    return false;
                }
    </script>
      <script>
        function captureAndSave() {
            const elementToCapture = document.getElementById('myDiv');

            html2canvas(elementToCapture)
                .then(function (canvas) {
                    const image = canvas.toDataURL('image/jpeg');
                    const link = document.createElement('a');
                    link.href = image;
                    link.download = 'captura.jpg';
                    link.click();
                });
        }
    </script>
</html>