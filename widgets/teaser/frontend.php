<?php
if(! $instance['num_events']) return false;


$arr_featured_events = get_option('goldstar_featured_events', '1');
$goldstar_options = get_option('goldstar_options');

if ( empty( $arr_featured_events ))  return false;

$title_class = 'goldstar-widget-title';
$container_class = 'goldstar-widget-container';
?>
<style>
    <?php echo '.' . $container_class.' .' . $title_class;  ?>  {
        background-color:   <?php echo $instance['bg_color']                ? $instance['bg_color'] : 'none' ?>;
        color:              <?php echo $instance['title_color']             ? $instance['title_color'] : 'inherit'; ?>;
        font-size:          <?php echo $instance['widget_title_font_size']  ? $instance['widget_title_font_size']. 'px !important' : 'inherit'; ?>;

        <?php

         // rounded
        if ($instance['teaser_widget_title_rounded']):
        $redius = $instance['teaser_widget_title_rounded_radius']; ?>
        border-radius:      <?php echo $redius; ?>px !important;
        -moz-border-radius: <?php echo $redius; ?>px !important;
        -webkit-border-radius: <?php echo $redius; ?>px !important;
        <?php endif; ?>
    }
</style>

<?php
    $title = $instance['title'];
    echo '<div class="'.$container_class.'">'.$args['before_widget'];

    if ( ! empty( $title ) ) {
        ?>
        <div class="<?php echo $title_class ?>">
            <?php echo $args['before_title'] .$title. $args['after_title']; ?>
        </div>
        <?php
    }


    $logo_position = explode('_', $goldstar_options['teaser_widget_logo_position']);
?>

<?php
if(isset($goldstar_options['teaser_widget_logo_url']) && $goldstar_options['teaser_widget_logo_url']):
if ($logo_position[0] == 't' || $logo_position[0] == 'tb'): ?>
    <div style="text-align: <?php echo isset($logo_position[1]) && $logo_position[1] ? $logo_position[1] : 'right' ?>">
        <img src="<?php echo $goldstar_options['teaser_widget_logo_url'];  ?>" />
    </div>
<?php
endif;
endif; ?>

<ul>
    <?php
        $xml = Goldstar_Common::getXmlContentWithTerritory(Goldstar_Common::getCurrentTerritoryId());

        // Check error form read xml
        if(isset($events['error']) && $events['error'] === true) {
//            return 'Error: '. $events['msg'];
              return '';
        }

        /* check choice category or not */
        $goldstar_options = Goldstar_Common::getGoldstarOptions();
        if(empty($goldstar_options['category'])) {
            //echo "<p class='error'>Please active some category</p>";
            return '';
        }

        $events = $xml->xpath(Goldstar_Common::get_xpath_query(array(
                'category' => $goldstar_options['category']
        )));

        /* Find list events_id */
        $events_id = array();
        foreach($events as $event) {
            if($event && isset($event['id']))
                $events_id[] = (string)$event['id'];
        }

        /* just allow feature in category currently */
        $arr_featured_events = array_intersect($arr_featured_events, $events_id);
        $arr_featured_events = array_values($arr_featured_events);

        // foreach usage
        $number_display = intval( $instance['num_events']);
        $number_feature =  sizeof($arr_featured_events);

        for ( $i = 0, $j=0; $i < $number_feature && $j < $number_display; $i++ ):

            // Event time
            $arr_event      = $xml->xpath("//event[@id='".$arr_featured_events[$i]."']");

            if(empty($arr_event)) continue;

            $event = $arr_event[0];
            $title = (string)$event->title_as_text;
            $link = (string)$event->link;

            // YYYY-mm-dd
            $arrupcomming_date = $event->xpath('upcoming_dates/event_date/date');

            $time_end = 0;
            $date_string = '';

            if(isset($arrupcomming_date[0])) {
                $start_upcomming_date = (string)$arrupcomming_date[0];
                $endupcomming_date = (string)end($arrupcomming_date);

                $date_start_arr = explode('-', $start_upcomming_date);
                $time_start = mktime(0, 0, 0, $date_start_arr[1], $date_start_arr[2], $date_start_arr[0]); // 0 -year, 1 -month, 2 -day
                $date_start_time = date('Y-m-d', $time_start);

                $date_end_arr = explode('-', $endupcomming_date);
                $time_end =  mktime(0, 0, 0, $date_end_arr[1], $date_end_arr[2], $date_end_arr[0]);
                $date_end_time = date('Y-m-d', $time_end);

                if($time_start === $time_end) {
                    $date_string = date('M j', $time_start). ','. date(' Y', $time_start);
                }
                else {
                    if($date_end_arr[0]=== $date_start_arr[0]){ // savem year
                        $date_string = date('M j ', $time_start) .' - '. date('M j', $time_end).','. date(' Y', $time_end);
                    }
                    else {
                        $date_string = date('M j', $time_start). ','. date(' Y', $time_start) .' - '. date('M j', $time_end). ','. date(' Y', $time_end);
                    }
                }
            }

            $today = time();

            if($date_string === 'none' || $time_end < $today) {
                continue;
            }

            $j++;// display one feature

            /* image */
            $image = (string)$event->image;
            // 3264 - issue
            $default_width = 250;
            $_arr_search = array("https://i.gse.io", "http://i.gse.io");
            $_arr_replace = array("http://images.goldstar.com");
            $image = str_replace($_arr_search, $_arr_replace, $image);
            if(preg_match('/\?([^=]+)=/', $image)) {
                $image = $image.'&w='.$default_width;
            }
            else {
                $image = $image.'?w='.$default_width;
            }
    
