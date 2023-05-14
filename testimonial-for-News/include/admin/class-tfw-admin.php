<?php


//include './wp-content/plugins/testimonial-for-News/class-tfw-testimonial-for-wordpress.php';

class Admin  {

    public function __construct() {

        add_filter( 'enter_title_here', [$this,'change_title_text'] );
        add_action( 'admin_init', [$this,'remove_testimonial_media_support'] );
        add_action( 'add_meta_boxes', [$this,'add_testimonial_meta_box'] );
        add_action( 'save_post_testimonial', [$this,'save_testimonial_meta_box_data'] );
        add_action( 'admin_menu', [$this,'my_custom_submenu_page'] );
        add_action( 'admin_init', [$this,'my_custom_settings'] );


    }




   public function remove_testimonial_media_support() {
        remove_post_type_support( 'testimonial', 'editor' );
    }
    public function change_testimonial_title_placeholder_text( $title ) {
        $screen = get_current_screen();
        if ( 'testimonial' == $screen->post_type ) {
            $title = 'Enter news Title';
        }
        return $title;
    }
    
    
    public function add_testimonial_meta_box() {
        add_meta_box(
            'testimonial_meta_box',
            'Enter News Description here',
            [$this,'render_testimonial_meta_box'],
            'testimonial',
            'normal',
            'default'
        );
    }
    
    public function render_testimonial_meta_box( $post ) {
        $News_description = get_post_meta( $post->ID, 'News_description', true );
        wp_nonce_field( basename( __FILE__ ), 'testimonial_meta_box_nonce' );
        ?>
        <textarea minlength="50" maxlength="500" id="News_description" name="News_description" style="width:100%; height: 350px;"><?php echo esc_textarea( $News_description ); ?></textarea>
        <?php
    }
    
    public function save_testimonial_meta_box_data( $post_id ) {
        if ( ! isset( $_POST['testimonial_meta_box_nonce'] ) ) {
            return;
        }
        if ( ! wp_verify_nonce( $_POST['testimonial_meta_box_nonce'], basename( __FILE__ ) ) ) {
            return;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( isset( $_POST['post_type'] ) && 'testimonial' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        }
    
        if ( isset( $_POST['News_description'] ) ) {
            update_post_meta( $post_id, 'News_description', sanitize_textarea_field( $_POST['News_description'] ) );
        }
    
        
    }
    
    
    
    
      
    // Submenu of woocommerce 
    
    public function my_custom_submenu_page() {
        add_submenu_page(
            'woocommerce',
            'testimonial',
            'testimonial',
            'manage_options',
            'my-submenu',
            'my_custom_submenu_callback'
        );
    }
    
    
    public function my_custom_submenu_callback() {
        // Add your sub-menu content here
    }
    public function my_custom_settings() {
        add_settings_section(
            'woocommerce',
            'woocommerce',
            'my_custom_section_callback',
            'general'
        );
    
        add_settings_field(
            'my_custom_field',
            'My Custom Field',
            'my_custom_field_callback',
            'general',
            'my_custom_section'
        );
    
        register_setting(
            'general',
            'my_custom_field'
        );
    }
    
    
    public function my_custom_section_callback() {
        // Add your section description here
    }
    
    public function my_custom_field_callback() {
        // Add your field HTML here
    }
    
    public function change_title_text( $title ) {
        $screen = get_current_screen();
    
        if ( $screen->post_type == 'testimonial' ) { // Replace 'your_post_type' with the actual post type where you want to change the title text
            $title = 'Enter News Title';
        }
    
        return $title;
   
   
   
       }

}

new Admin;

// Add custom settings tab
add_filter('woocommerce_settings_tabs_array', 'add_custom_settings_tab', 50);
function add_custom_settings_tab($settings_tabs) {
    $settings_tabs['custom_settings'] = __('Custom Settings', 'costum');
    return $settings_tabs;
}

// Display custom settings fields
add_action('woocommerce_settings_tabs_custom_settings', 'display_custom_settings_fields');
function display_custom_settings_fields() {
    woocommerce_admin_fields(get_custom_settings_fields());
}

// Define custom settings fields
function get_custom_settings_fields() {
    $settings = array(
        'section_title' => array(
            'name' => __('Custom Settings', 'costum'),
            'type' => 'title',
            'desc' => '',
            'id'   => 'custom_settings_section_title'
        ),
        'field1' => array(
            'name' => __('if color', 'costum'),
            'type' => 'text',
            'desc' => '',
            'id'   => 'custom_settings_field1'
        ),
        'field2' => array(
            'name' => __('Background color', 'costum'),
            'type' => 'text',
            'desc' => '',
            'id'   => 'custom_settings_field2'
        ),
        'section_end' => array(
            'type' => 'sectionend',
            'id'   => 'custom_settings_section_end'
        )
    );

    return apply_filters('custom_settings_fields', $settings);
}

// Save custom settings
add_action('woocommerce_update_options_custom_settings', 'save_custom_settings');
function save_custom_settings() {
    woocommerce_update_options(get_custom_settings_fields());
}

// Display saved values
add_action('woocommerce_admin_field_field1', 'display_custom_setting_field1');
function display_custom_setting_field1($value) {
    $field_value = get_option('custom_settings_field1');
    echo $value['desc'] . '<br><input type="text" name="' . $value['id'] . '" value="' . $field_value . '" />';
}

add_action('woocommerce_admin_field_field2', 'display_custom_setting_field2');
function display_custom_setting_field2($value) {
    $field_value = get_option('custom_settings_field2');
    echo $value['desc'] . '<br><input type="text" name="' . $value['id'] . '" value="' . $field_value . '" />';
}



?>