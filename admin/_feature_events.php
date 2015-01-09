<?php require_once '_admin_menu.php';

$xml = Goldstar_Common::getXmlContentWithTerritory(Goldstar_Common::getCurrentTerritoryId());
if(isset($xml) && $xml['error'] === true) {
    echo "<p class='error'>".$xml['msg']."</p>";
    die;
}

/*Filter by category*/
$goldstar_options = Goldstar_Common::getGoldstarOptions();
if(empty($goldstar_options['category'])) {
    echo "<p class='error'>Please active some category</p>";
    die;
}

$events = $xml->xpath(Goldstar_Common::get_xpath_query(array(
            'category' => $goldstar_options['category']
        )));

$arr_feature_event = Goldstar_Feature_Event_Page::getFeatureEvents();
if(empty($arr_feature_event)) $arr_feature_event = array();
?>
<div class="wrap clear">
    <style>input[type='text'] { width:200px; padding:4px; } </style>


    <fieldset>
        <legend><h2>Featured Events Listing</h2></legend>
        <hr/>
        <form method="post" >
            <p id="processing-goldstar">Processing ...</p>

            <input type="hidden" id="goldstar-selected-events"
                   name="goldstar-selected-events"
                   value="<?php if ( is_array($arr_feature_event) && !empty($arr_feature_event) ) echo implode(',', $arr_feature_event); ?>" />

            <table style="visibility: hidden" cellpadding="0" cellspacing="0" border="0" class="display" id="goldstar-featured-events" width="100%">
                <thead>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th style="width: 200px;">Date Range</th>
                    <th>Presenting Org Name</th>
                    <th style="width: 90px;">City Location</th>
                </tr>
                </thead>

                <?php

                if( $events ) {

                    foreach ( $events as $event ) {

                        /* parse data */
                        $title = (string)$event->title_as_text;
                        $link = (string)$event->link;

                        $arr_event_id = ($event->xpath('@id'));
                        $event_id = (string)$arr_event_id[0];

                        $arr_venue_city = ($event->xpath('venue/name'));
                        $venue_city = (string)$arr_venue_city[0];

                        $arr_address = ($event->xpath('venue/address/locality'));
                        $address = (string)$arr_address[0];

                        // YYYY-mm-dd
                        $arrupcomming_date = $event->xpath('upcoming_dates/event_date/date');

                        $date = 'none';

                        if(isset($arrupcomming_date[0])) {
                            $start_upcomming_date = (string)$arrupcomming_date[0];
                            $endupcomming_date = (string)end($arrupcomming_date);

                            $date_start_arr = explode('-', $start_upcomming_date);
                            $date_start_time = date('Y-m-d', mktime(0, 0, 0, $date_start_arr[1], $date_start_arr[2], $date_start_arr[0]));

                            $date_end_arr = explode('-', $endupcomming_date);
                            $date_end_time = date('Y-m-d', mktime(0, 0, 0, $date_end_arr[1], $date_end_arr[2], $date_end_arr[0]));

                            $date = $date_start_time .' to '. $date_end_time;
                        }

                        // Custom url follow the permalink structure
                        ?>
                        <tr class="odd gradeX">
                            <td>
                                <input name="event_ids[]" value="<?php echo $event_id; ?>"
                                    <?php echo in_array($event_id, $arr_feature_event) ? 'checked' : ''  ?> type="checkbox" />
                            </td>
                            <td><a target="_blank" href='<?php echo $link ?>'><?php echo $title; ?></a></td>
                            <td><?php echo $date ; ?></td>
                            <td><?php echo $venue_city; ?></td>
                            <td><?php echo $address ?></td>
                        </tr>
                    <?php
                    }
                } ?>

                <tbody></tbody>

            </table>

            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
            <form>
    </fieldset>
</div>
<script type="text/javascript">
    (function($) {
        /*  Dom is ready */
        $(function() {

            /* PAGINATION FOR TABLE */
            var $_goldstar_selected_events = $('#goldstar-selected-events');

            var selected_event_obj = $('#goldstar-selected-events'),
                arr_selected_event = selected_event_obj.val();

            if ( ! selected_event_obj.length ) {
                return false;
            }

            if (arr_selected_event == undefined) {
                arr_selected_event = '';
            }

            arr_selected_event = arr_selected_event.split(',');
            $table = $("#goldstar-featured-events");

            var _all_event_bycat = [];

            $table.dataTable({
                "aaSorting": [[ 4, "desc" ]],
                "fnDrawCallback": function() {
                    // in case your overlay needs to be put away automatically you can put it here
                    $('#goldstar-featured-events').css('visibility', 'inherit');
                    $('#processing-goldstar').hide();

                    /* intersect */
                    $.arrIntersect = function(a,b) {
                        return $.grep(b, function(i) {
                            return $.inArray(i,a) > -1;
                        });
                    };

                    arr_selected_event = $.arrIntersect(arr_selected_event, _all_event_bycat);
                    $_goldstar_selected_events.val(arr_selected_event.filter(Boolean).join(","));
                },
                "fnCreatedRow": function ( row, data, index ) {

                    if ( row.childNodes[1] != undefined ) {

                        var checkbox = row.childNodes[1].children[0],
                            event_id = checkbox.value;

                        if ( jQuery.inArray(event_id.toString(), arr_selected_event) != -1 ) {
                            $(checkbox).attr('checked', true);
                        }
                        _all_event_bycat[index] = event_id;
                    }
                }
            });

            /* BIND EVEN WHEN CLICK  */
            $table.on('click', "input[name='event_ids[]']", function() {
                var
                    $this = $(this),
                    val = $this.val(),
                    checked = $this.is(':checked');

                if(checked === true) {
                    arr_selected_event.push(val);
                }
                else {
                    arr_selected_event.splice($.inArray(val.toString(), arr_selected_event), 1);
                }

                $_goldstar_selected_events.val(arr_selected_event.filter(Boolean).join(","));
            });
        });
    })(jQuery);
</script>