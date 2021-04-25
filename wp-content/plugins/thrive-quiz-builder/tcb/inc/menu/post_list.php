<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}

?>
<div id="tve-post_list-component" class="tve-component" data-view="post_list">
	<div class="dropdown-header component-name" data-prop="docked">
		<?php echo __( 'Post List', 'thrive-cb' ); ?>
		<i></i>
	</div>
	<div class="dropdown-content">
		<div class="mb-10 row sep-bottom tcb-text-center post-list-actions">
			<div class="col-xs-12">
				<button class="tve-button orange click" data-fn="editMode"><?php echo __( 'Edit Design', 'thrive-cb' ); ?></button>
				<button class="tve-button grey click margin-left-20" data-fn="filterPosts"><?php echo __( 'Filter Posts', 'thrive-cb' ); ?></button>
			</div>
		</div>

		<div class="tve-control mb-10 sep-bottom sep-top hide-tablet hide-mobile" data-view="Type"></div>

		<div class="tve-control mt-10" data-view="ColumnsNumber"></div>

		<div class="tve-control mt-10 sep-top" data-view="HorizontalSpace"></div>

		<div class="tve-control mt-10 sep-bottom" data-view="VerticalSpace"></div>

		<div class="tve-control mt-10 hide-tablet hide-mobile" data-view="ContentSize"></div>

		<div class="tve-control mb-10 hide-tablet hide-mobile" data-view="WordsTrim"></div>

		<div class="tve-control mb-10 hide-tablet hide-mobile" data-view="ReadMoreText"></div>
	</div>
</div>
