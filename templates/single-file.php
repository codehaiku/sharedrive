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
 * @category Sharedrive\SingleFile
 * @package  Sharedrive
 * @author   Joseph G. <emailnotdisplayed@domain.tld>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version  GIT:github.com/codehaiku/sharedrive
 * @link     github.com/codehaiku/sharedrive The Plugin Repository
 */

if (! defined('ABSPATH') ) {
    return;
}
?>
<div id="sharedrive-single-file">
	<div class="sharedrive-single-file-wrap">
		<div class="sd-left-column">
			<div class="sd-left-column-wrap">
				<div class="sd-title">
					<h3>
						<a href="<?php echo esc_url(the_permalink()); ?>" title="<?php echo esc_attr( the_title() ); ?>">
							<?php the_title(); ?>
						</a>
					</h3>
				</div>
				
				<div class="sd-icon"></div>

				<div class="sd-file-size">
					3.4 MB
				</div>
				<div class="sd-download">
					<a href="<?php echo esc_url( Sharedrive\Download::getDownloadUri( get_the_ID() ) ); ?>" class="button">
						<?php esc_html_e('Download', 'sharedrive'); ?>
					</a>
				</div>
			</div>
		</div>
		<div class="sd-right-column">
			<div class="sd-right-column-wrap">
				<div class="sd-file-owner">
					<div class="sd-file-owner-avatar">
						<?php echo get_avatar( get_the_author_meta('ID'), 32 ); ?>
					</div>
					<div class="sd-file-owner-name">
						<?php echo get_the_author_link(); ?>
					</div>
				</div>
				<div class="sd-file-properties">
					<h3 class="sd-file-properties-label">
						<?php esc_html_e('Properties', 'sharedrive'); ?>
					</h3>
					<div class="sd-file-properties-list">
						<ul>
							<!-- File Name -->
							<ul class="sd-file-properties-list">
								<li class="sd-file-properties-key">
									<?php esc_html_e('File Name', 'sharedrive'); ?>
								</li>
								<li class="sd-file-properties-value">
									<?php the_title(); ?>
								</li>
							</ul>
							<!-- File Size -->
							<ul class="sd-file-properties-list">
								<li class="sd-file-properties-key">
									<?php esc_html_e('File Size:', 'sharedrive'); ?>
								</li>
								<li class="sd-file-properties-value">
									3.4 MB
								</li>
							</ul>
							<!-- Last Modified -->
							<ul class="sd-file-properties-list">
								<li class="sd-file-properties-key">
									<?php esc_html_e('Last Modified:', 'sharedrive'); ?>
								</li>
								<li class="sd-file-properties-value">
									<?php the_date(); ?>
								</li>
							</ul>
							<!-- Sharing Option -->
							<ul class="sd-file-properties-list">
								<li class="sd-file-properties-key">
									<?php esc_html_e('Sharing Option:', 'sharedrive'); ?>
								</li>
								<li class="sd-file-properties-value">
									Public
								</li>
							</ul>
							<!-- Description -->
							<ul class="sd-file-properties-list">
								<li class="sd-file-properties-key">
									<?php esc_html_e('Description:', 'sharedrive'); ?>
								</li>
								<li class="sd-file-properties-value">
									<?php echo wp_kses( get_post_meta( get_the_ID(), 'sd-file-description', true ) , 'post' ); ?>
								</li>
							</ul>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>