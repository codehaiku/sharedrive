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
 * @category Sharedrive\i18
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
 * Register Plugin i18 (internationalization)
 *
 * @category Sharedrive\i18
 * @package  Sharedrive
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     github.com/codehaiku/sharedrive The Plugin Repository
 * @since    1.0  
 */
final class I18
{

    /**
     * Class Constructor.
     *
     * @return void
     */
    public function __construct() 
    {

        add_action('plugins_loaded', array( $this, 'sharedriveLocalizePlugin' ));

        return;
    }

    /**
     * Sharedrive l8n callback.
     *
     * @return void
     */
    public function sharedriveLocalizePlugin() 
    {

        $rel_path = SHAREDRIVE_DIR_PATH . 'languages';

        load_plugin_textdomain('sharedrive', false, $rel_path);

        return;
    }

}

$sharedrivei18 = new I18();
