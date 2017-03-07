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
        register_activation_hook( __FILE__, 'flushOnActivate' );
	}

	public function registerTaxonomy() {
		$labels = array(
			'name'                       => _x( 'Directories', 'taxonomy general name', 'textdomain' ),
			'singular_name'              => _x( 'Directory', 'taxonomy singular name', 'textdomain' ),
			'search_items'               => __( 'Search Directories', 'textdomain' ),
			'popular_items'              => __( 'Popular Directories', 'textdomain' ),
			'all_items'                  => __( 'All Directories', 'textdomain' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Directory', 'textdomain' ),
			'update_item'                => __( 'Update Directory', 'textdomain' ),
			'add_new_item'               => __( 'Add New Directory', 'textdomain' ),
			'new_item_name'              => __( 'New Directory Name', 'textdomain' ),
			'separate_items_with_commas' => __( 'Separate writers with commas', 'textdomain' ),
			'add_or_remove_items'        => __( 'Add or remove writers', 'textdomain' ),
			'choose_from_most_used'      => __( 'Choose from the most used writers', 'textdomain' ),
			'not_found'                  => __( 'No writers found.', 'textdomain' ),
			'menu_name'                  => __( 'Directories', 'textdomain' ),
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
}
new TaxonomyDirectory();