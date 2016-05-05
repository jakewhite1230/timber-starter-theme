<?php
add_action( 'admin_enqueue_scripts', 'wptuts_add_color_picker' );
function wptuts_add_color_picker( $hook ) {
 
    if( is_admin() ) { 
     
        // Add the color picker css file       
        wp_enqueue_style( 'wp-color-picker' ); 
         
        // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'custom-script-handle', plugins_url( 'custom-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true ); 
    }
}
class Menu_Item_Custom_Fields_Example {

	/**
	 * Holds our custom fields
	 *
	 * @var    array
	 * @access protected
	 * @since  Menu_Item_Custom_Fields_Example 0.2.0
	 */
	protected static $fields = array();
	protected static $menu_color_picker = array();
	protected static $textareas = array();



	/**
	 * Initialize plugin
	 */
	public static function init() {
		add_action( 'wp_nav_menu_item_custom_fields', array( __CLASS__, '_fields' ), 10, 4 );
		add_action( 'wp_nav_menu_item_custom_fields', array( __CLASS__, '_textareas' ), 10, 4 );
		add_action( 'wp_nav_menu_item_custom_fields', array( __CLASS__, '_menu_color_picker' ), 10, 4 );
		add_action( 'wp_update_nav_menu_item', array( __CLASS__, '_save' ), 10, 3 );
		add_filter( 'manage_nav-menus_columns', array( __CLASS__, '_columns' ), 99 );

		self::$fields = array(
			//'field_description' => __( 'Color Option', 'menu-item-custom-fields-example' ),
			//'field-02' => __( 'Custom Field #2', 'menu-item-custom-fields-example' ),
		);
		self::$textareas = array(
			'text_area_description' => __( 'Menu Description', 'menu-item-custom-fields-example' ),
			//'field-02' => __( 'Custom Field #2', 'menu-item-custom-fields-example' ),
		);
		self::$menu_color_picker = array(
			'menu_border_color' => __( 'Menu Border Color', 'menu-item-custom-fields-example' ),
			//'field-02' => __( 'Custom Field #2', 'menu-item-custom-fields-example' ),
		);
	}


	/**
	 * Save custom field value
	 *
	 * @wp_hook action wp_update_nav_menu_item
	 *
	 * @param int   $menu_id         Nav menu ID
	 * @param int   $menu_item_db_id Menu item ID
	 * @param array $menu_item_args  Menu item data
	 */
	public static function _save( $menu_id, $menu_item_db_id, $menu_item_args ) {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		check_admin_referer( 'update-nav_menu', 'update-nav-menu-nonce' );
		foreach ( self::$fields as $_key => $label ) {
			$key = sprintf( '_menu_item_%s', $_key );

			// Sanitize
			if ( ! empty( $_POST[ $key ][ $menu_item_db_id ] ) ) {
				// Do some checks here...
				$value = $_POST[ $key ][ $menu_item_db_id ];
			}
			else {
				$value = null;
			}

			// Update
			if ( ! is_null( $value ) ) {
				update_post_meta( $menu_item_db_id, $key, $value );
			}
			else {
				delete_post_meta( $menu_item_db_id, $key );
			}
		}
		foreach ( self::$menu_color_picker as $_key => $label ) {
			$key = sprintf( '_menu_color_picker_%s', $_key );

			// Sanitize
			if ( ! empty( $_POST[ $key ][ $menu_item_db_id ] ) ) {
				// Do some checks here...
				$value = $_POST[ $key ][ $menu_item_db_id ];
			}
			else {
				$value = null;
			}

			// Update
			if ( ! is_null( $value ) ) {
				update_post_meta( $menu_item_db_id, $key, $value );
			}
			else {
				delete_post_meta( $menu_item_db_id, $key );
			}
		}
		foreach ( self::$textareas as $_key => $label ) {
			$key = sprintf( '_textarea_item_%s', $_key );

			// Sanitize
			if ( ! empty( $_POST[ $key ][ $menu_item_db_id ] ) ) {
				// Do some checks here...
				$value = $_POST[ $key ][ $menu_item_db_id ];
			}
			else {
				$value = null;
			}

			// Update
			if ( ! is_null( $value ) ) {
				update_post_meta( $menu_item_db_id, $key, $value );
			}
			else {
				delete_post_meta( $menu_item_db_id, $key );
			}
		}
	}


