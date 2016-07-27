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


    <div class=""wrap">
    <h2><span class="dashicons dashicons-admin-settings"></span>Add Widget After Content Options</h2>
    <hr/>
<?php do_action( 'ps_lts_settings_top' ); ?>
    
    <?php 
        if( isset( $_GET[ 'tab' ] ) ) {
            $active_tab = $_GET[ 'tab' ];
        }else {
            $active_tab = 'basic';
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
                        <p><?php $url2 = 'https://https://pintopsolutions.com/contact/';
                            $link2     = sprintf( __( 'Need help? Or have an idea how this plugin can be made better. Reach out <a href=%s>on our website?</a>', $this->plugin_name ), esc_url( $url2 ) );
                            echo $link2; ?></p>

                        <p><?php $url4 = 'https://wordpress.org/support/view/plugin-reviews/add-widget-after-content?filter=5#postform';
                            $link4        = sprintf( __( 'We invite you to <a href=%s>leave an honest review.</a>', $this->plugin_name ), esc_url( $url4 ) );
                            echo $link4; ?></p>

                    </div>
                </div>
            </div>
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
                                if ( ! class_exists( 'AWAC_comments' ) ) {
                                    $url1 = 'https://pintopsolutions.com/downloads/awac-comments/';
                                    $link1       = sprintf( __( '<strong>Show After Comments</strong> - Now you can add AWAC after comments. <a href=%s>Learn More</a>', $this->plugin_name ), esc_url( $url1 ) );
                                    echo $link1;
                                }
                                submit_button();

                                ?>
                            </form>
                            <div class="clear"></div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <?php do_action( 'ps_lts_settings_bottom' ); ?>
    </div>