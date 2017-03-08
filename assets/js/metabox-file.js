jQuery( document ).ready( function($) {

    var uploader = new plupload.Uploader({
        browse_button: 'browse', // this can be an id of a DOM element or the DOM element itself
        //url: 'http://thrive.dsc/wp-admin/admin-ajax.php?action=file_upload_action&post_id=' + $('#post_ID').val(),
        url: ajaxurl,
        filters: {
            mime_types : [
                { title : "Image files", extensions : "jpg,png,gif,jpeg" },
                { title : "Zip files", extensions : "zip" },
                { title : "Doc Files", extensions : "doc,docx,pdf,xls" },
                { title : "PDF Files", extensions : "pdf" }
            ],
        },
        multi_selection: false,
        multipart_params: {
            action: 'file_upload_action',
            post_id: $('#post_ID').val()
        }
    });
    
    uploader.init();
    
    uploader.bind('Error', function( uploader, error ) {
        console.error( error );
    });

    uploader.bind( 'FilesAdded', function(up, files) {

        var html = '';

        var fileCount = up.files.length,
        i = 0,
        ids = $.map( up.files, function (item) { return item.id; } );

        for (i = 0; i < ( fileCount - 1 ); i++) {
            uploader.removeFile( uploader.getFile( ids[i] ) );
        }
        
        plupload.each(files, function( file ) {
            html += '<li id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b</li>';
        });

        document.getElementById('filelist').innerHTML = html;

    });

    uploader.bind('UploadProgress', function(up, file) {
        document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
    });

    uploader.bind('UploadComplete', function(files, UploadedFIles){
       // Upload is Complete callback
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

            if ( UploadedFIles.length >= 1 ) {
                $.each( UploadedFIles, function( index, file ){
                    file_name = file.name;
                });
            }
            $('input#title').val(file_name);
            $('#new-post-slug').val(file_name);
            $('#sd-current-file-object').html( $('input#title').val() );
        }
    });

    document.getElementById('start-upload').onclick = function() {
        uploader.start();
    };

} );