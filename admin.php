<?php
if ( ! defined( 'ABSPATH' ) ) exit;

require_once( WOCI_PLUGIN_SRC . '/menu.php' );

class WOCI_Admin extends WOCI_Base {

	public function __construct( $context ) {

		parent::__construct( $context );

		$this->plugins_loaded();
	}

	public function plugins_loaded(){

		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	public function init(){
	}


	public function admin_menu(){
		$this->menu = new WOCI_Menu( $this );
	}

	public function admin_init(){
	}
}
