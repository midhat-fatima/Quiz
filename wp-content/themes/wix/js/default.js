// JavaScript Document
jQuery(document).ready(function($){
  var wix_custom_uploader;
 $('#upload_image_button').click(function(e) {
 
        e.preventDefault();
 
        //If the uploader object has already been created, reopen the dialog
        if (wix_custom_uploader) {
            wix_custom_uploader.open();
            return;
        }
 
        //Extend the wp.media object
        wix_custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 
        //When a file is selected, grab the URL and set it as the text field's value
        wix_custom_uploader.on('select', function() {
            attachment = wix_custom_uploader.state().get('selection').first().toJSON();
            $('#upload_image').val(attachment.id);
			$('#image').attr('src', attachment.url);
        });
 
        //Open the uploader dialog
        wix_custom_uploader.open();
 
    }); 
 
});