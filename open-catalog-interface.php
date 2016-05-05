<?php
/*
 * Plugin Name: PunchOut Open Catalog Interface for WooCommerce
 * Plugin URI: http://www.miguelmartinez.com/
 * Description: Simple plugin implementation of a PunchOut Catalog using Open Catalog Interface for WooCommerce.
 * Version: 0.0.1
 * Author: Miguel Martinez <punchout.oci@miguelmartinez.com>
 * Author URI: http://miguelmartinez.com
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class OpenCI_Result {

	public static function create( $success = true ) {
		return new OpenCI_Result( $success );
	}

	public function __construct( $success ) {
		$this->success = $success;
		$this->errors = array();
		$this->data = null;
	}

	public function failed(){
		$this->success = false;
		return $this;
	}

	public function add_error( $error ){
		$this->errors[] = $error;
		return $this;
	}

	public function with_data( $data ){
		$this->data = $data;
		return $this;
	}
}

class OpenCI_OpenCatalogInterface {

	const SETTING_COOKIE = 'wp_openci_hookurl';
	const SETTING_USERNAME = 'username';
	const SETTING_PASSWORD = 'password';
	const SETTING_HOOK_URL = 'HOOK_URL';
	const SETTING_REQUEST_METHOD = 'POST';
	const SETTING_GATEWAY = '/gateway/';

	public function __construct( ) {

		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
	}

	public function plugins_loaded(){

		if( is_admin() )
			return;

		add_action( 'init', array( $this, 'init' ) );

		add_filter( 'oci_get_cart_url', array( $this, 'woocommerce_get_cart_url' ) );
		add_action( 'oci_after_cart_table', array( $this, 'woocommerce_after_cart_table' ) );
	}

    private static function get_current_slug(){
        $array = explode( '?', $_SERVER['REQUEST_URI'], 2 );
        return (string)strtolower( $array[0] );
    }

    private static function authenticate( $username, $password ){

    	$result = OpenCI_Result::create();

		$username = sanitize_user( $username );
		$password = trim( $password );

		if ( empty( $username ) || empty( $password ) ) {

			if ( empty($username) )
				$result->add_error('The username is empty.');

			if ( empty($password) )
				$result->add_error('The password is empty.');

			return $result->failed();
		}

		$user = get_user_by( 'login', $username );

		if ( ! $user )
			return $result->failed()->add_error( "Invalid username: $username" );

		if ( ! wp_check_password( $password, $user->user_pass, $user->ID ) )
			return $result->failed()->add_error( "The password for the username $username is incorrect." );

		return $result->with_data( $user );
    }

    private static function generate_key(){
		session_start();
		return session_id();
    }

    private static function set_hookUrl(){

		$hook_url = self::_g( self::SETTING_HOOK_URL );
		$key = self::generate_key();
		
		setcookie( self::SETTING_COOKIE, $key , time() + DAY_IN_SECONDS, SITECOOKIEPATH, COOKIE_DOMAIN );
		
		set_transient( $key, $hook_url, DAY_IN_SECONDS );

		return true;
    }

    private static function get_hookUrl(){

    	if( empty( $_COOKIE[ self::SETTING_COOKIE ] ) )
    		return 'empty';

    	$key = $_COOKIE[ self::SETTING_COOKIE ];

		$hook_url = get_transient( $key );

		return $hook_url === false ? 'false' : $hook_url;
    }

    private static function shop(){
		header ('HTTP/1.1 302 Moved temporarily');
		header ('Location: ' . home_url( '/shop/' ) );
		exit();
    }

    //
    // We are waiting to see the gateway (self::SETTING_GATEWAY) route
    // come by. When we see the gateway route the following is done:
    //
    // 	* It must be a POST
    //  * Empty the cart
    //	* Authenticate the caller
    //	* Call set_hookUrl() to init session
    //	* Redirect called to catalog (/shop/)
    //
	public function init(){

		//
		// SRM Sever Setting - URL of Product Catalog:
		// 	https://catalog.com/gateway/
		//
		if( self::get_current_slug() != self::SETTING_GATEWAY )
			return;

		//
		// SRM Sever Setting - URL of Product Catalog:
		//	Must be a POST
		//
		if( $_SERVER['REQUEST_METHOD'] != self::SETTING_REQUEST_METHOD )
			self::shop();

		global $woocommerce;

		$woocommerce->cart->empty_cart();

		$username = self::_g( self::SETTING_USERNAME );
		$password = self::_g( self::SETTING_PASSWORD );

		$result = self::authenticate( $username, $password );
		if( ! $result->success ){
			setcookie( self::SETTING_COOKIE, '', time() - MINUTE_IN_SECONDS, SITECOOKIEPATH, COOKIE_DOMAIN );
			die( 'Intruder Alert!!<br/>' );
		}

		self::set_hookUrl();
			
		self::shop();
	}

	public function woocommerce_get_cart_url( $cart_url ){

		global $post;

		$hook_url = self::get_hookUrl();  

		$cart_page_id = absint( get_option('woocommerce_cart_page_id') );
		if( $cart_page_id == $post->ID && ! empty( $hook_url ) )
			return $hook_url;
		return $cart_url;
	}

	public function woocommerce_after_cart_table(){
		global $woocommerce;

		$i = 0;
		foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ){
			$i++;

			$post = $cart_item['data']->post;
			$price = get_post_meta( $post->ID, '_price', true );

			echo sprintf( '<input type="hidden" name="NEW_ITEM-CUST_FIELD1[%d]" value="" />', $i );
			echo sprintf( '<input type="hidden" name="NEW_ITEM-LONGTEXT_%d:132[]" value="" />', $i );
			echo sprintf( '<input type="hidden" name="NEW_ITEM-CUST_FIELD3[%d]" value="EXT" />', $i );
			echo sprintf( '<input type="hidden" name="NEW_ITEM-CUST_FIELD4[%d]" value="" />', $i );
			echo sprintf( '<input type="hidden" name="NEW_ITEM-CUST_FIELD5[%d]" value="" />', $i );
			echo sprintf( '<input type="hidden" name="NEW_ITEM-EXT_PRODUCT_ID[%d]" value="" />', $i );
			echo sprintf( '<input type="hidden" name="NEW_ITEM-EXT_CATEGORY_ID[%d]" value="" />', $i );
			echo sprintf( '<input type="hidden" name="NEW_ITEM-PRICE[%d]" value="%.3f" />', $i, $price );
			echo sprintf( '<input type="hidden" name="NEW_ITEM-SERVICE[%d]" value="" />', $i );
			echo sprintf( '<input type="hidden" name="NEW_ITEM-MANUFACTCODE[%d]" value="" />', $i );
			echo sprintf( '<input type="hidden" name="NEW_ITEM-LEADTIME[%d]" value="0" />', $i );
			echo sprintf( '<input type="hidden" name="NEW_ITEM-EXT_SCHEMA_TYPE[%d]" value="U135" />', $i );
			echo sprintf( '<input type="hidden" name="NEW_ITEM-DESCRIPTION[%d]" value="%s" />', $i, $post->post_title );
			echo sprintf( '<input type="hidden" name="NEW_ITEM-MATGROUP[%d]" value="60105416" />', $i );
			echo sprintf( '<input type="hidden" name="NEW_ITEM-MATNR[%d]" value="" />', $i );
			echo sprintf( '<input type="hidden" name="NEW_ITEM-VENDOR[%d]" value="1769797" />', $i );
			echo sprintf( '<input type="hidden" name="NEW_ITEM-VENDORMAT[%d]" value="574754" />', $i );
			echo sprintf( '<input type="hidden" name="NEW_ITEM-MANUFACTMAT[%d]" value="" />', $i );
			echo sprintf( '<input type="hidden" name="NEW_ITEM-UNIT[%d]" value="CT" />', $i );
			echo sprintf( '<input type="hidden" name="NEW_ITEM-PRICEUNIT[%d]" value="1.0" />', $i );
			echo sprintf( '<input type="hidden" name="NEW_ITEM-CURRENCY[%d]" value="USD" />', $i );
			echo sprintf( '<input type="hidden" name="NEW_ITEM-QUANTITY[%d]" value="%.3f" />', $i, $cart_item['quantity'] );
		}
	}

	private static function _g( $key ){
		return !empty( $_GET[ $key ] ) ? $_GET[ $key ] : self::_p( $key );
	}

	private static function _p( $key ){
		return !empty( $_POST[ $key ] ) ? $_POST[ $key ] : 'empty';
	}
}

$oci = new OpenCI_OpenCatalogInterface();

