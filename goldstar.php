<?php

/**
 * @package Goldstar plugin
 * @version 1.0
 */
/*
  Plugin Name: Goldstar
  Plugin URI: http://wordpress.org/plugins/goldstar/
  Description: This plugin will provide a basic list of discount ticket offers from goldstar.com
  Author: Elinext Group
  Author URI: www.elinext.com
  Version: 1.0
 */

/* Some event will fire the first when active or deactive plugin */

// Set some default value when active plugin
function goldstar_active() {
    /* Also use: 
     * - location_{territory_id}
     * - category
     */
    update_option('goldstar_options', array(
       'title',
       'api_key' => '',
       'territory_id' => '',
       'affiliate_id' => '',
       'settings_display_color' => '#000000',
       'settings_display_order' => 'START-DATE-ASC',
       'filter_date' => '1',
       'filter_location' => '1',
       'filter_price' => '1',
       'api_valid' => '0',
       'content' => '',
       'list_territory_id' => '',
    ));
}
register_activation_hook(__FILE__, 'goldstar_active');

// Delete options variable when deactive plugin
function goldstar_deactive() {
    delete_option('goldstar_options');
}
register_deactivation_hook(__FILE__, 'goldstar_deactive');

/*//*/

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
            $url      = self::$category_link . '?api_key=' . $api_key;

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
            $url      = self::$territory_link . '?api_key=' . $api_key;
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

    }

}