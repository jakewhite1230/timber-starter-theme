<?php
/*
Plugin Name: Hero Section Widget
Plugin URI: http://alabaster.io
Description: Easily set up a hero section widget
Author: jake White
Version: 1.0
Author URI: http://jake.alabaster.io
*/
// Block direct requests

add_action( 'init', 'hero_add_excerpts_to_pages' );
function hero_add_excerpts_to_pages() {
	add_post_type_support( 'page', 'excerpt' );
	}
// add_action('user_register', 'set_user_metaboxes');
add_action('admin_init', 'set_user_metaboxes');
function set_user_metaboxes($user_id=NULL) {

    // These are the metakeys we will need to update
    $meta_key['order'] = 'meta-box-order_post';
    $meta_key['hidden'] = 'metaboxhidden_post';

    // So this can be used without hooking into user_register
    if ( ! $user_id)
        $user_id = get_current_user_id(); 

    // Set the default order if it has not been set yet
    if ( ! get_user_meta( $user_id, $meta_key['order'], true) ) {
        $meta_value = array(
            'normal' => 'postexcerpt',
        );
        update_user_meta( $user_id, $meta_key['order'], $meta_value );
    }

    // Set the default hiddens if it has not been set yet
    if ( ! get_user_meta( $user_id, $meta_key['hidden'], true) ) {
        $meta_value = array('postcustom','trackbacksdiv','commentstatusdiv','commentsdiv','slugdiv','authordiv','revisionsdiv', 'formatdiv');
        update_user_meta( $user_id, $meta_key['hidden'], $meta_value );
    }
}









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
		if ( isset( $instance[ 'display_post_content' ] ) && $instance['display_post_content'] == 1 ) {
			$display_post_content = 1;
		}
		else {
			$display_post_content = 0;
		}
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
			 $thumbnail_src = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
			?>
			<?php if($display_post_content == 0){
				echo '<a href="' . get_permalink( $post->ID ) . '">';
				}?>
			<div class="front-page-hero post-<?php echo apply_filters( 'widget_title', $post->ID ); ?>" style="background-image: url('<?php echo $thumbnail_src; ?>');">
			<?php if($display_post_content == 1){?>
				<div class="hero_widget_post_content">
					<h3 class="hero_widget_title"><?php echo apply_filters( 'widget_title', $post->post_title );?></h3>
					<p class="hero_widget_excerpt"><?php echo $post->post_excerpt; ?></p>
					<p class="hero_widget_learnmore"><a href="<?php get_permalink( $post->ID ) ?>" title="Learn More, <?php $post->post_title; ?>">Learn More <span class="glyphicon glyphicon-triangle-right"></span></a></p>
				</div>

			<?php }?>
				
			</div>
			<?php if($display_post_content == 0){
				echo '</a>';
				}?>
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
		
		if ( isset( $instance[ 'hero_id' ] ) ) {
			$hero_id = $instance[ 'hero_id' ];
		}
		else {
			$hero_id = 0;
		}

		if ( isset( $instance[ 'display_post_content' ] ) && $instance['display_post_content'] == 1 ) {
			$display_post_content = 1;
		}
		else {
			$display_post_content = 0;
		}

		?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'hero_id' ); ?>"><?php _e( 'Featured:' ); ?></label> 
			
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
		<p>
			<label for="<?php echo $this->get_field_id( 'display_post_content' ); ?>">Display Post Content</label>
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
		$instance['hero_id'] = ( ! empty( $new_instance['hero_id'] ) ) ? strip_tags( $new_instance['hero_id'] ) : '';
		$instance['display_post_content'] = ( ! empty( $new_instance['display_post_content'] ) ) ? strip_tags( $new_instance['display_post_content'] ) : '';
		return $instance;
	}
} // class My_Widget