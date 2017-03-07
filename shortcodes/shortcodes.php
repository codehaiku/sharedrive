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
 * @category Sharedrive\Shortcodes
 * @package  Sharedrive\Shortcodes
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
 * Registers Plugin Shortcodes
 *
 * @category Sharedrive\Shortcodes
 * @package  Sharedrive
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     github.com/codehaiku/sharedrive The Plugin Repository
 * @since    1.0  
 */
final class Shortcodes
{

    /**
     * Class Constructor.
     *
     * @return void
     */
    private function __construct() 
    {
        
        return $this;

    }

    /**
     * Instantiate our class.
     * 
     * @return mixed The instance of this class.
     */
    public static function instance() 
    {
        
        static $instance = null;

        if (null === $instance ) {

            $instance = new Shortcodes();

        }

        return $instance;

    }


}

$sharedrive_shortcode = Shortcodes::instance();
