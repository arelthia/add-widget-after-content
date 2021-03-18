<?php

/**
 * Admin settings page
 *
 *
 * @link       https://pintopsolutions.com
 * @since      2.2
 *
 * @package    Add Widget After Content
 * @subpackage Add Widget After Content/partials
 */
?>


    <div class="wrap">
    <h2><span class="dashicons dashicons-admin-settings"></span>Add Widget After Content Options</h2>
    <hr/>
<?php do_action( 'ps_awac_settings_top' ); ?>
    
    <?php 
        if( isset( $_GET[ 'tab' ] ) ) {
            $active_tab = $_GET[ 'tab' ];
        }else {
            $active_tab = 'awac_basic';
        } 
    ?>

    <?php foreach ($tabs as $tab => $value) { ?>
<a href="?page=awac-options&tab=<?php echo $tab?>" class="nav-tab <?php echo $tab == $active_tab ? 'nav-tab-active' : ''; ?>"><?php echo $value ?></a>
 <?php   }  ?>

    <div id="ps_admin" class="metabox-holder has-right-sidebar">
        <div class="inner-sidebar">

            <div class="meta-box-sortables">
               <div class="postbox">
                    <div class="inside">
                        <p><?php $url1 = 'https://pintopsolutions.com/contact/?utm_source=awacadmin&utm_medium=link&utm_content=contact&utm_campaign=plugin';
                            $link1     = sprintf( __( 'Need help? Or have an idea how this plugin can be made better. Reach out <a href=%s>on our website?</a>', $this->plugin_name ), esc_url( $url1 ) );
                            echo $link1; ?></p>

                        <p><?php $url2 = 'https://wordpress.org/support/view/plugin-reviews/add-widget-after-content?filter=5#postform';
                            $link2        = sprintf( __( 'We invite you to <a href=%s>leave an honest review.</a>', $this->plugin_name ), esc_url( $url2 ) );
                            echo $link2; ?></p>



                    </div>

                </div>
            </div>


            <?php

            if ( ! class_exists( 'awacPlus' ) ) {
                $url6 = 'https://pintopsolutions.com/downloads/awac-plus/?utm_source=awacadmin&utm_medium=banner&utm_content=awacplus&utm_campaign=plugin';
                $img6 = plugins_url('../images/awac-plus.png', __FILE__ );
                $link6 = sprintf( __( '<a href=%s><img src="%s" alt=""></a>', $this->plugin_name ), esc_url( $url6 ), esc_url( $img6 ) );
                echo $link6;
            }

            if ( ! class_exists( 'awacWidgetControls' ) ) {
                $url5 = 'https://pintopsolutions.com/downloads/awac-widget-controls/?utm_source=awacadmin&utm_medium=banner&utm_content=awaccontrols&utm_campaign=plugin';
                $img5 = plugins_url('../images/awac-controls.png', __FILE__ );
                $link5       = sprintf( __( '<a href=%s><img src="%s" alt=""></a>', $this->plugin_name ), esc_url( $url5 ), esc_url( $img5 ) );
                echo $link5;
            }

            if ( ! class_exists( 'awacWidgetStyles' ) ) {
                $url4 = 'https://pintopsolutions.com/downloads/awac-widget-area-styles/?utm_source=awacadmin&utm_medium=banner&utm_content=awacstyles&utm_campaign=plugin';
                $img4 = plugins_url('../images/awac-styles.png', __FILE__ );
                $link4       = sprintf( __( '<a href=%s><img src="%s" alt=""></a>', $this->plugin_name ), esc_url( $url4 ), esc_url( $img4 ) );
                echo $link4;
            }
            
            ?>
        </div>

        <div id="post-body" class="has-sidebar">
            <div id="post-body-content" class="has-sidebar-content">
                <div id="normal-sortables" class="meta-box-sortables">

                    <div class="postbox">
                        <div class="inside">
                            <!-- <h2 class="hndle"><?php /* echo $tabs[$active_tab]*/  ?></h2> -->
                            <form method="post" action="options.php">
                                <?php
                                settings_fields( $active_tab );
                                $section = ('awac_basic' == $active_tab) ? 'awac-options' : $active_tab;
                                do_settings_sections( $section );

                                submit_button();

                                ?>
                            </form>
                            <div class="clear"></div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
        <?php do_action( 'ps_awac_settings_bottom' ); ?>
    </div>