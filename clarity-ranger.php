<?php
/**
 * Plugin Name: Clarity Ranger
 * Plugin URI: #
 * Text Domain: clarity-ranger
 * Description: Audit your website and manage your links.
 * Version: 1.1
 * Author: Marc Moeller
 * Author URI: https://moellerseo.com/
 */

/**
 * This is just a sample plugin.
 */
defined( 'ABSPATH' ) or die( "Access denied !" );

/**
 *
 * @var define constants
 */
define( 'CLARITY_VERSION', '1.1' );
define( 'CLARITY_AUTHOR', 'Marc Moeller' );
define( 'CLARITY__AUTH_URL', 'https://moellerseo.com/' );

/**
 *
 * @var plugin URL
 */
define( "CLARITY_URL",  plugin_dir_url( __FILE__ ) );

// plugin path
/**
 *
 * @var plugin path
 */
define( "CLARITY_PATH", plugin_dir_path( __FILE__ ) );

/**
 *
 * @var basename clarity-ranger/clarity-ranger.php
 */
define( "CLARITY_BASENAME", plugin_basename( __FILE__ ) );

/**
 *
 * @var plugin main file - monster-seo-pro.php
 */
define( "CLARITY_FILE", __FILE__ );

/**
 *
 * @var text domain
 */
define( 'CLARITY_DOMAIN', 'clarity-ranger' );

// entry point
setup_clranger_plugin();

// Internal Links - Entry Point
//setup_internallinks_analyzer();

/**
 * init plugin either in admin mode or as frontend
 *
 * @since 3.4.0
 */
function setup_clranger_plugin () {
    /*
     * setup includes the required files but defers the actual loading to
     * plugins_loaded action. This ensures that tests are run in isolation but
     * required files included for testing. In test env, frontend files are
     * included by default as is_admin() returns false
     */
    require_once CLARITY_PATH . 'include/class-helper.php';
    require_once CLARITY_PATH . 'include/class-activation.php';

	 $int_link_incoming_list = new Clranger_activation();

    if ( is_admin() ) {
        // ajax runs in admin context
        require_once CLARITY_PATH . 'admin/class-ajax.php';

        require_once CLARITY_PATH . 'admin/class-admin.php';

/*
			  require_once CLARITY_PATH . 'admin/class_internallinks_analyzer.php';
  			$lAnalyzer = new Internal_Links_Analyzer();
			  $lAnalyzer->setup();
*/

        add_action( 'plugins_loaded', 'load_clranger_admin' );

    } else {



    }
	
	register_activation_hook( __FILE__, array( $int_link_incoming_list , 'int_link_incoming_list')  );
}

/**
 * load and setup Clranger_Admin class
 *
 * @ since 1.0.0
 */
function load_clranger_admin () {
    if ( get_option( 'clranger_test' ) ) {
        return;
    }
    $clranger_admin = new Clranger_Admin();
    $clranger_admin->setup();
}

/**
 * load and setup Sos_Frondend class
 *
 * @ since 1.0.0
 */
function load_clranger_frontend () {

}




