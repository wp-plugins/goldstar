<?php

/**
 * @package Goldstar plugin
 * @version 1.4
 */
/*
  Plugin Name: Goldstar
  Plugin URI: http://wordpress.org/plugins/goldstar/
  Description: This plugin will provide a list of discount ticket offers from goldstar.com
  Author: Goldstar
  Author URI: www.goldstar.com
  Version: 1.4
 */

/* Some event will fire the first when active or deactive plugin */

// Set some default value when active plugin
function goldstar_active() {
    /* Also use: 
     * - location_{territory_id}
     * - category
     * - Just create options if it do not exits yet!
     */
    if(get_option('goldstar_options', false) === false) {
        update_option('goldstar_options', array(
            'title',
            'api_key' => '',
            'territory_id' => '',
            'affiliate_id' => '',
            'settings_display_color' => '#005f93',
            'settings_display_order' => 'START-DATE-ASC',
            'filter_date' => '1',
            'filter_location' => '1',
            'filter_price' => '1',
            'api_valid' => '0',
            'content' => '',
            'list_territory_id' => '',
            'goldstar_slug' => '',
            'teaser_widget_logo_url' => '',
            'teaser_widget_logo_position' => '',
            'teaser_widget_logo_link_to' => '',
        ));
    }
}
register_activation_hook(__FILE__, 'goldstar_active');

// Delete options variable when deactive plugin
function goldstar_deactive() {
    // Do nothing
}
register_deactivation_hook(__FILE__, 'goldstar_deactive');

register_uninstall_hook(__FILE__, 'goldstar_uninstall_plugin');

function goldstar_uninstall_plugin() {
    delete_option('goldstar_options');
}

/*//*/

require_once(__DIR__. '/widgets/teaser/teaser.php');

if (is_admin()) {
    require_once dirname(__FILE__) . '/admin/admin.php';

    /* Active settings link */

    function goldstar_add_settings_link($links) {
        $settings_link = '<a href="plugins.php?page=admin-goldstar">Settings</a>';
        array_push($links, $settings_link);
        return $links;
    }

    $plugin = plugin_basename(__FILE__);
    add_filter("plugin_action_links_$plugin", 'goldstar_add_settings_link', 10);

    /* // */
}

/* Some function at admin.php use this file.
 * This file will always loaded!!
 *  */
require_once dirname(__FILE__) . '/frontend.php';

if (!class_exists("Goldstar_API")) {

    class Goldstar_API {

        public static $category_link  = "http://www.goldstar.com/api/categories.json";
        public static $territory_link = "http://www.goldstar.com/api/territories.json";
        public static $feed_link      = "https://www.goldstar.com/api/listings.xml";

        public function init() {
            
        }

        public static function isValidAPI($api_key) {
            throw new Exception('Not implement');
        }

        /**
         * Call api and return all categories belong this api_key
         * 
         * <example>
         *  array(1 => array('id' => '', 'name' => '') ... )
         * </example>
         * 
         * @param type $api_key
         */
        public static function getCategories($api_key, $is_raw = false) {
            $url      = self::$category_link . '?api_key=' . urlencode($api_key);

            try{
                $str_data = Goldstar_Common::request($url);
            }
            catch(Exception $e) {
                if($is_raw) {
                    return '{}';
                }

                return array();
            }


            if ($is_raw)
                return $str_data;

            $arr_data = json_decode($str_data, true);
            return $arr_data;
        }

        /**
         * Call api to return territory of specific api
         * 
         * <example>
         * array( 1=> array( 'id' =>'', 'name' =>'', 'slug' => '', 'initials' => '', 'timezone ' => '') ... )
         * </example>
         * 
         * @param type $api_key
         * @return type
         */
        public static function getTerritories($api_key, $is_raw = false) {
            $url      = self::$territory_link . '?api_key=' . urlencode($api_key);
            try{
                $str_data = Goldstar_Common::request($url);
            }
            catch(Exception $e) {
                if($is_raw)
                    return '{}';
                return array();
            }

            if ($is_raw)
                return $str_data;

            $arr_data = json_decode($str_data, true);

            return $arr_data;
        }

    }

}

