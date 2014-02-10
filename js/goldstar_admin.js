/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function showLoading() {
    if(document.getElementById("TB_overlay") === null) {
        jQuery("body").append("<div id='TB_overlay'></div><div id='TB_window'></div>");
        jQuery("#TB_overlay").addClass("TB_overlayBG");//use background and opacity
    }

    jQuery("body").append("<div id='TB_load'><img src='"+imgLoader.src+"' width='208' /></div>");//add loader to the page
jQuery('#TB_load').show();//show loader
    jQuery('#TB_load').show();

    jQuery("#TB_overlay").click(tb_remove);
};

function hideLoading() {
    tb_remove();
    jQuery("#TB_load").remove();
};

(function($) {

    $(function() {
        
        $.fn.check_api = function() {
            var $_api_key = $("#affiliate-key").val();
                $.ajax({
                    url: goldstar_obj.admin_url,
                    data: {
                        'action': 'goldstar_get_categories',
                        'api_key': $_api_key
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        showLoading();
                    },
                    success: function(data) {
                        hideLoading();

                        /* This outputs the result of the ajax request */
                        var _html = "";
                        var $_container_row = $("#goldstar-list-categories .inside");

                        /* API is invalid */

                        if(data['list_category']['error'] !== undefined) {
                            $("#goldstar-api-key-success").addClass('hidden');
                            $("#goldstar-api-key-error").removeClass('hidden');
                            $("#affiliate-key-check").val('0');
                            $_container_row.html('');
                            return;
                        } else {
                            $("#goldstar-api-key-success").removeClass('hidden');
                            $("#goldstar-api-key-error").addClass('hidden');

                            /* Hidden variable to confirm api is valid or not */
                            $("#affiliate-key-check").val('1');
                        }

                         $(data['list_category']).each(function(key, value) {
                            var _cate_slug = value['id'];
                            var _str_check = '';

                            if($.inArray(value['name']+'', data['list_select_category']) !== -1) {
                                _str_check = 'checked="true"';
                            }


                            _html += '<div class="row">' +
                                          '<input type="checkbox" name="goldstar_options[category][]" value="'+value['name']+'" id="goldstar-settings-category-'+_cate_slug+'" '+_str_check+' />' +
                                          '<label for="goldstar-settings-category-'+_cate_slug+'">'+value['name']+'</label>' +
                                      '</div>';
                         });

                        $_container_row.html(_html);
                    },
                    error: function(errorThrown) {
                        console.log(errorThrown);
                    }
                });
        };
        /* Bind event for change api key */
        $('#affiliate-key').keyup(function(e) {
            clearTimeout($.data(this, 'timer'));
            if (e.keyCode == 13) {
                $.fn.check_api();
            }
            else
              $(this).data('timer', setTimeout($.fn.check_api, 500));
        });
       

        /* Tb show */
        $("#show-list-territory").click(function() {

            var $_api_key = $("#affiliate-key").val();
            var $_terrritory_id = $("#settings-display-terrid").val();

            var _url = goldstar_obj.admin_url + '?action=goldstar_get_territories&height=300&width=635&territory_id='+$_terrritory_id+'&api_key='+$_api_key;
            tb_show("Territories List", _url);
            
            return false;

        });

        /* Bind event for choice-territory-id */
        $("#choice-territory-id").live('click',function(){ 
            var $_choice = $("#territory-list-container :radio:checked").val();
            if($_choice === undefined) {
                alert('Please choice Territory id.');
                return;
            }

            tb_remove();
            $("#settings-display-terrid").val($_choice);
        });
    });

})(jQuery);
