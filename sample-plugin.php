<?php
/**
 * @package Hello_Dolly
 * @version 1.6
 */
/*
Plugin Name: Ait-rest api
Plugin URI: http://www.onixoni.com/
Description: This is a plugin for Ait-Themes for WP Rest API V2.
Author: ANIK BARUA
Version: 1.01
*/



add_action('init', 'allow_localhost_api_requests', -100);
function allow_localhost_api_requests(){
    add_filter( 'http_request_host_is_external', '__return_true', 30 );
}


add_action('auth0_user_login', 'auth0UserLoginAction', 0, 5);

function auth0UserLoginAction($user_id, $user_profile, $is_new, $id_token, $access_token)
{
}


add_action('rest_api_init', 'dt_comments_syntext_remove_hooks');
function dt_comments_syntext_remove_hooks()
{
    
    // Add the plaintext content to GET requests for individual posts
    register_api_field('comment', 'plaintext', array(
        'get_callback' => 'dt_return_syntext_remove_content'
    ));
}

add_action('rest_api_init', 'dt_post_syntext_remove_hooks');
function dt_post_syntext_remove_hooks()
{
    
    // Add the plaintext content to GET requests for individual posts
    register_api_field('post', 'plaintext', array(
        'get_callback' => 'dt_return_syntext_remove_content'
    ));
}

add_action('rest_api_init', 'dt_syntext_remove_hooks');
function dt_syntext_remove_hooks()
{
    
    // Add the plaintext content to GET requests for individual posts
    register_api_field('ait-item', 'plaintext', array(
        'get_callback' => 'dt_return_syntext_remove_content'
    ));
}

// Return plaintext content for posts
function dt_return_syntext_remove_content($object, $field_name, $request)
{
    return strip_tags(html_entity_decode($object['content']['rendered']));
}



add_action('rest_api_init', 'dt_register_api2_hooks');

function dt_register_api2_hooks()

{

    // Add the plaintext content to posts

    register_api_field('ait-items', 'meta-data', array(

        'get_callback' => 'get_ait_items'

    ));

    

}





/**

 * Grab latest post title by an author!

 *

 * @param array $data Options for the function.

 * @return string|null Post title for the latest,  * or null if none.

 */

function get_ait_items($object, $field_name, $request)

{

    

    $prefix = "ait-items_category_"; //Prefix values to be fetched

    $val    = $prefix . $object['id'];

    $sql    = "SELECT * FROM  `wp_options` WHERE  `option_name` = '$val' LIMIT 0 , 30";

    

    $result = mysql_query($sql) or die(mysql_error());

    

    $active_plugins_data = "";

    while ($rows = mysql_fetch_array($result)) {

        $active_plugins_data .= $rows["option_value"];

        

    }

    

    $array = unserialize($active_plugins_data);

    return $array;

}







add_action('rest_api_init', 'dt_register_api1_hooks');

function dt_register_api1_hooks()

{

    // Add the plaintext content to posts

    register_api_field('ait-locations', 'meta-data', array(

        'get_callback' => 'get_ait_location'

    ));

    

}





/**

 * Grab latest post title by an author!

 *

 * @param array $data Options for the function.

 * @return string|null Post title for the latest,  * or null if none.

 */

function get_ait_location($object, $field_name, $request)

{

    

    $prefix = "ait-locations_category_"; //Prefix values to be fetched

    $val    = $prefix . $object['id'];

    $sql    = "SELECT * FROM  `wp_options` WHERE  `option_name` = '$val' LIMIT 0 , 30";

    

    $result = mysql_query($sql) or die(mysql_error());

    

    $active_plugins_data = "";

    while ($rows = mysql_fetch_array($result)) {

        $active_plugins_data .= $rows["option_value"];

        

    }

    

    $array = unserialize($active_plugins_data);

    return $array;

}



add_action('rest_api_init', 'dt_register_api3_hooks');
function dt_register_api3_hooks()
{
    // Add the plaintext content to posts
    register_api_field('ait-items-tags', 'meta-data_ait-items-tags', array(
        'get_callback' => 'get_ait_items_tags'
    ));
    
}


/**
 * Grab latest post title by an author!
 *
 * @param array $data Options for the function.
 * @return string|null Post title for the latest,  * or null if none.
 */
function get_ait_items_tags($object, $field_name, $request)
{
    
    $prefix = "ait-items_tags_"; //Prefix values to be fetched
    $val    = $prefix . $object['id'];
    $sql    = "SELECT * FROM  `wp_options` WHERE  `option_name` = '$val' LIMIT 0 , 30";
    
    $result = mysql_query($sql) or die(mysql_error());
    
    $active_plugins_data = "";
    while ($rows = mysql_fetch_array($result)) {
        $active_plugins_data .= $rows["option_value"];
        
    }
    
    $array = unserialize($active_plugins_data);
    return $array;
}

