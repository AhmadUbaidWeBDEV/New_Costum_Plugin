<?php 
/**
 * Plugin Name: Testimonial for Wordpress 
 * Description: My First Plugin 
 * Version: 1.0
 * Author: Ahmad Ubaid
 **/
$news_id='';

if (isset($_GET['newsid'])) {
    $GLOBALS['news_id'] = $_GET['newsid'];
class dispProdut{
    
    public function __construct() {

        add_shortcode('testimonial_news_description', [$this,'testimonial_news_description_shortcode']);
        

    }
    public function testimonial_news_description_shortcode($get_news) {
        
        $query = new WP_Query(array(
            'post_type' => 'testimonial',
            'p' => $GLOBALS['news_id'], // Specify the news ID to retrieve
            'posts_per_page' => 1,
        ));
        $output = '';
        if ($query->posts) {
            foreach ($query->posts as $post) {
                setup_postdata($post);
                $postid=$post->ID;
                $news_description = get_post_meta($post->ID, 'News_description', true); 
                $output .= '<div data-para-id="'.$postid.'" class="testimonial">';
                $output .= '<p>' . $post->ID . '</p>';
                $output .= '<img width="400px" src="'.get_the_post_thumbnail_url($post->ID,'full').'" alt="Testimonial Image">';
                $output .= '<h3>' . $post->post_title . '</h3>';  
                $output .= '<p  data-para-id="'.$postid.'"  class="para">' . $news_description . '</p>';            
                $output .= '<div><a href="http://localhost/dashboard/wordpress/?page_id=31" >Back</a></div>';
                $output .= '</div>';
            
            
            }        
        } else {
            // No news found with the provided ID
            echo 'No news found.';
        }
    
        wp_reset_postdata();
        return $output;   
    }

}
new dispProdut; 
}else{ 

 class front{
    
    

    public function __construct() {

    //    add_shortcode('Recent_testimonial_news_description', [$this,'Recent_testimonial_news_description_shortcode']);
        add_shortcode('testimonial_news_description', [$this,'testimonial_news_description_shortcode']);
        add_action( 'wp_enqueue_scripts', [$this,'addJSFile'] );
        add_action('wp_ajax_my_ajax_callback', [$this,'my_ajax_callback']);
        add_action('wp_footer', [$this,'display_product_in_footer']);
    }

    public function testimonial_news_description_shortcode($get_news) {
        $get_news = shortcode_atts(array('posts_per_page' => 10,), $get_news, 'testimonial_news_description');
        $posts_per_page = absint($get_news['posts_per_page']);
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    
        // Define the taxonomy query if the taxonomy parameter is set
        $taxonomy_query = array();
        if (isset($_POST['news_type']) && is_array($_POST['news_type'])) {
            $taxonomy_query[] = array(
                'taxonomy' => 'news_type',
                'field' => 'slug',
                'terms' => $_POST['news_type']
            );
        }
    
        $query_args = array(
            'post_type' => 'testimonial',
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,
            'tax_query' => $taxonomy_query // Include the taxonomy query
        );
    
        $query = new WP_Query($query_args);
    
        $output = '';
        // Add the taxonomy checkboxes
        $taxonomy_terms = get_terms(array('taxonomy' => 'news_type', 'hide_empty' => false));
    
        $output .= '<form style="position:absolute; left:30px;top:410px" method="POST" action="">';
        $output .= '<p>News Type:</p>';
    
        foreach ($taxonomy_terms as $term) {
            $output .= '<input type="checkbox" name="news_type[]" value="' . $term->name . '"> ' . $term->name . '<br>';
        }
    
        $output .= '<input class="filter" type="submit" value="Apply Filter">';
        $output .= '</form>';
    
        // Display the selected news types
        if (isset($_POST['news_type']) && is_array($_POST['news_type'])) {
            $output .= '<h5 style="display:inline; font-weight:normal;">Selected news :                 </h5>';
            foreach ($_POST['news_type'] as $selected_news_type) {
                $output .= '<h5 style="display:inline;font-weight:lighter;">' . $selected_news_type . ', </h5>';
            }
        }
    
        if ($query->posts) {
            $output .= '<div style="border 1px solid black;display:flex; justify-content:start">';
            $output .= '<div style="">'; // Styling for Latest News
            if(!(isset($_POST['news_type']))){
            $output .= '<h2 class="ltest">Latest News</h2>';
            }
            foreach ($query->posts as $post) {
                setup_postdata($post);
    
                $postid = $post->ID;
                $news_description = get_post_meta($post->ID, 'News_description', true);
    
                $length = strlen($news_description);
                $calfewtxt = ceil($length / 4);
                $fewtext = substr($news_description, 0, $calfewtxt);
    
                $output .= '<div data-para-id="' . $postid . '"style="margin-bottom:60px; " class="testimonial">';
                $output .= '<a style="text-decoration:none;color:black;" href="http://localhost/dashboard/wordpress/?page_id=31&&newsid=' . $postid . '"><img width="300px" height="170px" src="' . get_the_post_thumbnail_url($post->ID, 'full') . '" alt="Testimonial Image"></a>';
                $output .= '<a style="text-decoration:none;color:black;" href="http://localhost/dashboard/wordpress/?page_id=31&&newsid=' . $postid. '"><h5 style="font-weight:normal;">' . $post->post_title . '</h5></a>';
                $output .= '<a class="showpara" style="color:black; font-size: smaller; width:440px;"  data-para-id="' . $postid . '" href="http://localhost/dashboard/wordpress/?page_id=31&&newsid=' . $postid . '"><p style=" width:440px;" >' . $fewtext . '</p></a>';
                $output .= '<button class="show-para-btn" data-para-id="' . $postid . '" >Readmore</button>';
                $output .= '<button class="hide-para-btn"  data-para-id="' . $postid . '" >Read Less</button>';
                $output .= '<div class="para" style="font-size: smaller; width:440px;"  data-para-id="' . $postid . '" >' . $news_description . '</div>';
                $output .= '</div>';
                }
                
                $output .= '</div>'; // End of Latest News styling
                
                // Recent News styling
                
                $output .= '<div style=" margin-left: 190px;">';
                if(!(isset($_POST['news_type']))){
                $output .= '<h4 style="margin-top:60px" class="rcnt">Recent News</h4>';
                }
                $recent_news_output = ''; // Variable to store recent news output
                $recent_news_counter = 0; // Counter to track the number of recent news added
                
                foreach ($query->posts as $post) {
                    setup_postdata($post);
                
                    $postid = $post->ID;
                    $news_description = get_post_meta($post->ID, 'News_description', true);
                
                    $length = strlen($news_description);
                    $calfewtxt = ceil($length / 4);
                    $fewtext = substr($news_description, 0, $calfewtxt);
                
                    if (($recent_news_counter < 5 && $paged == 1)&&(!(isset($_POST['news_type'])))) {
                         $recent_news_output .= '<div data-para-id="' . $postid . '"style="margin-bottom:30px; " class="resenttestimonial">';
                         $recent_news_output .= '<a style="text-decoration:none;color:black;" href="http://localhost/dashboard/wordpress/?page_id=31&&newsid=' . $postid . '"><img width="170px" height="70px" src="' . get_the_post_thumbnail_url($post->ID, 'full') . '" alt="Testimonial Image"></a>';
                         $recent_news_output .= '<a style="text-decoration:none;color:black;" href="http://localhost/dashboard/wordpress/?page_id=31&&newsid=' . $postid . '"><h6  style=" font-size:14px; font-weight:normal;">' . $post->post_title . '</h6></a>';
                         $recent_news_output .= '<a class="showpara1" style="color:black; font-size: 11px; width:300px;"  data-para-id="' . $postid . '" href="http://localhost/dashboard/wordpress/?page_id=31&&newsid=' . $postid . '"><p style=" width:300px;" >' . $fewtext . '</p></a>';
                         $recent_news_output .= '<button style=" font-size: 11px;" class="show-para-btn1" data-para-id="' . $postid . '" >Readmore</button>';
                         $recent_news_output .= '<button class="hide-para-btn1"  data-para-id="' . $postid . '" >Read Less</button>';
                         $recent_news_output .= '<div class="para1" style="font-size:11px; width:300px;"  data-para-id="' . $postid . '" >' . $news_description . '</div>';
                         $recent_news_output .= '</div>';
                         $recent_news_counter++;
                    }
                }                
                $output .= $recent_news_output;
                $output .= '</div>'; // End of Recent News styling
                $output .= '</div>'; // End of flex container
                $output .= '</div>'; // End of container div
                
        $pagination = paginate_links(array(
            'total' => $query->max_num_pages, 
            'current' => $paged, 
            'prev_text' => __('&laquo; Previous'), 
            'next_text' => __('Next &raquo;'), 
        ));

        if ($pagination) {
            $output .= '<div style="display:flex;justify-content:center;" class="pagination">' . $pagination . '</div>';
        }
                wp_reset_postdata();
                
                return $output;
                }
                
                
    
    
    
            }


           public function display_product_in_footer() {
            // Get a random product
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => 5,
                'orderby'        => 'rand',
            );
            $products = new WP_Query($args);
        
            // Check if a product is found
            if ($products->have_posts()) {
                while ($products->have_posts()) {
                    $products->the_post();
                    global $product;
        
                    // Display the product information
                    echo '<div>';
                    echo '<h2>' . get_the_title() . '</h2>';
        
                    // Check if the product is a variable product
                    if ($product->is_type('variable')) {
                        $variations = $product->get_available_variations(); // Get the available variations
        
                        // Prepare an array to hold variation options grouped by attributes
                        $variation_options = array();
        
                        // Loop through variations to gather options
                        foreach ($variations as $variation) {
                            $variation_attributes = $variation['attributes'];
        
                            // Loop through variation attributes
                            foreach ($variation_attributes as $attribute_name => $attribute_value) {
                                // Add attribute value to corresponding attribute name array
                                if (!isset($variation_options[$attribute_name])) {
                                    $variation_options[$attribute_name] = array();
                                }
                                if (!in_array($attribute_value, $variation_options[$attribute_name])) {
                                    $variation_options[$attribute_name][] = $attribute_value;
                                }
                            }
                        }
        
                        // Display the variation options
                        foreach ($variation_options as $attribute_name => $attribute_values) {
                            echo '<p>' . $attribute_name . ': </p>';
                            echo '<select>';
                            foreach ($attribute_values as $attribute_value) {
                                echo '<option value="' . $attribute_value . '">' . $attribute_value . '</option>';
                            }
                            echo '</select>';
                        }
                    }
        
                    echo '<div class="product-image">' . $product->get_image() . '</div>'; // Display the product image
                    echo '<p>' . $product->get_price_html() . '</p>';
                    // Add any other product details you want to display
                    echo '</div>';
                }
                wp_reset_postdata();
            }
        }
        
//        add_action('wp_footer', 'display_product_in_footer');
        
        
//        add_action('wp_footer', 'display_product_in_footer');
        
        
       // add_action('wp_footer', 'display_product_in_footer');
        
            
     //       add_action('wp_footer', 'display_product_in_footer');
            
            
//            add_action('wp_footer', 'display_product_in_footer');
            

    public function my_ajax_callback() {

    
    }
    


    public function addJSFile() {
        wp_enqueue_script('front', plugins_url('front.js', __FILE__), array('jquery'), '4.0');
        wp_localize_script('front', 'my_ajax_obj', array('ajax_url' => admin_url('admin-ajax.php') ));    
        wp_enqueue_style('testimonial-style', plugins_url('testimonial.css', __FILE__));
    }
}

