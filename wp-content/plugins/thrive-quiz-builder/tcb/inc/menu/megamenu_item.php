<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
}
?>
<div id="tve-megamenu_item-component" class="tve-component" data-view="MegamenuItem">
	<div class="dropdown-header" data-prop="docked">
		<div class="group-description">
			<?php echo __( 'Main Options', 'thrive-cb' ); ?>
		</div>
		<i></i>
	</div>
	<div class="dropdown-content">
		<div class="hide-tablet hide-mobile pb-10 hide-states">
			<div class="tve-control" data-view="HasIcon"></div>
			<div class="tve-control i-enabled gl-st-icon-toggle-2" data-view="ModalPicker"></div>
		</div>
		<div class="i-selected i-enabled">
			<div class="tve-control no-space gl-st-icon-toggle-1" data-view="ColorPicker"></div>
			<div class="hide-states pt-10">
				<div class="tve-control gl-st-icon-toggle-1" data-view="Slider"></div>
			</div>
		</div>
	</div>
</div>
