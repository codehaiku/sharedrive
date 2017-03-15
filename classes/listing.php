<?php

namespace Sharedrive;

class Listing {
	
	var $params = array();

	public function __construct( $args = array() ) {
		$this->params = $args();
	}
	
	public function render() {

	}

	public function getListingType() {

	}
}