jQuery("document").ready(function(){
	jQuery('.cover_photo_upload').click(function(e) {
        e.preventDefault();

        var custom_uploader = wp.media({
            title: 'Custom Image',
            button: {
                text: 'Upload Image'
            },
            multiple: false  // Set this to true to allow multiple files to be selected
        })
        .on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            jQuery('.cover_photo').css({"display":"block"});
            jQuery('.cover_photo').attr('src', attachment.url+"?id="+ jQuery.now());
            jQuery('.cover_photo_url').val(attachment.url);

        })
        .open();
    });
});