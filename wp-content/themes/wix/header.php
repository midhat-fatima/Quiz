<?php
/*
 * The template for displaying the header
 */
$wix_options = get_option( 'wix_theme_options' ); ?>
<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">   
    <?php if(!empty($wix_options['favicon'])) { ?>
	<link rel="shortcut icon" href="<?php echo esc_url($wix_options['favicon']);?>" type="image/x-icon">
    <?php } ?>
    <!--[if lt IE 9]>
      <script src="<?php echo get_template_directory_uri(); ?>/js/respond.min.js"></script>
    <![endif]-->
    <?php wp_head();?>
</head>
<body <?php body_class(); ?>>
<!-- Header Start  -->
<header>
	<section class="container">
    	<article class="row">
        	<?php if ( get_header_image() ) : ?>
		      <div id="site-header"> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"> <img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt=""> </a> </div>
      		<?php endif; ?>
        	<div class="logo">
        	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
            <?php if(empty( $wix_options['logo'])){
				bloginfo('name');
		 	}else{
           		echo  "<img src='".esc_url($wix_options['logo'])."' class='custom_header_img'/>";
		 	}?>
        </a>
        </div>
            <div class="navbar-header pull-right">
          	<button type="button" class="navbar-toggle navbar-toggle-top" data-toggle="collapse" data-target=".navbar-collapse"> 
                <span class="sr-only">Toggle navigation</span> 
                <span class="icon-bar icon-color"></span> 
                <span class="icon-bar icon-color"></span> 
                <span class="icon-bar icon-color"></span> 
            </button>
        </div>
        </article>
        <div class="clearfix"></div>
        <article class="col-md-12 wix-padding-none">
        <?php $wix_args = array(
				'theme_location'  => 'primary',
				'container'       => 'nav',
				'container_class' => 'wix-menu navbar-collapse collapse',
				'container_id'    => 'bs-example-navbar-collapse-1',
				'menu_class'      => 'wix-menu navbar-collapse collapse',
				'menu_id'         => '',
				'echo'            => true,
				'fallback_cb'     => 'wp_page_menu',
				'before'          => '',
				'after'           => '',
				'link_before'     => '',
				'link_after'      => '',
				'items_wrap'      => '<ul>%3$s</ul>',
				'depth'           => 0,
				'walker'          => ''
			);
			wp_nav_menu($wix_args); ?>
        </article>
    </section>
</header>
<!-- Header End  -->
<div class="clearfix"></div>