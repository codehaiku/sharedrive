<?php
$is_user_files = $this->isListingTypeUser();
$tabs_urls = $this->getTabsLink();
?>
<div id="sharedrive-actions">
	<ul class="sharedrive-actions-list">
		<li class="sharedrive-actions-list-item">
			<a class="<?php echo !$is_user_files ? 'selected ': ''; ?>sharedrive-actions-list-item-anchor" 
			href="<?php echo esc_url( $tabs_urls['all'] ); ?>">
				<?php esc_html_e('All Shared Files &amp; Directory', 'sharedrive'); ?>
			</a>
		</li>
		<li class="sharedrive-actions-list-item">
			<a class="<?php echo $is_user_files ? 'selected ': ''; ?>sharedrive-actions-list-item-anchor" 
			href="<?php echo esc_url( $tabs_urls['user'] ); ?>">
				<?php esc_html_e('My Files &amp; Directory', 'sharedrive'); ?>
			</a>
		</li>
	</ul>
</div>