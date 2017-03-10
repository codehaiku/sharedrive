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
 * @category Sharedrive\Admin\Settings
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
 * Registers all the admin settings inside Settings > Sharedrive
 *
 * @category Sharedrive\Admin\Settings
 * @package  Sharedrive
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     github.com/codehaiku/sharedrive The Plugin Repository
 * @since    1.0  
 */
final class AdminSettings
{

    /**
     * Our class constructor
     */
    public function __construct() 
    {
        
        add_action('admin_menu', array( $this, 'adminMenu' ));
        add_action('admin_head', array( $this, 'adminJsConfig' ) );

    }
    
    /**
     * Display 'Sharedrive' link under 'Settings'
     *
     * @return void
     */
    public function adminMenu() 
    {

        add_options_page(
            'Sharedrive Settings', 'Sharedrive', 'manage_options', 
            'sharedrive', array( $this, 'optionsPage' )
        );

        add_action('admin_init', array( $this, 'registerSettings' ));

        return;
    }

    /**
     * Registers all settings related to Sharedrive.
     *
     * @return void
     */
    public function registerSettings() 
    {

        // Register our settings section.
        add_settings_section(
            'sharedrive-general-group', __('General Settings', 'sharedrive'), 
            array( $this, 'sectionCallback' ), 'sharedrive-settings-section' 
        );

        $fields = array(
            array(
                'id' => "sd_allowed_file_types",
                'label' => __('Allowed File Types', 'sharedrive'),
                'callback' => 'sharedrive_settings_allowed_file_type',
                'section' => 'sharedrive-settings-section',
                'group' => 'sharedrive-general-group',
            ),
        );

        foreach ( $fields as $field ) {
                
            add_settings_field(
                $field['id'], $field['label'], 
                $field['callback'], $field['section'], 
                $field['group']
            );
            
            register_setting('sharedrive-settings-group', $field['id']);

            $file = str_replace('_', '-', $field['callback']);
            include_once trailingslashit(SHAREDRIVE_DIR_PATH) . 
            'settings-fields/field-' . sanitize_title($file) . '.php';
        }


    }

    /**
     * Callback function for the first Section.
     *
     * @return void
     */
    public function sectionCallback() 
    {
        esc_html_e(
            'All settings related to ShareDrive.', 'sharedrive'
        );
        return;
    }

    /**
     * Callback function for the second Section.
     *
     * @return void
     */
    public function redirectCallback() 
    {
        return;
    }

    /**
     * Renders the 'wrapper' for our options pages.
     *
     * @return void
     */
    public function optionsPage() 
    {
        ?>

        <div class="wrap">
            <h2>
                <?php esc_html_e('Sharedrive Settings', 'sharedrive'); ?>
             </h2>
             <form id="sharedrive-settings-form" action="options.php" method="POST">
                <?php settings_fields('sharedrive-settings-group'); ?>
                <?php do_settings_sections('sharedrive-settings-section'); ?>
                <?php submit_button(); ?>
             </form>
        </div>
        
        <?php
    }

    public function adminJsConfig() {
        File::initMimeTypes();
        $allowed_mime_types = preg_replace( '/\s+/', '', str_replace( ' ', '', implode( ',', File::getAllowedFileTypes() ) ) );
        $banned_mime_types = File::getBannedTypes();
        ?>
        <script>
            var sharedrive = {
                settings: {
                    mime_types_allowed: [
                        { title : "Files Allowed", extensions : "<?php echo trim( $allowed_mime_types ); ?>" }
                    ],
                    mime_types_banned: {
                        types: ".<?php echo implode(',.', $banned_mime_types) ; ?>",
                        regex: /(\.bat|\.cmd|\.php)$/i
                    },
                    max_file_size: <?php echo wp_max_upload_size(); ?>
                }
            }
        </script>
        <?php /* ;*/
    }

}

$sharedriveSettings = new AdminSettings();

