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
	        sprintf( __( 'File Update (Max %s)', 'sharedrive' ), Helpers::formatSize( wp_max_upload_size() ) ),
	        array( $this, 'metaBoxForm' ),
	        'file', 'normal', 'high'
	    );

	    add_meta_box( 
	        'sharedrive-file-metabox-privacy',
	        sprintf( __( 'File Privacy', 'sharedrive' ), Helpers::formatSize( wp_max_upload_size() ) ),
	        array( $this, 'metaBoxPrivacy' ),
	        'file', 'normal', 'high'
	    );
	    
	}

	public function metaBoxPrivacy() {

		$privacy = new Privacy();

		$default_privacy = 'private';
		$saved_privacy = get_post_meta( Helpers::getPostID(), 'sharedrive_file_privacy', true );

		if ( ! empty ( $saved_privacy ) ) {
			$default_privacy = $saved_privacy;
		}

		$privacies = $privacy->getCollection( $default_privacy );
		?>
		
		<?php if ( ! empty( $privacies ) ) { ?>
		<p>
			<select id="sharedrive-privacy-field" name="sd-file-privacy">
				<?php foreach( $privacies as $privacy ) { ?>
					<?php $selected = ''; ?>
					<?php if ( $privacy['is_default'] ) { ?>
						<?php $selected = 'selected'; ?>
					<?php } ?>
					<option <?php echo $selected; ?> value="<?php echo $privacy['value'];?>">
						<?php echo $privacy['label']; ?>
					</option>
				<?php } ?>
			</select>
		</p>
		<?php } ?>
		
		<p>
			<label for="sd-file-privacy-users">
				<strong><?php esc_html_e('Select Members', 'sharedrive'); ?></strong>
			</label>
			<?php $meta_file_users = (array)get_post_meta( Helpers::getPostID(), 'sharedrive_file_privacy_users', true ); ?>
			<?php $privacy_users = implode(',', $meta_file_users ); ?>
			<input placeholder="Start by typing the name of the user..." class="widefat" type="text" name="sd-file-privacy-users"  id="sd-file-privacy-users" value="<?php echo $privacy_users ?>" />
				<span class="description">
				<?php esc_html_e('Use the text field above to share this file to specific users.', 'sharedrive'); ?>
			</span>
		</p>
		
		<?php
	}

	public function metaBoxFileDetails () {
		
		$post_id = Helpers::getPostID();
		$sharedrive_file_name = get_post_meta( $post_id, 'sharedrive_file_name', true );
		$sharedrive_file_type = get_post_meta( $post_id, 'sharedrive_file_type', true );
		$sharedrive_file_size = Helpers::formatSize( get_post_meta( $post_id, 'sharedrive_file_size', true ) );

		if ( empty( $sharedrive_file_name ) ) {
			esc_html_e('No physical file found. Use the \'File Update\' metabox to upload new file.', 'sharedrive');
			return;
		}

		?>
		<table class="wp-list-table widefat fixed striped">
		
			<?php if ( ! empty( $sharedrive_file_name ) ) { ?>
				<tr>
					<th><strong><?php echo esc_html_e('Name', 'sharedrive'); ?></strong></th>
					<td><?php echo esc_html( $sharedrive_file_name ); ?></td>
				</tr>
			<?php } ?>
			<tr>
				<th><strong>Sharing Option</strong></th>
				<td><?php echo ucwords( str_replace( '_', ' ', get_post_meta( $post_id, 'sharedrive_file_privacy', true ) ) ); ?></td>
			</tr>

			<?php if ( ! empty( $sharedrive_file_type ) ) { ?>
				<tr>
					<th><strong><?php echo esc_html_e('Type', 'sharedrive'); ?></strong></th>
					<td><?php echo esc_html( $sharedrive_file_type ); ?></td>
				</tr>
			<?php } ?>
			<?php if ( ! empty( $sharedrive_file_size ) ) { ?>
				<tr>
					<th><strong><?php echo esc_html_e('Size', 'sharedrive'); ?></strong></th>
					<td><?php echo esc_html( $sharedrive_file_size ); ?></td>
				</tr>
			<?php } ?>
			<tr>
				<th><strong>No. Downloads</strong></th>
				<td><?php echo absint( get_post_meta( $post_id, 'sharedrive_file_download', true ) ); ?></td>
			</tr>

			<tr>
				<th><strong>Owner</strong></th>
				<td><?php echo absint( get_post_meta( $post_id, 'sharedrive_file_owner', true ) ); ?></td>
			</tr>
			
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

		if ( isset( $_POST['sd-file-privacy'] ) ) {
			update_post_meta( $post_id, 'sharedrive_file_privacy', $_POST['sd-file-privacy'] );
		}

		if ( isset( $_POST['sd-file-privacy-users'] ) ) {
			$file_users = explode( ',', $_POST['sd-file-privacy-users'] );
			update_post_meta( $post_id, 'sharedrive_file_privacy_users', array_filter( array_map( 'trim', $file_users ), 'is_numeric' ) );
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
