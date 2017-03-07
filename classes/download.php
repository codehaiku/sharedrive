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

		if ( isset( $_GET['sd_download'] ) ) {

			do_action('sharedrive_before_download');

			$file = Download::getAttachmentPath( $post_id );

			if ( file_exists( $file ) ) {

			   	$this->setDownloadableHeader( $file );
			    
			    readfile( $file );

				do_action('sharedrive_after_download');

			    exit;
			}
		}
	}

	protected function setDownloadableHeader( $file ) {

		if ( empty ( $file ) ) {
			return;
		}

		header('Content-Description: File Transfer');
	    header('Content-Type: application/octet-stream');
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

		$file_url = $upload_dir['basedir'] . '/sharedrive/'.absint($post_id).'/'.absint($post_author_id).'/'.$file_name;

		return $file_url;
	}
}

NEW Download();