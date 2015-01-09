<?php

class Goldstar_Teaser_Widget extends WP_Widget {

    const  TEASER_WIDGET_DEFAULT_TITLE = 'Featured Events';
    const  TEASER_WIDGET_DEFAULT_NUM_DISPLAY = '5';
    const  TEASER_WIDGET_DEFAULT_BG_COLOR =  '#ffffff';
    const  TEASER_WIDGET_DEFAULT_TITLE_COLOR = '#000000';
    const  TEASER_WIDGET_DEFAULT_EVENT_TITLE_SIZE = '12';
    const  TEASER_WIDGET_DEFAULT_EVENT_DATE_SIZE = '11';
    const  TEASER_WIDGET_DEFAULT_LOGO_POSITION = 'b_right';
    const  TEASER_WIDGET_DEFAULT_ROUNDED_CORNER_RADIUS = '5';
    
    
    public function __construct() {
        parent::__construct(
            
            // ID of widget
            'goldstar_teaser_widget',

            // Widget name 
            __('Goldstar Teaser', 'goldstar_teaser_widget_domain'),

            // Widget description
            array( 'description' => __( 'Goldstar Teaser', 'goldstar_teaser_widget_domain' ), )
        );
    }
        
    // Widget Backend 
    public function form( $instance ) {
        ob_start();
        include __DIR__. '/admin.php';
        $html = ob_get_contents();
        ob_end_clean();
        echo $html;
    }
	
    // Updating widget replacing old instances with new
    public function update( $new_instance, $old_instance ) {
        
        $instance = array();
        $instance['title']                  = ! empty( $new_instance['title'] ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['bg_color']               = !empty( $new_instance['bg_color'] ) ? strip_tags($new_instance['bg_color']) : '';
        $instance['title_color']            = !empty( $new_instance['title_color'] ) ? strip_tags($new_instance['title_color']) : '';
        $instance['num_events']             = !empty( $new_instance['num_events'] ) ? intval(strip_tags($new_instance['num_events'])) : '';
        $instance['link']                   = !empty( $new_instance['link'] ) ? strip_tags($new_instance['link']) : '';
        $instance['event_title_font_size']  = !empty( $new_instance['event_title_font_size'] ) ? strip_tags($new_instance['event_title_font_size']) : '';
        $instance['event_title_bold']       = !empty( $new_instance['event_title_bold'] ) ? 1 : 0;
        $instance['event_date_font_size']   = !empty( $new_instance['event_date_font_size'] ) ? $new_instance['event_date_font_size'] : '';
        $instance['event_date_color']       = !empty( $new_instance['event_date_color'] ) ? $new_instance['event_date_color'] : '';
        $instance['widget_title_font_size'] = !empty( $new_instance['widget_title_font_size'] ) ? $new_instance['widget_title_font_size'] : '';
        $instance['event_title_color']      = !empty( $new_instance['event_title_color'] ) ? $new_instance['event_title_color'] : 'inherit';
        $instance['teaser_widget_title_rounded']        = !empty( $new_instance['teaser_widget_title_rounded'] ) ? 1 : 0;
        $instance['teaser_widget_title_rounded_radius'] = !empty( $new_instance['teaser_widget_title_rounded_radius'] ) ? $new_instance['teaser_widget_title_rounded_radius'] : '';
        
        return $instance;
    }
    
    /**
     * Creating widget front-end
     * This is where the action happens
     */
    public function widget( $args, $instance ) {
        ob_start();
        include __DIR__. '/frontend.php';
        $html = ob_get_contents();
        ob_end_clean();
        echo $html;
    }
    
}  

function goldstar_teaser_register_scripts() {
    wp_register_style('goldstar-teater-widget-css', plugins_url('goldstar/widgets/teaser/css/style.css'));
}
    
function goldstar_teaser_print_scripts() {
    wp_print_styles('goldstar-teater-widget-css');
}

function goldstar_teaser_load_widget() {
    register_widget( 'Goldstar_Teaser_Widget' );
}

add_action( 'init', 'goldstar_teaser_register_scripts' );
add_action( 'wp_print_styles', 'goldstar_teaser_print_scripts' );
add_action('widgets_init', 'goldstar_teaser_load_widget');