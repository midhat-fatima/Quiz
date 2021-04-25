<?php
/**
 * The 404 template file
*/
get_header(); ?>
<!-- Details Start  -->
<section class="detail-section">
	<div class="container">
    	<div class="row details-mian">
        	<?php get_sidebar(); ?>
            <article class="col-md-8">
            	<header>
			<div class="jumbotron">
				<h1><?php _e('Epic 404 - Article Not Found','wix'); ?></h1>
				<p><?php _e("This is embarassing. We can't find what you were looking for.",'wix'); ?></p>
                <section class="post_content">
                    <p><?php _e('Whatever you were looking for was not found, but maybe try looking again or search using the form below.','wix'); ?></p>
                    <div class="row">
                        <div class="col-sm-12">
                            <?php get_search_form(); ?>									
                        </div>
                	</div>
				</section>
			</div>
		</header>
            </article>
        </div>
    </div>    
</section>
<!-- Details End  -->
<?php get_footer(); ?>