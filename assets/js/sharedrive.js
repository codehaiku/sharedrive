jQuery( document ).ready( function($) {

	'use strict';

    var sharedriveUploaderSettings = {
        browse_button: 'sd-choose-file-btn', // this can be an id of a DOM element or the DOM element itself
        url: sharedrive.ajaxurl,
        filters: {
            mime_types : sharedrive.settings.mime_types_allowed,
            max_file_size: sharedrive.settings.max_file_size // 1MB
        },
        multi_selection: true,
        multipart_params: {
            action: 'file_bulk_upload_action',
            taxonomy_id: $('input[name=taxonomy_id]').val()
        }
    };

    var uploader = new plupload.Uploader( sharedriveUploaderSettings );

    uploader.init();

    uploader.bind( 'FilesAdded', function( up, files ) {

        var html = '';

        plupload.each(files, function( file ) {
            html += '<li id="' + file.id + '"><span class="sd-file-progress-file">' + file.name + ' (' + plupload.formatSize(file.size) + ') </span> <span class="sd-file-progress"></span></li>';
        });

        $('#sd-form-upload-progress-window-ul').append( html );

        $('#sd-choose-file-start-upload').prop("disabled", false);

        return;

    });

    plupload.addFileFilter('mime_types', function(mime_types, file,instanceCallBack) {

        if ( mime_types.regexp.test(file.name) ) {

            if ( sharedrive.settings.mime_types_banned.regex.test(file.name) ) {

                this.trigger('Error', {
                    code : plupload.FILE_SIZE_ERROR,
                    message : plupload.translate('Error: The selected type of file ('+file.name+') is currently not supported.' ),
                    file : file
                });

                instanceCallBack(false);

                return;
            }

            instanceCallBack(true);

            return;

        } else {

            this.trigger('Error', {
                code : plupload.FILE_SIZE_ERROR,
                message : plupload.translate('Error: The selected type of file is currently not supported.' ),
                file : file
            });

            instanceCallBack(false);
            
            return;

        }

    });

    uploader.bind( 'FilesAdded', function( up, files ) {
        
    });

    // Upload Filter Error
    plupload.addFileFilter('max_file_size', function(maxSize, file, instanceCallBack) {
        var undef;
        // Invalid file size
        if ( file.size !== undef && maxSize && file.size > maxSize) {
            this.trigger('Error', {
                code : plupload.FILE_SIZE_ERROR,
                message : plupload.translate('Error: The selected file\'s size exceeds the maximum upload size.' ),
                file : file
            });
            instanceCallBack(false);
            return;
        } else {
            instanceCallBack(true);
            return;
        }
    });

    uploader.bind('Error', function( uploader, error ) {

        var error_message = '';
        
        if ( error.message ) {
            error_message = error.message;
        }
        
        $('#sd-form-upload-progress-window-ul').append('<li>'+error.file.name + '&nbsp;<span class="sd-error">(' + error_message+')</span></li>');

        return;

    });

    uploader.bind('UploadComplete', function() {
    	location.reload();
    });

    uploader.bind('FileUploaded', function (instance, file, server ){
        
        var server_response = JSON.parse(server.response);

        // Usually when the file size is greater than the max_post_size, the server
        // will not accept the file. Hence, returning null. We need to catch this error.
        // '0' is return from wp-admin/admin-ajax.php if there are no readable output.
        if ( 0 === server_response  ) {
            
            $('#'+file.id).append('<span class="sd-error description"> (Error processing files. The file ('+file.name+') is too large for the server to accept).</span>');
            return;

        }

        if ( 201 === server_response.status ) {
            
            $('#'+file.id).append('<span class="sd-error "> ('+server_response.message+').</span>');
            return;

        }

        if ( 200 === server_response.status ) {

            var file_name = '';
            var file_count = parseInt( $('#taxonomy-sd-count-file').text() );

            $('#'+file.id).append('<span class="sd-success"> (' + server_response.message + ').</span>');

            $('input#title').val(file.name);
            $('#new-post-slug').val(file.name);
            $('#sd-current-file-object').html( $('input#title').val() );

            file_count += 1;

            $('#taxonomy-sd-count-file').text( file_count );

        }

    });

    uploader.bind('UploadProgress', function(up, file) {
        $('#'+file.id+ ' .sd-file-progress ').css('width', file.percent+'%');
    });

    $('#sd-choose-file-start-upload').on('click', function(){
         uploader.start();
    });

    // Backdrop Remove
    $('.sharedrive-modal-upload-form').on('click', function(e){
    	
    	// Prevent child element 'click' event to be accidentally processed.
    	if ( e.target !== e.currentTarget ) {
	    	return;
	    }

    	$(this).parent().removeClass('active');

    });
    // New file
    $('#sharedrive-actions-new-files').on('click', function(){
    	$('#sharedrive-modal-upload-form-new-file').parent().addClass('active');
    });

    $('#sharedrive-actions-new-dir').on('click', function(){
    	$('#sharedrive-modal-upload-form-new-dir').parent().addClass('active');
    });
}); 