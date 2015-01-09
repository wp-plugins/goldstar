<?php
$title                  = isset( $instance['title'] ) ? $instance['title'] : Goldstar_Teaser_Widget::TEASER_WIDGET_DEFAULT_TITLE;
$bg_color               = isset( $instance['bg_color'] ) ? $instance['bg_color'] : Goldstar_Teaser_Widget::TEASER_WIDGET_DEFAULT_BG_COLOR;
$title_color            = isset( $instance['title_color'] ) ? $instance['title_color'] : Goldstar_Teaser_Widget::TEASER_WIDGET_DEFAULT_TITLE_COLOR;
$link                   = isset( $instance['link'] ) ? $instance['link'] : '';
$num_events             = isset( $instance['num_events'] ) ? $instance['num_events'] : Goldstar_Teaser_Widget::TEASER_WIDGET_DEFAULT_NUM_DISPLAY;
$event_title_bold       = isset( $instance['event_title_bold'] ) && $instance['event_title_bold'] ? 1 : 0;
$event_title_font_size  = isset( $instance['event_title_font_size'] ) ? $instance['event_title_font_size'] : Goldstar_Teaser_Widget::TEASER_WIDGET_DEFAULT_EVENT_TITLE_SIZE;
$event_date_font_size   = isset( $instance['event_date_font_size'] ) ? $instance['event_date_font_size'] : Goldstar_Teaser_Widget::TEASER_WIDGET_DEFAULT_EVENT_DATE_SIZE;
$event_date_color       = isset( $instance['event_date_color'] ) ? $instance['event_date_color'] : Goldstar_Teaser_Widget::TEASER_WIDGET_DEFAULT_TITLE_COLOR;
$widget_title_font_size = isset( $instance['widget_title_font_size'] ) ? $instance['widget_title_font_size'] : Goldstar_Teaser_Widget::TEASER_WIDGET_DEFAULT_EVENT_TITLE_SIZE;
$event_title_color      = isset( $instance['event_title_color'] ) ? $instance['event_title_color'] : '';
$teaser_widget_title_rounded    = isset( $instance['teaser_widget_title_rounded'] ) ? $instance['teaser_widget_title_rounded'] : '';
$teaser_widget_title_rounded_radius = isset( $instance['teaser_widget_title_rounded_radius'] )
&& $instance['teaser_widget_title_rounded_radius'] ? $instance['teaser_widget_title_rounded_radius'] : Goldstar_Teaser_Widget::TEASER_WIDGET_DEFAULT_ROUNDED_CORNER_RADIUS;
?>
<p>
    <label for="<?php echo $this->get_field_id( 'num_events' ); ?>"><?php _e( 'Total events for displaying:' ); ?></label> 
    <input class="widefat" id="<?php echo $this->get_field_id( 'num_events' ); ?>" 
           name="<?php echo $this->get_field_name( 'num_events' ); ?>" type="text" 
           value="<?php echo esc_attr( $num_events ); ?>" />
</p>

