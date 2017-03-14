<?php
/**
 * Plugin Name: Sharedrive
 * Description: Sharedrive allows you to upload file and share it to any of your site members.
 * Version: 1.0.0
 * Author: Dunhakdis
 * Author URI: http://dunhakdis.com
 * Text Domain: sharedrive
 * License: GPL2
 *
 * Includes all the file necessary for Sharedrive.
 *
 * PHP version 5.4+
 *
 * @category Sharedrive\Bootstrap
 * @package  Sharedrive
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  GIT:github.com/codehaiku/sharedrive
 * @link     github.com/codehaiku/sharedrive The Plugin Repository
 */

/**
 * This file is part of the Sharedrive WordPress Plugin Package.
 *
 * (c) Joseph Gabito <joseph@useissuestabinstead.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package Sharedrive
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

// Terminate Sharedrive for PHP version 5.3.0 and below.
if ( version_compare( PHP_VERSION, '5.3.0', '<' ) ) {
	add_action( 'admin_notices', 'sharedrive_admin_notice' );
	/**
	 * Displays admin notifications if the installed PHP version is less than 5.3.0
	 *
	 * @return void
	 */
	function sharedrive_admin_notice() {
	?>
		<div class="notice notice-error is-dismissible">
	        <p>
	        	<strong>
	        		<?php esc_html_e( 'Notice: Sharedrive uses PHP Class Namespaces 
	        		which is only available in servers with PHP 5.3.0 version and above. 
	        		Update your server\'s PHP version. You can deactivate 
	        		Sharedrive in the meantime.', 'sharedrive' ); ?>
	        	</strong>
	        </p>
	    </div>
	<?php }
	return;
}

// Define Sharedrive Plugin Version.
define( 'SHAREDRIVE_VERSION', '1.0' );

// Define Sharedrive Directory Path.
define( 'SHAREDRIVE_DIR_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );

// Define Sharedrive URL Path.
define( 'SHAREDRIVE_DIR_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );

// Include Sharedrive i18n.
require_once SHAREDRIVE_DIR_PATH . 'i18.php';

// Include Sharedrive Settings Class.
require_once SHAREDRIVE_DIR_PATH . 'admin-settings.php';

// Include Static Helpers.
require_once SHAREDRIVE_DIR_PATH . 'classes/helpers.php';

// Include Sharedrive Shortcodes.
require_once SHAREDRIVE_DIR_PATH . 'classes/file.php';

// Include Sharedrive Privacy
require_once SHAREDRIVE_DIR_PATH . 'classes/privacy.php';

// Include Post Type Registration Class.
require_once SHAREDRIVE_DIR_PATH . 'classes/post-type-file.php';

// Include Taxonomy Registration Class.
require_once SHAREDRIVE_DIR_PATH . 'classes/taxonomy-directory.php';

// Include File Metabox.
require_once SHAREDRIVE_DIR_PATH . 'classes/meta-box-file.php';

// Include The Download Script
require_once SHAREDRIVE_DIR_PATH . 'classes/download.php';

// Include Sharedrive Single File.
require_once SHAREDRIVE_DIR_PATH . 'classes/single-file.php';

// Include Breadcrumb
require_once SHAREDRIVE_DIR_PATH . 'classes/breadcrumb.php';

// Include Sharedrive Shortcodes.
require_once SHAREDRIVE_DIR_PATH . 'shortcodes/shortcodes.php';


