<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class WOCI_Menu {

	public function __construct( $container ) {

		$this->container = $container;
		$this->browser_title = 'Open Catalog Interface for WooCommerce'; 
		$this->menu_title = 'OCI 4 WooC';
		$this->id = 'woci_menu';

		add_menu_page(								
			$this->browser_title,				// The value used to populate the browser's title bar when the menu page is active
			$this->menu_title,					// The text of the menu in the administrator's sidebar
			'administrator',					// What roles are able to access the menu
			$this->id,							// The ID used to bind submenu items to this menu 
			array( $this, 'callback' )			// The callback function used to render this menu
		);

		add_submenu_page(
			$this->id,							// The ID of the top-level menu page to which this submenu item belongs
			$this->browser_title,				// The value used to populate the browser's title bar when the menu page is active
			'Settings',							// The label of this submenu item displayed in the menu
			'administrator',					// What roles are able to access this submenu item
			$this->id,							// The ID used to represent this submenu item
			array( $this, 'callback' )	// The callback function used to render the options for this submenu item
		);

		add_submenu_page(
			$this->id,							// The ID of the top-level menu page to which this submenu item belongs
			$this->browser_title,				// The value used to populate the browser's title bar when the menu page is active
			'Se',							// The label of this submenu item displayed in the menu
			'administrator',					// What roles are able to access this submenu item
			$this->id.'ff',							// The ID used to represent this submenu item
			array( $this, 'callback' )	// The callback function used to render the options for this submenu item
		);
	}

	public function callback() {
		$this->render( $_GET['page'] );
	}

	private function render( $page ) {?>
		<div class="wrap">
			<div id="icon-themes" class="icon32"></div>
			<h2><?php echo $this->menu_title; ?></h2>
			<?php settings_errors(); ?>
			<form method="post" action="options.php"><?php
				
				//settings_fields( $this->option_group );
				//do_settings_sections( $this->menu_id );

				submit_button(); ?>
			</form>
		</div><?php
	}
}
