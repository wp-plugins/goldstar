<?php

/**
 * This file is part of the Golstar Plugin.
 *
 * (c) tuannq@elinext.com <http://elinext.com>
 *
 * This source file is subject to the elinext.com license that is bundled
 * with this source code in the file LICENSE.
 */

$goldstar_price = array(
    'free' => "Free",
    '<10' => "Under $10",
    '10-25' => "$10-$25",
    '25-50' => "$25-$50",
    '50-100' => "$50-$100",
    '>100'  => "Over $100"
);
?>
<div class="wrap goldstar-frontend">
    <div class="wrap-inner">
        <?php
        /* Not have any string after remove tags => This is empty string. Don't show it */
        // $is_empty_content = (preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-\*]/s', "",strip_tags($goldstar_content, '<img>')));
        // $is_empty_content = empty($is_empty_content);
        $is_empty_content = empty($goldstar_content);

        if(!$is_empty_content || !empty($goldstar_title)): ?>
        <div class="produce-wrap">
            <div class="produce-wrap-inner">
                <?php if(!empty($goldstar_title)): ?>
                <h2><?php echo $goldstar_title ?></h2>
                <?php endif; ?>

                <?php if(!$is_empty_content): ?>
                <div class="content">
                    <?php echo wpautop($goldstar_content) ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="content-can-filter">
        <div class="content-can-filter-inner">  
            
        
        <div class="content">
            <div class="content-inner">
                <?php if($goldstar_filter_date == 1 || $goldstar_filter_location == 1 || $goldstar_filter_price == 1 ): ?>
                <div class="filter" style="background-color: <?php echo isset($goldstar_options['settings_display_color']) ? $goldstar_options['settings_display_color']: '#e7e7e7'; ?> !important">
                    <div class="filter-inner">
                            <?php if($goldstar_filter_date == 1): ?>
                            <div class="date-filter filter-item">
                                <div class="date-filter-from-to">
                                    <div class="date-filter-from-wrapper">
                                        <input type="text" name="from_date" placeholder="Start date" class="from-date" id="filter-from-date" /> 
                                    </div>
                                    <div class="date-filter-to-wrapper">
                                        <input type="text" name="to_date" placeholder="End date" class="end-date" id="filter-to-date"/>
                                    </div>
                                </div>
                                <div class="date-util">
                                    <a href="javascript:void(0);" id="choice-today" 
                                       data-date-from="<?php echo date("Y-m-d", time()) ?>"
                                       data-date-to="<?php echo date("Y-m-d", time()) ?>"
                                       class="button button-gray">Today</a>
                                    <a href="javascript:void(0);" id="choice-tomorrow"
                                       data-date-from="<?php echo date("Y-m-d", strtotime("+1 day")) ?>"
                                       data-date-to="<?php echo date("Y-m-d", strtotime("+1 day")) ?>"
                                       class="button button-gray">Tomorrow</a>
                                    <a href="javascript:void(0);" id="choice-weekend"
                                       data-date-from="<?php echo date("Y-m-d", strtotime("next Saturday")) ?>"
                                       data-date-to="<?php echo date("Y-m-d", strtotime("next Sunday")) ?>"
                                       class="button button-gray">This Weekend</a>
                                </div>
                            </div>
                            <?php endif; ?>
                        
                            <?php if(!empty($goldstar_category)): ?>
                            <div class="category-filter filter-item">
                                <select name="category" placeholder="Category" id="filter-by-category">
                                    <option value=""> Category </option>
                                    <?php foreach($goldstar_category as $cate): ?>
                                        <option value="<?php echo $cate ?>"> <?php echo $cate ?> </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endif; ?>
                            
                            <?php if($goldstar_filter_location == 1): ?>
                            <div class="location-filter filter-item">
                                <select name="location" placeholder="Location" id="filter-by-location">
                                    <option value=""> Location </option>
                                    <?php foreach(${"goldstar_location_$plugin_territory_id"} as $location): ?>
                                        <option value="<?php echo $location ?>"> <?php echo $location ?> </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endif; ?>
                            
                            <?php if($goldstar_filter_price == 1): ?>
                            <div class="price-filter filter-item">
                                <select name="price" placeholder="Price"  id="filter-by-price">
                                    <option value=""> Price </option>
                                    
                                    <?php foreach($goldstar_price as $index=> $price): ?>
                                        <option value="<?php echo $index ?>"> <?php echo $price ?> </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endif; ?>
                        <div style="clear:both" ></div>
                    </div>
                </div><!-- #end filter -->
                <?php endif; ?>
                <div class="list" id="goldstar-list-feed">
                    
                    <?php 
                    $arr_events = Goldstar_Shortcode::get_list_events_data(array('page' => 1, 'plugin_territory_id' => $plugin_territory_id));
                    $total_event = count($arr_events);
                    
                    $html = Goldstar_Shortcode::get_html_list_events($arr_events, array('page' => 1, 'plugin_territory_id' => $plugin_territory_id));
                    echo $html;
                    ?>                    
                    <div id="goldstar-loading" class="hidden"></div>
                </div>
                <div class="pagination" id="goldstar_pagination">
                </div>
                
                <script type="text/javascript">
                        /* Pass this value to pagination. Remember the goldstar.js must after jquery */
                        var goldstar_paging = goldstar_paging || {};
                        goldstar_paging.total_event = <?php echo $total_event; ?>;
                        goldstar_paging.page_size = <?php echo Goldstar_Shortcode::$page_size ?>;
                        
                        var goldstar_extrainfo = goldstar_extrainfo || {};
                        goldstar_extrainfo.plugin_territory_id = '<?php echo $plugin_territory_id ?>';
                </script>
                
                
            </div>
        </div>
        </div><!-- #content-can-filter-inner-->
    </div><!-- #content-can-filter-->
    </div>
</div><!-- #wrap -->