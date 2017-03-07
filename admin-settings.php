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

        return;
    }

    /**
     * Registers all settings related to Sharedrive.
     *
     * @return void
     */
    public function registerSettings() 
    {

    }

    /**
     * Callback function for the first Section.
     *
     * @return void
     */
    public function sectionCallback() 
    {
        echo esc_html_e(
            'All settings related to the 
        	visibility of your site and pages.', 'sharedrive'
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

}

$sharedriveSettings = new AdminSettings();
