<?php

namespace Sharedrive;

class Listing {
	
	var $params = array();

	public function __construct( $args = array() ) {

		$defaults = array(
			'post_type' => 'file',
			'paged' => get_query_var( 'paged' ),
			'tax_query' => array(
				array(
					'taxonomy' => 'directory',
					'terms' => 'root',
					'field' => 'slug',
					'operator' => 'IN'
				)
			)
		);

		if ( 'user_file' === $this->getListingType() ) {
			$defaults['meta_query'] = array(
				'relation' => 'OR',
				array(
						'key' => 'sharedrive_file_privacy',
						'value' => 'public',
						'compare' => '='
					),
				array(
						'key' => 'sharedrive_file_privacy_users',
						'value' => serialize( strval( absint( get_current_user_id() ) ) ),
						'compare' => 'LIKE'
					)
			);
		}

		$this->query_args = wp_parse_args( $args, $defaults );


	}

	public function displayFileTable() {
		require_once SHAREDRIVE_DIR_PATH . 'templates/listing.php';
	}

	public function displayTabs() {
		require_once SHAREDRIVE_DIR_PATH . 'templates/listing-tabs.php';
	}

	public function getCurrentDirectories() {
		
		$root = get_term_by('slug', 'root', 'directory' );
		$parent = 0;
		
		if ( is_tax('directory') ) {
			$parent = get_queried_object()->term_id;
		}

		$directories = get_terms( array(
		    'taxonomy' => 'directory',
		    'hide_empty' => false,
		    'parent' => $parent,
		    'exclude' => $root->term_id
		));

		if ( ! empty( $directories ) ) {
			return $directories;
		}

		return array();

	}

	public function getListingType() {
		
		$listing_type = 'all_files';

		if ( ! empty( $_COOKIE['sharedrive_listing_view'] ) ) {
			$listing_type = $_COOKIE['sharedrive_listing_view'];
		}

		$active_listing_type_filter = filter_input( INPUT_GET, 'show', FILTER_SANITIZE_STRING );
		
		if ( ! empty( $active_listing_type_filter ) ) {
			$listing_type = $active_listing_type_filter;
		}

		return $listing_type;

	}

	public function isListingTypeUser() {

		if ( 'user_file' === $this->getListingType() ) {
			return true;
		
		} else{
			return false;
		}

		return false;
	}

	public function getTabsLink() {

		$file_url = get_post_type_archive_link('file');
		$all_shared_files_link = add_query_arg( 
				array( 'show' => 'all_files' ),
				$file_url
			);
		$user_files_link = add_query_arg( 
				array( 'show' => 'user_file' ), $file_url
			);

		return array(
				'all' => $all_shared_files_link,
				'user' => $user_files_link
			);
	}
}