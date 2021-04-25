<?php
/**
 * Thrive Themes - https://thrivethemes.com
 *
 * @package thrive-visual-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden
} ?>

<div id="tve-image-component" class="tve-component" data-view="Image">
	<div class="dropdown-header" data-prop="docked">
		<?php echo __( 'Main Options', 'thrive-cb' ); ?>
		<i></i>
	</div>
	<div class="dropdown-content">
		<div class="tve-control" data-view="ImagePicker"></div>
		<div class="tve-control" data-view="ImageFullSize"></div>

		<hr>

		<div class="tve-control" data-view="ImageSize"></div>
		<div class="tve-control" data-view="StyleChange"></div>
		<div class="tve-control" data-view="ImageTitle"></div>
		<div class="tve-control" data-view="ImageAltText"></div>
		<div class="tve-control scrolled" data-key="StylePicker" data-initializer="style_picker_control"></div>

		<hr>

		<div class="tve-control no-space mb-5" data-view="ImageCaption"></div>
		<div class="tve-control no-space" data-key="ToggleURL" data-extends="Switch" data-label="<?php echo __( 'Add link to image', 'thrive-cb' ); ?>"></div>

		<div class="image-link mt-10"></div>

	</div>
</div>

<div id="tve-image-effects-component" class="tve-component" data-view="ImageEffects">
	<div class="dropdown-header" data-prop="docked">
		<?php echo __( 'Image Effects', 'thrive-cb' ); ?>
		<i></i>
	</div>
	<div class="dropdown-content">
		<div class="tve-control" data-view="ImageGreyscale"></div>
		<div class="tve-control" data-view="ImageOpacity"></div>
		<div class="tve-control" data-view="ImageBlur"></div>
		<div class="tve-control" data-view="ImageOverlaySwitch"></div>
		<div class="tve-control" data-view="ImageOverlay"></div>
	</div>
</div>
