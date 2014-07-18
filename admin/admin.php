<?php
/**
 * This file is part of the Golstar Plugin.
 *
 * (c) tuannq@elinext.com <http://elinext.com>
 *
 * This source file is subject to the elinext.com license that is bundled
 * with this source code in the file LICENSE.
 */

// Register settings for goldstar
function goldstar_register_options() {
    register_setting('goldstar-group', 'goldstar_options', 'goldstar_validate_options');
}

/* Save file xml when validate.
 * I Trick at here 
 * - 1: generate xml file only when api key change or territory id change
 * - 2: make location var
 *  */
function goldstar_validate_options($input) {
    
    extract($input, EXTR_PREFIX_ALL, 'goldstar'); 
    
    $goldstar_options = get_option('goldstar_options');
    $input['api_key'] = trim ($input['api_key']); /* clean api key data before save! */
    
    // Save xml file
    $input['has_change_api'] = $goldstar_options['has_change_api'];
    if($goldstar_options['api_key'] != $goldstar_api_key || $goldstar_options['territory_id'] != $goldstar_territory_id) {
        /* construct funcion to save */
        $input['has_change_api'] = true;
    }
    
    if($goldstar_api_valid === "1") {
        // Get location
        $arr_territory_id = array();
        $arr_data = Goldstar_API::getTerritories($goldstar_api_key);
        foreach($arr_data as $i => $arr_item) {
            $arr_territory_id[] = $arr_item['id'];
        }        
        
        $input['list_territory_id'] = $arr_territory_id;

        if(!empty($input['category'])) {
            usort($input['category'], 'sort_array_by_alphabe_normal');
        }

    }
    
    return $input;
}

// add action
add_action('admin_init', 'goldstar_register_options');

/*//*/

/* Used for add editor */
add_action('admin_print_scripts', 'do_jslibs');
add_action('admin_print_styles', 'do_css');

function do_css() {
    wp_enqueue_style('thickbox');
}

function do_jslibs() {
    wp_enqueue_script('editor');
    wp_enqueue_script('thickbox');
    add_action('admin_head', 'wp_tiny_mce');
}
/*//*/

/* Hook to add css or javascript */
add_action('admin_head', 'goldstar_admin_plugin_css');

function goldstar_admin_plugin_css() {
    echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('css/goldstar.css', dirname(__FILE__)) . '">';
    echo '<script type="text/javascript" src="' . plugins_url('js/goldstar_admin.js', dirname(__FILE__)) . '">';
}
/*//*/

/** Add hook register goldstar admin menu. */
add_action('admin_menu', 'goldstar_admin_plugin_menu');

/** Function for hook admin_menu. */
function goldstar_admin_plugin_menu() {
    add_plugins_page('Goldstar Options', 'Goldstar', 'manage_options', 'admin-goldstar', 'goldstar_admin_plugin_options');
}

/*Register ajax callback function */
add_action( 'wp_ajax_goldstar_get_categories', 'goldstar_get_categories' );

/* Define callback ajax function */
function goldstar_get_categories() {
    // The $_REQUEST contains all the data sent via ajax
    if ( isset($_REQUEST['api_key']) ) {
        $api_key = $_REQUEST['api_key'];
        $arr_data = Goldstar_API::getCategories($api_key); // Data empty mean
        
        $goldstar_options = get_option('goldstar_options');
        $arr_select_category = $goldstar_options['category'];
        $arr_list_territory_id = Goldstar_API::getTerritories($api_key); // save the first time for check territory
        
        echo json_encode(array(
            'list_category' => $arr_data,
            'list_select_category' => $arr_select_category,
            'list_territory_id' => $arr_list_territory_id,
        ));
    }
    // Always die in functions echoing ajax content
   die();
}
/*/*/

/*Register ajax callback function */
add_action( 'wp_ajax_goldstar_get_territories', 'goldstar_get_territories' );

/* Define callback ajax function */
function goldstar_get_territories() {
    // The $_REQUEST contains all the data sent via ajax
    if ( isset($_REQUEST['api_key']) ) {
        $api_key = $_REQUEST['api_key'];
        $arr_data = Goldstar_API::getTerritories($api_key);
        if(isset($arr_data['error'])) {
            echo 'API key is invalid';
            exit();
        }
        
        $territory_id = isset($_REQUEST['territory_id']) ? $_REQUEST['territory_id'] : '';
        
        $str_html = goldstar_admin_territori_list_html($arr_data, $territory_id);
        
        echo $str_html;
    }
    // Always die in functions echoing ajax content
   die();
}

function goldstar_admin_territori_list_html ($arr_data, $territory_id) {
    ob_start();
    include dirname(__FILE__) . '/_popup_territory.php';
    $data = ob_get_contents();
    ob_end_clean();
    
    return $data;
}
/*//*/

/** Show content page goldstar. */
function goldstar_admin_plugin_options() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions ccess this page.'));
    }

    $goldstar_plugin_page            = 'http://wordpress.org/plugins/goldstar/';
    $goldstar_contact_link           = 'https://www.goldstar.com/help/contact_us';
    $goldstar_territory_link         = 'https://github.com/goldstar/apidocs/wiki/Territories';

    $arr_filter_display = array(
        'Date',
        'Location',
        'Price',
    );

    $arr_display_order = Goldstar_Shortcode::$arr_display_filter;
    
    /*Define ajax url admin*/
    $admin_url = admin_url( 'admin-ajax.php' );
    
    $goldstar_options = get_option('goldstar_options');
    
    $is_show_error = isset($goldstar_options['api_key']) && ($goldstar_options['api_key'] !== "") ? true : false;
    if ($is_show_error === false) {
        $class_error = 'hidden';
        $class_success = 'hidden';
    } else {
        $class_error = $goldstar_options['api_valid'] == '1' ? 'hidden' : '';
        $class_success = $goldstar_options['api_valid'] == '1' ? '' : 'hidden';
    }
    
    /* Get list categories by api */
    $arr_category = array();
    if ($goldstar_options['api_valid']) {
        $arr_category = Goldstar_API::getCategories($goldstar_options['api_key']);
    }
    
    include dirname(__FILE__) . '/_admin_template.php';
}