new front;
}

/*
   public function testimonial_news_description_shortcode($get_news) {
        $get_news = shortcode_atts(array('posts_per_page' => 10,), $get_news, 'testimonial_news_description');
        $posts_per_page = absint($get_news['posts_per_page']); 
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    
        // Define the taxonomy query if the taxonomy parameter is set
        
        $taxonomy_query = array() ; 
        if (isset($_POST['news_type']) && is_array($_POST['news_type'])) {
            $taxonomy_query[] = array(
                'taxonomy' => 'news_type',
                'field' => 'slug',
                'terms' => $_POST['news_type']
            );
        }        
        $query_args = array(
            'post_type' => 'testimonial',
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,
            'tax_query' => $taxonomy_query // Include the taxonomy query
        );
    
        $query = new WP_Query($query_args);
        
        $output = ''; 
    // Add the taxonomy checkboxes
$taxonomy_terms = get_terms(array('taxonomy' => 'news_type', 'hide_empty' => false));

$output .= '<form style="position:absolute; left:30px;top:260px" method="POST" action="">';
$output .= '<p>News Type:</p>';

foreach ($taxonomy_terms as $term) {
    $output .= '<input type="checkbox" name="news_type[]" value="' . $term->name . '"> ' . $term->name . '<br>';

}
$output .= '<input class="filter" type="submit" value="Apply Filter">';
$output .= '</form>';
  // Display the selected news types
  if (isset($_POST['news_type']) && is_array($_POST['news_type'])) {
    $output .= '<h5 style="display:inline; font-weight:lighter;">Selected News Types:</h5>';
    foreach ($_POST['news_type'] as $selected_news_type) {
        $output .= '<h5 style="display:inline;font-weight:lighter;">      ' . $selected_news_type . ', </h5>';
    }
}


        if ($query->posts){
            $output .= '<h2>Latest News</h2>';
            foreach ($query->posts as $post) {
                setup_postdata($post);

                $postid = $post->ID;
                $news_description = get_post_meta($post->ID, 'News_description', true); 
    
                $length = strlen($news_description);
                $calfewtxt = ceil($length / 4);
                $fewtext = substr($news_description, 0, $calfewtxt);
                
                $output .= '<div data-para-id="'.$postid.'" class="testimonial">';
            //    $output .= '<h3>Latest News</h3>';
                $output .= '<a style="text-decoration:none;color:black;" href="http://localhost/dashboard/wordpress/?page_id=31&&newsid='.$postid.'"><img width="300px" height="170px" src="'.get_the_post_thumbnail_url($post->ID,'full').'" alt="Testimonial Image"></a>';
                $output .= '<a style="text-decoration:none;color:black;" href="http://localhost/dashboard/wordpress/?page_id=31&&newsid='.$postid.'"><h5 style="font-weight:normal;">' . $post->post_title . '</h5></a>';
                $output .= '<a class="showpara" style="color:black; font-size: smaller; width:440px;"  data-para-id="'.$postid.'" href="http://localhost/dashboard/wordpress/?page_id=31&&newsid='.$postid.'"><p style=" width:440px;" >' . $fewtext . '</p></a>'; 
                $output .= '<button class="show-para-btn" data-para-id="'.$postid.'" >Readmore</button>';  
                $output .= '<button class="hide-para-btn"  data-para-id="'.$postid.'" >Read Less</button>'; 
                $output .= '<div class="para" style="font-size: smaller; width:440px;"  data-para-id="'.$postid.'" >' . $news_description . '</div>';
                $output .= '</div>';
            
            
            }

            


        }else{
            $output .= '<p>No testimonials found.</p>';
            wp_reset_postdata();
        }
    
    
        $pagination = paginate_links(array(
            'total' => $query->max_num_pages, 
            'current' => $paged, 
            'prev_text' => __('&laquo; Previous'), 
            'next_text' => __('Next &raquo;'), 
        ));

        if ($pagination) {
            $output .= '<div class="pagination">' . $pagination . '</div>';
        }
    
        wp_reset_postdata();
        return $output;
    
    
    }

*/




?>