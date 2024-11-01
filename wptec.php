<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Admin tables extra columns : Easy way to create custom columns on post, page & user admin tables 
 * Plugin URI:        https://wordpress.org/plugins/wptec/
 * Description:       Create custom sortable columns on wordPress admin User, Post, Page, comment and Media Tables.
 * Version:           1.0.0
 * Author:            javmah
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wptec
 * Domain Path:       /languages
*/
# বিসমিল্লাহ
# If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
# freemius Starts

if ( function_exists( 'wptec_fs' ) ) {
    wptec_fs()->set_basename( false, __FILE__ );
} else {
    
    if ( !function_exists( 'wptec_fs' ) ) {
        // Create a helper function for easy SDK access.
        function wptec_fs()
        {
            global  $wptec_fs ;
            
            if ( !isset( $wptec_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/includes/freemius/start.php';
                $wptec_fs = fs_dynamic_init( array(
                    'id'             => '11303',
                    'slug'           => 'wptec',
                    'premium_slug'   => 'wptec-professional',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_f1ad9975819f8fa43832c238b9257',
                    'is_premium'     => false,
                    'premium_suffix' => 'Professional',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'trial'          => array(
                    'days'               => 7,
                    'is_require_payment' => true,
                ),
                    'menu'           => array(
                    'slug'    => 'wptec',
                    'support' => false,
                    'parent'  => array(
                    'slug' => 'options-general.php',
                ),
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $wptec_fs;
        }
        
        // Init Freemius.
        wptec_fs();
        // Signal that SDK was initiated.
        do_action( 'wptec_fs_loaded' );
    }
    
    /**
     * Currently plugin version. Start at version 1.0.0 and use SemVer - https://semver.org Rename this for your plugin and update it as you release new versions.
     */
    define( 'WPTEC_VERSION', '1.0.0' );
    /**
     * The code that runs during plugin deactivation. This action is documented in includes/class-wptec-deactivator.php
     */
    function deactivate_wptec()
    {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-wptec-deactivator.php';
        Wptec_Deactivator::deactivate();
    }
    
    /**
     * deactivation hook.
     */
    register_deactivation_hook( __FILE__, 'deactivate_wptec' );
    /**
     * The core plugin class that is used to define internationalization, admin-specific hooks, and public-facing site hooks.
     */
    require plugin_dir_path( __FILE__ ) . 'includes/class-wptec.php';
    /**
     * Begins execution of the plugin. Since everything within the plugin is registered via hooks, then kicking off the plugin from this point in the file does. 
     * not affect the page life cycle.
     * @since    1.0.0
     */
    function run_wptec()
    {
        $plugin = new Wptec();
        $plugin->run();
    }
    
    run_wptec();
}

#************************************** বিসমিল্লাহ *****************************************
#************************************ Objective & HELP ***********************************
#1. Create A Banner
#2. Add all the Missing Features
#3. Add sorting code
#4.
#5. Separate FREE & PAID
#6.
#7.
#8.
#9.
#10