/**
 * Add REST API support to an already registered post type.
 */
add_action('init', 'my_custom_post_type_rest_support', 25);
function my_custom_post_type_rest_support()
{
    global $wp_post_types;
//be sure to set this to the name of your post type!
    if (isset($wp_post_types['demo-post-typdemo-pe'])) {
        $wp_post_types['demo-post-typdemo-pe']->show_in_rest          = true;
        $wp_post_types['demo-post-typdemo-pe']->rest_base             = 'demo-post-typdemo-pe';
        $wp_post_types['demo-post-typdemo-pe']->rest_controller_class = 'WP_REST_Posts_Controller';
    }
    
    //be sure to set this to the name of your post type!
    if (isset($wp_post_types['ait-item'])) {
        $wp_post_types['ait-item']->show_in_rest          = true;
        $wp_post_types['ait-item']->rest_base             = 'ait-item';
        $wp_post_types['ait-item']->rest_controller_class = 'WP_REST_Posts_Controller';
    }
	
 //be sure to set this to the name of your post type!
    if (isset($wp_post_types['ait-review'])) {
        $wp_post_types['ait-review']->show_in_rest          = true;
        $wp_post_types['ait-review']->rest_base             = 'ait-review';
        $wp_post_types['ait-review']->rest_controller_class = 'WP_REST_Posts_Controller';
    }
    if (isset($wp_post_types['ait-ad-space'])) {
        $wp_post_types['ait-ad-space']->show_in_rest          = true;
        $wp_post_types['ait-ad-space']->rest_base             = 'ait-ad-space';
        $wp_post_types['ait-ad-space']->rest_controller_class = 'WP_REST_Posts_Controller';
    }
    
    if (isset($wp_post_types['ait-member'])) {
        $wp_post_types['ait-member']->show_in_rest          = true;
        $wp_post_types['ait-member']->rest_base             = 'member';
        $wp_post_types['ait-member']->rest_controller_class = 'WP_REST_Posts_Controller';
    }
    if (isset($wp_post_types['ait-portfolio-item'])) {
        $wp_post_types['ait-portfolio-item']->show_in_rest          = true;
        $wp_post_types['ait-portfolio-item']->rest_base             = 'portfolio';
        $wp_post_types['ait-portfolio-item']->rest_controller_class = 'WP_REST_Posts_Controller';
    }
    if (isset($wp_post_types['ait-partner'])) {
        $wp_post_types['ait-partner']->show_in_rest          = true;
        $wp_post_types['ait-partner']->rest_base             = 'partner';
        $wp_post_types['ait-partner']->rest_controller_class = 'WP_REST_Posts_Controller';
    }
    if (isset($wp_post_types['ait-event'])) {
        $wp_post_types['ait-event']->show_in_rest          = true;
        $wp_post_types['ait-event']->rest_base             = 'ait-event';
        $wp_post_types['ait-event']->rest_controller_class = 'WP_REST_Posts_Controller';
    }
    
}




/**
 * Add REST API support to an already registered taxonomy.
 */
add_action('init', 'my_custom_taxonomy_rest_support', 25);
function my_custom_taxonomy_rest_support()
{
    global $wp_taxonomies;
    global $wp_term;
    
    //be sure to set this to the name of your taxonomy!
    
    if (isset($wp_taxonomies['ait-items'])) {
        $wp_taxonomies['ait-items']->show_in_rest          = true;
        $wp_taxonomies['ait-items']->rest_base             = 'ait-items';
        $wp_taxonomies['ait-items']->rest_controller_class = 'WP_REST_Terms_Controller';
    }
if (isset($wp_taxonomies['ait-review'])) {
        $wp_taxonomies['ait-review']->show_in_rest          = true;
        $wp_taxonomies['ait-review']->rest_base             = 'ait-review';
        $wp_taxonomies['ait-review']->rest_controller_class = 'WP_REST_Terms_Controller';
    }
    
    if (isset($wp_taxonomies['ait-locations'])) {
        $wp_taxonomies['ait-locations']->show_in_rest          = true;
        $wp_taxonomies['ait-locations']->rest_base             = 'ait-locations';
        $wp_taxonomies['ait-locations']->rest_controller_class = 'WP_REST_Terms_Controller';
    }
    
    if (isset($wp_taxonomies['ait-items_tags'])) {
        $wp_taxonomies['ait-items_tags']->show_in_rest          = true;
        $wp_taxonomies['ait-items_tags']->rest_base             = 'ait-items_tags';
        $wp_taxonomies['ait-items_tags']->rest_controller_class = 'WP_REST_Terms_Controller';
    }

    if (isset($wp_taxonomies['category_media'])) {
        $wp_taxonomies['category_media']->show_in_rest          = true;
        $wp_taxonomies['category_media']->rest_base             = 'category_media';
        $wp_taxonomies['category_media']->rest_controller_class = 'WP_REST_Terms_Controller';
    }
    
    if (isset($wp_taxonomies['ait-spaces'])) {
        $wp_taxonomies['ait-spaces']->show_in_rest          = true;
        $wp_taxonomies['ait-spaces']->rest_base             = 'ait-spaces';
        $wp_taxonomies['ait-spaces']->rest_controller_class = 'WP_REST_Terms_Controller';
    }
    
    //if ( isset( $wp_taxonomies['category_media'] ) ) {
    //        $wp_taxonomies['category_media']->show_in_rest = true;
    //        $wp_taxonomies['category_media']->rest_base = 'category_media';
    //        $wp_taxonomies['category_media']->rest_controller_class = 'WP_REST_Terms_Controller';
    //    }
    
}


