<?php
/**
 * Settings page located under the Settings Menu.
 * Settings page gives you even more control.  
 * Use the options  to prevent the widget from showing on a specific post type or post format.
 *
 * @package     Add Widget After Content
 * @subpackage  Add Widget After Content Admin
 * @copyright   Copyright (c) 2015-2019, Arelthia Phillips
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
				__( 'Add Widget After Content Options', $this->plugin_name ), 
				__( 'Widget After Content', $this->plugin_name ), 
				'manage_options', 
				'awac-options', 
				array($this, 'awac_options_display')
			);
			
		}

	
		/**
	 	 * Renders the content of the awac options page  
	 	 */
		public function awac_options_display(){
			//Get the tabs that need to be displayed
			$tabs = $this->awac_get_tabs($this->awac_get_extension_settings());
			require plugin_dir_path( __FILE__ ) . 'partials/awac-options-display.php';
		}

        /**
         * Get the settings added by styles using filters
         * @return array of extensions
         *
         * Other plugins can add to the awac_extensions setting during plugin activation
         * $extensions =  get_option('awac_extensions');
         * update_option('awac_extensions', extensionClass::register_awac_comments($extensions) );
         *
         * Plugins should
         * public static function deactivate(){
         * $extensions = get_option('awac_extensions');
         * if(isset($extensions['awac_basic']['awac-comments'])) {
         * unset($extensions['awac_basic']['awac-comments']);
         * update_option('awac_extensions', $extensions);}}
         *
         *
         * public static function register_awac_comments($extensions){
         * $extensions['TAB']['extension-id']['id']= 'extension-id';
         * $extensions['TAB']['extension-id']['name']= 'Extension Name';
         * $extensions['TAB']['extension-id']['description']= 'Extension Description.';
         * return $extensions;}
         *
         * TAB options are awac_basic, styles, addon
         */
        public function awac_get_extension_settings(){
            $extensions = get_option( 'awac_extensions');

            return $extensions;
        }


        /**
         * @param $extension_settings
         * @return mixed
         */
        public function awac_get_tabs($extension_settings){

            $tabs['awac_basic']  = __( 'General', $this->plugin_name );
            if( ! empty( $extension_settings['styles'] ) ) {
                $tabs['styles'] = __( 'Styles', $this->plugin_name );
            }

            if( ! empty( $extension_settings['addons'] ) ) {

                $tabs['addons'] = __( 'Add-ons', $this->plugin_name );
            }

            $tabs = apply_filters( 'awac_add_tabs', $tabs );

            return $tabs;
        }




		/**
		 * Registers settings fields 
		 */
		public function awac_initialize_options(){
			add_settings_section(
				'awac_basic', 
				__('Where to show the widget area', $this->plugin_name), 
				array($this, 'awac_basic_section_display'), 
				'awac-options',
				array('class'=>'subtitle')
			);
			/**
			 * all_post_categories written by @doncullen
		 	 */
			add_settings_field(
				'all_post_categories', 
				__('Post Categories<p class="description">The widget will not show on post categories that are checked</p>', $this->plugin_name ), 
				array($this, 'awac_postcategories_boxes_display'), 
				'awac-options',
				'awac_basic'
			);
			register_setting(
				'awac_basic', 
				'all_post_categories'
			);

			add_settings_field(
				'all_post_types', 
				__('Post Types<p class="description">The widget will not show on post types that are checked</p>', $this->plugin_name ), 
				array($this, 'awac_type_boxes_display'), 
				'awac-options',
				'awac_basic'
			);
			register_setting(
				'awac_basic', 
				'all_post_types'
			);
			add_settings_field(
				'all_post_formats', 
				__('Post Formats<p class="description">The widget will not show on post formats that are checked</p>', $this->plugin_name ), 
				array($this, 'awac_formats_boxes_display'), 
				'awac-options',
				'awac_basic'
				
			);
			register_setting(
				'awac_basic', 
				'all_post_formats'
			);

			add_settings_field(
				'awac_priority',
				__('Widget Priority<p class="description"></p>', $this->plugin_name ),
				array($this, 'awac_priority_display'),
				'awac-options',
				'awac_basic',
				array('type'=>'radio')

			);
			register_setting(
				'awac_basic',
				'awac_priority'
			);


			//add settings created to show on the styles tab
			$settings = $this->awac_get_extension_settings();
			if( ! empty( $settings['styles'] ) ) {
				add_settings_section(
				'awac_styles', 
				__( 'Styles', $this->plugin_name ),
				array($this, 'awac_styles_section_display'), 
				'styles'
				);

				register_setting( 'styles', 'awac_styles' );
			}


			//add settings created to show on the addon tab
			if( ! empty( $settings['addons'] ) ) {
				add_settings_section(
				'awac_addons', 
				__( 'Addons', $this->plugin_name ),
				 array($this, 'awac_addon_section_display'), 
				'addons'
				);

				register_setting( 'addons', 'awac_addons' );
			}

		}

		public function awac_styles_section_display(){

		}

		public function awac_addon_section_display(){

		}



		/**
		 * Description for the basic
		 *
		 */
		public function awac_basic_section_display(){
			//echo __('<p>By default the widget will display on all posts. Use the options below to prevent the widget from showing on a specific post type or post format.</p>', $this->plugin_name  );
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
		 * Display the categories for posts
		 * @doncullen 
		 */
		public function awac_postcategories_boxes_display(){
			$post_categories = get_categories(); 
			$options = (array)get_option('all_post_categories');
			
			
			foreach ( $post_categories as $category ) {
				$cat = $category->name;
				if( !isset($options[$cat]) ){
					$options[$cat] = 0;
				}
				echo '<label><input name="all_post_categories['. $cat .']" id="all_post_categories['. $cat .']" type="checkbox" value="1" class="code" ' . checked( 1, $options[$cat], false ) . ' />'. $cat .'</label><br />' ;
				
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
				echo __( 'Theme does not support post formats', $this->plugin_name );
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
		 * Display the number field for setting the priority of the_content filter insert_after_content
		 */
		public function awac_priority_display(){
			$option = get_option('awac_priority');
			?>
			<div>
				<label for="awac_priority">
					<input type='number' name='awac_priority' min="1" max="100" value='<?php echo $option ?>'>
					<p class='description'><?php esc_html_e( 'Used to specify the order in which the widget area will be displayed.', $this->plugin_name ) ?></p>
				</label>
			</div>

		<?php
		}


        /**
         * Display rate us message in footer only on settings page.
         * @param  string $text wordpress admin footer text
         * @return string       updated footer text
         */
        public function awac_display_admin_footer($text) {

            $currentScreen = get_current_screen();

            if ( $currentScreen->id == 'appearance_page_awac-options' ) {
                $rate_text = sprintf( __( 'Thank you for using <a href="%1$s" target="_blank">Add Widget After Content</a>! Please <a href="%2$s" target="_blank">rate us</a> on <a href="%2$s" target="_blank">WordPress.org</a>',  $this->plugin_name ),
                    'https://pintopsolutions.com/downloads/add-widget-after-content/',
                    'https://wordpress.org/support/view/plugin-reviews/add-widget-after-content?filter=5#postform'
                );

                return str_replace( '</span>', '', $text ) . ' | ' . $rate_text . '</span>';
            } else {
                return $text;
            }
        }

	} /*End class AddWidgetAfterContentAdmin*/


}