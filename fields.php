<?php
class WOCI_Field {

	public function __construct( $name, $length ) {
		$this->name = $name;
		$this->length = $length;
	}
}

$OCI_FIELDS = array(
	new WOCI_Field("NEW_ITEM-DESCRIPTION", 		40),
	new WOCI_Field("NEW_ITEM-MATNR", 				40),
	new WOCI_Field("NEW_ITEM-QUANTITY", 			15),
	new WOCI_Field("NEW_ITEM-UNIT", 				3),
	new WOCI_Field("NEW_ITEM-PRICE", 				15),
	new WOCI_Field("NEW_ITEM-CURRENCY", 			5),
	new WOCI_Field("NEW_ITEM-PRICEUNIT", 			5),
	new WOCI_Field("NEW_ITEM-LEADTIME", 			5),
	new WOCI_Field("NEW_ITEM-LONGTEXT_:132", 		0),
	new WOCI_Field("NEW_ITEM-VENDOR", 			10),
	new WOCI_Field("NEW_ITEM-VENDORMAT", 			40),
	new WOCI_Field("NEW_ITEM-MANUFACTCODE", 		10),
	new WOCI_Field("NEW_ITEM-MANUFACTMAT", 		40),
	new WOCI_Field("NEW_ITEM-MATGROUP", 			10),
	new WOCI_Field("NEW_ITEM-SERVICE", 			1),
	new WOCI_Field("NEW_ITEM-CONTRACT", 			10),
	new WOCI_Field("NEW_ITEM-CONTRACT_ITEM", 		5),
	new WOCI_Field("NEW_ITEM-EXT_QUOTE_ID", 		35),
	new WOCI_Field("NEW_ITEM-EXT_QUOTE_ITEM", 	10),
	new WOCI_Field("NEW_ITEM-EXT_PRODUCT_ID", 	40),
	new WOCI_Field("NEW_ITEM-ATTACHMENT", 		255),
	new WOCI_Field("NEW_ITEMATTACHMENT_TITLE", 	255),
	new WOCI_Field("NEW_ITEMATTACHMENT_PURPOSE", 	1),
	new WOCI_Field("NEW_ITEMEXT_SCHEMA_TYPE", 	10),
	new WOCI_Field("NEW_ITEMEXT_CATEGORY_ID", 	60),
	new WOCI_Field("NEW_ITEM-EXT_CATEGORY", 		40),
	new WOCI_Field("NEW_ITEM-SLD_SYS_NAME", 		60),
	new WOCI_Field("NEW_ITEM-CUST_FIELD1", 		10),
	new WOCI_Field("NEW_ITEM-CUST_FIELD2", 		10),
	new WOCI_Field("NEW_ITEM-CUST_FIELD3", 		10),
	new WOCI_Field("NEW_ITEM-CUST_FIELD4", 		20),
	new WOCI_Field("NEW_ITEM-CUST_FIELD5 ",		50)
);