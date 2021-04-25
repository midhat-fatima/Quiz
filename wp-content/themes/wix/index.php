<?php
/**
 * The main template file.
 */	
get_header(); ?>
<!-- Details Start  -->
<section class="detail-section">
	<div class="container">
    	<div class="row details-mian">
        	<?php get_sidebar(); ?>
            <article class="col-md-8 no-padding-left">
            	<div class="detail-inner masonry-container">
                <?php  if ( have_posts() ) :  while ( have_posts() ) : the_post(); ?>
                    <div class="col-md-6 box">
                    	<div class="article">
                        	<?php $wix_feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID)); ?>
                        	<div class="post-box-img">
                            	<?php if($wix_feat_image){ ?>
                                <a href="<?php the_permalink(); ?>"><img src="<?php echo $wix_feat_image; ?>" alt="banner" /></a>
								<?php } else { ?>
                                <a href="<?php the_permalink(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/no-image.png" alt="banner" /></a>
                                <?php } ?>
                                <div class="post-box-hover">
                                	<div class="post-box-hover-center">
                                    <div class="post-box-hover-center1">
                                    	<a href="<?php echo get_permalink(); ?>"><i class="zoom-icon"></i></a>
                                   	</div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="post-box-details">
                            <a href="<?php the_permalink(); ?>"><?php  the_title(); ?></a>
                               <?php  the_excerpt(); ?>
                            </div>
                             <div class="post-box-link">
                        		<ul>
                                	<?php wix_entry_meta();?>
                               </ul>
                            </div>
                        </div>
                    </div>
                <?php endwhile; endif; // end have_posts() ?>
                    </div>
                   <?php if(get_option('posts_per_page ') < $wp_query->found_posts) { ?>
                    <div class="col-md-12">
                        <?php if(get_option('posts_per_page ') < $wp_query->found_posts) { ?>
                    	<span class="default-pagination-next"><?php previous_posts_link(); ?></span>
                        <span class="default-pagination-previous"><?php next_posts_link(); ?></span>                 	
                     <?php } ?>
                    </div>
                    <?php }
                ?>
                    <!--Pagination End-->
            </article>
        </div>
    </div>    
</section>
<!-- Details End  -->
<?php get_footer(); ?>