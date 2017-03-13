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
		
		//add_action( 'directory_add_form_fields', array( $this, 'directoryAddFileField' ), 10, 2 );
		add_action( 'directory_edit_form_fields', array( $this, 'directoryEditFileField' ), 10, 2 );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

		add_action( 'wp_ajax_file_bulk_upload_action', array( $this, 'fileBulkUploadAction' )); 

        register_activation_hook( __FILE__, 'flushOnActivate' );

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

    // Add term page
	public function directoryAddFileField() {
	// this will add the custom meta field to the add new term page
	?>
		<div class="form-field">
			<label for="term_meta[custom_term_meta]"><?php _e( 'Example meta field', 'sharedrive' ); ?></label>
			<input type="text" name="term_meta[custom_term_meta]" id="term_meta[custom_term_meta]" value="">
			<p class="description"><?php _e( 'Enter a value for this field','sharedrive' ); ?></p>
		</div>
	<?php
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
	<?php
	}
}

new TaxonomyDirectory();
