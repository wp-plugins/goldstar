<?php
// $is_show_error: show error or not
// $goldstar_options['api_key']: api key
// $goldstar_options['api_valid']: api valid or not
// $is_show_error == false mean: at the first time don't save any api

$goldstar_options['goldstar_slug'] = isset($goldstar_options['goldstar_slug']) ? $goldstar_options['goldstar_slug'] : '';
$goldstar_options['teaser_widget_logo_url'] = isset($goldstar_options['teaser_widget_logo_url']) ? $goldstar_options['teaser_widget_logo_url'] : '';
$goldstar_options['teaser_widget_logo_position'] = isset($goldstar_options['teaser_widget_logo_position'] ) ? $goldstar_options['teaser_widget_logo_position'] : '';
$goldstar_options['teaser_widget_logo_link_to'] = isset($goldstar_options['teaser_widget_logo_link_to']) ? $goldstar_options['teaser_widget_logo_link_to'] : '';
?>

<?php require_once '_admin_menu.php'; ?>
<div class="wrap goldstar-admin">
    <?php settings_errors(); ?>
    <div id="icon-edit-pages" class="icon32"></div>
    <h2>Add New Post</h2>

    <div id="goldstar-notice" class="error hidden below-h2">

    </div>

    <div id="goldstar-body" class="metabox-holder columns-2">
        <div id="goldstar-body-content">
            <form method="post" action="options.php" id="goldstar-settings-form">
            <?php settings_fields('goldstar-group'); 
            do_settings_sections( 'goldstar-group' ); ?>
            <!--titlediv-->
            <div id="titlediv">
                <div id="titlewrap">
                    <input type="text" name="goldstar_options[title]" size="30" value="<?php echo isset($goldstar_options['title']) ?  $goldstar_options['title'] : '' ?>" id="title" autocomplete="off" placeholder="Enter the title">
                </div>
                <div class="inside">
                    <div id="edit-slug-box" class="hide-if-no-js">
                    </div>
                </div>
            </div>
            <!--//titlediv-->

            <div class="meta-box-sortables ui-sortable">
                <div id="goldstar-info" class="postbox">
                    <h3 class="hndle"><span>Goldstar API Information</span></h3>
                    <div class="inside">
                        <strong> For more information visit <a href="<?php echo $goldstar_plugin_page ?>">Goldstar plugin</a> page.</strong>
                        <div class="warning" id="goldstar-message">
                            <p>You must obtain a Goldstar affliate ID and AP key in order for this plugin to become operational. Please <a href="<?php echo $goldstar_contact_link ?>">contact Goldstar</a>.</p>
                        </div>

                        <div id="goldstar-info-detail" class="child">
                            <h4 class="hndle"><span>Goldstar Affiliate ID and API Key </span></h4>
                            <div class="inside">
                                <table class="form-table">
                                    <tbody>
                                        <tr valign="top">
                                            <th scope="row">
                                                <label for="affiliate-id">Goldstar Affiliate ID:</label>
                                            </th>
                                            <td>
                                                <input name="goldstar_options[affiliate_id]" type="text" id="affiliate-id" value="<?php echo $goldstar_options['affiliate_id'] ?>" class="regular-text code">
                                            </td>
                                        </tr>

                                        <tr valign="top">
                                            <th scope="row">
                                                <label for="affiliate-key">Goldstar API key:</label>
                                            </th>
                                            <td>
                                                <input name="goldstar_options[api_key]" type="text" id="affiliate-key" value="<?php echo $goldstar_options['api_key'] ?>" class="regular-text code">
                                                <input name="goldstar_options[api_valid]" type="hidden" id="affiliate-key-check" value="<?php echo $goldstar_options['api_valid'] ?>"  />
                                                <div class="goldstar-status-container" >
                                                    <div class="goldstar-error <?php echo $class_error ?>" id="goldstar-api-key-error">
                                                        <li class="goldstar-message">API key is not valid</li>
                                                    </div>

                                                    <div class="goldstar-success <?php echo $class_success ?>" id="goldstar-api-key-success">
                                                        <li class="goldstar-message">API key is valid</li>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div><!-- #goldstar-info-->

                <div id="goldstar-content" class="postbox">
                    <?php wp_editor($goldstar_options['content'], 'content', array('textarea_name' => 'goldstar_options[content]')); ?>
                </div> <!-- #goldstar-content-->

                <div id="goldstar-widget-settings" class="postbox">
                    <h3 class="hndle"><span>Teaser Widget Settings</span></h3>
                    <div class="inside">
                        <div class="goldstar-settings-display child">
                            <h4 class="hndle"><span>URL Settings</span></h4>
                            <div class="inside">
                                <table class="form-table">
                                    <tbody>
                                    <tr valign="top">
                                        <th scope="row">
                                            <label for="settings-display-color">Goldstar's slug:</label>
                                        </th>
                                        <td>
                                            <input name="goldstar_options[goldstar_slug]" type="text" id="goldstar-slug" value="<?php echo $goldstar_options['goldstar_slug'] ?>" class="regular-text code">
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="goldstar-settings-display child">
                            <h4 class="hndle"><span>Logo Settings</span></h4>
                            <div class="inside">
                                <table class="form-table">
                                    <tbody>
                                    <tr valign="top">
                                        <th scope="row">
                                            <label for="settings-display-color">URL:</label>
                                        </th>
                                        <td>
                                            <input readonly="true" placeholder="Click to upload a new graphic" name="goldstar_options[teaser_widget_logo_url]" type="text" id="teaser-widget-logo-url" value="<?php echo $goldstar_options['teaser_widget_logo_url'] ?>" class="regular-text code">
                                            <input  id="_upload-button" class="button" type="button" value="Upload Logo" />

                                            <input opt-name="teaser_widget_logo_url" id="eli-delete-teaser-logo"
                                                   image-url="<?php echo $goldstar_options['teaser_widget_logo_url'] ?>"
                                                   class="delete-btn <?php if (! $goldstar_options['teaser_widget_logo_url']) echo 'hidden'; ?>"
                                                   type="button" value="&nbsp;" />
                                            <div>
                                                <a id="eli-teaser-widget-logo-link" href="<?php echo $goldstar_options['teaser_widget_logo_url'] ?>">
                                                    <img
                                                        <?php if (! $goldstar_options['teaser_widget_logo_url'] ): ?>
                                                            style="display: none"
                                                        <?php endif; ?>
                                                        class="thumb" src="<?php echo $goldstar_options['teaser_widget_logo_url'] ?>" />
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <th scope="row">
                                            <label for="settings-display-color">Position:</label>
                                        </th>
                                        <td>
                                            <?php $teaser_widget_logo_position = $goldstar_options['teaser_widget_logo_position'] ?>
                                            <select class="arts-select" name="goldstar_options[teaser_widget_logo_position]">
                                                <option <?php echo $teaser_widget_logo_position == 'b_left' ? 'selected' : '' ?> value="b_left">Bottom - Left</option>
                                                <option <?php echo $teaser_widget_logo_position == 'b_right' ? 'selected' : '' ?> value="b_right">Bottom - Right</option>
                                                <option <?php echo $teaser_widget_logo_position == 'b_center' ? 'selected' : '' ?> value="b_center">Bottom - Center</option>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr valign="top">
                                        <th scope="row">
                                            <label for="settings-display-color">Link to:</label>
                                        </th>
                                        <td>
                                            <input name="goldstar_options[teaser_widget_logo_link_to]" type="text" id="teaser-widget-logo-link-to" value="<?php echo $goldstar_options['teaser_widget_logo_link_to'] ?>" class="regular-text code">
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

                <div id="goldstar-settings" class="postbox">
                    <h3 class="hndle"><span>Plugin Display Settings</span></h3>
                    <div class="inside">

                        <!--goldstar-settings-display -->
                        <div class="goldstar-settings-display child">
                            <h4 class="hndle"><span>Display Settings</span></h4>
                            <div class="inside">

                                <table class="form-table">
                                    <tbody>
