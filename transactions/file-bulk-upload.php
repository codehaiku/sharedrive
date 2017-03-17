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
 * @category Sharedrive\Actions\FileUpload
 * @package  Sharedrive
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  GIT:github.com/codehaiku/sharedrive
 * @link     github.com/codehaiku/sharedrive The Plugin Repository
 */

require_once SHAREDRIVE_DIR_PATH . 'classes/file.php';

use Sharedrive\File;

if ( ! defined('ABSPATH') ) {
    return;
}

File::initMimeTypes();

$pid = filter_input( INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT );
$taxonomy_id = filter_input( INPUT_POST, 'taxonomy_id', FILTER_VALIDATE_INT );

$allowed_files = File::getAllowedFileTypes();
$banned_files = File::getBannedTypes();

// Only logged-in users are allowed to upload file.
if ( ! is_user_logged_in() ) {
	wp_die( __('Are you sure you want to do this?', 'sharedrive') );
}

if ( empty( $taxonomy_id ) ) {
	$response = wp_json_encode(
		array(
			'status' => 201,
			'message' => sprintf( 
				__('Error: No directory selected. Please select a directory.', 'sharedrive'), 
				Sharedrive\Helpers::formatSize( wp_max_upload_size() ) 
				)
		));
	echo $response;
	Sharedrive\Helpers::stop();
}

if ( isset( $_FILES['file'] ) ) {

	$name  = $_FILES['file']['name'];
	$type  = $_FILES['file']['type'];
	$size  = $_FILES['file']['size'];
	$error = $_FILES['file']['error'];
	$author = get_current_user_id();

	$file_name = pathinfo( $name, PATHINFO_FILENAME );
	$file_extension = pathinfo( $name, PATHINFO_EXTENSION );
	$destination_file_name = sanitize_title( $file_name ) . '.' . $file_extension;

	// File size is usually empty when uploaded file > max_upload_size.
	if ( 0 === $size  ) {
		$response = wp_json_encode(
			array(
				'status' => 201,
				'message' => sprintf( 
					__('Error: Max upload size issue. Uploaded files should be less than %s', 'sharedrive'), 
					Sharedrive\Helpers::formatSize( wp_max_upload_size() ) 
					)
			));
		echo $response;
		Sharedrive\Helpers::stop();
	}

	if ( ! in_array( $file_extension, $allowed_files ) ) {
		$response = wp_json_encode(
			array(
				'status' => 201,
				'message' => sprintf( 
							esc_html__('Error uploading file. The file type (.%s) is not allowed', 'sharedrive'), 
							$file_extension 
						), 
				)
			);
		echo $response;
		Sharedrive\Helpers::stop();
	}

	if ( in_array( $file_extension, $banned_files ) ) {

		$response = wp_json_encode(
			array(
				'status' => 201,
				'message' =>  sprintf( 
							esc_html__('Error uploading file. The file type (.%s) is not allowed', 'sharedrive'), 
							$file_extension 
						)
			));
		
		echo $response;

		Sharedrive\Helpers::stop();
	}

	// Create post object
	$new_file = array(
		'post_title'    => wp_strip_all_tags( $name ),
		'post_status'   => 'publish',
		'post_type'     => 'file',
		'post_author'   => absint( $author ),
		'tax_input' => array(
				'directory' => absint( $taxonomy_id )
			),
		'meta_input' => array(
				'sharedrive_file_name' => $destination_file_name,
				'sharedrive_file_type' => $type,
				'sharedrive_file_size' => $size,
				'sharedrive_file_privacy' => 'private',
				'sharedrive_file_owner' => absint( get_current_user_id() ),
			)
	);

	// Insert the post into the database
	$file_id = wp_insert_post( $new_file );

	if ( !empty( $file_id ) && $file_id > 0 ) {
		$file = new Sharedrive\File( $file_id, $_FILES );
		$file->processHttpUpload( 'new', esc_html__('File added. Upload Successful', 'sharedrive') );
	}

}

Sharedrive\Helpers::stop();
