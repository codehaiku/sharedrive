<?php
$is_user_files = $this->isListingTypeUser();
$tabs_urls = $this->getTabsLink();
?>
<div id="sharedrive-actions">
	<div id="sharedrive-actions-file-type-filter">
		<ul class="sharedrive-actions-list">
			<li class="sharedrive-actions-list-item">
				<a class="<?php echo $is_user_files ? 'selected ': ''; ?>sharedrive-actions-list-item-anchor" 
				href="<?php echo esc_url( $tabs_urls['user'] ); ?>">
					<?php esc_html_e('My Files &amp; Directory', 'sharedrive'); ?>
				</a>
			</li>
			<li class="sharedrive-actions-list-item">
				<a class="<?php echo !$is_user_files ? 'selected ': ''; ?>sharedrive-actions-list-item-anchor" 
				href="<?php echo esc_url( $tabs_urls['all'] ); ?>">
					<?php esc_html_e('All Shared Files &amp; Directory', 'sharedrive'); ?>
				</a>
			</li>
		</ul>
	</div>
	<div id="sharedrive-actions-internals">
		<ul class="sharedrive-actions-internals-list">
			
			<li class="sharedrive-actions-internals-list-item">
				<a href="#" id="sharedrive-actions-new-files" title="<?php esc_attr_e('Upload Files', 'sharedrive'); ?>">
					<span class="sd-actions-internals-itm-primary"><i class="fa fa-file"></i></span>
					<span class="screen-reader-text">
						<?php esc_html_e('Upload Files', 'sharedrive'); ?>
					</span>
					<span class="sd-actions-internals-itm-plus"><i class="fa fa-plus-square-o plus-square-o-file"></i></span>
				</a>
			</li>

			<li class="sharedrive-actions-internals-list-item">
				<a href="#" id="sharedrive-actions-new-dir" title="<?php esc_attr_e('Create new Directory', 'sharedrive'); ?>">
					<span class="sd-actions-internals-itm-primary"><i class="fa fa-folder"></i></span>
					<span class="screen-reader-text">
						<?php esc_html_e('Create Directory', 'sharedrive'); ?>
					</span>
					<span class="sd-actions-internals-itm-plus"><i class="fa fa-plus-square-o"></i></span>
				</a>
			</li>
			
		</ul>
	</div>
	<div class="clearfix"></div>
</div>