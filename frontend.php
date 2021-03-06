<?php

/**
 * This file is part of the Golstar Plugin.
 *
 * (c) tuannq@elinext.com <http://elinext.com>
 *
 * This source file is subject to the elinext.com license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * Add shortcode [goldstar-plugin] to wordpress
 * 
 * @author tuannq <tuannq@elinext.com>
 */
class Goldstar_Shortcode {

    public static $page_size      = 20;
    public static $delimiter_date = '-';
    public static $territory_none = 'none';


    public static $arr_display_filter = array(
        'START-DATE-ASC' => 'Start Date',
        'END-DATE-ASC'   => 'End Date',
        'ALPHABETA'      => 'Alphabetical (by event title)',
        'PRICE-HIGHT'    => 'Price: High',
        'PRICE-SLOW'     => 'Price: Low',
    );

    /**
     * Load or don't load script. Just load when have shortcode
     * 
     * @var boolean 
     */
    public static $add_script;

    public static function init() {
        add_shortcode("goldstar-plugin", array(__CLASS__, 'handle_shortcode'));

        add_action('init', array(__CLASS__, 'register_script'));
        add_action('wp_footer', array(__CLASS__, 'print_script'));
        add_action( 'wp_ajax_nopriv_goldstar_get_feed', array(__CLASS__,'goldstar_get_feed' ));
        add_action('wp_ajax_goldstar_get_feed', array(__CLASS__, 'goldstar_get_feed'));
    }


