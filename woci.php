<?php
/*
 * Plugin Name: WooCommerce Open Catalog Interface
 * Plugin URI: http://miguelmartinez.com/wordpress/plugin/woci/
 * Description: Open Catalog Interface integration for WooCommerce.
 * Version: 0.0.1
 * Author: Miguel Martinez
 * Author URI: http://miguelmartinez.com/
 */
if ( ! defined( 'ABSPATH' ) ) exit;

require_once( dirname(__FILE__) . '/base.php' );


final class WOCI {

	public function __construct() {

		define( 'WOCI_PLUGIN_FILE', __FILE__ );
		define( 'WOCI_VERSION', '1.0.0' );
		define( 'WOCI_DOMAIN', 'woci' );

		$this->handle =	$this->bootstrap();
	}

	private function bootstrap(){

		if( is_admin() && ! ( defined('DOING_AJAX') && DOING_AJAX ) ){
			require_once( dirname(__FILE__) . '/admin.php' );
			return new WOCI_Admin( $this );
		}

		require_once( dirname(__FILE__) . '/public.php' );
		return new WOCI_Public( $this );
	}
}

$GLOBALS['woci'] = new WOCI();

