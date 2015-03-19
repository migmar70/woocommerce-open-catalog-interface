<?php
/*
 * Plugin Name: RmS Open Catalog Interface for WooCommerce
 * Plugin URI: http://miguelmartinez.com/wordpress/plugin/woci/
 * Description: rMode Software Open Catalog Interface for WooCommerce.
 * Version: 0.0.1
 * Author: Miguel Martinez
 * Author URI: http://miguelmartinez.com/
 */
if ( ! defined( 'ABSPATH' ) ) 
	exit;

require_once( dirname(__FILE__) . '/base.php' );
require_once( dirname(__FILE__) . '/logger.php' );

final class WOCI {

	public function __construct() {

		define( 'WOCI_PLUGIN_DIR', dirname( __FILE__ ) );
		define( 'WOCI_PLUGIN_SRC', WOCI_PLUGIN_DIR );
		define( 'WOCI_VERSION', '1.0.0' );
		define( 'WOCI_DOMAIN', 'woci' );

		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
	}

	public function plugins_loaded(){

		$this->log = new WOCI_Logger();
		$this->plugin =	$this->bootstrap();
	}

	private function bootstrap(){

		if( is_admin() && ! ( defined('DOING_AJAX') && DOING_AJAX ) ){
			require_once( WOCI_PLUGIN_SRC . '/admin.php' );
			return new WOCI_Admin( $this );
		}

		require_once( WOCI_PLUGIN_SRC . '/public.php' );
		return new WOCI_Public( $this );
	}

}

$GLOBALS['woci'] = new WOCI();

function WOCI_activation_hook(){
	require( WOCI_PLUGIN_SRC . 'activation.php' );	
}

function WOCI_deactivation_hook(){
	require( WOCI_PLUGIN_SRC . 'deactivation.php' );	
}

register_activation_hook( __FILE__, 'WOCI_activation_hook' );
register_deactivation_hook( __FILE__, 'WOCI_deactivation_hook' );


