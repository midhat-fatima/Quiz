<?php
/*
 * The Author template file 
*/
get_header(); ?>
<!-- Details Start  -->
<section class="detail-section">
	<div class="container">
    	<div class="row details-mian">
        	<?php get_sidebar(); ?>
            <article class="col-md-8">
            	<div class="wix-inner-post">
                <div class="col-md-12 wix-page-title">
        			<h1><?php echo 'Author : All posts by <a>'.get_the_author().'</a>'; ?></h1>
				</div>
                	<?php if ( have_posts() ) :  while ( have_posts() ) : the_post(); ?>
                	<aside class="col-md-12 no-padding-left wix-single-post">
                    	<?php $wix_feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
                        if($wix_feat_image){ ?>
                            <a href="<?php the_permalink(); ?>"><img src="<?php echo $wix_feat_image; ?>" alt="banner" /></a>
                        <?php } ?>
                        <h2 class="post-titel"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <div class="post-box-link">
                        	<ul>
								<?php wix_entry_meta();?>
                            </ul>
                        </div>                        
                        <div class="wix-post-content">
                        	<?php the_excerpt(); ?>
                        </div>
                        <div class="post-box-link tag">
                        	<ul>
                                <li><?php echo get_the_tag_list('Tags: ',', ',''); ?></li>
                            </ul>
						</div>
                    </aside>
                    <?php endwhile; endif; 
                    if(get_option('posts_per_page ') < $wp_query->found_posts) { ?>
                    <div class="col-md-12">
                        <?php if(get_option('posts_per_page ') < $wp_query->found_posts) { ?>
                    	<span class="default-pagination-next"><?php previous_posts_link(); ?></span>
                        <span class="default-pagination-previous"><?php next_posts_link(); ?></span>                 	
                     <?php } ?>
                    </div>
                    <?php }
                    ?>
                    <!--Pagination End-->
                </div>
            </article>
        </div>
    </div>
</section>
<!-- Details End  -->
<?php get_footer(); ?>