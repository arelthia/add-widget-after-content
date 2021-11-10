<?php
//ini_set('log_errors', true);
//ini_set('error_log', dirname(__FILE__).'/awac_errors.log');
/**
 *
 * @package   Add Widget After Content
 * @author    Arelthia Phillips
 * @license   GPL-3.0+
 * @link      https://pintopsolutions.com/downloads/add-widget-after-content/
 * @copyright Copyright (C) 2014-2020 Arelthia Phillips
 *
 * Plugin Name: 		Add Widget After Content
 * Description: 		This plugin adds a widget area after post content before the comments. You can also tell it not to display on a specific post or post format. 
 * Plugin URI: 			https://pintopsolutions.com/downloads/add-widget-after-content/
 * Author: 				Arelthia Phillips
 * Author URI: 			http://www.arelthiaphillips.com
 * Version: 			2.4.2
 * License: 			GPL-3.0+
 * License URI:       	http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: 		add-widget-after-content
 * Domain Path:       	/languages
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}
define( 'AWAC_PLUGIN_DIR',         plugin_dir_path( __FILE__ ) );
define( 'AWAC_PLUGIN_FILE',        __FILE__ );



require_once(AWAC_PLUGIN_DIR . 'add-widget-after-content-admin.php');

if ( !class_exists( 'AddWidgetAfterContent' ) ) {
	
	
	/**
	 * @package AddWidgetAfterContent
	 * @author  Arelthia Phillips
	 */
	class AddWidgetAfterContent {
		/**
		 * Unique identifier for your plugin.
		 *
		 * The variable name is used as the text domain when internationalizing strings
		 * of text. 
		 *
		 * @since    2.0.1
		 *
		 * @var      string
		 */
		protected $plugin_slug = 'add-widget-after-content';
		protected $plugin_version = '2.4.2';
		protected $settings;
		/**
		 * Initialize the plugin 
		 * @access public
		 * @return AddWidgetAfterContent
		 */
		function __construct() {

			add_action( 'init', array( $this, 'load_textdomain' ) );
			add_action(	'widgets_init', array( $this,'register_sidebar'));
			add_action( 'add_meta_boxes', array( $this,'after_content_create_metabox') );
			add_action( 'save_post', array( $this,'after_content_save_meta') );
			add_filter(	'the_content', array( $this,'insert_after_content'), $this->get_content_filter_priority());
			$this->settings = new AddWidgetAfterContentAdmin($this->plugin_slug, $this->plugin_version );	
		}

		/**
		 * Fired when the plugin is activated
		 * 
		 */
		public static function activate() {
            if (get_option('awac_priority') === false){
                update_option('awac_priority', '10');
            }




			update_option('awac_extensions', array());
		}


		/**
		 * Get the priority to use when filtering the content
		 */
		public static function get_content_filter_priority(){
            if (get_option('awac_priority') === false){
                update_option('awac_priority', '10');
            }

			return get_option('awac_priority');

		}

		/**
		 * Register the widget area/sidebar that will go after the content
		 * 
		 */
		public function register_sidebar() {
			$args = array(
	                'id' => 'add-widget-after-content',
	                'name' => __( 'After Content', $this->plugin_slug ),
	                'description' => __( 'This widget section shows after the content, but before comments on single post pages', $this->plugin_slug ),
	                'before_widget' => '<div class="awac-wrapper"><div class="awac widget %1$s">',
	                'after_widget' => '</div></div>',
	                'before_title' => '<h4 class="widget-title">',
	                'after_title' => '</h4>'
	    	);

			register_sidebar( apply_filters( 'awac_sidebar_arguments', $args ) );
		} 

		/**
		 * Return the plugin slug.
		 */
		public function get_plugin_slug() {
			return $this->plugin_slug;
		}


		/**
		 * Determines if the widget area should show on the current post/page
		 * @param  int $post_id The id of the current post
		 * @return bool   false if should not show or true if it should show
		 */
		public static function show_awac($post_id){
			$exclude_format = (array)get_option('all_post_formats');
			$exclude_type = (array)get_option('all_post_types');
			$exclude_category = (array)get_option('all_post_categories');
			$ps_type = get_post_type( $post_id );
			$ps_format = get_post_format();
			$ps_category = get_the_category();
			
			if(!is_singular()){ 
		   		return false;
		   	}
			
			//should the widget be shown after the content
		   $ps_hide_widget = get_post_meta( get_the_ID(), '_awac_hide_widget', true ); 
		   if( $ps_hide_widget ){ 
		   		return false;
		   	}

			if ( false === $ps_format ) {
				$ps_format = 'standard';
			}

			foreach ($ps_category as $categories) {
				$cat = $categories->name;
				if(isset($exclude_category[$cat]) == 1){
					return false;
				}
				//return true;
			}

			if(isset($exclude_type[$ps_type]) == 1){
				return false;
			}	
			
			if(isset($exclude_format[$ps_format]) == 1){
				return false;
			}	
			

			return(true);
			

		   
		}


		/**
		 * Add the widget after the post content if the widget is not set to be hide
		 * @param  string $content content of the current post
		 * @return $content the post content plus the widget area content
		 */
		public function insert_after_content( $content ) {
			if($this->show_awac(get_the_ID())){
				$awac_content = $this->get_after_content();
		    	$content.= apply_filters('awac_content', $awac_content );
			}
		   	return $content;
		}

		/**
		 * Get what ever is to be in the widget area, but don't display it yet
		 * @return string the content of the add-widget-after-content sidebar/widget
		 */
		public function get_after_content() {
			ob_start();
			dynamic_sidebar( 'add-widget-after-content' ); 
			$sidebar = ob_get_contents();
			ob_end_clean();
			return $sidebar;
		}

		/**
		 * Add a meta box to admin pages that are not excluded
		 */
		public function after_content_create_metabox( $post_type ) {

			//get all excluded post types
			$exclude_type = (array)get_option('all_post_types');

			//get all excluded post formats
			$exclude_format = (array)get_option('all_post_formats');

			//get the current post format
			$format = get_post_format();
			
			//if not an excluded post type and not an excluded post format
			//TODO:: Metabox not shown on postformats that are excluded !isset( $exclude_format[$format] ) 
		    if( !isset( $exclude_type[$post_type] ) ){
		        add_meta_box( 'ps-meta', 'Widget After Content', array( $this, 'after_content_metabox' ), $post_type , 'normal', 'high' );
			}else{
				remove_meta_box( 'ps-meta', $post_type, 'normal' );
			}
		}

		/**
		 * Fills the Widget After Content metabox with its content
		 * @param  object $post the post object for the post
		 */
		public function after_content_metabox( $post ) {
			
			$ps_hide_widget = get_post_meta( $post->ID, '_awac_hide_widget', true ); 
			$status = checked( $ps_hide_widget, 1, false );
			$html = __( 'Remove widget after content for this post.', $this->plugin_slug );
			$html .='<p>Yes: <input type="checkbox" name="ps_hide_widget"';
			$html .= esc_attr( $status );
			$html .='/></p>';

			echo apply_filters( 'awac_metabox_content', $html );       
		}


		/**
		 * Saves _awac_hide_widget when the post is saved
		 * @param  int $post_id the id of the current post being saved
		 */
		public function after_content_save_meta( $post_id) {
    
		    //only do if post meta is set
		    if ( isset( $_POST['ps_hide_widget'] ) ){
		        update_post_meta( $post_id, '_awac_hide_widget', TRUE );
		    } else {
		        update_post_meta( $post_id, '_awac_hide_widget', FALSE );
		    }
		    do_action( 'awac_after_save_meta');

		} 	

		/**
		 * Load the plugin text domain for translation.
		 *
		 */
		public function load_textdomain() {

			$domain = $this->plugin_slug;
			$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

			load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
			load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

		}


		

	}//end class AddWidgetAfterContent

}//end check for class



if( class_exists( 'AddWidgetAfterContent' ) ) { 
	
	/**
	 * Register callback to be fired when plugin is activated
	 */
	register_activation_hook( __FILE__, array( 'AddWidgetAfterContent', 'activate' ) ); 
	
	/**
	 * Register callback to be fired when plugin is uninstalled
	 */
	//register_uninstall_hook(  __FILE__, array( 'AddWidgetAfterContent','uninstall' ) );

	/**
	 * instantiate the plugin class  
	 */
	$AWAC = new AddWidgetAfterContent(); 
}