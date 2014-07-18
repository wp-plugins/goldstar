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

        $.fn.checkTerritorIdIsExists = function(territor_id) {
            var isExists = false;
            if($.isEmptyObject(goldstar_obj.list_territory_id)) return false;

            $(goldstar_obj.list_territory_id).each(function(key, value) {
                if(territor_id == value['id']) {
                    isExists = true;
                    return false;
                }
            });

            return isExists;
        };
        
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

                        if(!data['list_category'] || data['list_category']['error'] !== undefined) {
                            $("#goldstar-api-key-success").addClass('hidden');
                            $("#goldstar-api-key-error").removeClass('hidden');
                            $("#affiliate-key-check").val('0');
                            $_container_row.html('');

                            /* Set empty */
                            goldstar_obj.list_territory_id = {};
                            return;
                        } else {
                            $("#goldstar-api-key-success").removeClass('hidden');
                            $("#goldstar-api-key-error").addClass('hidden');
                            goldstar_obj.list_territory_id = data.list_territory_id;

                            /* Hidden variable to confirm api is valid or not */
                            $("#affiliate-key-check").val('1');
                        }

                        var _str_check = '';

                        if($.isEmptyObject(data['list_select_category'])) {
                            data['list_select_category'] = [];
                        }

                         $(data['list_category']).each(function(key, value) {
                            var _cate_slug = value['id'];

                             _str_check = '';
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

        /* Check some settings before submit */
        $("#goldstar-settings-form").submit(function() {

            var messages = '';
            var main_focus = '';

            // Is missing api key
            var value_key = $("#affiliate-key").val();
            var is_missing_key = $.trim(value_key) == "" ? true : false;
            if(is_missing_key) {
                messages += "\n + Affiliate key is missing!";
                main_focus = $("#affiliate-key");
            }

            /* Is missing key */
            if(!is_missing_key) {
                // Is error - check by javascript
                var is_error = $("#goldstar-api-key-success").hasClass('hidden');

                /* Is key error */
                if(is_error) {
                    messages += "\n + Affiliate key is invalid!";
                    main_focus = $("#affiliate-key");
                }

                if(!is_error) {
                    // Missing Territory id
                    var territory_id = $.trim($("#settings-display-terrid").val());
                    var is_missing_territory_id = territory_id == "" ? true : false;

                    if(is_missing_territory_id) {
                        messages += "\n + Territory Id is missing!";
                        main_focus = $("#settings-display-terrid");
                    }

                    if(!is_missing_territory_id) {
                        // Invalid territory id ??
                        var is_invalid_territory_id = false;
                        if(!$.isNumeric(territory_id) || !$.fn.checkTerritorIdIsExists(territory_id)) {
                            is_invalid_territory_id = true;
                        }
                        if(is_invalid_territory_id) {
                            messages += "\n + Territory Id is invalid!";
                            main_focus = $("#settings-display-terrid");
                        }
                    }

                    // Have aleast one category is selected
                    var is_have_aleast_one = $("#goldstar-list-categories input:checked").length > 0 ? true : false;
                    if(!is_have_aleast_one) {
                        messages += "\n + At least on category must be choice!";
                        if(main_focus != '') {
                            main_focus = $("#goldstar-list-categories");
                        }

                    }
                }
            }

            if(messages !== '') {
                messages = "Warning: \n" + messages + "\n\n  Do you want to continue ?";
                if(confirm(messages)) {
                    return true;
                }
                else {
                    if(main_focus != ''){
                        main_focus.focus();
                    }

                    return false;
                }
            }

            return true;
        })
    });

})(jQuery);
