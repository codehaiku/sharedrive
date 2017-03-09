jQuery( document ).ready( function($) {

    var uploader = new plupload.Uploader({
        browse_button: 'browse', // this can be an id of a DOM element or the DOM element itself
        url: ajaxurl,
        filters: {
            mime_types : sharedrive.settings.mime_types_allowed,
            max_file_size: sharedrive.settings.max_file_size // 1MB
        },
        multi_selection: false,
        multipart_params: {
            action: 'file_upload_action',
            post_id: $('#post_ID').val()
        }
    });
    
    uploader.init();
    
    uploader.bind('Error', function( uploader, error ) {

        var error_message = '';
        
        if ( error.message ) {
            error_message = error.message;
        }
        
        $('#start-upload').addClass('button-disabled');

        $('#filelist').html('<li class="sd-error">'+error_message+'</li>');

        return;

    });

    uploader.bind( 'FilesAdded', function( up, files ) {

        var html = '';

        var fileCount = up.files.length,
        i = 0,
        ids = $.map( up.files, function (item) { return item.id; } );

        for (i = 0; i < ( fileCount - 1 ); i++) {
            uploader.removeFile( uploader.getFile( ids[i] ) );
        }
        
        plupload.each(files, function( file ) {
            html += '<li id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></li>';
        });

        document.getElementById('filelist').innerHTML = html;

        $('#start-upload').removeClass('button-disabled');

        return;

    });

    uploader.bind('UploadProgress', function(up, file) {
        $('#'+file.id+ ' b ').html( '<span>' + file.percent + "%</span>" );
        console.log($('#'+file.id+ ' b '));
    });

    uploader.bind('UploadComplete', function(files, UploadedFIles){
       // Upload is Complete callback
    });

    // Upload Filter Error
    plupload.addFileFilter('max_file_size', function(maxSize, file, instanceCallBack) {
        var undef;
        // Invalid file size
        if (file.size !== undef && maxSize && file.size > maxSize) {
            this.trigger('Error', {
                code : plupload.FILE_SIZE_ERROR,
                message : plupload.translate('Error: The selected file size exceeds the maximum upload size.' ),
                file : file
            });
            instanceCallBack(false);
        } else {

            instanceCallBack(true);
        }
    });

    plupload.addFileFilter('mime_types', function(mime_types, file,instanceCallBack) {
        if ( mime_types.regexp.test(file.name) ) {
            instanceCallBack(true);
        } else {
            this.trigger('Error', {
                code : plupload.FILE_SIZE_ERROR,
                message : plupload.translate('Error: The selected type of file is currently not supported.' ),
                file : file
            });
            instanceCallBack(false);
        }
    });

    uploader.bind('FileUploaded', function (instance, file, server ){
        
        var server_response = JSON.parse(server.response);

        // Usually when the file size is greater than the max_post_size, the server
        // will not accept the file. Hence, returning null. We need to catch this error.
        // '0' is return from wp-admin/admin-ajax.php if there are no readable output.
        if ( 0 === server_response  ) {
            $('#filelist').append('<li class="sd-error">Error processing files. The file is too large for the server to accept.</li>');
            return;
        }

        if ( 201 === server_response.status ) {
            $('#filelist').append('<li class="sd-error">'+server_response.message+'</li>');
            return;
        }
        if ( 200 === server_response.status ) {

            var file_name = '';

            $('#filelist').append('<li class="sd-success">' + server_response.message + '</li>');

            $('input#title').val(file.name);
            $('#new-post-slug').val(file.name);
            $('#sd-current-file-object').html( $('input#title').val() );
        }
    });

    document.getElementById('start-upload').onclick = function() {
        uploader.start();
    };

} );