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
 * @category Sharedrive\Templates
 * @package  Sharedrive
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  GIT:github.com/codehaiku/sharedrive
 * @link     github.com/codehaiku/sharedrive The Plugin Repository
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

$args = array(
	'echo'           => true,
	'form_id'        => 'loginform',
	'label_username' => __( 'Username', 'sharedrive' ),
	'label_password' => __( 'Password', 'sharedrive' ),
	'label_remember' => __( 'Remember Me', 'sharedrive' ),
	'label_log_in'   => __( 'Log In', 'sharedrive' ),
	'id_username'    => 'user_login',
	'id_password'    => 'user_pass',
	'id_remember'    => 'rememberme',
	'id_submit'      => 'wp-submit',
	'remember'       => true,
	'value_username' => '',
	'value_remember' => false,
	'redirect' 		 => home_url(),
);

$error_login_message = '';

$message_types = array();

$http_request_login = filter_input( INPUT_GET, 'login', FILTER_SANITIZE_SPECIAL_CHARS );

$http_request_type = filter_input( INPUT_GET, 'type', FILTER_SANITIZE_SPECIAL_CHARS );

$http_request_logout = filter_input( INPUT_GET, 'loggedout', FILTER_SANITIZE_SPECIAL_CHARS );

if ( isset( $http_request_login ) ) {

	if ( 'failed' === $http_request_login ) {

		if ( isset( $http_request_type ) ) {

			$message_types = array(

				'default' => array(
						'message' => __( 'There was an error trying to sign-in to your account. Make sure the credentials below are correct.', 'sharedrive' ),
					),
				'__blank' => array(
						'message' => __( 'Required: Username and Password cannot be empty.', 'sharedrive' ),
					),
				'__userempty' => array(
						'message' => __( 'Required: Username cannot be empty.', 'sharedrive' ),
					),
				'__passempty' => array(
						'message' => __( 'Required: Password cannot be empty.', 'sharedrive' ),
					),
				'fb_invalid_email' => array(
						'message' => __( 'Facebook email address is invalid or is not yet verified.', 'sharedrive' ),
					),
				'fb_error' => array(
						'message' => __( 'Facebook Application Error. Misconfigured or App is rejected.', 'sharedrive' ),
					),
				'app_not_live' => array(
						'message' => __( 'Unable to fetch your Facebook Profile.', 'sharedrive' ),
					),
				'gears_username_or_email_exists' => array(
						'message' => __( 'Username or email address already exists', 'sharedrive' ),
					),
				'gp_error_authentication' => array(
						'message' => __( 'Google Plus Authentication Error. Invalid Client ID or Secret.', 'sharedrive' ),
					),
			);

			$message = $message_types['default']['message'];

			if ( array_key_exists( $http_request_type, $message_types ) ) {

				$message = $message_types[ $http_request_type ]['message'];

			}

			$error_login_message = '<div id="message" class="error">' . esc_html( $message ) . '</div>';

		} else {

			$error_login_message = '<div id="message" class="error">' . esc_html__( 'Error: Invalid username and password combination.', 'sharedrive' ) . '</div>';

		}
	}
}

if ( isset( $http_request_logout ) ) {
	$error_login_message = '<div id="message" class="success">' . esc_html__( 'You have logged out successfully.', 'sharedrive' ) . '</div>';
}

$http_request_redirected = filter_input( INPUT_GET, '_redirected', FILTER_SANITIZE_SPECIAL_CHARS );

if ( isset( $http_request_redirected ) ) {
	$error_login_message = '<div id="message" class="success">' . esc_html__( 'Members only page. Please use the login form below to access the page.', 'sharedrive' ) . '</div>';
}

?>
<?php if ( ! is_user_logged_in() ) { ?>
	<div class="mg-top-35 mg-bottom-35 sharedrive-login-form">
		<div class="sharedrive-login-form-form">
			<div class="sharedrive-login-form__actions">
				<h3>
					<?php esc_html_e( 'Account Sign-in', 'sharedrive' ); ?>
				</h3>
				<?php do_action( 'gears_login_form' ); ?>
			</div>
			<div class="sharedrive-login-form-message">
				<?php echo wp_kses_post( $error_login_message ); ?>
			</div>
			<div class="sharedrive-login-form__form">
				<?php echo wp_login_form( $args ); ?>
			</div>
		</div>
	</div>
<?php } else { ?>
	<div class="mg-top-35 mg-bottom-35 sharedrive-login-sucessfull" style="background: #CDDC39; padding: 15px 15px 15px 15px;border-radius: 4px;color: #616161;">
		<p style="margin-bottom: 0px;">
			<?php $success_message = apply_filters( 'sharedrive_login_message_success', esc_html__( 'Great! You have succesfully login.', 'sharedrive' ) ); ?>
			<?php echo esc_html( $success_message ); ?>
		</p>
	</div>
<?php } ?>
