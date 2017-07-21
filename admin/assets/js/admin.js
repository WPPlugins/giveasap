function giveasap_image_media( $imageContainer, $imageInput ) {
    'use strict';
 
    var file_frame;
 
    /**
     * If an instance of file_frame already exists, then we can open it
     * rather than creating a new instance.
     */
  
    if ( undefined !== file_frame ) {
 
        file_frame.open();
    
        return;
 
    }
 
    /**
     * If we're this far, then an instance does not exist, so we need to
     * create our own.
     *
     * Here, use the wp.media library to define the settings of the Media
     * Uploader. We're opting to use the 'post' frame which is a template
     * defined in WordPress core and are initializing the file frame
     * with the 'insert' state.
     *
     * We're also not allowing the user to select more than one image.
     */
    file_frame =  wp.media({
        multiple: false,
    });

    file_frame.on('open',function() {
      var selection = file_frame.state().get('selection');
      var ids = $imageInput.val().split(',');
        ids.forEach(function(id) {
          var attachment = wp.media.attachment(id);
          attachment.fetch();
          selection.add( attachment ? [ attachment ] : [] );
        });
    });

    // When an image is selected in the media frame...
    file_frame.on( 'select', function() {
      
      // Get media attachment details from the frame state
      var attachments = file_frame.state().get('selection').toJSON();

      var attachmentIDs = [];
      $imageContainer.empty();
      var $galleryID = $imageContainer.attr("id");
      for( var i = 0; i < attachments.length; i++ ) {
        if( attachments[ i ].type == "image" ) {
          attachmentIDs.push( attachments[ i ].id );
          $imageContainer.append( sortable_gallery_image_create( attachments[ i ], $galleryID ) );
        }
      }
  
      $imageInput.val( attachmentIDs.join() );
      sortable_gallery_image_remove();
    });
 
    // Now display the actual file_frame
    file_frame.open();
 
}

function sortable_image_gallery_media( $imageContainer, $imageInput ) {
    'use strict';
 
    var file_frame;
 
    /**
     * If an instance of file_frame already exists, then we can open it
     * rather than creating a new instance.
     */
  
    if ( undefined !== file_frame ) {
 
        file_frame.open();
    
        return;
 
    }
 
    /**
     * If we're this far, then an instance does not exist, so we need to
     * create our own.
     *
     * Here, use the wp.media library to define the settings of the Media
     * Uploader. We're opting to use the 'post' frame which is a template
     * defined in WordPress core and are initializing the file frame
     * with the 'insert' state.
     *
     * We're also not allowing the user to select more than one image.
     */
    file_frame =  wp.media({
        multiple: true,
    });

    file_frame.on('open',function() {
      var selection = file_frame.state().get('selection');
      var ids = $imageInput.val().split(',');
        ids.forEach(function(id) {
          var attachment = wp.media.attachment(id);
          attachment.fetch();
          selection.add( attachment ? [ attachment ] : [] );
        });
    });

    // When an image is selected in the media frame...
    file_frame.on( 'select', function() {
      
      // Get media attachment details from the frame state
      var attachments = file_frame.state().get('selection').toJSON();

      var attachmentIDs = [];
      $imageContainer.empty();
      var $galleryID = $imageContainer.attr("id");
      for( var i = 0; i < attachments.length; i++ ) {
        if( attachments[ i ].type == "image" ) {
          attachmentIDs.push( attachments[ i ].id );
          $imageContainer.append( sortable_gallery_image_create( attachments[ i ], $galleryID ) );
        }
      }
  
      $imageInput.val( attachmentIDs.join() );
      sortable_gallery_image_remove();
    });
 
    // Now display the actual file_frame
    file_frame.open();
 
}

function sortable_gallery_image_create( $attachment, $galleryID ) {
  var image_url = '';
  

  if( $attachment.sizes.thumbnail ) {
    image_url = $attachment.sizes.thumbnail.url;
  } else {
    image_url = $attachment.sizes.full.url;
  }
  var $output = '<li tabindex="0" role="checkbox" aria-label="' + $attachment.title + '" aria-checked="true" data-id="' + $attachment.id + '" class="attachment save-ready selected details">';
    $output += '<div class="attachment-preview js--select-attachment type-image subtype-jpeg portrait">';
      $output += '<div class="thumbnail">'
        
          $output += '<div class="centered">'
            $output += '<img src="' + image_url + '" draggable="false" alt="">'
          $output += '</div>'
        
      $output += '</div>'
      
    $output += '</div>'
    
      $output += '<button type="button" data-gallery="#' + $galleryID + '" class="button-link check remove-sortable-wordpress-gallery-image" tabindex="0"><span class="media-modal-icon"></span><span class="screen-reader-text">Deselect</span></button>'
    
    
  $output += '</li>';
  return $output;

}

function sortable_gallery_image_remove( ) {
  jQuery(".remove-sortable-wordpress-gallery-image").on( 'click', function(){
    $id = jQuery(this).parent().attr("data-id");
    $gallery = jQuery(this).attr("data-gallery");
    $imageInput = jQuery( $gallery + "_input" );
    console.log( $imageInput );
    jQuery(this).parent().remove();
    var ids = $imageInput.val().split(',');
    $idIndex = ids.indexOf( $id );
    if( $idIndex >= 0 ) {
      ids.splice( $idIndex, 1 );
      $imageInput.val( ids.join() );
    }
  });
}

function giveasap_prepare_format_from_php( format ) {
  format = format.replace("d", "dd");
  format = format.replace("j", "d");
  format = format.replace("Y", "yy");
  format = format.replace("m", "mm");

  return format;
}

(function($){

   
  $(document).ready(function(){
    var imageButton = $(".add-sortable-wordpress-gallery");
    sortable_gallery_image_remove();
    imageButton.each( function(){
      var galleryID = $(this).attr("data-gallery");
      var imageContainer = $( galleryID );
      var imageInput = $( galleryID + "_input" );
      imageContainer.sortable();
      imageContainer.on( "sortupdate", function( event, ui ) {
                  $ids = [];
                  $images = imageContainer.children("li");
                  $images.each( function(){ 
                    $ids.push( $(this).attr("data-id") );
                  });
                  imageInput.val($ids.join());
              } );
       
      $(this).on('click', function(){
        sortable_image_gallery_media( imageContainer, imageInput );
      });
      
    });

    var singleImageButton = $(".add-single-wordpress-image");
    singleImageButton.each( function(){
      var galleryID = $(this).attr("data-gallery");
      var imageContainer = $( galleryID );
      var imageInput = $( galleryID + "_input" );
       
      $(this).on('click', function(){
        giveasap_image_media( imageContainer, imageInput );
      });
      
    });

    var $expand_users = $("#giveasap_expand_users");
    $expand_users.on( 'click', function(){
       var $users_container = $("#giveasap_users_container");
       $users_container.toggleClass("hidden");
    });

    var $date_format = $("#date_format");
    $date_format.on( 'change', function(){
      console.log('change');
      $format = giveasap_prepare_format_from_php( $(this).val() );
      $(".datepicker").datepicker('option', {
          altFormat: $format
      });
    });

  });
})(jQuery)