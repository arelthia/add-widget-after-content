<?php

/**
 * This file is used to markup the tabbed setting pages
 *
 */
?>

		<div class="wrap">
                
              

	                <?php if( isset( $_GET[ 'page' ] ) ) {
        						$active_tab = $_GET[ 'page' ];
        					} else if( $active_tab == 'awac-options' ) {
        						$active_tab = 'awac-options';
        					} else {
        						$active_tab = 'awac-licenses';
        					} 
        					?>

                  
                <h2 class="nav-tab-wrapper">
                <a href="?page=awac-options" class="nav-tab <?php echo 'awac-options' == $active_tab ? 'nav-tab-active' : ''; ?>"><?php _e( 'Settings', $this->plugin_name ); ?></a>
                
                <?php $admin_tabs = ''; $admin_tabs =  apply_filters( 'awac_admin_tabs', $admin_tabs ); ?>
               
                </h2>
                <form method="post" action="options.php">
               		<?php
               		if( 'awac-options' == $active_tab ){
               		  echo '<h2><span class="dashicons dashicons-admin-settings"></span>';
                     _e( 'Add Widget After Content Options', $this->plugin_name );
                    echo '</h2>'; 
                    settings_fields( 'exclude_section' );
                    do_settings_sections( 'awac-options' );
                    submit_button();
               		}else{
                    echo '<h2><span class="dashicons dashicons-admin-settings"></span>';
                     _e( 'AWAC Add-Ons', $this->plugin_name );
                    echo '</h2>'; 
                    settings_fields( 'addon_section' );
                    do_settings_sections( 'awac-licenses' );
                    submit_button();
               	    
               		}
                  ?>
                 </form>
		</div>
