
<div class="sd-file-upload-form-header">
	<h3>
		<?php esc_html_e('Uploading Files in ', 'sharedrive'); ?>
		<?php $directory_id = Sharedrive\FileUploadForm::getDirectoryId(); ?>
		<?php $directory = get_term_by( 'id', absint( $directory_id ), 'directory' ); ?>

		<strong><i class="fa fa-folder"></i> <?php echo $directory->name;?></strong>
	</h3>
</div>
<div class="sd-file-upload-form-body">
	<p>
		<?php esc_html_e("Click 'Choose Files' to start selecting your file in your computer's file explorer, and then click start 'Start Upload' to upload the files.. You can also drog and drop the files in this modal instead of manually selecting it using your file explorer window.", 'sharedrive'); ?>
	</p>

	<p>
		<?php esc_html_e('The maximum file size should be less than ', 'sharedrive'); ?>
		<strong><?php echo Sharedrive\Helpers::formatSize( wp_max_upload_size() ); ?></strong>
	</p>
	
	<div id="sd-form-upload-progress-window">
		<ul id="sd-form-upload-progress-window-ul"></ul>
	</div>

	<div class="sd-file-upload-form-form">
		<form id="sd-file-upload-form-html">
			<input type="hidden" name="taxonomy_id" value="<?php echo Sharedrive\FileUploadForm::getDirectoryId(); ?>">
			<button id="sd-choose-file-btn" type="button" class="button">Choose files</button>
			<button id="sd-choose-file-start-upload" type="button" class="button" disabled="disabled" >Start Upload</button>
		</form>
	</div>
</div>