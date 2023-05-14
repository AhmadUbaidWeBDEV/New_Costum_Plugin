<?php 
/**
 * Plugin Name: Testimonial for News 
 * Description: My First Plugin 
 * Version: 1.0
 * Author: Ahmad Ubaid
 **/
   
define('latestnews',__DIR__.'\include\front\class-tfw-testimonial.php');
define('adminfile',__DIR__.'\include\admin\class-tfw-admin.php');

NewMain::file_include_check();

class NewMain{

public function __construct(){
    add_action( 'init', [$this,'create_posttype']);
//    add_action('admin_menu', [$this,'add_news_type_submenu']);
    add_action( 'init', [$this,'register_news_type_taxonomy'] );
    //    add_action('init', [$this, 'register_custom_taxonomies']);
}


function create_posttype() {
    register_post_type( 'testimonial',
        array(
            'labels' => array(
                'name' => __( 'Testimonials' ),
                'singular_name' => __( 'Testimonial' )
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array( 'title', 'editor', 'thumbnail' )
        )
    );

}

public static function file_include_check(){

    if(!defined('latestnews')){
        die("Access Denied!");
    }else{
    
        require latestnews;
    }
    
    if(!defined('adminfile')){
        die("Access Denied!");
    }else{
    
        require adminfile;
    }
    
}


function register_news_type_taxonomy() {
    $labels = array(
        'name'                       => 'News Type',
        'singular_name'              => 'News Type',
        'search_items'               => 'Search News Types',
        'popular_items'              => 'Popular News Types',
        'all_items'                  => 'All News Types',
        'edit_item'                  => 'Edit News Type',
        'update_item'                => 'Update News Type',
        'add_new_item'               => 'Add New News Type',
        'new_item_name'              => 'New News Type Name',
        'separate_items_with_commas' => 'Separate news types with commas',
        'add_or_remove_items'        => 'Add or remove news types',
        'choose_from_most_used'      => 'Choose from the most used news types',
        'not_found'                  => 'No news types found',
        'menu_name'                  => 'News Type',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'news-type' ),
    );

    register_taxonomy( 'news_type', 'testimonial', $args );
}



}




new NewMain;


?>