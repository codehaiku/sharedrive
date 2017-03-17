
<div class="sd-file-upload-form-header">
	<h3>
		<?php esc_html_e('Creating new Directory under ', 'sharedrive'); ?>
		<?php $directory_id = Sharedrive\FileUploadForm::getDirectoryId(); ?>
		<?php $directory = get_term_by( 'id', absint( $directory_id ), 'directory' ); ?>
		<strong><i class="fa fa-folder"></i> <?php echo $directory->name;?></strong>
	</h3>
</div>
<div class="sd-file-upload-form-body">
	
	<div class="sd-file-upload-form-form">
		<form id="sd-file-upload-form-html">
			<label for="sd-new-dir-frm-name">
				<?php esc_html_e('Name:', 'sharedrive'); ?>
			</label>
			
			<input maxlength="80" type="text" id="sd-new-dir-frm-name" name="name" 
			placeholder="<?php esc_attr_e('Directory Name...', 'sharedrive'); ?>" />

			<button type="button" class="button">
				<?php esc_html_e('Create Directory', 'sharedrive'); ?>
			</button>
		</form>
	</div>
</div>