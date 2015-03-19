<?php

class WOCI_Base {

	protected $context = null;

	public function __construct( $context ) {
		$this->context = $context;
	}

	protected function t( $message ){
		return __( $message, WOCI_DOMAIN );
	}

	protected function e( $message ) {
		echo $this->t( $message );
	}

	protected function debug( $message ) {
		$this->context->debug( $message );
	}
}
