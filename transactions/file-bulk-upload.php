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

if ( ! defined('ABSPATH') ) {
    return;
}

$pid = filter_input( INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT );
$taxonomy_id = filter_input( INPUT_POST, 'taxonomy_id', FILTER_VALIDATE_INT );



if ( empty( $taxonomy_id ) ) {
	return;
}

if ( isset( $_FILES['file'] ) ) {

	$name  = $_FILES['file']['name'];
	$type  = $_FILES['file']['type'];
	$size  = $_FILES['file']['size'];
	$error = $_FILES['file']['error'];
	$tmp_name = $_FILES['file']['size'];
	$author = get_current_user_id();

	$file_name = pathinfo( $name, PATHINFO_FILENAME );
	$file_extension = pathinfo( $name, PATHINFO_EXTENSION );
	$destination_file_name = sanitize_title( $file_name ) . '.' . $file_extension;

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
			)
	);
	 
	// Insert the post into the database
	$file_id = wp_insert_post( $new_file );

	if ( !empty( $file_id ) && $file_id > 0 ) {
		$file = new Sharedrive\File( $file_id, $_FILES );
		$file->upload( 'new' )->processHttpUpload();
	}

}

Sharedrive\Helpers::stop();
