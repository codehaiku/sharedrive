jQuery( document ).ready( function($) {

    var sharedriveUploaderSettings = {
        browse_button: 'sharedrive-browse-files', // this can be an id of a DOM element or the DOM element itself
        url: ajaxurl,
        filters: {
            //mime_types : sharedrive.settings.mime_types_allowed,
            //max_file_size: sharedrive.settings.max_file_size // 1MB
        },
        multi_selection: true,
        multipart_params: {
            action: 'file_bulk_upload_action',
            taxonomy_id: $('input[name=tag_ID]').val()
        }
    };

    var uploader = new plupload.Uploader(sharedriveUploaderSettings);

    uploader.init();

    uploader.bind( 'FilesAdded', function( up, files ) {

        var html = '';

        plupload.each(files, function( file ) {
            html += '<li id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></li>';
        });

        $('#filelist').append( html );

        $('#start-upload').removeClass('button-disabled');

        return;

    });

    uploader.bind('Error', function( uploader, error ) {

        var error_message = '';
        
        if ( error.message ) {
            error_message = error.message;
        }
        
        $('#filelist').append('<li class="sd-error">'+error.file.name + '&nbsp;<strong><span class="sd-error-type">(' + error_message+')</span></strong></li>');

        return;

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

            $('#'+file.id).append('<span class="sd-success"> (' + server_response.message + ').</span>');

            $('input#title').val(file.name);
            $('#new-post-slug').val(file.name);
            $('#sd-current-file-object').html( $('input#title').val() );

        }

    });

    uploader.bind('UploadProgress', function(up, file) {
        $('#'+file.id+ ' b ').html( '<span>' + file.percent + "%</span>" );
    });

    $('#sharedrive-start-upload').on('click', function(){
         uploader.start();
    });

}); 