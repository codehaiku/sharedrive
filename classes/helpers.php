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
 * @category Sharedrive\Helpers
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
 * @category Sharedrive\Helpers
 * @package  Sharedrive
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     github.com/codehaiku/sharedrive The Plugin Repository
 * @since    1.0  
 */
final class Helpers
{
	public static function getPost() {
		global $post;

		if ( ! empty ( $post ) ) {
			return $post;
		}
		
		return false;
	}

	public static function getPostID() {

		global $post;
		
		if ( ! empty( $post ) ) {
			return $post->ID;
		}

		return 0;
	}
}
