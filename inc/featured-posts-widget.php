<?php
/*
Plugin Name: Featured Posts Widget
Plugin URI: http://alabaster.io
Description: Easily set up a hero section widget
Author: jake White
Version: 1.0
Author URI: http://jake.alabaster.io
*/
// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');
	
	
add_action( 'widgets_init', function(){
     register_widget( 'Featured_Posts' );
});	
/**
 * Adds My_Widget widget.
 */
class Featured_Posts extends WP_Widget {
	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'Featured_Posts', // Base ID
			__('Featured Posts', 'text_domain'), // Name
			array('description' => __( 'Set up a featured post', 'text_domain' ),) // Args
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
		$the_sidebars = wp_get_sidebars_widgets();
		$count = count( $the_sidebars['featured-posts'] );
		$column_count = 12 / $count;






		if ( isset( $instance[ 'display_post_content' ] ) && $instance['display_post_content'] == 1 ) {
			$display_post_content = 1;
		}
		else {
			$display_post_content = 0;
		}
		// get the excerpt of the required story
		if ( $instance['featured_post_id'] == 0 ) {
		
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
		
			$post = get_post( $instance['featured_post_id'] );	
		
		}
				
		if ( array_key_exists('before_widget', $args) ) echo $args['before_widget'];
		
		if ( $post ) {
			 $thumbnail_src = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
			?>
			<div class="col-xs-12 col-sm-<?php echo $column_count ; ?> post-<?php echo apply_filters( 'widget_title', $post->ID );?>" >
			<?php if($display_post_content == 0){
				echo '<a href="' . get_permalink( $post->ID ) . '">';
				}?>
				<div class="featured-post" style="background-image: url('<?php echo $thumbnail_src; ?>');">
			<?php if($display_post_content == 1){?>
				<div class="featured_post_content">
					<a href="<?php echo get_permalink( $post->ID ); ?>">
						<h3 class="featured_post_title"><?php echo apply_filters( 'widget_title', $post->post_title );?></h3>	
					</a>
				</div>

			<?php }?>
				</div>
			<?php if($display_post_content == 0){
				echo '</a>';
				}?>
			</div>
			<?php

			
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
		
		if ( isset( $instance[ 'featured_post_id' ] ) ) {
			$featured_post_id = $instance[ 'featured_post_id' ];
		}
		else {
			$featured_post_id = 0;
		}

		if ( isset( $instance[ 'display_post_content' ] ) && $instance['display_post_content'] == 1 ) {
			$display_post_content = 1;
		}
		else {
			$display_post_content = 0;
		}

		?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'featured_post_id' ); ?>"><?php _e( 'Featured:' ); ?></label> 
			
			<select id="<?php echo $this->get_field_id( 'featured_post_id' ); ?>" name="<?php echo $this->get_field_name( 'featured_post_id' ); ?>">
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
			
				$selected = ( $post->ID == $featured_post_id ) ? 'selected' : ''; 
				
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
		<p>
			<label for="<?php echo $this->get_field_id( 'display_post_content' ); ?>">Display Post Title</label>
			<input id="<?php echo $this->get_field_id( 'display_post_content' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'display_post_content' ); ?>" value="1" <?php if($display_post_content == 1){echo 'checked';}?>>
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
		$instance['featured_post_id'] = ( ! empty( $new_instance['featured_post_id'] ) ) ? strip_tags( $new_instance['featured_post_id'] ) : '';
		$instance['display_post_content'] = ( ! empty( $new_instance['display_post_content'] ) ) ? strip_tags( $new_instance['display_post_content'] ) : '';
		return $instance;
	}
} // class My_Widget