if (!class_exists('Goldstar_Common')) {

    class Goldstar_Common {

        public static $timeoutRequest = 1000;
        public static $_goldstar_dir_xml_name = 'goldstar-xml';
        private static $_goldstar_options;

        public static function request($url) {

            $request = new WP_Http;
            $result = $request->request( $url, array(
                'timeout' => self::$timeoutRequest,
            ) );

            if ( is_wp_error($result) )
            {
                throw new Exception($result->get_error_message());
            }

            return $result['body'];            
        }

        public static function createDirContentXMl($goldstar_dir_xml_name)
        {
            $_upload = WP_CONTENT_DIR.'/uploads';

            $_path_data_xml = $_upload;

            if(!file_exists($_path_data_xml)) {
                return array(
                    'error' => true,
                    'msg' => "Please create wp-content/uploads directory."
                );
            }

            if(is_multisite()) {
                $sites = get_blog_details(get_current_blog_id());
                $_final_path_data_xml = $_path_data_xml = $_upload.'/'.$goldstar_dir_xml_name.'/'.$sites->domain;
                if(file_exists($_path_data_xml)) {
                    if(!is_writable($_path_data_xml)) {
                        return array(
                            'error' => true,
                            'msg' => "Please set write permission to <strong>$_path_data_xml</strong>."
                        );
                    }
                    else {
                        return array(
                            'error' => false,
                            'msg' => '',
                            'path' => $_final_path_data_xml,
                        );
                    }

                }
            }
            else {
                $_final_path_data_xml = $_path_data_xml = $_upload.'/'.$goldstar_dir_xml_name;
            }

            $_path_data_xml  = $_upload.'/'.$goldstar_dir_xml_name;
            if(file_exists($_path_data_xml)) {
                if(!is_writable($_path_data_xml)) {
                    return array(
                        'error' => true,
                        'msg' => "Please set write permission to <strong>$_path_data_xml</strong>."
                    );
                }
                else {
                    if(is_multisite()) {
                        mkdir($_final_path_data_xml);
                    }

                    return array(
                        'error' => false,
                        'msg' => '',
                        'path' => $_final_path_data_xml,
                    );
                }
            }

            $_path_data_xml  = $_upload;

            if(!is_writable($_path_data_xml)) {
                return array(
                    'error' => true,
                    'msg' => "Please set write permission to <strong>$_path_data_xml</strong>."
                );
            }
            else {

                mkdir($_upload.'/'.$goldstar_dir_xml_name);
                if(is_multisite()) {
                    mkdir($_final_path_data_xml);
                }

                return array(
                    'error' => false,
                    'msg' => '',
                    'path' => $_final_path_data_xml,
                );
            }
        }

        public static function getCurrentTerritoryId() {
            $goldstar_options = get_option('goldstar_options');
            return isset($goldstar_options['territory_id']) ? intval($goldstar_options['territory_id']) : 0;
        }

        /**
         * Also depend on terriotry but get goldstar-0.xml for used
         */
        public static function getXmlContentWithTerritory($id = 0) {

            // Check error api first

            $goldstar_options = get_option('goldstar_options');

            if($goldstar_options['api_valid'] === "0" || $goldstar_options['api_valid'] === "") {
                return array(
                    'error' => true,
                    'msg' => __('The API is invalid. Please input a valid API Key' . ' (<a href="' . admin_url('plugins.php?page=admin-goldstar') . '">Click here</a>)')
                );
            }

            // create directory if it not exists yet
            $_arrReceived = Goldstar_Common::createDirContentXMl(Goldstar_Common::$_goldstar_dir_xml_name);
            if(isset($_arrReceived['error']) && $_arrReceived['error'] === true) {
                return $_arrReceived;
            }


            $_path_data_xml = $_arrReceived['path'];
            $filename = $_path_data_xml.'/'."goldstar-".$id.".xml";

            // Create that file if not exists
            if(!file_exists($filename)) {
                /* create that file from xml */
                $url = Goldstar_API::$feed_link . '?api_key=' . $goldstar_options['api_key'];

                // set terrritory = 0
                $url .= "&territory_id=".$id;

                try {
                    $xml = Goldstar_Common::request($url);
                }
                catch(Exception $e) {
                    return array(
                        'error' => true,
                        'msg' => $e->getMessage(),
                    );
                }

                $xml = simplexml_load_string($xml);

                //Handler error
                if (isset($xml->error)) {
                    return array(
                        'error' => true,
                        'msg' => (string)$xml->error,
                    );
                }

                $xml->asXML($filename);
                @chmod($filename, 0755);
            }




            return simplexml_load_file($filename);
        }

        /**
         * Query xml by xpath
         *
         * @param type $arr_filter
         */
        public static function get_xpath_query($arr_filter) {

            $arr_condition = array();
            if (!empty($arr_filter['from_date'])) {
                $_date           = str_replace(Goldstar_Shortcode::$delimiter_date, '', $arr_filter['from_date']);
                $arr_condition[] = "translate(upcoming_dates/event_date/date,'-', '') >= '$_date'";
            }

            if (!empty($arr_filter['to_date'])) {
                $_date           = str_replace(Goldstar_Shortcode::$delimiter_date, '', $arr_filter['to_date']);
                $arr_condition[] = "translate(upcoming_dates/event_date/date,'-', '') <= '$_date'";
            }

            if (!empty($arr_filter['location'])) {
                $arr_condition[] = 'venue/address/locality="'.$arr_filter['location'].'"';
            }

            $category_list_where = '';
            if (empty($arr_filter['category'])) {
                throw new Exception("This function used when category is not empty");
            }

            if (!empty($arr_filter['category'])) {
                if (is_array($arr_filter['category'])) {
                    $arr_categories_list = array();
                    foreach ($arr_filter['category'] as $category) {
                        $arr_categories_list[] = 'category_list/category/name="'.$category.'"';
                    }
                    $arr_condition[] = '('. implode(' or ', $arr_categories_list) . ')';
                } else {
                    $arr_condition[] = 'category_list/category/name="'.$arr_filter['category'].'"';
                }
            }

            if (!empty($arr_filter['price'])) {
                /* Parse price */
                if ($arr_filter['price'] === 'free') {
                    $arr_condition[] = "(translate(substring-before(our_price_range,'-'),' ','') = 'COMP' or our_price_range = 'COMP')";
                } elseif (strpos($arr_filter['price'], '<') !== false) {
                    $_price          = (int) substr($arr_filter['price'], strpos($arr_filter['price'], '<') + 1);
                    $arr_condition[] = "(number(translate(substring-before(our_price_range,'-'),'$','')) < $_price or number(translate(substring-after(our_price_range,'-'),'$','')) < $_price or number(translate(our_price_range,'$','')) < $_price )";
                } elseif (strpos($arr_filter['price'], '>') !== false) {
                    $_price          = (int) substr($arr_filter['price'], strpos($arr_filter['price'], '>') + 1);
                    $arr_condition[] = "(number(translate(substring-before(our_price_range,'-'),'$','')) >= $_price or number(translate(substring-after(our_price_range,'-'),'$','')) >= $_price or number(translate(our_price_range,'$','')) >= $_price )";
                } elseif (strpos($arr_filter['price'], '-') !== false) {
                    list($_start_price, $_end_price) = explode("-", $arr_filter['price']);
                    $arr_condition[] = "(number(translate(substring-before(our_price_range,'-'),'$','')) >= $_start_price or number(translate(substring-after(our_price_range,'-'),'$','')) >= $_start_price or number(translate(our_price_range,'$','')) >= $_start_price ) ";
                    $arr_condition[] = "(number(translate(substring-before(our_price_range,'-'),'$','')) < $_end_price or number(translate(substring-after(our_price_range,'-'),'$','')) < $_end_price or number(translate(our_price_range,'$','')) < $_end_price ) ";
                }
            }
            $xpath_query = 'event';
            if (!empty($arr_condition)) {
                $xpath_query .= '[' . implode(" and ", $arr_condition) . ']';
            }

            return $xpath_query;
        }

        public static function getGoldstarOptions() {
            if(self::$_goldstar_options !== null)
                return self::$_goldstar_options;

            return self::$_goldstar_options = get_option('goldstar_options');
        }

    }

}

class Goldstar_Feature_Event_Page {
    public static  function init() {
        add_action('admin_init', array(__CLASS__, 'queue_javascript_css'));
    }

    public static function queue_javascript_css()
    {
        // script
        wp_enqueue_script('jquery-datatable', plugins_url('goldstar/js/jquery.dataTables.js'), array('jquery'), '1.9.4', true);

        // css
        wp_enqueue_style('jquery-datatable', plugins_url('goldstar/css/jquery.dataTables.css'));
    }
    public static function getFeatureEvents() {
        return get_option('goldstar_featured_events');
    }
}

if(is_admin() && isset($_REQUEST['page']) && $_REQUEST['page'] === 'goldstar-featured-events') {
    Goldstar_Feature_Event_Page::init();
}