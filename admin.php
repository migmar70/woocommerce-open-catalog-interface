<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WOCI_Admin extends WOCI_Base {

	public function __construct( $context ) {

		parent::__construct( $context );

		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
	}

	public function plugins_loaded(){

		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	public function init(){
	}

	var $menu_title = 'Open Catalog Interface';
	var $menu_id = 'oci_menu';

	public function admin_menu(){

		add_menu_page(								
			$this->menu_title,						// The value used to populate the browser's title bar when the menu page is active
			$this->menu_title,						// The text of the menu in the administrator's sidebar
			'administrator',						// What roles are able to access the menu
			$this->menu_id,							// The ID used to bind submenu items to this menu 
			array( $this, 'admin_menu_callback' )	// The callback function used to render this menu
		);

		add_submenu_page(
			$this->menu_id,							// The ID of the top-level menu page to which this submenu item belongs
			$this->menu_title,						// The value used to populate the browser's title bar when the menu page is active
			'Inbound Settings',						// The label of this submenu item displayed in the menu
			'administrator',						// What roles are able to access this submenu item
			$this->menu_id,							// The ID used to represent this submenu item
			array( $this, 'admin_menu_callback' )	// The callback function used to render the options for this submenu item
		);

		add_submenu_page(
			$this->menu_id,
			FIELDS_MENU_TITLE,
			FIELDS_MENU_TITLE,
			'administrator',
			FIELDS_MENU_ID,
			array( $this, 'admin_menu_callback' )
		);
	}

	var $option_group = 'oci_settings_group';
	var $option_name = 'oci_settings';

	public function admin_init(){

		$this->settings = OpenCI_Settings::create( 
			array(

				(object)array(
					'option_group' => 'oci_settings_group',
					'option_name' => 'oci_settings',
					'page' => $this->menu_id, 
					'id' => 'oci_settings_inbound', 
					'title' => 'Inbound Settings',
					'callback' => array( function(){
						return '<p class="description">Product Catalog Endpoint. The Endpoint is part of the URL configured in the "Catalog Application Call Structure" in the remote SRM Server.</p>';
					}),
					'fields' => array(
						(object)array(
							'id' => 'route',
							'label' => 'Endpoint',
							'default' => '/gateway/',
							'callback' => array( function($option_name, $setting_id, $setting_value, $args ){
								return sprintf('<input type="text" id="%s" name="%s[%s]" value="%s" />', $setting_id, $option_name, $setting_id, $setting_value );
							}),
							'callback_args' => array(
								'this could be null'
							)
						)
					)
				)/*,

				(object)array(
					'option_group' => 'oci_settings_group',
					'option_name' => 'oci_settings',
					'page' => $this->menu_id, 
					'id' => 'oci_settings_outbound', 
					'title' => 'Outboud Settings',
					'callback' => array( function(){
						return '<p class="description">Fields to return to remote SRM Server.</p>';
					}),
					'fields' => array(
						(object)array(
							'id' => 'description',
							'label' => 'Description',
							'callback' => array( function($option_name, $setting_id, $setting_value, $args ){
								return $setting_id;
							}),
							'callback_args' => array(
								'length' => 40
							)
						)
					)
				)*/

			)
		);
	}

	public function admin_menu_callback(){
		$this->admin_menu_render( $_GET['page'] );
	}

	public function admin_menu_render( $active_tab ){?>
		<div class="wrap">
			<div id="icon-themes" class="icon32"></div>
			<h2><?php echo $this->menu_title; ?></h2>
			<?php settings_errors(); ?>
			<form method="post" action="options.php"><?php
				
				settings_fields( $this->option_group );
				do_settings_sections( $this->menu_id );

				submit_button(); ?>
			</form>
		</div><?php
	}
}
