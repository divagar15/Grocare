<?php

/************************************
Licensing & Automatic Updates - implementation. Built on top of EDD-SL (https://easydigitaldownloads.com/extensions/software-licensing/)
 ************************************* */

// this is the URL our updater / license checker pings. This should be the URL of the site with EOIC_PLUGIN_PATHDD installed
define( 'FCA_EOI_STORE_URL', 'https://fatcatapps.com/' );

// the name of your product. This should match the download name in EDD exactly
$plugin_name = 'Optin Cat';

switch ( FCA_EOI_EDITION ) {

	case 'optin_cat_elite':
		$plugin_name = 'Optin Cat Premium: Elite';
		break;
	
	case 'optin_cat_business':
		$plugin_name = 'Optin Cat Premium: Business';
		break;
	
	case 'optin_cat_personal':
		$plugin_name = 'Optin Cat Premium';
		break;
	
	default:
		$plugin_name = 'Optin Cat';

}

define( 'FCA_EOI_ITEM_NAME', $plugin_name );

/************************************
 Licensing UI
 ************************************* */
 // slug for licensing link
define('FCA_EOI_LOC', 'easy-opt-ins');


if( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
    // load our custom updater
    include plugin_dir_path( __FILE__ ) . 'classes/edd_sl/EDD_SL_Plugin_Updater.php';
}

class EasyOptInsLicense {
    
    public $settings;
    public $license_key;

    public function __construct( $settings ) {
            // retrieve our license key from the DB
            $this->license_key = trim( get_option( 'fca_eoi_license_key' ) );
            // setup the updater
            $edd_updater = new EDD_SL_Plugin_Updater( FCA_EOI_STORE_URL, FCA_EOI_PLUGIN_FILE , array(
                    'version' 	=> '1.6.3', 				// current version number
                    'license' 	=> $this->license_key , 		// license key (used get_option above to retrieve from DB)
                    'item_name' => FCA_EOI_ITEM_NAME, 	// name of this plugin
                    'author' 	=> 'Fatcat Apps',  // author of this plugin
                    'url'       => home_url()
                )
            );
            //register setting sub page
            add_action( 'admin_menu', array( $this , 'fca_eoi_license_menu' ) );
            
            add_action('admin_init', array( $this , 'fca_eoi_register_option' ) );
            
            add_action('admin_init', array( $this , 'fca_eoi_activate_license' ) );
            
            add_action('admin_init', array( $this , 'fca_eoi_deactivate_license' ) );
            
            /* license check */
            $dh_eoi_page = (isset($_REQUEST['page']) && $_REQUEST['page'] == 'easy-opt-ins-license')?true:false;
            $dh_eoi_license_status = get_option('fca_eoi_license_status', 'inactive');
            if (!in_array($dh_eoi_license_status, array('valid', 'expired')) && !$dh_eoi_page && 'easy-opt-ins' == $settings['post_type'] ) {
                    // commented out beneath line of code so the UI can be used even if they don't have a valid license
                    //add_action('admin_menu', array( $this , 'fca_eoi_plugin_not_activated' ));
            }
        }
        
     /**
      * register setting sub page
      */   
    function fca_eoi_license_menu() {
         add_submenu_page( 'edit.php?post_type=easy-opt-ins', __('Settings', FCA_EOI_LOC), __('Settings', FCA_EOI_LOC), 'manage_options', 'easy-opt-ins-license', array( $this , 'fca_eoi_license_page' ) );

    }
    
