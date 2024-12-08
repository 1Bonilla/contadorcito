$(document).ready(function() {
    //Cargar el listado de empresas
    cargarEmpresas();

    
    $('#form-empresa').on('submit', function(e) {
        e.preventDefault(); 
        var formData = $(this).serialize(); 

        $.ajax({
            url: 'backend.php',
            type: 'POST',
            data: formData, 
            success: function(response) {
                console.log(response); 
                alert(response); 
                cargarEmpresas();
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText); 
                alert('Hubo un error al agregar la empresa');
            }
        });
    });
});
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('reporteForm');
    const mensajeDiv = document.getElementById('mensaje');

    form.addEventListener('submit', function (event) {
        event.preventDefault(); 

   
        const formData = new FormData(form);

   
        fetch('backend.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.text())
        .then(data => {
       
            mensajeDiv.innerHTML = data;
        })
        .catch(error => {
        
            mensajeDiv.innerHTML = `<p style="color: red;">Error al generar el reporte: ${error.message}</p>`;
        });
    });
});