?>
    <li>
        <div class="widget-event-img-wrapper">
            <span class="widget-event-img-mask">
                <a href="<?php echo $link; ?>"><img src="<?php echo $image; ?>" /></a>
            </span>
        </div>
            
        <div class="widget-event-content-right">
            <span><a
                    style="font-weight:<?php echo isset($instance['event_title_bold']) && $instance['event_title_bold'] ? 'bold' : 'normal';  ?>;
                    font-size: <?php echo isset($instance['event_title_font_size']) && $instance['event_title_font_size'] ? $instance['event_title_font_size']. 'px' : 'inherit';  ?>;
                    color: <?php echo isset($instance['event_title_color']) && $instance['event_title_color'] ? $instance['event_title_color'] : '';  ?>;"
                    href="<?php echo $link; ?>"><?php echo $title; ?>
                </a>
            </span>

            <span style="
                  color: <?php echo isset($instance['event_date_color']) && $instance['event_date_color'] ? $instance['event_date_color'] : 'inherit' ?>;
                  font-size: <?php echo isset($instance['event_date_font_size']) && $instance['event_date_font_size'] ? $instance['event_date_font_size']. 'px' : 'inherit' ?>;">
                <?php echo $date_string; ?>
            </span>
        </div>
    </li>
    <?php endfor; ?>
</ul>

<?php if ( isset($goldstar_options['goldstar_slug']) && $goldstar_options['goldstar_slug'] ): ?>
<p class="view-more-events"><a href='<?php echo get_site_url(). '/'. $goldstar_options['goldstar_slug'] ?>'>&raquo; View more events</a></p>
<?php endif;

if(isset($goldstar_options['teaser_widget_logo_url']) && $goldstar_options['teaser_widget_logo_url']):
if ($logo_position[0] == 'b' || $logo_position[0] == 'tb'): ?>
    <div style="text-align: <?php echo isset($logo_position[1]) && $logo_position[1] ? $logo_position[1] : 'right' ?>">
        <a href="<?php echo $goldstar_options['teaser_widget_logo_link_to'];  ?>" target="_blank"><img src="<?php echo $goldstar_options['teaser_widget_logo_url'];  ?>" /></a>
    </div>
<?php
endif;
endif; ?>

<?php echo $args['after_widget'].'</div>';