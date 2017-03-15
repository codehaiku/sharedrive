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
 * @category Sharedrive\Page\Redirect
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
 * @category Sharedrive\Page\Redirect
 * @package  Sharedrive
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     github.com/codehaiku/sharedrive The Plugin Repository
 * @since    1.0  
 */
final class FilePostType
{

    public function __construct() {
       
        add_action( 'init', array( $this, 'index' ) );
        add_action( 'init', array( $this, 'setDirectoryCookies' ) );
        add_action( 'delete_post', array( 'Sharedrive\File', 'delete' ), 10 );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
        add_action( 'save_post', array( $this, 'setDefaultTerm') , 100, 2 );

        register_activation_hook( __FILE__, 'flushOnActivate' );

    }

    public function setDirectoryCookies() {
       
       $show = filter_input( INPUT_GET, 'show', FILTER_SANITIZE_STRING );

        if ( ! empty( $show ) ) {
            // First delete the cookie
            setcookie( 'sharedrive_listing_view', $show, time() - 3600, '/' );
            // then add value
            setcookie( 'sharedrive_listing_view', $show, strtotime('+2 day'), '/' );
        }

        return;
    }

    public function setDefaultTerm() {
        $file = Helpers::getPost();
        $post_id = filter_input( INPUT_POST, 'post_ID', FILTER_SANITIZE_NUMBER_INT );
        if ( ! empty( $post_id ) ) {
            if ( $file->post_type === 'file' ) {
                $defaults = array(
                    'directory' => array( 'root' )
                );
                $taxonomies = get_object_taxonomies( $file->post_type );

                foreach ( (array) $taxonomies as $taxonomy ) {
                    $terms = wp_get_post_terms( $post_id, $taxonomy );
                    if ( empty( $terms ) && array_key_exists( $taxonomy, $defaults ) ) {
                        wp_set_object_terms( $post_id, $defaults[$taxonomy], $taxonomy );
                    }
                }
            }
        }
    }

    public function enqueue() {
        if ( is_post_type_archive('file') ) {
            wp_enqueue_style( 'sharedrive', SHAREDRIVE_DIR_URL . 'assets/css/sharedrive-archive.css', false );
            wp_enqueue_style( 'font-awesome', SHAREDRIVE_DIR_URL . 'assets/css/font-awesome.min.css', false );
        }
    }
    /**
     * Redirects pages into our login page.
     *
     * @return void.
     */
    public static function index() 
    {
        $labels = array(
            'name'               => esc_html__( 'All Files', 'sharedrive' ),
            'singular_name'      => esc_html__( 'File', 'sharedrive' ),
            'menu_name'          => esc_html__( 'Files', 'sharedrive' ),
            'name_admin_bar'     => esc_html__( 'File', 'sharedrive' ),
            'add_new'            => esc_html__( 'Add New', 'sharedrive' ),
            'add_new_item'       => esc_html__( 'Add New File', 'sharedrive' ),
            'new_item'           => esc_html__( 'New File', 'sharedrive' ),
            'edit_item'          => esc_html__( 'Edit File', 'sharedrive' ),
            'view_item'          => esc_html__( 'View File', 'sharedrive' ),
            'all_items'          => esc_html__( 'All Files', 'sharedrive' ),
            'search_items'       => esc_html__( 'Search Files', 'sharedrive' ),
            'parent_item_colon'  => esc_html__( 'Parent Files:', 'sharedrive' ),
            'not_found'          => esc_html__( 'No files found.', 'sharedrive' ),
            'not_found_in_trash' => esc_html__( 'No files found in Trash.', 'sharedrive' )
        );

        $args = array(
            'labels'             => $labels,
            'description'        => esc_html__( 'Description.', 'sharedrive' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'files' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title','comments' ),
            'menu_icon'          => 'dashicons-media-archive',
        );

        register_post_type( 'file', $args );
       
    }

    public function flushOnActivate() {
        // Flush WordPress Rewrite Rules
        flush_rewrite_rules();
    }

}

new FilePostType();

