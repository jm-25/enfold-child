<?php

/*
* Add your own functions here. You can also copy some of the theme functions into this file. 
* Wordpress will use those functions instead of the original functions then.
*/

define('child_template_directory', dirname( get_bloginfo('stylesheet_url')) );

/*---------------------------------------------------------------------
Sticky footer
---------------------------------------------------------------------*/
function stiky_footer_add_to_header() {
   echo '<div id="content-wrapper">';
}
add_action( 'ava_after_main_container', 'stiky_footer_add_to_header', 10 );
function stiky_footer_add_to_footer_top() {
	echo '</div> <!-- end content-wrapper -->';
	//echo '<div id="footer-wrapper">';
}
add_action( 'ava_before_footer', 'stiky_footer_add_to_footer_top', 10 );



/*---------------------------------------------------------------------
Enqueue Custom Styles
---------------------------------------------------------------------*/
function enqueue_custom_styles(){
	//Add our main stylesheet	
	$mtime = filemtime(get_stylesheet_directory() . '/css/main.css');
	wp_enqueue_style('custom-style', child_template_directory.'/css/main.css', array(), $mtime, 'all'); 
	//Remove childs theme stylesheet
	wp_dequeue_style('avia-style', child_template_directory.'/style.css'); 
	wp_deregister_style( 'avia-style' );
	//Add childs theme stylesheet again at the very end
	$stime = filemtime(get_stylesheet_directory() . '/style.css');
	wp_enqueue_style('avia-style', child_template_directory.'/style.css', array(), $stime, 'all'); 

}
add_action('wp_enqueue_scripts','enqueue_custom_styles', 20);


/*---------------------------------------------------------------------
Add customs scripts
---------------------------------------------------------------------*/

function enqueue_custom_scripts(){
	$cstime = filemtime(get_stylesheet_directory() . '/scripts.js');
	wp_enqueue_script('custom_scripts', child_template_directory.'/scripts.js', array('jquery'),$cstime, true); 
}
add_action('wp_enqueue_scripts','enqueue_custom_scripts');

/*---------------------------------------------------------------------
SIDEBARS
---------------------------------------------------------------------*/

/**
 * Register New sidebars.
 */
/*
function custom_widgets_init() {

	register_sidebar( array(
		'name'          => 'Footer Socket 1',
		'id'            => 'footer_socket',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
	) );
	
	register_sidebar( array(
		'name'          => 'Footer Socket 2',
		'id'            => 'footer_socket_2',
		'before_widget' => '<div class="widget">',
		'after_widget'  => '</div>',
	) );	
	
}
add_action( 'widgets_init', 'custom_widgets_init' );
*/

/*---------------------------------------------------------------------
Set builder mode to debug
---------------------------------------------------------------------*/
add_action('avia_builder_mode', "builder_set_debug");
function builder_set_debug()
{
	return "debug";
}
//Add class to layout builder elements
add_theme_support('avia_template_builder_custom_css');

/*---------------------------------------------------------------------
Add customs shortcodes to Advance layout editor
---------------------------------------------------------------------*/
add_filter('avia_load_shortcodes', 'avia_include_shortcode_template', 15, 1);
function avia_include_shortcode_template($paths)
{
	$template_url = get_stylesheet_directory();
    	array_unshift($paths, $template_url.'/shortcodes/');

	return $paths;
}


/*---------------------------------------------------------------------
WIDGET
---------------------------------------------------------------------*/

/**
 * Social Icons.
 */	
 
class Social_Icons extends WP_Widget {
 
//process the new widget
public function __construct() {
$option = array(
'classname' => 'social_icons',
'description' => 'Display Theme social Icons'
);
$this->WP_Widget('Social_Icons', 'Social Icons', $option);
}
 
//build the widget settings form
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'text_domain' );
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( esc_attr( 'Title:' ) ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
		echo 'Control from theme options';	
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
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}
 
//display the widget
function widget($args, $instance) {	 
	echo '<section class="widget">';
	if ( ! empty( $instance['title'] ) ) {
		echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
	}
	$social_args 	= array('outside'=>'ul', 'inside'=>'li', 'append' => '');
	echo avia_social_media_icons($social_args, false);
	echo '</section>';
}
 
}
 
add_action('widgets_init', 'social_icons_register');
 
//Register the widget

function social_icons_register() {register_widget('Social_Icons');}		



/**
 * Horizontal Menu.
 */	
 
class Horizontal_Menu extends WP_Widget {
	 
	//process the new widget
	public function __construct() {
		$option = array(
		'classname' => 'horizontal_menu',
		'description' => 'Display Theme Footer Menu'
		);
		$this->WP_Widget('Horizontal_Menu', 'Horizontal Menu', $option);
	}
	 
	//build the widget settings form
	function form($instance) {
		echo 'Select Footer Menu in Appearance/Menus';
	}
	 
	//save the widget settings
	function update($new_instance, $old_instance) { 
		return $old_instance;
	}
	 
	//display the widget
	function widget($args, $instance) {
		  
    $avia_theme_location = 'avia3';
    $avia_menu_class = $avia_theme_location . '-menu';

    $args = array(
        'theme_location'=>$avia_theme_location,
        'menu_id' =>$avia_menu_class,
        'container_class' =>$avia_menu_class,
        'fallback_cb' => '',
        'depth'=>1,
        'echo' => false,
        'walker' => new avia_responsive_mega_menu(array('megamenu'=>'disabled'))
    );
	
	  $menu = wp_nav_menu($args);
	  
	  if($menu){ 
	  	echo "<div class='horizontal-menu'> <div id='socket'>";
		  echo 	"<nav class='sub_menu_socket' ".avia_markup_helper(array('context' => 'nav', 'echo' => false)).">";
		  echo 		$menu;
		  echo 	"</nav>";
		  echo '</div> </div>';
		}

	}
 
}
 
add_action('widgets_init', 'footer_social_register');
 
//Register the widget

function footer_social_register() {register_widget('Horizontal_Menu');}		














    