jQuery(function(){
 


    jQuery(document).ajaxStop(jQuery.unblockUI);



    jQuery('.wpeden-theme-opt-form').submit(function(e){

        e.preventDefault();

        jQuery('div.wpeden-theme-options').block({
            css: {
                border: 'none',
                padding: '15px',
                margin: '-100px 0 0 50px',
                backgroundColor: 'rgba(0,0,0,0.4)',
                '-webkit-border-radius': '5px',
                '-moz-border-radius': '5px',
                'border-radius': '5px',

                color: '#fff',
                'font-size' : '10pt',
                'font-weight' : '700'
            }
        });

        jQuery(this).ajaxSubmit({
            success: function(res){
                jQuery('div.wpeden-theme-options').unblock();
                jQuery.growlUI('Done', 'Options Updated Successfully!',10000);
            }
        });


    });

    jQuery('.colorpicker').wpColorPicker();



    // Uploading files
    var file_frame;


    jQuery('.btn-media-upload').live('click', function( event ){

        event.preventDefault();
        var dfield = jQuery(jQuery(this).attr('rel'));
        //alert(dfield.attr('id'));
        // If the media frame already exists, reopen it.
        if ( file_frame ) {
            file_frame.open();
            return;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: jQuery( this ).data( 'uploader_title' ),
            button: {
                text: jQuery( this ).data( 'uploader_button_text' )
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
            // We set multiple to false so only get one image from the uploader
            attachment = file_frame.state().get('selection').first().toJSON();
            dfield.val(attachment.url);

        });

        // Finally, open the modal
        file_frame.open();
    });


    jQuery('select').chosen();
     

     
     
     jQuery('.typocb').click(function(){
          var cbid = '#'+this.id+"-x";
          if(!jQuery(this).hasClass('active')) jQuery(cbid).val(1);
          else jQuery(cbid).val(0); 
     });
     

     
     jQuery('a[data-toggle="tab"]').on('shown', function (e) {
        //save the latest tab; use cookies if you like 'em better:
        localStorage.setItem('lastTab', jQuery(e.target).attr('id'));
      });

     //go to the latest tab, if it exists:
     var lastTab = localStorage.getItem('lastTab');
     if (lastTab) {
          jQuery('#'+lastTab).tab('show');
      }

});


  