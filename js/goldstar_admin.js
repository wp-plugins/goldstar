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

        $("#settings-display-color").wpColorPicker();

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
        });

        var formfieldselector = '#teaser-widget-logo-url';

        var formfield   = $(formfieldselector); //The input field that will hold the uploaded file url
        var thumb       = $("#eli-teaser-widget-logo-link img");
        var remove_btn      = $("#eli-delete-teaser-logo");

        // Upload logo
        bind_upload_event();

        function bind_upload_event () {
            /* user clicks button on custom field, runs below code that opens new window */
            jQuery('#_upload-button').click(function() {
                formfield   = $(formfieldselector);
                tb_show('','media-upload.php?TB_iframe=true');
                return false;
            });

            // user inserts file into post. only run custom if user started process using the above process
            // window.send_to_editor(html) is how wp would normally handle the received data

            window.goldstar_send_to_editor = window.send_to_editor;
            window.send_to_editor = function(html){

                if (formfield) {
                    var fileurl = jQuery('img',html).attr('src');
                    formfield.val(fileurl);
                    remove_btn.attr('image-url', fileurl);
                    remove_btn.removeClass('hidden');
                    remove_btn.show();
                    remove_btn.attr('disabled', false);
                    thumb.show();
                    thumb.attr('src', fileurl);
                    thumb.parent().attr('href', fileurl);
                    tb_remove();
                    formfield = null;
                } else {
                    window.goldstar_send_to_editor(html);
                }
            };
        }

        $('#eli-delete-teaser-logo').on('click', delete_image_by_url);
        function delete_image_by_url() {

            var image       = $(this),
                image_url   = image.attr('image-url');

            if (! image_url) {
                console.log('Do not have image-url attribute');
                return false;
            }

            if(confirm('Are you sure ?')) {

                $.ajax({
                    url: goldstar_obj.admin_url,
                    type: 'post',
                    data: {
                        'action': 'goldstar_delete_image_by_url'
                    },
                    beforeSend: function() {
                        showLoading();
                        image.hide();
                        thumb.hide();
                    },
                    success: function(res) {
                        formfield.val('');
                        image.hide();
                        remove_btn.hide();
                        hideLoading();
                    },
                    error: function(errorThrown) {

                    }
                });
            }
        };

    }); /* -End dom ready */

})(jQuery);
