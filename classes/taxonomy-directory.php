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
 * @category Sharedrive\TaxonomyDirectory
 * @package  Sharedrive
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  GIT:github.com/codehaiku/sharedrive
 * @link     github.com/codehaiku/sharedrive The Plugin Repository
 */

namespace Sharedrive;

use WP_Query;

if (! defined('ABSPATH') ) {
    return;
}

/**
 * Sharedrive Option Methods.
 *
 * @category Sharedrive\TaxonomyDirectory
 * @package  Sharedrive
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     github.com/codehaiku/sharedrive The Plugin Repository
 * @since    1.0  
 */
final class TaxonomyDirectory
{
	public function __construct() {
			
		add_action('init', array( $this, 'registerTaxonomy' ));
		
        add_action( 'wp', array( $this, 'taxonomyStyleSheet' )); 

		add_action( 'directory_edit_form_fields', array( $this, 'directoryEditFileField' ), 10, 2 );

        add_action( 'edited_directory', array( $this, 'save_taxonomy_custom_meta' ), 10, 2 );  
		
		add_action( 'create_directory', array( $this, 'save_taxonomy_custom_meta' ), 10, 2 );

		add_action( 'wp_ajax_file_bulk_upload_action', array( $this, 'fileBulkUploadAction' )); 

		add_action( 'wp_ajax_sd_create_directory', array( $this, 'createDirectory' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

        register_activation_hook( __FILE__, 'flushOnActivate' );

        return;

	}

	public function taxonomyStyleSheet() {
		if ( is_tax('directory') ) {
            wp_enqueue_style( 'sharedrive', SHAREDRIVE_DIR_URL . 'assets/css/sharedrive-archive.css', false );
            wp_enqueue_style( 'font-awesome', SHAREDRIVE_DIR_URL . 'assets/css/font-awesome.min.css', false );
        }
	}

	public function createDirectory() {

		require_once SHAREDRIVE_DIR_PATH . 'transactions/directory-new.php';

		return;

	}

	public function fileBulkUploadAction() {
		
		require_once SHAREDRIVE_DIR_PATH . 'transactions/file-bulk-upload.php';

		return;

	}

	public function enqueue() {

		wp_enqueue_script( 
			'sharedrive-taxonomy-file-js', 
			SHAREDRIVE_DIR_URL . 'assets/js/taxonomy-file.js', 
			array( 'jquery', 'plupload-all' ) 
		);
		
		return;
	}

	public function registerTaxonomy() {

		$labels = array(
			'name'                       => esc_html__( 'Directories', 'sharedrive' ),
			'singular_name'              => esc_html__( 'Directory', 'sharedrive' ),
			'search_items'               => esc_html__( 'Search Directories', 'sharedrive' ),
			'popular_items'              => esc_html__( 'Popular Directories', 'sharedrive' ),
			'all_items'                  => esc_html__( 'All Directories', 'sharedrive' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Directory', 'sharedrive' ),
			'update_item'                => esc_html__( 'Update Directory', 'sharedrive' ),
			'add_new_item'               => esc_html__( 'Add New Directory', 'sharedrive' ),
			'new_item_name'              => esc_html__( 'New Directory Name', 'sharedrive' ),
			'separate_items_with_commas' => esc_html__( 'Separate writers with commas', 'sharedrive' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove writers', 'sharedrive' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used writers', 'sharedrive' ),
			'not_found'                  => esc_html__( 'No writers found.', 'sharedrive' ),
			'menu_name'                  => esc_html__( 'Directories', 'sharedrive' ),
		);

		$args = array(
			'hierarchical'          => true,
			'labels'                => $labels,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'directory' ),
		);

		register_taxonomy( 'directory', 'file', $args );
	}

	public function flushOnActivate() {
        // Flush WordPress Rewrite Rules
        flush_rewrite_rules();

        return;
    }

	public function directoryEditFileField( $instance, $taxonomy ) {
	
	// put the term ID into a variable
	$t_id = $instance->term_id;
 
	// retrieve the existing value(s) for this meta field. This returns an array
	$term_meta = get_option( "taxonomy_$t_id" ); ?>
	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="term_meta[custom_term_meta]">
				<?php esc_html_e( 'Add Files', 'sharedrive' ); ?>
			</label>
		</th>
		<td>
			<style>
				#sd-attached-file {
					background: #f1f1f1;
					padding: 5px 10px;
				}
				#filelist .sd-success{ color: #558B2F; }
				#filelist .sd-error{ color: #f44336; }
				#sharedrive-file-window p {
					margin-top: 0;
					margin-bottom: 15px;
				}
			</style>
			<ul id="filelist" style="margin-top: 0; margin-bottom: 0;"></ul>
			<div id="sharedrive-file-window">
				<p>
					<?php 
						printf( 
							esc_html__(
								'There are %s files attached in this directory.', 
								'sharedrive'
							), 
							'<strong id="taxonomy-sd-count-file">' . absint( $instance->count ) . '</strong>'
						); 
					?>
					<?php $directory_link = get_term_link( absint( $instance->term_id ), $instance->taxonomy); ?>
					<a target="__blank" href="<?php echo esc_url( $directory_link ); ?>" title="<?php echo esc_attr( $instance->name ); ?>">
						<?php esc_html_e('View Files &rarr;', 'sharedrive'); ?> 
					</a>
				</p>
				<hr/>
				<p>
					<?php 
						printf( 
							esc_html__(
								'File size limit %s per file.', 
								'sharedrive'
							), 
							'<strong>' . Helpers::formatSize( wp_max_upload_size() ) . '</strong>'
							); 
					?>
				</p>
				<p>
			    	<a id="sharedrive-browse-files" class="button button-secondary" href="javascript:;">
			    		<?php echo esc_html_e('Browse File', 'sharedrive'); ?>
			    	</a>
			    	<a id="sharedrive-start-upload" class="button button-disabled button-secondary" href="javascript:;">
			    		<?php echo esc_html_e('Start Upload', 'sharedrive'); ?>
			    	</a>
		    	</p>
		    	
		    	<p class="description">
		    		<?php esc_html_e("Click 'Browse File' to get started. You can select multiple files.", 'sharedrive'); ?>
		    	</p>
			</div>
		</td>
	</tr>	
	<tr class="form-field">
		<th scope="row" valign="top">
			<label for="term_meta[custom_term_meta]">
				<?php esc_html_e( 'Privacy', 'sharedrive' ); ?>
			</label>
		</th>
		<td>
		<?php
			$privacy = new Privacy();

			$default_privacy = 'private';
			$term_id = filter_input( INPUT_GET, 'tag_ID', FILTER_SANITIZE_NUMBER_INT );
			$saved_privacy = get_term_meta( $term_id, 'sharedrive_directory_privacy', true );

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
			<?php $meta_directory_users = (array)get_term_meta( $term_id, 'sharedrive_directory_privacy_users', true ); ?>
			<?php $privacy_users = implode(',', $meta_directory_users ); ?>
			<input placeholder="Start by typing the name of the user..." class="widefat" type="text" name="sd-file-privacy-users"  id="sd-file-privacy-users" value="<?php echo $privacy_users ?>" />
				<span class="description">
				<?php esc_html_e('Use the text field above to share this file to specific users.', 'sharedrive'); ?>
			</span>
		</p>
		</td>
	</tr>
	<?php
	}

	public function save_taxonomy_custom_meta( $term_id ) {

		if ( isset( $_POST['sd-file-privacy'] ) ) {
			update_term_meta( $term_id, 'sharedrive_directory_privacy', $_POST['sd-file-privacy'] );
		} else {
			update_term_meta( $term_id, 'sharedrive_directory_privacy', 'private' );
		}

		$directory_users = explode( ',', $_POST['sd-file-privacy-users'] );
		
		if ( isset( $_POST['sd-file-privacy-users'] ) ) {
			update_term_meta( $term_id, 'sharedrive_directory_privacy_users', $directory_users );
		} else {
			update_term_meta( $term_id, 'sharedrive_directory_privacy_users', '' );
		}

		return;

	}
}

new TaxonomyDirectory();
