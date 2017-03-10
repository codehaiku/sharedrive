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

$max_upload_size = wp_max_upload_size();
$tmp_file_size = 0;
$pid = filter_input( INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT );

$file = new Sharedrive\File( $pid, $_FILES );

if ( isset( $_FILES['file']['size'] ) ) {
	$tmp_file_size = $_FILES['file']['size'];
}
// Basic Validations.

// Only logged-in users are allowed to upload file.
if ( ! is_user_logged_in() ) {
	wp_die( __('Are you sure you want to do this?', 'sharedrive') );
}

// File size should not be 0. File size is usually empty when uploaded file > max_upload_size.
if ( 0 === $tmp_file_size  ) {
	$response = wp_json_encode(array(
			'status' => 201,
			'message' => sprintf( 
				__('Max upload size issue. Uploaded files should be less than %s', 'sharedrive'), 
				Sharedrive\Helpers::formatSize( $max_upload_size ) 
				)
		));
	echo $response;
	Sharedrive\Helpers::stop();
}

$file->processHttpUpload( 'update', esc_html___('File has been successfully uploaded. Please save/update the post', 'sharedrive') );

Sharedrive\Helpers::stop();
