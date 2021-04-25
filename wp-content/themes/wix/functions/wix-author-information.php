<?php add_action( 'widgets_init', 'wix_widget' );
function wix_widget() {
    register_widget( 'wix_info_widget' );
}
class wix_info_widget extends WP_Widget {
    function __construct() {
        $wix_widget_ops = array( 'classname' => 'wix_info', 'description' => __('A widget that displays the title, content, image and socia links. ', 'wix') );
        $wix_control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'wix-info-widget' );
        parent::__construct( 'wix-info-widget', __('wix Author information', 'wix'), $wix_widget_ops, $wix_control_ops );
    }
    function widget( $wix_args, $wix_instance ) {
        extract( $wix_args );
        //Our variables from the widget settings.
        $wix_title = apply_filters('widget_title', $wix_instance['title'] );
        $wix_name = $wix_instance['content'];
		$wix_image = $wix_instance['image'];
        echo $before_widget;
        //Display widget ?>
  <ul>
    <li class="wix-widget-author"><img align="left" src="<?php if($wix_instance['image']) { echo $wix_instance['image']; } else { echo get_template_directory_uri().'/images/default-user.png'; } ?>" class="img-circle">
      <h4><?php echo $wix_instance['title']; ?></h4>
      <?php echo $wix_instance['content']; ?></li>
    <li class="wix-social">
		<?php if($wix_instance['facebook']) { ?><a target="_blank" class="social facebook-icon" href="<?php echo $wix_instance['facebook']; ?>"></a> <?php }
            if($wix_instance['twitter']) { ?><a target="_blank" class="social twitter-icon" href="<?php echo $wix_instance['twitter']; ?>"></a><?php }
            if($wix_instance['linkedin']) { ?><a target="_blank" class="social linkedin-icon" href="<?php echo $wix_instance['linkedin']; ?>"></a><?php }
            if($wix_instance['google']) { ?><a target="_blank" class="social googleplus-icon" href="<?php echo $wix_instance['google']; ?>"></a><?php } ?>
    </li>
  </ul>
<?php echo $after_widget;
}
    //Update the widget
    function update( $new_instance, $old_instance ) {
        $wix_instance = $old_instance;
        //Strip tags from title and name to remove HTML
        $wix_instance['title'] = strip_tags( $new_instance['title'] );
        $wix_instance['content'] = strip_tags( $new_instance['content'] );
		$wix_instance['image'] = strip_tags( $new_instance['image'] );
        $wix_instance['facebook'] = strip_tags( $new_instance['facebook'] );
        $wix_instance['twitter'] = strip_tags( $new_instance['twitter'] );
		$wix_instance['linkedin'] = strip_tags( $new_instance['linkedin'] );
		$wix_instance['google'] = strip_tags( $new_instance['google'] );
        return $wix_instance;
    }
    function form( $wix_instance ) { ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'wix'); ?></label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php if(!empty($wix_instance['title'])) { echo $wix_instance['title']; } ?>" style="width:100%;" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'content' ); ?>"><?php _e('Content:', 'wix'); ?></label>
            <textarea id="<?php echo $this->get_field_id( 'content' ); ?>" name="<?php echo $this->get_field_name( 'content' ); ?>" style="width:100%;"><?php if(!empty($wix_instance['content'])) { echo $wix_instance['content']; } ?></textarea>
        </p>
        <p class="section">
        <label for="<?php echo $this->get_field_id( 'image' ); ?>"><?php _e('Image:', 'wix'); ?></label>
        <input id="<?php echo $this->get_field_id( 'image' ); ?>"  type="text" class="widefat wix_media_url upload" name="<?php echo $this->get_field_name( 'image' ); ?>" value="<?php if(!empty($wix_instance['image'])) { echo $wix_instance['image']; } ?>" placeholder="No file chosen" style="width:100%;" />
		<?php if(!empty($wix_instance['image'])) { ?><img src="<?php echo $wix_instance['image']; ?>" style='max-width:100%;' /><?php } ?>
        <input id="wix_image_uploader"  class="button wix_media_upload" type="button" value="Upload" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'facebook' ); ?>"><?php _e('Facebook url:', 'wix'); ?></label>
            <input id="<?php echo $this->get_field_id( 'facebook' ); ?>" name="<?php echo $this->get_field_name( 'facebook' ); ?>" value="<?php if(!empty($wix_instance['facebook'])) { echo $wix_instance['facebook']; } ?>" style="width:100%;" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'twitter' ); ?>"><?php _e('Twitter url:', 'wix'); ?></label>
            <input id="<?php echo $this->get_field_id( 'twitter' ); ?>" name="<?php echo $this->get_field_name( 'twitter' ); ?>" value="<?php if(!empty($wix_instance['twitter'])) { echo $wix_instance['twitter']; } ?>" style="width:100%;" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'linkedin' ); ?>"><?php _e('Linkedin url:', 'wix'); ?></label>
            <input id="<?php echo $this->get_field_id( 'linkedin' ); ?>" name="<?php echo $this->get_field_name( 'linkedin' ); ?>" value="<?php if(!empty($wix_instance['linkedin'])) { echo $wix_instance['linkedin']; } ?>" style="width:100%;" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'google' ); ?>"><?php _e('Google+ url:', 'wix'); ?></label>
            <input id="<?php echo $this->get_field_id( 'google' ); ?>" name="<?php echo $this->get_field_name( 'google' ); ?>" value="<?php if(!empty($wix_instance['google'])) { echo $wix_instance['google']; } ?>" style="width:100%;" />
        </p>
    <?php
    }
} ?>