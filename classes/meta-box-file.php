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
 * @category Sharedrive\MetaBoxFile
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
 * @category Sharedrive\MetaBoxFile
 * @package  Sharedrive
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     github.com/codehaiku/sharedrive The Plugin Repository
 * @since    1.0  
 */
final class MetaBoxFile {

	public function __construct()
	{

		add_action( 'add_meta_boxes_file', array( $this, 'registerMetaBox' ) );
		add_action( 'save_post_file', array( $this, 'processMetaBoxOnSave' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
    	add_action( 'wp_ajax_file_upload_action', array( $this, 'fileUploadAction' )); 

		return;
	}

	public function registerMetaBox()
	{
		add_meta_box( 
	        'sharedrive-file-details',
	        __( 'File Summary', 'sharedrive' ),
	        array( $this, 'metaBoxFileDetails' ),
	        'file', 'normal', 'high'
	    );

		add_meta_box( 
	        'sharedrive-file-metabox-control',
	        __( 'File Update', 'sharedrive' ),
	        array( $this, 'metaBoxForm' ),
	        'file', 'normal', 'high'
	    );
	    
	}

	public function metaBoxFileDetails () {
		?>
		<table class="wp-list-table widefat fixed striped">
			<tr><th>Name</th><td>screen-shot-2017-03-06-at-7-04-26-pm.png</td></tr>
			<tr><th>Type</th><td>PDF</td></tr>
			<tr><th>Size</th><td>1.36 MB</td></tr>
			<tr><th>No. Downloads</th><td>123</td></tr>
		</table>
		<?php
	}

	public function metaBoxForm() {
		
		wp_nonce_field( basename( __FILE__ ), 'smashing_post_class_nonce' ); 
		
		require_once SHAREDRIVE_DIR_PATH . 'templates/metabox-file-template.php';

		return;
	}

	public function processMetaBoxOnSave() {
		
		$post_id = Helpers::getPostID();
		/* Verify the nonce before proceeding. */
		/* Check if the current user has permission to edit the post. */
		/* Get the posted data and sanitize it for use as an HTML class. */
		/* If the new meta value does not match the old value, update it. */
		if ( isset( $_POST['sd-file-description'] ) ) {
			update_post_meta( $post_id, 'sd-file-description', $_POST['sd-file-description'] );
		}
		/* If there is no new meta value but an old value exists, delete it. */
		
	}

	public function enqueue( $hook ) {

		$__post = Helpers::getPost();
		
		if ( $hook == 'post-new.php' || $hook == 'post.php' ) { 
			if ( 'file' === $__post->post_type ) {
				wp_enqueue_script( 'sharedrive-metabox-file-js', 
					SHAREDRIVE_DIR_URL . 'assets/js/metabox-file.js', 
					array( 'jquery', 'plupload-all' ) );
			}
		}

		return;
	}
    
    public function fileUploadAction() {

    	require_once SHAREDRIVE_DIR_PATH . 'transactions/file-upload.php';
       
        return;
    }

}

new MetaBoxFile();
