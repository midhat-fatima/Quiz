<?php 
add_action( 'widgets_init', 'wix_post_comment_tag_widget' );
function wix_post_comment_tag_widget() {
	register_widget( 'wix_post_comment_tag_widget' );
}
class wix_post_comment_tag_widget extends WP_Widget {
	function __construct() {
		$wix_widget_ops = array( 'classname' => 'post_comment_tag', 'description' => __('A widget for Posts, Comments and Tags.', 'wix') );
		$wix_control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'post-comment-tag-widget' );
		parent::__construct( 'post-comment-tag-widget', __('Posts Comments and Tags', 'wix'), $wix_widget_ops, $wix_control_ops );
	}
	function widget( $wix_args, $instance ) {
		extract( $wix_args );
		//Our variables from the widget settings.
		$wix_title = apply_filters('widget_title', $instance['title'] );
		$wix_show_info = isset( $instance['show_info'] ) ? $instance['show_info'] : false;
		echo $before_widget;
		// Display the widget title 
		if ( $wix_title ) { ?>
<div class="clearfix"></div>
<div class="wix-post">
  <ul>
        <li class="active"><a href="#tab1" data-toggle="tab">Posts</a></li>
        <li><a href="#tab2" data-toggle="tab">Comments</a></li>
        <li><a href="#tab3" data-toggle="tab">Tags</a></li>
  </ul>
  <div class="separator-bold"></div>
    <div class="tab-content">
      <div class="tab-pane active" id="tab1">
        <ul>
          <?php $wix_args = array(
				'posts_per_page'   => 5,
				'orderby'          => 'post_date',
				'order'            => 'DESC',
				'post_type'        => 'post',
				'post_status'      => 'publish'
			);
			$wix_single_post = new WP_Query( $wix_args );
			while ( $wix_single_post->have_posts() ) {
				$wix_single_post->the_post(); ?>
			<li>
            <?php $wix_feat_image = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) );
			if($wix_feat_image!="")
				echo'<a href="'.get_permalink().'" title="Post Page"> <img src="'.$wix_feat_image.'" /></a>';
			else			
				echo'<a href="'.get_permalink().'" title="Post Page"> <img src="'.get_template_directory_uri().'/images/no-image-sidebar.png" /> </a>'; ?>
            <a href="<?php the_permalink();?>" title="Post Page"><?php the_title(); ?></a>
            <?php wix_excerpt(get_the_excerpt(),50);?>
          </li>
          <?php }
		  wp_reset_query(); ?>
        </ul>
      </div>
      <div class="tab-pane" id="tab2">
        <?php $wix_post_comment = get_comments( apply_filters( 'widget_comments_args', array( 'number' => 10, 'status' => 'approve', 'post_status' => 'publish' ) ) ); 
				echo '<ul id="recentcomments">';
				foreach ( (array) $wix_post_comment as $wix_comment) {
					echo'<li class="recentcomments"> '.$wix_comment->comment_author.' on <a href="' . esc_url( get_comment_link($wix_comment->comment_ID) ) . '">' . get_the_title($wix_comment->comment_post_ID) . '</a></li>';
					}
				echo'</ul>'; ?>
      </div>
      <div class="tab-pane" id="tab3">
        <?php echo '<div class="tagcloud">';
				wp_tag_cloud( apply_filters('widget_tag_cloud_args', array('taxonomy' => 'post_tag') ) );
			echo "</div>\n"; ?>
      </div>
    </div>
</div>
<?php }
	echo $after_widget;
}
	//Update the widget 
	function update( $new_instance, $old_instance ) {
		$wix_instance = $old_instance;
		//Strip tags from title and name to remove HTML 
		$wix_instance['title'] = strip_tags( $new_instance['title'] );
		$wix_instance['show_info'] = $new_instance['show_info'];
		return $wix_instance;
	}
	function form( $instance ) {
		//Set up some default widget settings.
		$wix_defaults = array( 'title' => __('post_comment_tag', 'wix'), 'name' => __('Posts Comments and Tags', 'wix'), 'show_info' => true );
		$instance = wp_parse_args( (array) $instance, $wix_defaults );
		echo 'Posts Comments and Tag'; ?>
<p>
  <input type="hidden" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="Posts Comments Tags"  />
</p>
<?php
	}
}
?>