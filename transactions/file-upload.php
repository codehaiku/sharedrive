<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once SHAREDRIVE_DIR_PATH . 'vendor/autoload.php';

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

$sensio_fs = new Filesystem();

$user_id = get_current_user_id();
$upload_dir = wp_upload_dir();
$upload_dir = trailingslashit( $upload_dir['basedir'] );
$sharedrive_upload_dir = trailingslashit( $upload_dir . 'sharedrive' );
$user_upload_dir = $sharedrive_upload_dir . trailingslashit( absint( $_POST['post_id'] ) ) . $user_id;

// Cleaning up.
if ( $sensio_fs->exists( $user_upload_dir ) ) {
	try {
		$sensio_fs->remove( $user_upload_dir );
	} catch ( IOExceptionInterface $e ) {
		echo "An error occurred cleaing up directory " . $e->getMessage();
	}
}
// Check if there is already a directory created for the user.
if ( ! $sensio_fs->exists( $user_upload_dir ) ) {
	// Create a new directory if there is no directory created for the current logged in user yet.
	try {
	    $sensio_fs->mkdir( $user_upload_dir, 0755 );
	    // Upload (copy) the submitted file into the directory.
	  	sd_upload_file( $_FILES, $user_upload_dir, $sensio_fs );
	} catch ( IOExceptionInterface $e ) {
		// Throw an error if there is was an error create a file.
	    echo "An error occurred while creating your directory at " . $e->getMessage();
	}
} else {
	// The directory already exists. Time to copy the temporary sent file to that directory.
	
	sd_upload_file( $_FILES, $user_upload_dir, $sensio_fs );
}

function sd_upload_file( $files = '', $destination = '', Filesystem $sensio_fs ) {
	try {

		$tmp_file_name = $files['file']['name'];
		$tmp_file_location = $files['file']['tmp_name'];
		$tmp_file_type = $files['file']['type'];
		$tmp_file_size = $files['file']['size'];

		$file_name = pathinfo( $tmp_file_name, PATHINFO_FILENAME );
		$file_extension = pathinfo( $tmp_file_name, PATHINFO_EXTENSION );
		$destination_file_name = sanitize_title( $file_name ) . '.' . $file_extension;

		// Move from temporary to static directory.
		$sensio_fs->copy( $tmp_file_location, trailingslashit( $destination ) . $destination_file_name );
		// Update the post meta.
		update_post_meta( absint( $_POST['post_id'] ), 'sharedrive_file_name', $destination_file_name );
		update_post_meta( absint( $_POST['post_id'] ), 'sharedrive_file_type', $tmp_file_type );
		update_post_meta( absint( $_POST['post_id'] ), 'sharedrive_file_size', $tmp_file_size );

	} catch( IOExceptionInterface $e ) {
		echo  $e->getMessage();
	}
}
echo 'RESPONSE';
die();
