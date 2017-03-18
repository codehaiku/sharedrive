<?php
header('Content-type: application/json');

$taxonomy = 'directory';
$current_user = wp_get_current_user();
$user_root_directory = $current_user->user_login;

// Check if the directory already exists
$user_directory = get_term_by( 'slug', $user_root_directory, $taxonomy );

if ( ! $user_directory ) {
	wp_insert_term( $user_root_directory, $taxonomy );
}

die();

$parent_term_id = $user_root_directory;



wp_insert_term(
	'Apple', // the term 
	$taxonomy, // the taxonomy
	array(
		'parent'=> absint( $parent_term_id )
	)
);


die();