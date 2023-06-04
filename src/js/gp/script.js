//selecting all required elements
const dropArea = document.querySelector(".drag-area"),
dragText = dropArea.querySelector("header"),

input = dropArea.querySelector("input");
//console.log(input);
let file; //this is a global variable and we'll use it inside multiple functions


input.addEventListener("change", function(){
  //getting user select file and [0] this means if user select multiple files then we'll select only the first one
  file = this.files[0];
  dropArea.classList.add("active");
  showFile(); //calling function
});


//If user Drag File Over DropArea
dropArea.addEventListener("dragover", (event)=>{
  event.preventDefault(); //preventing from default behaviour
  dropArea.classList.add("active");
  dragText.textContent = "Liberar para cargar archivo";
});

//If user leave dragged File from DropArea
dropArea.addEventListener("dragleave", ()=>{
  dropArea.classList.remove("active");
  dragText.textContent = "Arrastrar y soltar para cargar archivo";
});

//If user drop File on DropArea
dropArea.addEventListener("drop", (event)=>{
  event.preventDefault(); //preventing from default behaviour
  //getting user select file and [0] this means if user select multiple files then we'll select only the first one
  file = event.dataTransfer.files[0];
  showFile(); //calling function
});

function showFile(){
  let fileType = file.type; //getting selected file type
  let validExtensions = ["image/jpeg", "image/jpg"]; //adding some valid image extensions in array
  var fileSize = file.size;
  var fileSizeKB = fileSize / 1024;
  //console.log("Tamaño de la imagen: " + fileSizeKB + " KB");
  if(validExtensions.includes(fileType)){ //if user selected file is an image file
    if (fileSizeKB <= 300) {
      let fileReader = new FileReader(); //creating new FileReader object
      fileReader.onload = ()=>{
        let fileURL = fileReader.result; //passing user file source in fileURL variable
        let imgTag = `<img src="${fileURL}" alt="" id="imagenProducto">`; //creating an img tag and passing user selected file source inside src attribute
        dropArea.innerHTML = imgTag; //adding that created img tag inside dropArea container
        // Obtener el nombre del archivo
        let fileName = file.name;
        document.getElementById("filenameP").textContent = fileName;
        //console.log(fileName);
      }
      fileReader.readAsDataURL(file);
    }else{
      alert('Archivo muy pesado: Max 300Kb');
      dropArea.classList.remove("active");
      dragText.textContent = "Arrastra tu imagen";
    }
  }else{
    alert('Tipo de Archivos Permitido JPEG-JPG');
    dropArea.classList.remove("active");
    dragText.textContent = "Arrastra tu imagen";
  }
}

