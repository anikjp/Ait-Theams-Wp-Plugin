<?php
/*
Plugin Name: onixoni-plugin
Version: 0.1-alpha
Description: A Ait-Theam plagin
Author: ANIK BARUA
Author URI: YOUR SITE HERE
Plugin URI: PLUGIN SITE HERE
Text Domain: onixoni-plugin
Domain Path: /languages
*/

/**
 * Grab latest post title by an author!
 *
 * @param array $data Options for the function.
 * @return string|null Post title for the latest,  * or null if none.
 */
function my_awesome_func( $data ) {
    $posts = get_posts( array(
        'author' => $data['id'],
    ) );

    if ( empty( $posts ) ) {
        return null;
    }

    return $posts[0]->post_title;
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'myplugin/v1', '/author/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'my_awesome_func',
    ) );
} );



add_action( 'rest_api_init', function () {
    register_rest_route( 'myplugin/v1', '/ait-catagory/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_ait_catagory',
    ) );
} );


add_action( 'rest_api_init', function () {
    register_rest_route( 'myplugin/v1', '/ait-locations/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_ait_location',
    ) );
} );


add_action( 'rest_api_init', 'dt_register_api1_hooks' );
function dt_register_api1_hooks() {
	// Add the plaintext content to posts
	register_api_field(
		'ait-locations',
		'meta-data',
		array(
			'get_callback'    => 'get_ait_location',
		)
	);

}


/**
 * Grab latest post title by an author!
 *
 * @param array $data Options for the function.
 * @return string|null Post title for the latest,  * or null if none.
 */
function get_ait_location($object, $field_name, $request ) {

$prefix="ait-locations_category_"; //Prefix values to be fetched
$val= $prefix.$object['id'];  
$sql = "SELECT * FROM  `wp_options` WHERE  `option_name` = '$val' LIMIT 0 , 30";
 
   $result = mysql_query($sql) or die (mysql_error());
 
   $active_plugins_data="";
   while($rows = mysql_fetch_array($result)) {
     $active_plugins_data .= $rows["option_value"];
	
   }

$array = unserialize($active_plugins_data);
  return strip_tags($array );
}


add_action( 'rest_api_init', 'dt_syntext_remove_hooks' );
function dt_syntext_remove_hooks() {

    // Add the plaintext content to GET requests for individual posts
    register_api_field(
        'ait-item',
        'plaintext',
        array(
            'get_callback'    => 'dt_return_syntext_remove_content',
        )
    );
}

// Return plaintext content for posts
function dt_return_syntext_remove_content( $object, $field_name, $request ) {
    return strip_tags( html_entity_decode( $object['content']['rendered'] ) );
}