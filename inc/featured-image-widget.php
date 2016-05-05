<?php
/*
Plugin Name: My Useful Widget
Plugin URI: http://mydomain.com
Description: My useful widget
Author: Me
Version: 1.0
Author URI: http://mydomain.com
*/
// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');
	
	
add_action( 'widgets_init', function(){
     register_widget( 'Featured_Hero_Section' );
});	
/**
 * Adds My_Widget widget.
 */
class Featured_Hero_Section extends WP_Widget {
	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'Featured_Hero_Section', // Base ID
			__('Featured Hero Section', 'text_domain'), // Name
			array('description' => __( 'Set up a featured hero section', 'text_domain' ),) // Args
		);
	}
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		
		// get the excerpt of the required story
		if ( $instance['hero_id'] == 0 ) {
		
			$gp_args = array(
				'posts_per_page' => 1,
				'post_type' => array('post', 'page'),
				'orderby' => 'post_date',
				'order' => 'desc',
				'post_status' => 'publish'
			);
			$posts = get_posts( $gp_args );
			
			if ( $posts ) {
				$post = $post[0];
			} else {
				$post = null;
			}
		
		} else {
		
			$post = get_post( $instance['hero_id'] );	
		
		}
				
		if ( array_key_exists('before_widget', $args) ) echo $args['before_widget'];
		
		if ( $post ) {
		
			echo get_the_post_thumbnail( $post->ID, array(250,500), array('class'=>'story_featured_img') );
			echo '<h3 class="story_widget_title">' . apply_filters( 'widget_title', $post->post_title ). '</h3>';
			echo '<p class="story_widget_excerpt">' . $post->post_excerpt . '</p>';
			echo '<p class="story_widget_readmore"><a href="' . get_permalink( $post->ID ) . '" title="Read the story, ' . $post->post_title . '">more...</a></p>';
			
		} else {
		
			echo __( 'No recent story found.', 'text_domain' );
		}
			
		if ( array_key_exists('after_widget', $args) ) echo $args['after_widget'];
	}
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		
		if ( isset( $instance[ 'hero_id' ] ) ) {
			$hero_id = $instance[ 'hero_id' ];
		}
		else {
			$hero_id = 0;
		}
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'hero_id' ); ?>"><?php _e( 'Story:' ); ?></label> 
			
			<select id="<?php echo $this->get_field_id( 'hero_id' ); ?>" name="<?php echo $this->get_field_name( 'hero_id' ); ?>">
				<option value="0">Most recent</option> 
		<?php 
		// get the exceprt of the most recent story
		$gp_args = array(
			'posts_per_page' => -1,
			'post_type' => array('post', 'page'),
			'orderby' => 'post_date',
			'order' => 'desc',
			'post_status' => 'publish'
		);
		
		$posts = get_posts( $gp_args );
			foreach( $posts as $post ) {
			
				$selected = ( $post->ID == $hero_id ) ? 'selected' : ''; 
				
				if ( strlen($post->post_title) > 30 ) {
					$title = substr($post->post_title, 0, 27) . '...';
				} else {
					$title = $post->post_title;
				}
				echo '<option value="' . $post->ID . '" ' . $selected . '>' . $title . '</option>';
			}
		?>
			</select>
		</p>
		<?php 
	}
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		
		$instance = array();
		$instance['hero_id'] = ( ! empty( $new_instance['hero_id'] ) ) ? strip_tags( $new_instance['hero_id'] ) : '';
		return $instance;
	}
} // class My_Widget