//
function slug_get_post_meta_cb($object, $field_name, $request)
{
    return get_post_meta($object['id'], $field_name);
}

function slug_update_post_meta_cb($value, $object, $field_name)
{
    return update_post_meta($object['id'], $field_name, $value);
}

add_action('rest_api_init', function()
{
    register_api_field('ait-item', '_ait-item_item-data', $args = array(
        'get_callback' => 'slug_get_post_meta_cb',
        'update_callback' => 'slug_update_post_meta_cb',
        'schema' => null
    ));
    
});

add_action('rest_api_init', function()
{
    register_api_field('ait-item', '_ait-item_item-extension', $args = array(
        'get_callback' => 'slug_get_post_meta_cb',
        'update_callback' => 'slug_update_post_meta_cb',
        'schema' => null
    ));
    
});


add_action('rest_api_init', function()
{
    register_api_field('ait-item', '_ait-item_review-options', $args = array(
        'get_callback' => 'slug_get_post_meta_cb',
        'update_callback' => 'slug_update_post_meta_cb',
        'schema' => null
    ));
    
});

add_action('rest_api_init', function()
{
    register_api_field('ait-item', 'rating_count', $args = array(
        'get_callback' => 'slug_get_post_meta_cb',
        'update_callback' => 'slug_update_post_meta_cb',
        'schema' => null
    ));
    
});

add_action('rest_api_init', function()
{
    register_api_field('ait-item', 'rating_mean_rounded', $args = array(
        'get_callback' => 'slug_get_post_meta_cb',
        'update_callback' => 'slug_update_post_meta_cb',
        'schema' => null
    ));
    
});


add_action('rest_api_init', function()
{
    register_api_field('ait-ad-space', '_ait-ad-space_space', $args = array(
        'get_callback' => 'slug_get_post_meta_cb',
        'update_callback' => 'slug_update_post_meta_cb',
        'schema' => null
    ));
});

add_action('rest_api_init', function()
{
    register_api_field('ait-member', '_ait-member_member', $args = array(
        'get_callback' => 'slug_get_post_meta_cb',
        'update_callback' => 'slug_update_post_meta_cb',
        'schema' => null
    ));
});

add_action('rest_api_init', function()
{
    register_api_field('ait-partner', '_ait-partner_partner-options', $args = array(
        'get_callback' => 'slug_get_post_meta_cb',
        'update_callback' => 'slug_update_post_meta_cb',
        'schema' => null
    ));
});

add_action('rest_api_init', function()
{
    register_api_field('ait-portfolio-item', '_ait-portfolio-item_portfolio-item', $args = array(
        'get_callback' => 'slug_get_post_meta_cb',
        'update_callback' => 'slug_update_post_meta_cb',
        'schema' => null
    ));
});

add_action('rest_api_init', function()
{
    register_api_field('ait-event', '_ait-event_event-data', $args = array(
        'get_callback' => 'slug_get_post_meta_cb',
        'update_callback' => 'slug_update_post_meta_cb',
        'schema' => null
    ));
});



add_action('rest_api_init', function()
{
    register_rest_route('test/v1', '/get-all-post-ids/', array(
        'methods' => 'GET',
        'callback' => 'dt_get_all_post_ids'
    ));
});


// Return all post IDs
function dt_get_all_post_ids()
{
    $args = array(
        'numberposts' => -1,
        'post_type' => 'ait-item',
        'fields' => 'ids'
    );
    
    
    $all_post_ids = get_posts($args);
    // cache for 2 hours
    //set_transient( 'dt_all_post_ids', $all_post_ids, 60*60*2 );
    
    
    return $all_post_ids;
}