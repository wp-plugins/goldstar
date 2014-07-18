
<?php 
$numShowDate = 3;
if(!empty($arr_datas)):
foreach ($arr_datas as $child): 
    // Offer dates
    $date_array = array();
    $x = 0;
    foreach ($child->upcoming_dates->children() as $event_date) {

        $date_array[$x] = date("D, F d", strtotime($event_date->date));
        $x++;
    }
    $offer_dates_summary = '';
    if(count($date_array) > $numShowDate) {
        $date_array_summary = array_slice($date_array, 0, $numShowDate);
        $offer_dates_summary = implode("<br/>", $date_array_summary);
    }
    $offer_dates_full = implode("<br/>", $date_array);

    // Link
    $link = (string)$child->link;

    // Headline as text
    $headline_as_text = (string)$child->headline_as_text;

    // Venue name
    $venue_name = (string)$child->venue->name;

    // Venue address
    $venue_address = (string)$child->venue->address->locality;

    // Image
    $image = (string)$child->image;

    // Summary 
    $summary = (string)$child->summary_as_text;

    $summary_short = substr($summary, 0, 200).'...';

    // Full price
    $full_price = (string)$child->full_price_range;

    // Out price range
    $our_price = (string)$child->our_price_range;
?>
<div class="eli_row">
<h4 class="eli_title">
    <a  class="eli_a" target="_blank" href="<?php echo $link ?>"><?php echo $headline_as_text ?></a>
    </h4>
<span class="eli_address">
    at <?php echo $venue_name.', '.$venue_address ?>
</span>
<div class="eli_row-main">
    <a target="_blank" href="<?php echo $link ?>" class="eli_img eli_a"><img src="<?php echo $image ?>" class="eli_img" /></a>

    <div class="eli_information">
        <div class="eli_information-inner">
            <div class="eli_information-left">
                <div class="eli_offer-date eli_clear">
                    <?php if(!empty($offer_dates_full)): ?>
                    <span class="eli_offer-date-title">
                        OFFER DATES:
                    </span>
                    <?php if(!empty($offer_dates_summary)): ?>
                    <div class="eli_offer-date-content-summary">
                        <?php echo $offer_dates_summary; ?><br/>
                        <a target="_blank" href="<?php echo $link ?>" class="eli_expand-offer-date eli_a">+ <?php echo count($date_array) - $numShowDate; ?> more dates</a>
                    </div>
                    <?php endif; ?>
                    <div class="eli_offer-date-content-full <?php if(!empty($offer_dates_summary)) { echo 'eli_hidden'; } ?>">
                        <?php echo $offer_dates_full; ?>
                        <?php if(!empty($offer_dates_summary)): ?>
                            <br class="eli_br"/>
                            <a href="javascript:void(0);" class="eli_collapse-offer-date eli_a">[less-]</a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>


            <div class="eli_information-right">
                <?php if(!empty($full_price)): ?>
                <div class="eli_full-price eli_clear">
                    <span class="eli_full-price-title">
                        FULL PRICE: 
                    </span>
                    <span class="eli_full-price-content">
                        <?php echo $full_price ?>
                    </span>
                </div>
                <?php endif; ?>

                <?php if(!empty($our_price)): ?>
                <div class="eli_our-price eli_clear">
                    <span class="eli_our-price-title">
                        OFFER PRICE: 
                    </span>
                    <span class="eli_our-price-content">
                        <?php echo $our_price ?>
                    </span>
                </div>
                <?php endif; ?>
                <div class="eli_button-container">
                    <a target="_blank" href="<?php print $link ?>" class="eli_button eli_large"><span class="eli_span">Check Availability</span></a>
                </div>
            </div>
        </div>
    </div> <!--#information -->

</div>

<div class="eli_summary">
    <p class="eli_summary-short eli_p">
        <?php echo $summary_short ?>
        <a href="javascript:void(0);" class="eli_expand-summary eli_a">[more+]</a>
    </p>
    <p class="eli_summary-full eli_hidden eli_p">
        <?php echo $summary ?>
        <a href="javascript:void(0);" class="eli_less-summary eli_a">[less-]</a>
    </p>
</div>

<div style="clear:both"></div>
</div><!-- #end eli_row -->
<?php endforeach; ?>


    <?php if(isset($arr_filter['repagination']) && $arr_filter['repagination'] === 'yes'): ?>
    <script type="text/javascript">
        var goldstar_paging = goldstar_paging || {};
        goldstar_paging.total_event = <?php echo $total_event; ?>;
        goldstar_paging.page_size = <?php echo Goldstar_Shortcode::$page_size ?>;
    </script>
    <?php endif; ?>


<?php else: ?>
    Data not found!
    <script type="text/javascript">
        var goldstar_paging = goldstar_paging || {};
        goldstar_paging.total_event = 0;        
    </script>
<?php endif; ?>
