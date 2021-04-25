<?php wp_footer(); ?><footer>
	<div class="container">
    	<div class="footer">
    	<?php 
			$wix_options = get_option( 'wix_theme_options' );
			if(!empty($wix_options['footertext'])) {
				echo wp_filter_nohtml_kses($wix_options['footertext']);
			}
			echo __('Powered by ','wix'). "<a href='https://fasterthemes.com/wordpress-themes/wix' target='_blank'>".__('Wix WordPress Theme','wix')."</a>"; ?>
        </div>    
    </div>
</footer>
</body>
</html>