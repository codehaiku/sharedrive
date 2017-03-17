
<div id="sharedrive-container">
	
	<!-- Breadcrumbs -->
	<div id="sharedrive-breadcrumb">
		<?php $breadcrumb = new Sharedrive\Breadcrumbs(); ?>
		<?php $breadcrumb->render(); ?>
	</div>

	<!-- Directories-->
	<ul class="sharedrive-file-window">
	
	<?php $directories = $this->getCurrentDirectories(); ?>

	<?php if ( ! empty ( $directories ) ) { ?>
		<?php foreach( $directories as $directory ) { ?>
			<ul class="sharedrive-item directory">
				<li class="file-title file-column">
					<h3>
						<a href="<?php echo esc_url( get_term_link( $directory->term_id ) ); ?>">
							<?php echo esc_html__( $directory->name ); ?>
						</a>
					</h3>
				</li>
				<li class="file-date file-column">
					Directory
				</li>
				<li class="file-scope file-column">
					<?php echo get_term_meta( $directory->term_id, 'sharedrive_directory_privacy', true ); ?>
				</li>
				<li class="file-owner file-column">
					-
				</li>
			</ul>
		<?php } ?>
	<?php } ?>

	<!-- Files -->
	<?php $files = new WP_Query( $this->query_args ); ?>
	<?php if ( $files->have_posts() ) : ?>
		<?php while ( $files->have_posts() ) : $files->the_post(); ?>
			<?php $file_type = get_post_meta( get_the_ID(), 'sharedrive_file_type', true ); ?>
			<ul <?php post_class( array( 'sharedrive-item', sanitize_html_class( $file_type ) ) );?>>
				<li class="file-title file-column">
					<h3>
						<a href="<?php echo esc_url( the_permalink() ); ?>" title="<?php echo esc_attr(the_title()); ?>">
							<?php echo the_title(); ?>
						</a>
					</h3>
				</li>
				<li class="file-date file-column">
					<?php the_time('F jS, Y') ?>
				</li>
				<li class="file-scope file-column">
					<?php echo str_replace( '_', ' ', get_post_meta( get_the_ID(), 'sharedrive_file_privacy', true ) ); ?>
				</li>
				<li class="file-owner file-column">
					<?php echo get_avatar( get_the_author_meta( 'ID'), 64) ; ?>
				</li>
			</ul>
		<?php endwhile; ?>
	<?php else: ?>
		<ul class="sharedrive-item no-files-found">
			<li class="file-title file-column">
				<?php esc_html_e('There are no files inside this directory.', 'sharedrive'); ?>
			</li>
		</ul>
	<?php endif; ?>
	</ul>
	<?php
		$big = 9999999;
		$args = array(
			'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format' => '?paged=%#%',
			'current' => max( 1, get_query_var('paged') ),
			'total' => $files->max_num_pages
		); 
	?>
		
	<div id="sharedrive-pagination">
		<?php echo paginate_links( $args ); ?>
	</div>

	<?php wp_reset_postdata(); ?>

	<div id="sharedrive-file-upload-form">
		<div class="sharedrive-modal-form">
			<div class="sharedrive-modal-form-backdrop">
				<div class="sharedrive-modal-upload-form" id="sharedrive-modal-upload-form-new-file">
					<div class="sharedrive-modal-form-wrap">
						<?php Sharedrive\FileUploadForm::renderForm(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="sharedrive-create-directory-form">
		<div class="sharedrive-modal-form">
			<div class="sharedrive-modal-form-backdrop">
				<div class="sharedrive-modal-upload-form" id="sharedrive-modal-upload-form-new-dir">
					<div class="sharedrive-modal-form-wrap">
						<?php Sharedrive\FileUploadForm::createDirectoryForm(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div><!--#sharedrive-container-->