<!--                                        <tr valign="top">-->
<!--                                            <th scope="row">-->
<!--                                                <label for="settings-display-color">Navigation background color:</label>-->
<!--                                            </th>-->
<!--                                            <td>-->
<!--                                                <input name="goldstar_options[settings_display_color]" type="text" id="settings-display-color" value="--><?php //echo $goldstar_options['settings_display_color'] ?><!--" class="regular-text code">-->
<!--                                            </td>-->
<!--                                        </tr>-->

                                        <tr valign="top">
                                            <th scope="row">
                                                <label for="settings-display-order">Default display order:</label>
                                            </th>
                                            <td>
                                                <select name="goldstar_options[settings_display_order]" id="settings-display-order">
                                                    <?php foreach ($arr_display_order as $key => $label): ?>
                                                        <option value="<?php echo $key ?>" <?php if( $goldstar_options['settings_display_order'] == $key ) echo 'selected' ?> > 
                                                            <?php echo $label ?> </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr valign="top">
                                            <th scope="row">
                                                <label for="settings-display-terrid">Territory ID:</label>
                                            </th>
                                            <td>
                                                <input name="goldstar_options[territory_id]" type="text" id="settings-display-terrid" value="<?php echo $goldstar_options['territory_id'] ?>" class="regular-text code">
                                                <div class="description">
                                                    (<a href="<?php echo $goldstar_territory_link; ?>" id="show-list-territory"> click here </a> to view avaiable territories)
                                                </div>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>

                            </div>
                        </div><!--//goldstar-settings-display -->

                        <!-- goldstar-settings-category -->
                        <div class="goldstar-settings-category child" id="goldstar-list-categories">
                            <h4 class="hndle"><span>Category Selection</span></h4>
                            <div class="inside eli_clearfix">
                                <?php foreach ($arr_category as $index => $arr_cate): ?>
                                        <div class="row">
                                            <input type="checkbox" name="goldstar_options[category][]" 
                                                   <?php if(!empty($goldstar_options["category"]) && in_array($arr_cate['name'], $goldstar_options["category"])) { echo 'checked="true"';  } ?>
                                                   value="<?php echo $arr_cate['name'] ?>" id="goldstar-settings-category-<?php echo $arr_cate['id'] ?>" />
                                            <label for="goldstar-settings-category-<?php echo $arr_cate['id'] ?>"><?php echo $arr_cate['name'] ?></label>
                                        </div>
                                <?php endforeach; ?>
                            </div>
                            <div style="clear: both"></div>
                        </div><!-- #goldstar-settings-category -->

                        <!-- goldstar-settings-category -->
                        <div class="goldstar-settings-filter-display child">
                            <h4 class="hndle"><span>Filter Display Settings</span></h4>
                            <div class="inside">

                                <?php foreach ($arr_filter_display as $index => $filter_name):
                                    $slug_filter_name = strtolower($filter_name);
                                    ?>
                                    <div class="row">
                                        <div class="desc"><?php echo $filter_name ?> filter</div>
                                        <label><input type="radio" name ="goldstar_options[filter_<?php echo $slug_filter_name ?>]" value="1" <?php if(isset($goldstar_options["filter_$slug_filter_name"]) && $goldstar_options["filter_$slug_filter_name"] == '1') echo 'checked="true"' ?>  /> On </label>
                                        <label><input type="radio" name ="goldstar_options[filter_<?php echo $slug_filter_name; ?>]" value="0" <?php if(isset($goldstar_options["filter_$slug_filter_name"]) &&$goldstar_options["filter_$slug_filter_name"] == '0') echo 'checked="true"' ?> /> Off </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div><!-- #goldstar-settings-category -->

                        <?php submit_button(); ?>


                    </div><!-- inside 1 -->

                </div><!-- //#goldstar-settings -->
            </div><!-- //meta-box-sortables ui-sortable -->

            </form><!-- #end form -->
        </div><!-- #goldstar-body-content -->
    </div> <!-- #goldstar-body -->

</div>  <!-- #wrap-->

<script type="text/javascript">
    var goldstar_obj = goldstar_obj || {};
    goldstar_obj.admin_url = '<?php echo $admin_url ?>';

    <?php
    if($is_show_error == true && $goldstar_options['api_valid'] == 1): ?>
        goldstar_obj.list_territory_id = <?php echo Goldstar_API::getTerritories($goldstar_options['api_key'], true); ?>
    <?php endif; ?>
</script>