jQuery( document ).ready( function($) {

    var sharedriveUploaderSettings = {
        browse_button: 'sharedrive-browse-files', // this can be an id of a DOM element or the DOM element itself
        url: ajaxurl,
        filters: {
            mime_types : sharedrive.settings.mime_types_allowed,
            max_file_size: sharedrive.settings.max_file_size // 1MB
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

    uploader.bind('UploadProgress', function(up, file) {
        $('#'+file.id+ ' b ').html( '<span>' + file.percent + "%</span>" );
    });

    $('#sharedrive-start-upload').on('click', function(){
         uploader.start();
    });

}); 