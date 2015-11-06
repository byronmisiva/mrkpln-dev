jQuery(document).ready(function () {
	//genera evento de carga de nombre de 
    jQuery('.btn-sendemail').click(function () { 
        jQuery('.popup-with-form').click();

        jQuery('#user_file').val(jQuery(this).attr("href"));
        jQuery('#user_file_name').val(jQuery(this).attr("ttl"));
        jQuery ('#user_to').val("");
        jQuery ('#user_asunto').val("");
        jQuery ('#user_cuerpo').val("");

        jQuery ('#mensaje-envio').html("");

    })


    jQuery('#publish').click(function () {
        jQuery (' .subir-archiuvos').toggle("slow");
    })


	jQuery("#login").submit(function( event ) {
        jQuery.post("http://www.markplan.com/wp-admin/admin-ajax.php?action=mi_funcion_accion" ,jQuery("#login").serialize())
            .done(function (data) {
				jQuery ('#mensaje-envio').html(data);
                jQuery ('#user_to').val("");
                jQuery ('#user_asunto').val("");
                jQuery ('#user_cuerpo').val("");
            });
        event.preventDefault();
    });

    jQuery('input#publicar').click(function () {
        jQuery('.messageSave').html("Enviando");


        var titulo = jQuery('input#title').val();
        var content = jQuery('textarea#content').val();

        var categorias = [];
        jQuery('input:checked').each(function()
        {
            categorias.push(jQuery(this).val());
        });
        //var jsonCategorias = JSON.stringify(categorias);
        archivo = jQuery ('.faz').val();

        // This does the ajax request
        jQuery.ajax({
            url: ajaxurl,
            data: {
                'action':'misiva_save_post',
                'titulo' : titulo,
                'content' : content,
                'jsonCategorias' : categorias,
                'archivo' : archivo

            },
            success:function(data) {
                jQuery('.messageSave').html("Exito");
                location.reload();
            },
            error: function(errorThrown){
                jQuery('.messageSave').html("Error");
            }
        });
    })
});

function functionOcultarIframe () {

    jQuery ('.btn-subir-archivos h3').text("Subiendo archivo");
    jQuery (' .subir-archiuvos').hide();
}

function functionReload () {
    window.scrollTo(0,0);
    location.reload();
    jQuery ('.btn-subir-archivos').show();
    jQuery('.btn-subir-archivos').click(function () {
        jQuery (' .subir-archiuvos').show();
    })

}
function functionContenido () {

    jQuery ('.btn-subir-archivos').show();
    jQuery('.btn-subir-archivos').click(function () {
        jQuery (' .subir-archiuvos').show();
    })

}

function resizeIframe(obj) {
    var alto = obj.contentWindow.document.body.scrollHeight;
    obj.style.height =  alto + 'px';
}