    function fca_eoi_license_page(){
        
            $license  = get_option( 'fca_eoi_license_key' );
            $status   = get_option( 'fca_eoi_license_status', 'inactive' );
            ?>

            <div class="wrap">
                <h2><?php _e('Plugin Options', FCA_EOI_LOC); ?></h2>
                <form method="post" action="options.php">

                    <?php wp_nonce_field( 'fca_eoi_license_nonce', 'fca_eoi_license_nonce' ); ?>

                    <h3>
                        <?php _e('License', FCA_EOI_LOC); ?>
                        <?php if($status == 'valid' ) { ?>
                            <span style="color: #fff; background: #7ad03a; font-size: 13px; padding: 4px 6px 3px 6px; margin-left: 5px;"><?php _e('ACTIVE', FCA_EOI_LOC); ?></span>
                        <?php } elseif($status == 'expired' ) { ?>
                            <span style="color: #fff; background: #dd3d36; font-size: 13px; padding: 4px 6px 3px 6px; margin-left: 5px;"><?php _e('EXPIRED', FCA_EOI_LOC); ?></span>
                        <?php } else { ?>
                            <span style="color: #fff; background: #dd3d36; font-size: 13px; padding: 4px 6px 3px 6px; margin-left: 5px;"><?php _e('INACTIVE.', FCA_EOI_LOC); ?></span>
                        <?php } ?>
                    </h3>

                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th scope="row" valign="top">
                                    <?php _e('License Key', FCA_EOI_LOC); ?>
                                </th>
                                <td>
                                    <input id="fca_eoi_license_key" name="fca_eoi_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" /><br/>
                                    <label class="description" for="fca_eoi_license_key"><?php _e('Enter your license key', FCA_EOI_LOC); ?></label>
                                </td>
                            </tr>
                            <?php // if( false !== $license ) { ?>
                                <tr valign="top">
                                    <th scope="row" valign="top">
                                        <?php _e('Activate License', FCA_EOI_LOC); ?>
                                    </th>
                                    <td>
                                        <?php if($status == 'valid' ) { ?>
                                            <input type="submit" class="button-secondary" name="fca_eoi_license_deactivate" value="<?php _e('Deactivate License', FCA_EOI_LOC); ?>"/>
                                        <?php } elseif($status == 'expired' ) { ?>
                                            <input type="submit" class="button-secondary" name="fca_eoi_license_deactivate" value="<?php _e('Deactivate License', FCA_EOI_LOC); ?>"/>
                                        <?php } else { ?>
                                            <input type="submit" class="button-secondary" name="fca_eoi_license_activate" value="<?php _e('Activate License', FCA_EOI_LOC); ?>"/>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php //} ?>
                        </tbody>
                    </table>

                    <?php settings_fields( 'fca_eoi_license' ); ?>
                    <?php submit_button(); ?>

                </form>
			
            </div>
            <?php
    }
    
    
    function fca_eoi_register_option()
        {
            // creates our settings in the options table
            // register_setting('fca_eoi_license', 'fca_eoi_license_key', 'fca_eoi_sanitize_license');
            register_setting('fca_eoi_license', 'fca_eoi_license_key', array( $this , 'fca_eoi_sanitize_license' ));
        }
        
    function fca_eoi_sanitize_license( $new )
        {
            $old = get_option( 'fca_eoi_license_key' );
            if( $old && $old != $new ) {
                delete_option( 'fca_eoi_license_status' ); // new license has been entered, so must reactivate
            }
            return $new;
        }
        
   /************************************
    *    Activating the license        * 
    ************************************* */
    function fca_eoi_activate_license()
        {
            // listen for our activate button to be clicked
            if( isset( $_POST['fca_eoi_license_activate'] ) && isset( $_POST['fca_eoi_license_key'] ) && $_POST['fca_eoi_license_key'] ) {

                // run a quick security check
                if( ! check_admin_referer( 'fca_eoi_license_nonce', 'fca_eoi_license_nonce' ) ) {
                    return; // get out if we didn't click the Activate button
                }

                // retrieve the license from the database
                // $license = trim( get_option( 'fca_eoi_license_key' ) );    
                 $license = $_POST[ 'fca_eoi_license_key' ] ;
                // data to send in our API request
                $api_params = array(
                    'edd_action'=> 'activate_license',
                    'license' 	=> urlencode( $license ),
                    'item_name' => urlencode( FCA_EOI_ITEM_NAME ), // the name of our product in EDD
                    'url'       => urlencode( home_url() )
                );

                // Call the custom API.
                $response = wp_remote_get( add_query_arg( $api_params , FCA_EOI_STORE_URL ), array(
                    'timeout' => 30, 'sslverify' => false
                ) );

                // make sure the response came back okay
                if ( is_wp_error( $response ) )
                    return false;

                // decode the license data
                $license_data = json_decode( wp_remote_retrieve_body( $response ) );

                // Expired fix
                if ( $license_data->license == 'invalid' && isset($license_data->error) && $license_data->error == 'expired' ) {
                    $license_data->license = 'expired';   
                }
                // $license_data->license will be either "active" or "inactive"
                update_option( 'fca_eoi_license_status', $license_data->license );

            }
        }
      /************************************
       * Deactivating the license
       ************************************** */
    function fca_eoi_deactivate_license() {

        // listen for our activate button to be clicked
        if( isset( $_POST['fca_eoi_license_deactivate'] )  ) {

            // run a quick security check 
            if( ! check_admin_referer( 'fca_eoi_license_nonce', 'fca_eoi_license_nonce' ) ) {
                return; // get out if we didn't click the Activate button
            }

            // retrieve the license from the database
            $license = trim( get_option( 'fca_eoi_license_key' ) );

            // data to send in our API request
            $api_params = array( 
                'edd_action'=> 'deactivate_license', 
                'license'   => urlencode( $license ),
                'item_name' => urlencode( FCA_EOI_ITEM_NAME ), // the name of our product in EDD
                'url'       => urlencode( home_url() )
            );

            // Call the custom API.
            $response = wp_remote_get( add_query_arg( $api_params, FCA_EOI_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

            // make sure the response came back okay
            if ( is_wp_error( $response ) ) {
                return false;
            }

            // decode the license data
            $license_data = json_decode( wp_remote_retrieve_body( $response ) );
            if( $license_data->license == 'deactivated' || $license_data->license == 'failed' ) {
                delete_option( 'fca_eoi_license_status' );
            }

        }

    }
    
   /**
    * if user doesn't activate license yet, redirect to license page
    */ 
   function fca_eoi_plugin_not_activated () {
            
            ob_start();
            wp_redirect('edit.php?post_type=easy-opt-ins&page=easy-opt-ins-license', 301);
            exit();
            
        } 
}
