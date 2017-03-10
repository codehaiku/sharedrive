<?php
/**
 * This file is part of the Sharedrive WordPress Plugin Package.
 *
 * (c) Joseph Gabito <joseph@useissuestabinstead.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 * PHP Version 5.4
 * 
 * @category Sharedrive\Files
 * @package  Sharedrive
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  GIT:github.com/codehaiku/sharedrive
 * @link     github.com/codehaiku/sharedrive The Plugin Repository
 */

namespace Sharedrive;

require_once SHAREDRIVE_DIR_PATH . 'vendor/autoload.php';

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

if (! defined('ABSPATH') ) {
    return;
}

class File {

	public function __construct( $post_id = 0, $http_file = array() ) {

		// Get the user id of the current logged-in user.
		$user_id = get_current_user_id();
		// Get WordPress intance 'wp-uploads dir'.
		$upload_dir = wp_upload_dir();
		// Store the sharedrive/ dir for later use. 
		$sharedrive_upload_dir = trailingslashit( trailingslashit( $upload_dir['basedir'] ) . 'sharedrive' );
		// Create new string destination for new files
		$user_upload_dir = $sharedrive_upload_dir . trailingslashit( $post_id ) . $user_id;

		$file_name = pathinfo( $http_file['file']['name'], PATHINFO_FILENAME );

		$this->name = $http_file['file']['name'];
		$this->type = $http_file['file']['type'];
		$this->size = $http_file['file']['size'];
		$this->extension = pathinfo( $this->name, PATHINFO_EXTENSION );;
		$this->location = $http_file['file']['tmp_name'];
		$this->appliedName = sanitize_title( $file_name ) . '.' . $this->extension;
		$this->destination = trailingslashit( $user_upload_dir );
		$this->post_id = absint( $post_id );

		$this->sensio_fs = new Filesystem();

		return $this;

	}

	public function upload( $type = 'update', $success_message = '' ) {

		try {

			// Move from temporary to static directory.
			$this->sensio_fs->copy( $this->location, $this->destination . $this->appliedName );
					
			// Update the post meta.
			if ( 'update' === $type ) {
				update_post_meta( absint( $this->post_id ), 'sharedrive_file_name', $this->appliedName );
				update_post_meta( absint( $this->post_id ), 'sharedrive_file_type', $this->type );
				update_post_meta( absint( $this->post_id ), 'sharedrive_file_size', $this->size );
			}

			$response = wp_json_encode(array(
					'status' => 200,
					'message' => $success_message
				));

			echo $response;

		} catch( IOExceptionInterface $e ) {

			$response = wp_json_encode(array(
					'status' => 201,
					'message' => __('An error occurred while moving your uploaded file.
					 Make sure your uploads folder is writable.', 'sharedrive')
				));

			echo $response;

		}

		return;
	}

	public function processHttpUpload( $type = 'update', $success_message = '' ) {
		// Cleaning up.
		if ( $this->sensio_fs->exists( $this->destination ) ) {
			try {
				$this->sensio_fs->remove( $this->destination );
			} catch ( IOExceptionInterface $e ) {
				$response = wp_json_encode(
					array(
						'status' => 201,
						'message' => __('An error occurred cleaing up directory ', 'sharedrive') . $e->getMessage()
					)
				);
				echo $response;
				Sharedrive\Helpers::stop();
			}
		}
		// Check if there is already a directory created for the user.
		if ( ! $this->sensio_fs->exists( $this->destination ) ) {
			// Create a new directory if there is no directory created for the current logged in user yet.
			try {
			    $this->sensio_fs->mkdir( $this->destination, 0755 );
			    // Upload (copy) the submitted file into the directory.
			  	$this->upload( $type, $success_message );
			} catch ( IOExceptionInterface $e ) {
				// Throw an error if there is was an error create a file.
			    $response = wp_json_encode(array(
						'status' => 201,
						'message' => __('An error occurred while creating your directory at ', 'sharedrive') . $e->getMessage()
					));
				echo $response;
				Sharedrive\Helpers::stop();
			}
		} else {
			// The directory already exists. Time to copy the temporary sent file to that directory.
			$this->upload( $type, $success_message );
		}
	}
	
}