	/**
	 * Print field
	 *
	 * @param object $item  Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args  Menu item args.
	 * @param int    $id    Nav menu ID.
	 *
	 * @return string Form fields
	 */
	public static function _textareas( $id, $item, $depth, $args ) {
			foreach ( self::$fields as $_key => $label ) :
				$key   = sprintf( '_menu_item_%s', $_key );
				$id    = sprintf( 'edit-%s-%s', $key, $item->ID );
				$name  = sprintf( '%s[%s]', $key, $item->ID );
				$value = get_post_meta( $item->ID, $key, true );
				$class = sprintf( 'textarea-%s', $_key );
				?>
					<p class="description description-wide <?php echo esc_attr( $class ) ?>">
						<?php printf(
							'<<label for="%1$s">%2$s<br /><input type="text" id="%1$s" class="widefat %1$s" name="%3$s" value="%4$s" /></label>',
							esc_attr( $id ),
							esc_html( $label ),
							esc_attr( $name ),
							esc_attr( $value )
						) ?>
					</p>
				<?php
			endforeach;
		}

	public static function _menu_color_picker( $id, $item, $depth, $args ) {
		foreach ( self::$menu_color_picker as $_key => $label ) :
			$key   = sprintf( '_menu_color_picker_%s', $_key );
			$id    = sprintf( 'edit-%s-%s', $key, $item->ID );
			$name  = sprintf( '%s[%s]', $key, $item->ID );
			$value = get_post_meta( $item->ID, $key, true );
			$class = sprintf( 'field-%s', $_key );
			?>
				<p class="description description-wide <?php echo esc_attr( $class ) ?>">
					<?php printf(
						'<label for="%1$s">%2$s<br /><input type="text" id="%1$s" class="widefat %1$s color-field" name="%3$s" value="%4$s" /></label>',
						esc_attr( $id ),
						esc_html( $label ),
						esc_attr( $name ),
						esc_attr( $value )
					) ?>
				</p>
					<script type="text/javascript">
						(function( $ ) {
				 		// Add Color Picker to all inputs that have 'color-field' class
						    $(function() {
						        $('.color-field').wpColorPicker();
						    });
						})( jQuery );
					</script>
			<?php
		endforeach;
	}

	public static function _fields( $id, $item, $depth, $args ) {
		foreach ( self::$textareas as $_key => $label ) :
			$key   = sprintf( '_textarea_item_%s', $_key );
			$id    = sprintf( 'edit-%s-%s', $key, $item->ID );
			$name  = sprintf( '%s[%s]', $key, $item->ID );
			$value = get_post_meta( $item->ID, $key, true );
			$class = sprintf( 'textarea-%s', $_key );
			?>
				<p class="description description-wide <?php echo esc_attr( $class ) ?>">
					<?php printf(
						'<label for="%1$s">%2$s<br /><textarea type="text" id="%1$s" class="widefat %1$s" name="%3$s"  />%4$s</textarea></label>',
						esc_attr( $id ),
						esc_html( $label ),
						esc_attr( $name ),
						esc_attr( $value )
					) ?>
				</p>
			<?php
		endforeach;
	}


	/**
	 * Add our fields to the screen options toggle
	 *
	 * @param array $columns Menu item columns
	 * @return array
	 */
	public static function _columns( $columns ) {
		$columns = array_merge( $columns, self::$fields );
		$columns = array_merge( $columns, self::$textareas );
		$columns = array_merge( $columns, self::$menu_color_picker );

		return $columns;
	}
}
Menu_Item_Custom_Fields_Example::init();
