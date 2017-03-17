<?php
namespace Sharedrive;

add_action('wp', function(){
	FileUploadForm::getDirectoryId();

});
class FileUploadForm {

	public function __construct() {
		add_action('wp_enqueue_scripts', array($this, 'enqueue'));
	}

	public function enqueue() {
		wp_enqueue_script( 'sharedrive-plupload', SHAREDRIVE_DIR_URL . 'assets/js/sharedrive.js', array('plupload'), SHAREDRIVE_VERSION, true );
	}

	public static function renderForm() {
		self::uploadSettings();
		require_once SHAREDRIVE_DIR_PATH . 'templates/file-upload-form.php';
	}

	public static function getDirectoryId() {
		
		$current_term_id = 0;

		if ( is_tax( 'directory' ) ) {
			$current_term_id = get_queried_object()->term_id;
		} else {
			$root = get_term_by( 'slug', 'root', 'directory');
			$current_term_id = $root->term_id;
		}

		return $current_term_id;

	}

	public static function uploadSettings() {
		File::initMimeTypes();
        $allowed_mime_types = preg_replace( '/\s+/', '', str_replace( ' ', '', implode( ',', File::getAllowedFileTypes() ) ) );
        $banned_mime_types = File::getBannedTypes();
        ?>
        <script>
            var sharedrive = {
            	ajaxurl: '<?php echo admin_url('admin-ajax.php'); ?>',
                settings: {
                    mime_types_allowed: [
                        { title : "Files Allowed", extensions : "<?php echo trim( $allowed_mime_types ); ?>" }
                    ],
                    mime_types_banned: {
                        types: ".<?php echo implode(',.', $banned_mime_types) ; ?>",
                        regex: /(\.bat|\.cmd|\.php)$/i
                    },
                    max_file_size: <?php echo wp_max_upload_size(); ?>
                }
            }
        </script>
        <?php /* ;*/
	}
}

new FileUploadForm();