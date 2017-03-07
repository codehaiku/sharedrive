<?php
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

/**
 * Callback function for 'sharedrive_is_public' setting
 *
 * @return void
 */
function sharedrive_is_public_form() {

	echo '<label for="sharedrive_is_public"><input ' . checked( 1, get_option( 'sharedrive_is_public' ), false ) . ' value="1" name="sharedrive_is_public" id="sharedrive_is_public" type="checkbox" class="code" /> Check to make all of your posts and pages visible to public.</label>';
	echo '<p class="description">' . esc_html__( 'This option will overwrite the \'Private Login Page\' below. BuddyPress pages like user profile, members, and groups are still only available to the rightful owner of the profile.', 'sharedrive' ) . '</p>';

	return;
}
