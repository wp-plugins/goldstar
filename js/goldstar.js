/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
(function($) {

    /* Dom is ready */
    $(function() {

        /* global variable */
        var $from_date = $('#filter-from-date');
        var $to_date = $('#filter-to-date');


        /* START PAGINATION AT FIRST */
        if(goldstar_paging.total_event != 0  && (parseInt(goldstar_paging.total_event) > parseInt(goldstar_paging.page_size))) {
            $("#goldstar_pagination").removeClass('eli_hidden');
            $("#goldstar_pagination").pagination({
                items: goldstar_paging.total_event,
                itemsOnPage: goldstar_paging.page_size,
                cssStyle: 'light-theme',
                displayedPages: 3,
                edges: 1,
                onPageClick : function(pageNumber, event) {
                    $.fn.update_page(pageNumber, 'no');
                }
            });
        }
        else {
            $("#goldstar_pagination").addClass('eli_hidden');
        }
        /* END PAGINATION AT FIRST */

        $('body').on('click','.eli_expand-summary',function() {

            var $this = $(this);
            var $parent_row = $this.parents('div.eli_summary');
            var $s_full = $parent_row.find('p.eli_summary-full');
            var $s_short = $parent_row.find('p.eli_summary-short');

            $s_full.removeClass('eli_hidden')
            $s_short.addClass('eli_hidden');

        });

        $('body').on('click','.eli_less-summary', function() {

            var $this = $(this);
            var $parent_row = $this.parents('div.eli_summary');
            var $s_full = $parent_row.find('p.eli_summary-full');
            var $s_short = $parent_row.find('p.eli_summary-short');

            $s_full.addClass('eli_hidden')
            $s_short.removeClass('eli_hidden');

        });

        /* Date time */
        try {
            $from_date.datepicker({
                dateFormat: 'yy-mm-dd',
                showOn: 'button',
                buttonImage: goldstar_obj.calendar_src,
                buttonImageOnly: true
            });

            $to_date.datepicker({
                dateFormat: 'yy-mm-dd',
                showOn: 'button',
                buttonImage: goldstar_obj.calendar_src,
                buttonImageOnly: true
            });
        }
        catch(e) {
            console.log(e);
        }

        /*on("change", function(e) {
            var curDate = $(this).datepicker("getDate");
            var minDate = $from_date.datepicker("getDate");

            if (minDate != null && curDate < minDate) {
                alert("Invalid date");
                $(this).datepicker("setDate", minDate);
            }
        });*/

        $.fn.goldstar_showLoading = function($jmain_element) {

            var _top = $(window).scrollTop() + ($(window).height()/2) - 24 - $jmain_element.offset().top;
            $jmain_element.append('<div class="goldstar-loading" style="top:'+_top+'px"></div>');;

          };


        $("#choice-today").click(function() {
            var $_this = $(this);
            $from_date.val($_this.attr('data-date-from'));
            $to_date.val($_this.attr('data-date-to'));

            $(".goldstar-frontend .eli_filter .eli_button").removeClass('eli_active');
            $(this).addClass('eli_active');
            $.fn.update_page(1, 'yes');
        });


        $("#choice-tomorrow").click(function() {
            var $_this = $(this);
            $from_date.val($_this.attr('data-date-from'));
            $to_date.val($_this.attr('data-date-to'));

            $(".goldstar-frontend .eli_filter .eli_button").removeClass('eli_active');
            $(this).addClass('eli_active');
            $.fn.update_page(1, 'yes');
        });
        $("#choice-weekend").click(function() {
            var $_this = $(this);
            $from_date.val($_this.attr('data-date-from'));
            $to_date.val($_this.attr('data-date-to'));

            $(".goldstar-frontend .eli_filter .eli_button").removeClass('eli_active');
            $(this).addClass('eli_active');
            $.fn.update_page(1, 'yes');
        });
        /*//*/

        $('body').on('click',".eli_expand-offer-date", function() {

            var $this = $(this);
            var $parent_row = $this.parents('div.eli_information');
            var $s_full = $parent_row.find('div.eli_offer-date-content-full');
            var $s_short = $parent_row.find('div.eli_offer-date-content-summary');

            $s_full.removeClass('eli_hidden')
            $s_short.addClass('eli_hidden');

        });

        $("body").on('click','.eli_collapse-offer-date', function() {

            var $this = $(this);
            var $parent_row = $this.parents('div.eli_information');
            var $s_full = $parent_row.find('div.eli_offer-date-content-full');
            var $s_short = $parent_row.find('div.eli_offer-date-content-summary');

            $s_full.addClass('eli_hidden')
            $s_short.removeClass('eli_hidden');

        });

        $.fn.update_page = function($_page,$_repagination) {
           /* Get condition for filter */
            var $_filter_from_date = $from_date;
            var $_from_date = $_filter_from_date.val();
            $_from_date = $_from_date === undefined || $_from_date == $_filter_from_date.attr('placeholder')  ? '' : $_from_date;

            var $_filter_to_date = $to_date;
            var $_to_date = $_filter_to_date.val();
            $_to_date = $_to_date === undefined || $_to_date == $_filter_to_date.attr('placeholder')  ? '' : $_to_date;

            var $_location = $("#filter-by-location").val();
            $_location = $_location === undefined ? '' : $_location;

            var $_price = $("#filter-by-price").val();
            $_price = $_price === undefined ? '' : $_price;

            var $_category = $("#filter-by-category").val();
            $_category = $_category === undefined ? '' : $_category;
            /*//*/

           /* ajax call */
            $.ajax({
                url: goldstar_obj.admin_url,
                data: {
                    action: 'goldstar_get_feed',
                    page: $_page,
                    from_date: $_from_date,
                    to_date: $_to_date,
                    location: $_location,
                    price: $_price,
                    repagination: $_repagination,
                    category: $_category,
                    plugin_territory_id: goldstar_extrainfo.plugin_territory_id
                },
                beforeSend: function() {
                    $.fn.goldstar_showLoading($("#goldstar-list-feed"));
                },
                dataType: 'html',
                success: function(data) {

                    /* This outputs the result of the ajax request */
                    var $_container_row = $("#goldstar-list-feed");
                    $_container_row.html(data);

                    var _itotal_e_page = parseInt(goldstar_paging.total_event);
                    var _ipagesize = parseInt(goldstar_paging.page_size);

                    if($_repagination ==='yes') {
                        /* PAGINATION */
                        if(goldstar_paging.total_event != 0 && _itotal_e_page > _ipagesize ) {
                            $("#goldstar_pagination").removeClass('eli_hidden');
                            $("#goldstar_pagination").pagination({
                                items: goldstar_paging.total_event,
                                itemsOnPage: goldstar_paging.page_size,
                                cssStyle: 'light-theme',
                                displayedPages: 3,
                                edges: 1,
                                onPageClick : function(pageNumber, event) {
                                    $.fn.update_page(pageNumber, 'no');
                                }
                            });
                        }
                        else {
                            $("#goldstar_pagination").addClass('eli_hidden');
                        }
                        /* END PAGINATION */
                    }

                    /* scroll to top */
                    $("html, body").animate({ scrollTop:  $(".goldstar-frontend .eli_content-can-filter").offset().top }, 'slow');
                },
                error: function(errorThrown) {
                    console.log(errorThrown);
                }
            }); /*// End ajax*/
        }

        /* FILTER BUTTON */
        $(".goldstar-frontend .eli_content-can-filter-inner .eli_filter select.eli_select").bind('change', function() {
            var $_page = 1;
            $.fn.update_page($_page, 'yes');
        });

        $from_date.bind('change', function(){

            if($to_date.val() === "") {
                $to_date.val($from_date.val());
            }

            var $_page = 1;
            $.fn.update_page($_page, 'yes');

            /* Auto choice active */
            var $_from_date = $from_date.val();
            var $_to_date = $to_date.val();
            $(".goldstar-frontend .eli_filter .eli_button").removeClass('eli_active');

            if($_from_date !== "" && $_to_date !== "") {
                $(".goldstar-frontend .eli_filter .eli_button[data-date-from="+$_from_date+"][data-date-to="+$_to_date+"]").addClass('eli_active');
            }

        });

        $to_date.bind('change', function(){
            var $_page = 1;
            $.fn.update_page($_page, 'yes');

            /* Auto choice active */
            var $_from_date = $from_date.val();
            var $_to_date = $to_date.val();
            $(".goldstar-frontend .eli_filter .eli_button").removeClass('eli_active');

            if($_from_date !== "" && $_to_date !== "") {
                $(".goldstar-frontend .eli_filter .eli_button[data-date-from="+$_from_date+"][data-date-to="+$_to_date+"]").addClass('eli_active');
            }

        });
        /* END FILTER BUTTON */       
        
        /* DETECT VIEW PORT */
        $.fn.detectViewPort = function() {

            /*  LIMIT AT 480PX */
            var $_container_width = $("#goldstar-list-feed").width();
            if($_container_width <= 480) {
                $(".goldstar-frontend").parent().addClass('goldstar-mobile');
            }
            else {
                $(".goldstar-frontend").parent().removeClass('goldstar-mobile');
            }
        };
        
        $.fn.detectViewPort();
        
        $(window).resize(function() {
            $.fn.detectViewPort();
        });
        
        /* END DETECT VIEW PORT */
    });
    
})(jQuery);