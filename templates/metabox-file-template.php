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

$post_id = Sharedrive\Helpers::getPostID();
?>

<style>
#sd-attached-file {
	background: #f1f1f1;
	padding: 5px 10px;
}
#filelist li.sd-success{ color: #558B2F; }
#filelist li.sd-error{ color: #f44336; }
</style>
<p id="sd-attached-file">
	<label for="sd-current-file-object">
			<?php esc_html_e('Attached File:', 'sharedrive'); ?>
			<span id="sd-current-file-object"> 
				<?php $file = get_post_meta( $post_id, 'sharedrive_file_name', true  ); ?>
				<?php if ( ! empty ( $file ) ) { ?>
					<?php echo esc_html( $file ); ?>
					<a href="<?php echo esc_url( Sharedrive\Download::getDownloadUri( $post_id ) ); ?>" title="<?php echo esc_attr_e('Download File', 'sharedrive'); ?>">
						<?php esc_html_e('(Download File)', 'sharedrive'); ?>
					</a>
				<?php } else { ?>
					<?php esc_html_e('There are no files attached', 'sharedrive'); ?>
				<?php } ?>
			</span>
	
		</label>
	<hr>
</p>

<p>
<label for="sd-file-object">
	<strong><?php esc_html_e( "Upload New File", 'example' ); ?></strong>
</label>
<span class="description">
	<?php esc_html_e( '*The attached file will be overwritten when you upload a new file.', 'sharedrive' ); ?>
</span>
</p>

<ul id="filelist"></ul>
 
<div id="container">
    <p>
    	<a id="browse" class="button button-primary" href="javascript:;">
    		<?php echo esc_html_e('Browse File', 'sharedrive'); ?>
    	</a>
    	<a id="start-upload" class="button button-secondary" href="javascript:;">
    		<?php echo esc_html_e('Start Upload', 'sharedrive'); ?>
    	</a>
    </p>
</div>

<span class="description">
	<?php _e("Click 'Browse File' to select a file and click 'Start Upload' to begin uploading. Click here to see what type of files are allowed.", 'sharedrive'); ?>
</span>


<p>
	<label for="sd-file-description">
		<strong><?php esc_html_e( "Description", 'example' ); ?></strong>
	</label>

	<textarea id="sd-file-description" name="sd-file-description" class="widefat" rows="3"><?php echo get_post_meta($post_id, 'sd-file-description', true); ?></textarea>
	<span class="description">
		<?php esc_html_e('Providing a short description of the file 
		allows the readers to understand what this file is all about.', 'sharedrive'); ?>
	</span>
</p>
