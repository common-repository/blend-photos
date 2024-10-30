jQuery(function(jQuery){
  // Create variables (in this scope) to hold the API and image size
  var jcrop_api,
      boundx,
      boundy,
      // Grab some information about the preview pane
      $preview = jQuery('#blp-preview-pane'),
      $pcnt = jQuery('#blp-preview-pane .blp-preview-container'),
      $pimg = jQuery('#blp-preview-pane .blp-preview-container .blp-preview'),
      xsize = $pcnt.width(),
      ysize = $pcnt.height();
    
   
  jQuery('#target').Jcrop({
    onChange: updatePreview,
    onSelect: updatePreview,
    bgOpacity: 0.5,
    aspectRatio: xsize / ysize,
    setSelect: [ 200, 200, 400, 300 ],
    allowResize: true
  },function(){
    // Use the API to get the real image size
    var bounds = this.getBounds();
    boundx = bounds[0];
    boundy = bounds[1];

    jcrop_api = this; // Store the API in the jcrop_api variable

    // Move the preview into the jcrop container for css positioning
    //$preview.appendTo(jcrop_api.ui.holder);
  });

  function updatePreview(c) {
    if (parseInt(c.w) > 0) {
      var rx = xsize / c.w;
      var ry = ysize / c.h;
        
      jQuery('#blend_x').val(c.x);
      jQuery('#blend_y').val(c.y);
      jQuery('#blend_w').val(c.w);
      jQuery('#blend_h').val(c.h);
      responsiveCoords(c, '#target');

      $pimg.css({
        width: Math.round(rx * boundx) + 'px',
        height: Math.round(ry * boundy) + 'px',
        marginLeft: '-' + Math.round(rx * c.x) + 'px',
        marginTop: '-' + Math.round(ry * c.y) + 'px'
      });
    }
  }

  function responsiveCoords(c, imgSelector) {

          var imgOrignalWidth     = jQuery(imgSelector).prop('naturalWidth');
          var imgOriginalHeight   = jQuery(imgSelector).prop('naturalHeight');

          var imgResponsiveWidth  = parseInt(jQuery(imgSelector).css('width'));
          var imgResponsiveHeight = parseInt(jQuery(imgSelector).css('height'));                

          var responsiveX         = Math.ceil((c.x/imgResponsiveWidth) * imgOrignalWidth);
          var responsiveY         = Math.ceil((c.y/imgResponsiveHeight) * imgOriginalHeight);

          var responsiveW         = Math.ceil((c.w/imgResponsiveWidth) * imgOrignalWidth);
          var responsiveH         = Math.ceil((c.h/imgResponsiveHeight) * imgOriginalHeight);

          jQuery('#blend_x').val(responsiveX);
          jQuery('#blend_y').val(responsiveY);
          jQuery('#blend_w').val(responsiveW);
          jQuery('#blend_h').val(responsiveH);

  }

});