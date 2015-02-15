<?php
/**
 * Settings page located under the Settings Menu.
 * Settings page gives you even more control.  
 * Use the options  to prevent the widget from showing on a specific post type or post format.
 *
 * @package     Add Widget After Content
 * @subpackage  Add Widget After Content Admin
 * @copyright   Copyright (c) 2015, Arelthia Phillips
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.0.1
 */
if ( ! defined( 'WPINC' ) ) {
    die;
}


if ( !class_exists( 'AddWidgetAfterContentAdmin' ) ) {
	class AddWidgetAfterContentAdmin {
		/**
		 * The ID of this plugin.
		 * @access   private
		 * @var      string    
		 */
		private $plugin_name;
		/**
		 * The version of this plugin.
		 * @access   private
		 * @var      string    
		 */
		private $version;

		
		/**
		 * Initialize the settings page
		 * @access public
		 * @return AddWidgetAfterContentAdmin
		 */
		public function __construct( $plugin_name, $version ) {
			$this->plugin_name = $plugin_name;
			$this->version = $version;
			add_action('admin_menu', array( $this,'awac_add_options_page'));
			add_action('admin_init', array( $this,'awac_initialize_options'));
			add_filter('admin_footer_text', array( $this,'awac_display_admin_footer'));

		}


		/**
		 * Adds the 'Add Widget After Content Options' to the Appearance menu in the Dashboard
		 */
		public function awac_add_options_page(){
			add_theme_page(
				'Add Widget After Content Options', 
				'Widget After Content', 
				'manage_options', 
				'awac-options', 
				array($this, 'awac_options_display')
			);

		}

		/**
		 * Fired when the plugin is uninstalled
		 * 
		 */
		public static function uninstall() {
		    delete_option( 'all_post_types' );
			delete_option( 'all_post_formats' );    
		}

		/**
	 	 * Renders the content of the awac options page  
	 	 */
		public function awac_options_display(){
		?>
			<div class="wrap">
				
				<h2><span class="dashicons dashicons-admin-settings"></span>Add Widget After Content Options</h2>
				<form method="post" action="options.php">
					<?php
						settings_fields( 'exclude_section' );
						do_settings_sections( 'awac-options' );
						submit_button();

					?>
				</form>
			</div>

		<?php	
		}

		/**
		 * Registers settings fields 
		 */
		public function awac_initialize_options(){
			add_settings_section(
				'exclude_section', 
				__('Where to show the widget', $this->plugin_name), 
				array($this, 'awac_exclude_options_display'), 
				'awac-options',
				array('class'=>'subtitle')
			);
			add_settings_field(
				'all_post_types', 
				__('Post Types<p class="description">The widget will not show on post types that are checked</p>', $this->plugin_name ), 
				array($this, 'awac_type_boxes_display'), 
				'awac-options',
				'exclude_section'
			);
			register_setting(
				'exclude_section', 
				'all_post_types'
			);
			add_settings_field(
				'all_post_formats', 
				__('Post Formats<p class="description">The widget will not show on post formats that are checked</p>', $this->plugin_name ), 
				array($this, 'awac_formats_boxes_display'), 
				'awac-options',
				'exclude_section'
				
			);
			register_setting(
				'exclude_section', 
				'all_post_formats'
			);
		}


		/**
		 * Description for the exclude_section
		 *
		 */
		public function awac_exclude_options_display(){
			echo __('<p>By default the widget will display on all posts. Use the options below to prevent the widget from showing on a specific post type or post format.</p>', $this->plugin_name  );
		}

		/**
		 * Display the checkboxes for each post type
		 * 
		 */
		public function awac_type_boxes_display(){
			$post_types = get_post_types();
			$options = (array)get_option('all_post_types');
			
			
			foreach ( $post_types as $type ) {
				if( !isset($options[$type]) ){
					$options[$type] = 0;
				}
				echo '<label><input name="all_post_types['. $type .']" id="all_post_types['. $type .']" type="checkbox" value="1" class="code" ' . checked( 1, $options[$type], false ) . ' />'. $type .'</label><br />' ;
				
			}
		
		}

		/**
		 * Display the checkboxes for each post format
		 * 
		 */
		public function awac_formats_boxes_display(){
			
			if ( current_theme_supports( 'post-formats' ) ) {
			    $post_formats = get_theme_support( 'post-formats' );
			    if ( is_array( $post_formats[0] ) ) {       
			       foreach ($post_formats[0] as $post_format) {
			       		$formats[$post_format] = $post_format;
			       }
			    }
			}else{
				echo 'Theme does not support post formats';
				return;
			}

			$options = (array)get_option('all_post_formats');
			
			foreach ( $formats as $format ) {
				if( !isset($options[$format]) )
				{
					$options[$format] = 0;
				}
				echo '<label><input name="all_post_formats['. $format .']" id="all_post_formats['. $format .']" type="checkbox" value="1" class="code" ' . checked( 1, $options[$format], false ) . ' />'. $format .'</label><br />' ;
				
			}
		
		}


		/**
		 * Display rate us message in footer only on settings page.
		 * @param  string $text wordpress admin footer text
		 * @return string       updated footer text 
		 */
		function awac_display_admin_footer($text) {

			$currentScreen = get_current_screen();

			if ( $currentScreen->id == 'appearance_page_awac-options' ) {
				$rate_text = sprintf( __( 'Thank you for using <a href="%1$s" target="_blank">Add Widget After Content</a>! Please <a href="%2$s" target="_blank">rate us</a> on <a href="%2$s" target="_blank">WordPress.org</a>',  $this->plugin_name ),
					'https://www.pintopproductions.com/product/add-widget-content/',
					'https://wordpress.org/support/view/plugin-reviews/add-widget-after-content?filter=5#postform'
				);

				return str_replace( '</span>', '', $text ) . ' | ' . $rate_text . '</span>';
			} else {
				return $text;
			}
		}

	} /*End class AddWidgetAfterContentAdmin*/


}