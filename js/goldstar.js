/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
(function($) {

    /* Dom is ready */
    $(function() {
        
        /* START PAGINATION AT FIRST */
        if(goldstar_paging.total_event != 0 ) {
            $("#goldstar_pagination").removeClass('hidden');
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
            $("#goldstar_pagination").addClass('hidden');
        }
        /* END PAGINATION AT FIRST */
        
        $('.expand-summary').live('click',function() {

            var $this = $(this);
            var $parent_row = $this.parents('div.summary');
            var $s_full = $parent_row.find('p.summary-full');
            var $s_short = $parent_row.find('p.summary-short');

            $s_full.removeClass('hidden')
            $s_short.addClass('hidden');

        });
        
        $('.less-summary').live('click',function() {

            var $this = $(this);
            var $parent_row = $this.parents('div.summary');
            var $s_full = $parent_row.find('p.summary-full');
            var $s_short = $parent_row.find('p.summary-short');

            $s_full.addClass('hidden')
            $s_short.removeClass('hidden');            

        });

        /* Date time */
        $("#filter-from-date").datepicker({
            dateFormat: 'yy-mm-dd',
            showOn: 'button', 
            buttonImage: goldstar_obj.calendar_src, 
            buttonImageOnly: true
        });
        
        $("#filter-to-date").datepicker({
            dateFormat: 'yy-mm-dd',
            showOn: 'button', 
            buttonImage: goldstar_obj.calendar_src, 
            buttonImageOnly: true
        });
        /*on("change", function(e) {
            var curDate = $(this).datepicker("getDate");
            var minDate = $("#filter-from-date").datepicker("getDate");

            if (minDate != null && curDate < minDate) {
                alert("Invalid date");
                $(this).datepicker("setDate", minDate);
            }
        });*/
        
        $.fn.goldstar_showLoading = function($jmain_element) {
    
            var $r_left = 0;
            var $r_top = 0;
            var $r_width = 0;
            var $r_height = 0;

            /* caculate position to show loading */
            var $screen_width = $(window).width();
            var $screen_height = $(window).height();
            
            var $c_left =$jmain_element.position().left;
            var $c_width = $jmain_element.outerWidth();
            
            var $c_top = $jmain_element.position().top;
            var $c_height = $jmain_element.outerHeight();

            var $screen_top_on_d = document.documentElement.scrollTop || document.body.scrollTop;
            var $screen_left_on_d = document.documentElement.scrollLeft || document.body.scrollLeft;

            $r_left = $c_left;
            $r_top = $screen_top_on_d - $jmain_element.offset().top
            $r_width = $c_width;
            
            $r_height = $c_height;

            if($screen_top_on_d < $c_top && $screen_top_on_d + $screen_width > $c_top && $screen_top_on_d + $screen_width < $c_top + $c_height) {
              $r_height = $screen_top_on_d + $screen_height - $jmain_element.offset().top;
            }
            else if($screen_top_on_d >= $c_top && $screen_top_on_d + $screen_width >= $c_top && $screen_top_on_d + $screen_width <= $c_top + $c_height) {
              $r_height = $screen_height;
            }
            else if($screen_top_on_d >= $c_top && $screen_top_on_d + $screen_width >= $c_top && $screen_top_on_d + $screen_width > $c_top + $c_height) {
              $r_height = $jmain_element.offset().top + $c_height - $screen_top_on_d;
            }
            $jmain_element.append('<div class="goldstar-loading" style="left: '+$r_left+'px; top : '+$r_top+'px; width : '+$r_width+'px; height : '+$r_height+'px"></div>');;
            //console.log('<div class="goldstar-loading" style="left: '+$r_left+'px; top : '+$r_top+'px; width : '+$r_width+'px; height : '+$r_height+'px"></div>');
          };
        
        $.fn.goldstar_hideLoading = function() {
            $("#goldstar-loading").addClass('hidden');
        };
        
        $("#choice-today").click(function() {
            var $_this = $(this);
            $("#filter-from-date").val($_this.attr('data-date-from'));
            $("#filter-to-date").val($_this.attr('data-date-to'));
            
            $(".goldstar-frontend .filter .button").removeClass('active');
            $(this).addClass('active');
            $.fn.update_page(1, 'yes');
        });
        
        
        $("#choice-tomorrow").click(function() {
            var $_this = $(this);
            $("#filter-from-date").val($_this.attr('data-date-from'));
            $("#filter-to-date").val($_this.attr('data-date-to'));
            
            $(".goldstar-frontend .filter .button").removeClass('active');
            $(this).addClass('active');
            $.fn.update_page(1, 'yes');
        });
        $("#choice-weekend").click(function() {
            var $_this = $(this);
            $("#filter-from-date").val($_this.attr('data-date-from'));
            $("#filter-to-date").val($_this.attr('data-date-to'));
            
            $(".goldstar-frontend .filter .button").removeClass('active');
            $(this).addClass('active');
            $.fn.update_page(1, 'yes');
        });
        /*//*/
        
        $(".expand-offer-date").live('click',function() {

            var $this = $(this);
            var $parent_row = $this.parents('div.information');
            var $s_full = $parent_row.find('div.offer-date-content-full');
            var $s_short = $parent_row.find('div.offer-date-content-summary');

            $s_full.removeClass('hidden')
            $s_short.addClass('hidden');

        });

        $(".collapse-offer-date").live('click',function() {

            var $this = $(this);
            var $parent_row = $this.parents('div.information');
            var $s_full = $parent_row.find('div.offer-date-content-full');
            var $s_short = $parent_row.find('div.offer-date-content-summary');

            $s_full.addClass('hidden')
            $s_short.removeClass('hidden');

        });
        
        $.fn.update_page = function($_page,$_repagination) {
           /* Get condition for filter */
            var $_from_date = $("#filter-from-date").val();
            $_from_date = $_from_date === undefined ? '' : $_from_date;
            
            var $_to_date = $("#filter-to-date").val();
            $_to_date = $_to_date === undefined ? '' : $_to_date;
            
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
                    
                    if($_repagination ==='yes') {
                        /* PAGINATION */
                        if(goldstar_paging.total_event != 0 ) {
                            $("#goldstar_pagination").removeClass('hidden');
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
                            $("#goldstar_pagination").addClass('hidden');
                        }
                        /* END PAGINATION */
                    }
                    
                    /* scroll to top */
                    $("html, body").animate({ scrollTop:  $(".goldstar-frontend .content-can-filter").offset().top }, 'slow');
                },
                error: function(errorThrown) {
                    console.log(errorThrown);
                }
            }); /*// End ajax*/
        }
        
        /* FILTER BUTTON */
        $(".goldstar-frontend .content-can-filter-inner .filter select").bind('change', function() {            
            var $_page = 1;
            $.fn.update_page($_page, 'yes');
        });
        
        $("#filter-from-date").bind('change', function(){ 
            $("#filter-to-date").val($("#filter-from-date").val());            
            var $_page = 1;
            $.fn.update_page($_page, 'yes');
            
            /* Auto choice active */
            var $_from_date = $("#filter-from-date").val();
            var $_to_date = $("#filter-to-date").val();
            $(".goldstar-frontend .filter .button").removeClass('active');
            $(".goldstar-frontend .filter .button[data-date-from="+$_from_date+"][data-date-to="+$_to_date+"]").addClass('active');
        });
            
        $("#filter-to-date").bind('change', function(){ 
            var $_page = 1;
            $.fn.update_page($_page, 'yes');
            
            /* Auto choice active */
            var $_from_date = $("#filter-from-date").val();
            var $_to_date = $("#filter-to-date").val();
            $(".goldstar-frontend .filter .button").removeClass('active');
            $(".goldstar-frontend .filter .button[data-date-from="+$_from_date+"][data-date-to="+$_to_date+"]").addClass('active');
        });
        /* END FILTER BUTTON */       
        
        /* DETECT VIEW PORT */
        
        $.fn.detectViewPort = function() {
          var $_container_width = jQuery("#goldstar-list-feed").width();
          if($_container_width <= 480) {
              $(".goldstar-frontend").addClass('goldstar-mobile');
          }
          else {
              $(".goldstar-frontend").removeClass('goldstar-mobile');
          }
        };
        
        $.fn.detectViewPort();
        
        $(window).resize(function() {
            $.fn.detectViewPort();
        });
        
        /* END DETECT VIEW PORT */
    });
    
})(jQuery);