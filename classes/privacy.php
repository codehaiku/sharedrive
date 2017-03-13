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
 * @category Sharedrive\Privacy
 * @package  Sharedrive
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  GIT:github.com/codehaiku/sharedrive
 * @link     github.com/codehaiku/sharedrive The Plugin Repository
 */

namespace Sharedrive;

if ( !defined('ABSPATH') ) {
    return;
}

class Privacy extends File {

	public function __construct() {
		return $this;
	}

	public function getCollection( $default = 'private' ) {

		$collection = array(
			array(
				'label' => esc_html__('Private', 'sharedrive'),
				'value' => 'private',
				'is_default' => false
			),
			array(
				'label' => esc_html__('Public', 'sharedrive'),
				'value' => 'public',
				'is_default' => false
			),
			array(
				'label' => esc_html__('Selected Users', 'sharedrive'),
				'value' => 'selected_users',
				'is_default' => false
			)
		);

		foreach( $collection as $key => $privacy ) {
			if ( $default === $privacy['value'] ) {
				$collection[$key]['is_default'] = true;
			}
		}
	
		return apply_filters('sharedrive_privacy_collection', $collection );
	}
}
