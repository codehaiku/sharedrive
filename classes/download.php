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
 * @category Sharedrive\Download
 * @package  Sharedrive
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  GIT:github.com/codehaiku/sharedrive
 * @link     github.com/codehaiku/sharedrive The Plugin Repository
 */

namespace Sharedrive;

if (! defined('ABSPATH') ) {
    return;
}

/**
 * Sharedrive Option Methods.
 *
 * @category Sharedrive\Download
 * @package  Sharedrive
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     github.com/codehaiku/sharedrive The Plugin Repository
 * @since    1.0  
 */
final class Download
{
	public function __construct() {

		add_action( 'init', array($this, 'sendToClient' ) );

	}

	public function sendToClient() {
		
		$post_id = filter_input( INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT );

		if ( empty ( $post_id ) ) {
			return;
		}

		if ( isset( $_GET['sd_download'] ) ) {

			do_action('sharedrive_before_download');

			$file = Download::getAttachmentPath( $post_id );

			$download_count = absint( get_post_meta( $post_id, 'sharedrive_file_download', true ) );
			
			if ( file_exists( $file ) ) {

			   	$this->setDownloadableHeader( $file, $post_id );
			    
			    if ( readfile( $file ) ) {
			    	// update the download count if the file is successfully served.
			    	$download_count += 1;
			    	update_post_meta( $post_id, 'sharedrive_file_download', $download_count );
			    }

				do_action('sharedrive_after_download');

			    exit;
			}
		}

		return;

	}

	protected function setDownloadableHeader( $file, $post_id ) {

		if ( empty ( $file ) ) {
			return;
		}

		$the_file = get_post( $post_id );
		$content_type = get_post_meta( $the_file->ID, 'sharedrive_file_type', true );
		
		if ( empty ( $content_type ) ) {
			$content_type = 'application/octet-stream';
		}
		
		header('Content-Description: File Transfer');
	    header('Content-Type: ' . $content_type);
	    header('Content-Disposition: attachment; filename="' . basename( $file ) . '"');
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate');
	    header('Pragma: public');
	    header('Content-Length: ' . filesize($file));

	    return;
	}

	public static function getDownloadUri( $post_id = 0 ) {
		
		$download_uri = add_query_arg( array(
		    'sd_download' => uniqid(),
		    'id' => absint($post_id),
		), get_site_url( null, 'index.php', null ) );

		return $download_uri;
	}

	public static function getAttachmentPath( $post_id = 0 ) {

		if ( empty( $post_id ) ) {
			return;
		}
		
		$the_post = get_post( $post_id );
		$post_author_id = $the_post->post_author;
		$file_name = get_post_meta($post_id, 'sharedrive_file_name', true );
		$upload_dir = wp_upload_dir();

		$file_url = $upload_dir['basedir'] . '/sharedrive/'.absint($post_id).'/'.absint($post_author_id).'/'.$file_name . ".zip";

		return $file_url;
	}
}

NEW Download();