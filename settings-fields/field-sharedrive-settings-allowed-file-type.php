<?php
use Sharedrive\File;


function sharedrive_settings_allowed_file_type() { ?>
	
	<?php File::initMimeTypes(); ?>

	<?php $allowed_file_types = get_option("sd_allowed_file_types"); ?>

	<?php $allowed_mimetypes_default = File::getAllowedTypesDefault(); ?>

	<?php if ( empty( $allowed_file_types ) ) { ?>
		<?php $allowed_file_types = implode(',', $allowed_mimetypes_default ); ?>
	<?php } ?>

	<textarea id="sharedrive-allowed_file_types" name="sd_allowed_file_types" rows="3" cols="120"><?php echo esc_html( $allowed_file_types ); ?></textarea>

	<p class="description">

		<?php esc_html_e('Add/Remove file types inside the Textarea above (no dots). Dangerous file types are automatically disabled even if you put it in the textarea aboved. This is for security purposes. Banned file types includes the following:', 'sharedrive'); ?>

		<?php $banned_types = File::getBannedTypes() ?>

		<strong>.<?php echo implode(', .', $banned_types ); ?></strong>

	</p>

	<?php
	return;
}