    public static function handle_shortcode($atts) {
        self::$add_script = true;
        
        // Parse settings of api
        $goldstar_options = get_option('goldstar_options');
        $str_error = 'The API is invalid. Please input a valid API Key' . ' (<a href="' . admin_url('plugins.php?page=admin-goldstar') . '">Click here</a>)';

        /* Not config api yet */
        if($goldstar_options === false) {
            return $str_error;
        }
        extract($goldstar_options, EXTR_PREFIX_ALL, 'goldstar');

        // actual shortcode handling here
        extract(shortcode_atts(array(
            'hour' => 1,
            'territory_id' => self::$territory_none,
        ), $atts));

        $plugin_territory_id = $territory_id;
        
        // If not provide territory_id or territory_id is invalid will use default
        $goldstar_list_territory_id = (array)$goldstar_list_territory_id;
        if($plugin_territory_id === self::$territory_none || !in_array($plugin_territory_id, $goldstar_list_territory_id)) {
            $plugin_territory_id = $goldstar_territory_id;
        }
        $plugin_territory_id = (int)$plugin_territory_id;

        $_arrReceived = Goldstar_Common::createDirContentXMl(Goldstar_Common::$_goldstar_dir_xml_name);
        if($_arrReceived['error']) {
            return $_arrReceived['msg'];
        }

        $_path_data_xml = $_arrReceived['path'];

        $filename = $_path_data_xml.'/'."goldstar-{$plugin_territory_id}.xml";

        $time     = time(); // by second
        $modified = @filemtime($filename);

        // Make life of file one hour
        $expired_time = $modified + (int)$hour * 3600;

        $str_error = 'The API is invalid. Please input a valid API Key' . ' (<a href="' . admin_url('plugins.php?page=admin-goldstar') . '">Click here</a>)';

        // Api is invalid
        if ($goldstar_api_valid === "0") {
            return $str_error;
        }

        if ($time >= $expired_time || $goldstar_has_change_api === true || !file_exists($filename)) {
            $url = Goldstar_API::$feed_link . '?api_key=' . $goldstar_api_key;
            if (!empty($plugin_territory_id)) {
                $url .= "&territory_id=$plugin_territory_id";
            }
            
            /* Allow access xml by http protocol */

            try {
                $xml = Goldstar_Common::request($url);
            }
            catch(Exception $e) {
                return $e->getMessage();
            }

            $xml = simplexml_load_string($xml);

            //Handler error
            if (isset($xml->error)) {
                return $str_error;
            }            
            
            $goldstar_options['has_change_api'] = false;
            
            update_option('goldstar_options', $goldstar_options);
            
            $xml->asXML($filename);
            @chmod($filename, 0755);

            $updated = date("F d Y H:i:s.", filemtime($filename));
        } else {
            $xml = simplexml_load_file($filename);
        }
        
        // Update location
        $xml_list_locations = $xml->xpath('event/venue/address/locality');
        $arr_locations = array();
        foreach($xml_list_locations as $location) {
            $location = (string)$location;
            $arr_locations[$location] = $location;
        }
        
        $_tmp_locations = array_values($arr_locations);
        usort($_tmp_locations, 'sort_array_by_alphabe_normal');
        ${"goldstar_location_$plugin_territory_id"} = $_tmp_locations;

        ob_start();
        include dirname(__FILE__) . '/frontend-template.php';
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    public static function register_script() {
        wp_register_style('goldstar-css', plugins_url('css/goldstar.css', __FILE__));
        wp_register_style('jquery-ui-css', plugins_url('css/jquery-ui/jquery-ui.css', __FILE__));

        //wp_register_script('elisoft-jquery', plugins_url('js/jquery.elisoft.js', __FILE__));

        //wp_register_script('elisoft-jquery-ui-js', plugins_url('js/jquery-ui.elisoft.js', __FILE__), array('elisoft-jquery'), '1.0');
        wp_register_script('elisoft-simple-pagination-js', plugins_url('js/jquery.simplePagination.js', __FILE__), array('jquery'), '1.0', true);

        wp_register_script('goldstar-js', plugins_url('js/goldstar.js', __FILE__), array('jquery'), '1.0', true);
        wp_localize_script('goldstar-js', 'goldstar_obj', array(
            'calendar_src' => plugins_url('goldstar/img/date-button.png'),
            'admin_url'    => admin_url('admin-ajax.php'),
        ));
    }

    /**
     * After page has render. Use this method to enqueue script. 
     * This is perfect way to load script for shortcode plugins
     * 
     * @return void()
     */
    public static function print_script() {
        if (!self::$add_script)
            return;

        wp_print_styles('goldstar-css');
        wp_print_styles('jquery-ui-css');

        wp_print_scripts('jquery');
        wp_print_scripts('jquery-ui-core');
        wp_print_scripts('jquery-ui-datepicker');

        wp_print_scripts('elisoft-simple-pagination-js');
        wp_print_scripts('goldstar-js');
    }

    /* Define callback ajax function */

    public static function goldstar_get_feed() {
        // The $_REQUEST contains all the data sent via ajax
        if (isset($_REQUEST['page'])) {

            $page         = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
            $from_date    = isset($_REQUEST['from_date']) ? $_REQUEST['from_date'] : '';
            $to_date      = isset($_REQUEST['to_date']) ? $_REQUEST['to_date'] : '';
            $location     = isset($_REQUEST['location']) ? stripslashes($_REQUEST['location']) : '';
            $price        = isset($_REQUEST['price']) ? $_REQUEST['price'] : '';
            $repagination = isset($_REQUEST['repagination']) ? $_REQUEST['repagination'] : '';
            $category     = isset($_REQUEST['category']) ? stripslashes($_REQUEST['category']) : '';
            $plugin_territory_id = isset($_REQUEST['plugin_territory_id']) ? $_REQUEST['plugin_territory_id'] : '';
            
            // Get categories selected in the backend
            $goldstar_options = get_option('goldstar_options');
            if (! $category) {
                $category = $goldstar_options['category'];
            }
            
            $arr_filter = array(
                'page'         => $page,
                'from_date'    => $from_date,
                'to_date'      => $to_date,
                'location'     => $location,
                'price'        => $price,
                'repagination' => $repagination,
                'category'     => $category,
                'plugin_territory_id' => $plugin_territory_id,
            );

            // If no category is selected, then don't show any offer
            if (empty($goldstar_options['category'])) {
                $arr_events = array();
            } else {
                $arr_events = self::get_list_events_data($arr_filter);
            }
            
            $str_html = self::get_html_list_events($arr_events, $arr_filter);

            echo $str_html;
        }
        // Always die in functions echoing ajax content
        die();
    }

    public static function get_list_events_data($arr_filter) {
        $page_size = self::$page_size;

        $_arrReceived = Goldstar_Common::createDirContentXMl(Goldstar_Common::$_goldstar_dir_xml_name);
        if($_arrReceived['error']) {
            return $_arrReceived['msg'];
        }

        $_path_data_xml = $_arrReceived['path'];
        
        $filename = $_path_data_xml."/goldstar-{$arr_filter['plugin_territory_id']}.xml";

        $xml = simplexml_load_file($filename);
            
        $xpath_query = Goldstar_Common::get_xpath_query($arr_filter);
        
        $events = $xml->xpath($xpath_query);
        
        // Sort data 
        $goldstar_options = get_option('goldstar_options');
        $settings_display_order = isset($goldstar_options['settings_display_order']) ?  $goldstar_options['settings_display_order']: 'START-DATE-ASC';
        $events = self::_sort_events($events, $settings_display_order);
        
        if (empty($goldstar_options['category'])) {
            return array();
        }
        return $events;
    }
    
   private static function _sort_events (&$events , $settings_display_order) {
      switch($settings_display_order) {
          case 'START-DATE-ASC':
              usort($events, 'sort_by_start_date');
              break;
          case 'END-DATE-ASC':
              usort($events, 'sort_by_end_date');
              break;
          case 'ALPHABETA':
              usort($events, 'sort_by_alpha');
              break;
          case 'PRICE-HIGHT':
              usort($events, 'sort_by_price_hight');
              break;
          case 'PRICE-SLOW':
              usort($events, 'sort_by_price_slow');
      }
      
      return $events;
           
   }

    public static function get_html_list_events($arr_events, $arr_filter) {

        $page_size = self::$page_size;
        $total_event = count($arr_events);

        $arr_datas = array_splice($arr_events, ($arr_filter['page'] - 1) * $page_size, $page_size);

        ob_start();
        include dirname(__FILE__) . '/list-event-template.php';
        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }



}

Goldstar_Shortcode::init();


function sort_by_start_date($a, $b) {
    $a_price = str_replace("$", "", $a->our_price_range);
    $b_price = str_replace("$", "", $b->our_price_range);
    
    if(strpos($b_price, 'SOLD OUT') !== false) {
        return false;
    }
    elseif(strpos($a_price, 'SOLD OUT') !== false) {
        return true;
    }
    elseif(strpos($b_price, 'n/a') !== false) {
        return false;
    }
    elseif(strpos($a_price, 'n/a') !== false) {
        return true;
    }
    
    $arr_a = $a->upcoming_dates->xpath('event_date');

    $arr_b = $b->upcoming_dates->xpath('event_date');

    $a_date = isset($arr_a[0]->date) ? $arr_a[0]->date : 0;
    if($a_date == 0) return true;

    $b_date = isset($arr_b[0]->date) ? $arr_b[0]->date : 0;
    if($b_date == 0) return false;

    return strtotime($a_date) > strtotime($b_date);

};

function sort_by_end_date($a, $b) {
    $a_price = str_replace("$", "", $a->our_price_range);
    $b_price = str_replace("$", "", $b->our_price_range);
    
    if(strpos($b_price, 'SOLD OUT') !== false) {
        return false;
    }
    elseif(strpos($a_price, 'SOLD OUT') !== false) {
        return true;
    }
    elseif(strpos($b_price, 'n/a') !== false) {
        return false;
    }
    elseif(strpos($a_price, 'n/a') !== false) {
        return true;
    }

    $arr_a = $a->upcoming_dates->xpath('event_date');

    $arr_b = $b->upcoming_dates->xpath('event_date');

    $a_date = end($arr_a)->date;
    $b_date = end($arr_b)->date;

    return strtotime($a_date) > strtotime($b_date);

};

function sort_by_alpha($a, $b) {
    return strcasecmp($a->headline_as_text , $b->headline_as_text);
}


function sort_by_price_hight($a, $b) {
    $a_price = str_replace("$", "", $a->our_price_range);
    $b_price = str_replace("$", "", $b->our_price_range);
    
    if(strpos($b_price, 'SOLD OUT') !== false) {
        return false;
    }
    elseif(strpos($a_price, 'SOLD OUT') !== false) {
        return true;
    }
    elseif(strpos($b_price, 'n/a') !== false) {
        return false;
    }
    elseif(strpos($a_price, 'n/a') !== false) {
        return true;
    }
    
    if(strpos($a_price, 'COMP')!== false && strpos($b_price,'COMP')!== false){
        $_tmp_a_price = explode(" - ", $a_price);
        $_tmp_b_price = explode(" - ", $b_price);
        if(count($_tmp_a_price) > count($_tmp_b_price))  {
            return false;
        }
        if(count($_tmp_a_price) < count($_tmp_b_price))  {
            return true;
        }
        
        if(count($_tmp_a_price) == count($_tmp_b_price) && count($_tmp_b_price) == 2 ) {
            return $_tmp_a_price[1] < $_tmp_b_price[1];
        }
        
        return $a_price < $b_price;
    }
    
    if(strpos($a_price, 'COMP')!== false) {
        return true;
    }
    elseif(strpos($b_price, 'COMP') !== false) {
        return false;
    }
    
    return $a_price < $b_price;
}
              
function sort_by_price_slow($a, $b) {
    $a_price = str_replace("$", "", $a->our_price_range);
    $b_price = str_replace("$", "", $b->our_price_range);
    
    if(strpos($b_price, 'SOLD OUT') !== false) {
        return false;
    }
    elseif(strpos($a_price, 'SOLD OUT') !== false) {
        return true;
    }
    elseif(strpos($b_price, 'n/a') !== false) {
        return false;
    }
    elseif(strpos($a_price, 'n/a') !== false){
        return true;
    }
    
    if(strpos($a_price, 'COMP')!== false && strpos($b_price,'COMP')!== false){
        $_tmp_a_price = explode(" - ", $a_price);
        $_tmp_b_price = explode(" - ", $b_price);
        if(count($_tmp_a_price) > count($_tmp_b_price))  {
            return true;
        }
        if(count($_tmp_a_price) < count($_tmp_b_price))  {
            return false;
        }
        
        if(count($_tmp_a_price) == count($_tmp_b_price) && count($_tmp_b_price) == 2 ) {
            return $_tmp_a_price[1] > $_tmp_b_price[1];
        }
        
        return $a_price > $b_price;
    }
    if(strpos($a_price, 'COMP') !== false) {
        return false;
    }
    elseif(strpos($b_price, 'COMP') !== false) {
        return true;
    }
    
    // Increase
    return $a_price > $b_price;
}

function sort_array_by_alphabe_normal($a, $b) {
    return strcasecmp($a,$b);
}