<fieldset class="teaser-widget-fieldset">
    <legend>Widget Title</legend>
    <p>
        <label for="<?php echo $this->get_field_id( 'widget_title_font_size' ); ?>"><?php _e( 'Font size:' ); ?></label> 
        <select id="<?php echo $this->get_field_id( 'widget_title_font_size' ); ?>"
                name="<?php echo $this->get_field_name('widget_title_font_size'); ?>">
            <?php for ($i = 1; $i <= 100; $i++): ?>
            <option <?php echo $widget_title_font_size == $i ? 'selected' : ''; ?> value="<?php echo $i; ?>"><?php echo $i; ?>px</option>
            <?php endfor; ?>
        </select>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" 
               name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" 
               value="<?php echo esc_attr( $title ); ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'bg_color' ); ?>"><?php _e( 'Background color:' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'bg_color' ); ?>" 
               name="<?php echo $this->get_field_name( 'bg_color' ); ?>" type="text" 
               value="<?php echo esc_attr( $bg_color ); ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'title_color' ); ?>"><?php _e( 'Text color:' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'title_color' ); ?>" 
               name="<?php echo $this->get_field_name( 'title_color' ); ?>" type="text" 
               value="<?php echo esc_attr( $title_color ); ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'teaser_widget_title_rounded' ); ?>"><?php _e( 'Rounded corners:' ); ?></label> 
        <input class="teaser_widget_title_rounded" name="<?php echo $this->get_field_name( 'teaser_widget_title_rounded' ); ?>" 
               id="<?php echo $this->get_field_id( 'teaser_widget_title_rounded' ); ?>" 
               type="checkbox" 
               <?php echo esc_attr( $teaser_widget_title_rounded ) == 1 ? 'checked': '' ?> />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'teaser_widget_title_rounded_radius' ); ?>"><?php _e( 'Rounded corner radius:' ); ?></label> 
        <select 
            class="teaser_widget_title_rounded_radius" id="<?php echo $this->get_field_id( 'teaser_widget_title_rounded_radius' ); ?>"
                name="<?php echo $this->get_field_name('teaser_widget_title_rounded_radius'); ?>">
            <?php for ($i = 1; $i <= 20; $i++): ?>
            <option <?php echo $teaser_widget_title_rounded_radius == $i ? 'selected' : ''; ?> value="<?php echo $i; ?>"><?php echo $i; ?>px</option>
            <?php endfor; ?>
        </select>
    </p>
</fieldset>    

<fieldset class="teaser-widget-fieldset">
    <legend>Event Title</legend>
    <p>
        <label for="<?php echo $this->get_field_id( 'event_title_font_size' ); ?>"><?php _e( 'Font size:' ); ?></label> 
        <select id="<?php echo $this->get_field_id( 'event_title_font_size' ); ?>"
                name="<?php echo $this->get_field_name('event_title_font_size'); ?>">
            <?php for ($i = 1; $i <= 100; $i++): ?>
            <option <?php echo $event_title_font_size == $i ? 'selected' : ''; ?> value="<?php echo $i; ?>"><?php echo $i; ?>px</option>
            <?php endfor; ?>
        </select>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'event_title_color' ); ?>"><?php _e( 'Text color:' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id('event_title_color'); ?>" 
               name="<?php echo $this->get_field_name('event_title_color'); ?>" type="text" 
               value="<?php echo esc_attr( $event_title_color ); ?>" />
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'event_title_bold' ); ?>"><?php _e( 'Bold text:' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'event_title_bold' ); ?>" 
               name="<?php echo $this->get_field_name( 'event_title_bold' ); ?>" type="checkbox" 
               <?php echo esc_attr( $event_title_bold ) == 1 ? 'checked': '' ?> />
    </p>
</fieldset>

<fieldset  class="teaser-widget-fieldset">
    <legend>Event Date</legend>
    <p>
        <label for="<?php echo $this->get_field_id( 'event_date_font_size' ); ?>"><?php _e( 'Font size:' ); ?></label> 
        <select id="<?php echo $this->get_field_id( 'event_date_font_size' ); ?>"
                name="<?php echo $this->get_field_name('event_date_font_size'); ?>">
            <?php for ($i = 1; $i <= 100; $i++): ?>
            <option <?php echo $event_date_font_size == $i ? 'selected' : ''; ?> value="<?php echo $i; ?>"><?php echo $i; ?>px</option>
            <?php endfor; ?>
        </select>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id( 'event_date_color' ); ?>"><?php _e( 'Text color:' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id('event_date_color'); ?>" 
               name="<?php echo $this->get_field_name('event_date_color'); ?>" type="text" 
               value="<?php echo esc_attr( $event_date_color ); ?>" />
    </p>
</fieldset>

<style>
    .wp-picker-container, .wp-picker-container:active {
        display: block;
    }
</style>
<script type="text/javascript">
    (function($) {
        $(function() {

            $("#<?php echo $this->get_field_id( 'bg_color' ); ?>").wpColorPicker();
            $("#<?php echo $this->get_field_id( 'title_color' ); ?>").wpColorPicker();
            $("#<?php echo $this->get_field_id( 'event_date_color' ); ?>").wpColorPicker();

        });
    })(jQuery);
</script>