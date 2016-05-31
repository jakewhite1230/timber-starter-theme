<?php
if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
		} );
	return;
}

Timber::$dirname = array('templates', 'views');

class StarterSite extends TimberSite {

	function __construct() {
		add_theme_support( 'post-formats' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		parent::__construct();
	}

	function register_post_types() {
		//this is where you can register custom post types
	}

	function register_taxonomies() {
		//this is where you can register custom taxonomies
	}

	function add_to_context( $context ) {
		$context['foo'] = 'bar';
		$context['stuff'] = 'I am a value set in your functions.php file';
		$context['notes'] = 'These values are available everytime you call Timber::get_context();';
		$context['menu'] = new TimberMenu();
		$context['primary_menu'] = new TimberMenu('primary-menu');
		$context['site'] = $this;
		return $context;
	}

	function add_to_twig( $twig ) {
		/* this is where you can add your own fuctions to twig */
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter( 'myfoo', new Twig_Filter_Function( 'myfoo' ) );
		$twig->addFilter( 'render_post_excerpt', new Twig_Filter_Function( 'render_post_excerpt' ) );
		return $twig;
	}

}

function register_theme_menus(){
	register_nav_menus(
		array(
			'primary-menu' => __( 'Primary Menu' )
			)

		);
}

add_action('init', 'register_theme_menus');

require_once dirname( __FILE__ ) . '/menu-item-custom-fields/menu-item-custom-fields.php';
require_once 'inc/description_nav_walker.php';


function theme_styles() {

	


	wp_enqueue_style('bootstrap_css', get_template_directory_uri() . '/css/bootstrap.min.css');
	wp_enqueue_style('main_css', get_template_directory_uri() . '/style.css');
	wp_enqueue_style('googlefont_css', 'https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,600italic');

}

add_action('wp_enqueue_scripts', 'theme_styles');




function theme_js() {

	global $wp_scripts;

	wp_register_script('html5_shiv','https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js', '', '', false);
	wp_register_script('respond_js','https://oss.maxcdn.com/respond/1.4.2/respond.min.js', '', '', false);


	$wp_scripts->add_data('html5_shiv', 'conditional', 'lt IE 9');
	$wp_scripts->add_data('respond_js', 'conditional', 'lt IE 9');

	wp_enqueue_script('bootstrap_js', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), '', true );
	wp_enqueue_script('scroll_js', get_template_directory_uri() . '/js/jquery.smooth-scroll.min.js', array('jquery'), '', true );
	wp_enqueue_script('sticky_js', get_template_directory_uri() . '/js/jquery.sticky.js', array('jquery'), '', true );
	wp_enqueue_script('main_js', get_template_directory_uri() . '/js/main.js', array('jquery'), '', true );
	
}

add_action('wp_enqueue_scripts', 'theme_js');

//Allow SVG uploads
function custom_mtypes( $m ){
    $m['svg'] = 'image/svg+xml';
    $m['svgz'] = 'image/svg+xml';
    return $m;
}
add_filter( 'upload_mimes', 'custom_mtypes' );

function theme_prefix_setup() {
    add_theme_support( 'custom-logo' );
}
add_action( 'after_setup_theme', 'theme_prefix_setup' );

new StarterSite();

function myfoo( $text ) {
	$text .= ' test!';
	return $text;
}
function sidebar_widgets_init() {

	register_sidebar( array(
		'name'          => 'Footer Widget',
		'id'            => 'footer-widget',
		'before_widget' => '<div class="col-12">',
		'after_widget'  => '</div>',
		'before_title'  => '<h5 class="module-heading">',
		'after_title'   => '</h5>'
	) );

	register_sidebar( array(
		'name'          => 'Hero Widget',
		'id'            => 'hero-widget',
		'before_widget' => '<div class="hero-widget">',
		'after_widget'  => '</div>',
		'before_title'  => '<h5 class="module-heading">',
		'after_title'   => '</h5>'
	) );

	register_sidebar( array(
		'name'          => 'Featured Posts',
		'id'            => 'featured-posts',
		'before_widget' => '<div class="featured-post-container">',
		'after_widget'  => '</div>',
		'before_title'  => '<h5 class="module-heading">',
		'after_title'   => '</h5>'
	) );

}
add_action( 'widgets_init', 'sidebar_widgets_init' );
require_once 'inc/featured-image-widget.php';
require_once 'inc/featured-posts-widget.php';

function render_post_excerpt($id){
	$context['nav_post'] = Timber::get_post($id);
	return $context;
}

