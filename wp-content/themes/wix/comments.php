<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains comments and the comment form.
 *
 */
if ( post_password_required() ) {
	return;
} ?>
<div id="comments" class="wix-comments">
	<div id="comments" class="comments-area">
<?php if ( have_comments() ) : 	?>
   <h2 class="comments-title">
    <?php printf( _n( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'wix' ),
    	number_format_i18n( get_comments_number() ), get_the_title() ); ?>
  </h2>
    <ul class="comment-list">
    <?php wp_list_comments( array( 'callback' => 'wix_comment', 'style' => 'ul', 'short_ping' => true) ); ?>
    </ul>
       <?php paginate_comments_links();
       endif; // have_comments()
		comment_form(); ?>
</div>
</div><!-- #comments -->