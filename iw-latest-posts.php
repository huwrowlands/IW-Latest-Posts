<?php
/*
Plugin Name: IW Latest Posts
Plugin URI: http://www.inspiredworx.com/
Description: A simple plugin to display your latest blog posts.
Version: 0.0.1
Author: Huw Rowlands
Author URI: http://www.inspiredworx.com/
License: GPL2
*/
?>

<?php

class iw_latest_posts_plugin extends WP_Widget {

// constructor
    function iw_latest_posts_plugin() {
        parent::WP_Widget(false, $name = __('IW Latest Posts', 'iw_widget_plugin') );
    }

// widget form creation
function form($instance) {

// Check values
if( $instance) {
     $title = esc_attr($instance['title']);
     $post_count = esc_attr($instance['post_count']);
     $read_more_link = esc_attr($instance['read_more_link']);
     $read_more_text = esc_attr($instance['read_more_text']);     
} else {
     $title = '';
     $post_count = '';
     $read_more_link = '';
     $read_more_text = '';
}
?>

<p>
	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'iw_widget_plugin'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
</p>

<p>
	<label for="<?php echo $this->get_field_id('post_count'); ?>"><?php _e('How many posts to display:', 'iw_widget_plugin'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('post_count'); ?>" name="<?php echo $this->get_field_name('post_count'); ?>" type="text" value="<?php echo $post_count; ?>" />
</p>

<p>
	<label for="<?php echo $this->get_field_id('read_more_link'); ?>"><?php _e('Read More Link', 'iw_widget_plugin'); ?></label>	
	<select name="<?php echo $this->get_field_name('read_more_link'); ?>" id="<?php echo $this->get_field_id('read_more_link'); ?>" class="widefat">
	
		<option value="Select A Page">
			<?php echo esc_attr( __('Select A Page') ); ?>
		</option>
		<?php
			$pages = get_pages();
			foreach ($pages as $page) { ?>
				<option id="<?php echo $page->post_title; ?>" value="<?php echo get_page_link($page->ID); ?>"><?php echo $page->post_title; ?></option>
			  <?php //echo '<option value="' . get_page_link($page->ID) . '" id="' . $page->post_title . '"', $read_more_link == $page ? ' selected="selected"' : '', '>', $page->post_title, '</option>';
				 }
			?>
	</select>
</p>

<p>
	<label for="<?php echo $this->get_field_id('read_more_text'); ?>"><?php _e('Read More Text', 'iw_widget_plugin'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('read_more_text'); ?>" name="<?php echo $this->get_field_name('read_more_text'); ?>" type="text" value="<?php echo $read_more_text; ?>" />
</p>
<?php
}

// update widget
function update($new_instance, $old_instance) {
      $instance = $old_instance;
      // Fields
      $instance['title'] = strip_tags($new_instance['title']);
      $instance['post_count'] = strip_tags($new_instance['post_count']);
      $instance['read_more_link'] = strip_tags($new_instance['read_more_link']);
      $instance['read_more_text'] = strip_tags($new_instance['read_more_text']);

     return $instance;
}

// display widget
function widget($args, $instance) {
   extract( $args );
   // these are the widget options
   $title = apply_filters('widget_title', $instance['title']);
   $post_count = $instance['post_count'];
   $read_more_text = $instance['read_more_text'];
   $read_more_link = $instance['read_more_link'];
   echo $before_widget;
   // Display the widget
   echo '<div class="widget-text iw_latest_posts">';

   // Check if title is set
   if ( $title ) {
      echo $before_title . $title . $after_title;
   }

   // Check if post_count is set
   if( $post_count ) {
			echo '<ul class="iw_post_count">';
			$iw_args = array(
				'posts_per_page' => $post_count,
				'post_status' => 'publish'
			);
			$the_query = new WP_Query( $iw_args );
				while ($the_query -> have_posts()) : $the_query -> the_post(); ?>
					<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
				<?php endwhile;
			echo '</ul>';
   }
   
   // Check if read_more_text and read_more_link is set
   if( $read_more_text && $read_more_link ) {
     echo '<p class="iw_read_more_text"><a href="'.$read_more_link.'">'.$read_more_text.'</a></p>';
   }
   echo '</div>';
   echo $after_widget;
}
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("iw_latest_posts_plugin");'));
	
?>