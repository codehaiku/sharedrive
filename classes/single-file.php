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
 * @category Sharedrive\SingleFile
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
 * @category Sharedrive\SingleFile
 * @package  Sharedrive
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     github.com/codehaiku/sharedrive The Plugin Repository
 * @since    1.0  
 */
final class SingleFile {
	
	public function __construct() {
		add_filter('the_content', array( $this, 'renderFileWindow' ));
		add_action('wp_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	public function enqueue() {
		wp_enqueue_style( 'sharedrive-css', 
			SHAREDRIVE_DIR_URL . 'assets/css/sharedrive.css', 
			array(),
			SHAREDRIVE_VERSION,
			'all');
	}

	public function renderFileWindow( $content ) {
		if ( is_singular('file') && is_main_query() ) {
			ob_start();
			require_once SHAREDRIVE_DIR_PATH . 'templates/single-file.php';
			$file_window = ob_get_clean();
			$content .= $file_window;
			return $content;
		}
		return $content;
	}
}

new SingleFile();