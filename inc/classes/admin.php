<?php

if (!defined('ABSPATH')) {
    exit;
}

class PushLabs_Modern_Slider_Admin
{
    public function __construct()
    {
        $this->field_prefix = 'modernslider_';

        // Include CMB2
        include MODERN_SLIDER_PLUGIN_PATH . 'inc/vendor/cmb2/init.php';

        // Include CMB2 slider
        include MODERN_SLIDER_PLUGIN_PATH . 'inc/vendor/cmb2-field-slider/cmb2_field_slider.php';

        // Register the metabox
        add_action('cmb2_admin_init', array($this, 'register_metabox'));

        // Enqueue backend scripts
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    /**
     * Enqueue scripts and styles for the backend.
     *
     * @return void
     */
    public function enqueue_scripts()
    {
        // Register backend style
        wp_enqueue_style('modern-slider-backend', MODERN_SLIDER_PLUGIN_URL . 'assets/css/backend.css', array(), MODERN_SLIDER_PLUGIN_VERSION);
        wp_enqueue_script('modern-slider-backend', MODERN_SLIDER_PLUGIN_URL . 'assets/js/backend.js', array('jquery'), MODERN_SLIDER_PLUGIN_VERSION, true);
    }

    /**
     * Register the metabox for Modern Slider.
     *
     * @return void
     */
    public function register_metabox()
    {
        $metabox = new_cmb2_box(array(
            'id'           => 'modern_slider_metabox',
            'title'        => __('Modern Slider', 'modern-slider'),
            'object_types' => array('page'),
        ));

        $metabox->add_field(array(
            'name' => __('Container (Selector)', 'modern-slider'),
            'desc' => __('The selector in which the slider will take over. Please specify an HTML class or ID.', 'modern-slider'),
            'id'   => $this->field_prefix . 'container',
            'type' => 'text',
        ));

        $metabox->add_field(array(
            'name' => __('Slide Speed', 'modern-slider'),
            'desc' => __('Specify the delay in between slides (In ms). Default: 3000', 'modern-slider'),
            'id'   => $this->field_prefix . 'speed',
            'type' => 'text_small',
        ));

        $metabox->add_field(array(
            'name' => __('Slide Transition Speed', 'modern-slider'),
            'desc' => __('Specify the time it takes for the slide to transition (In ms). Default: 1000', 'modern-slider'),
            'id'   => $this->field_prefix . 'transition_speed',
            'type' => 'text_small',
        ));

        $metabox->add_field(array(
            'name'    => __('Toggle Navigation', 'modern-slider'),
            'desc'    => __('Enable/disable the navigation.', 'modern-slider'),
            'id'      => $this->field_prefix . 'navigation',
            'type'    => 'radio_inline',
            'options' => array(
                'off' => __('Off', 'modern-slider'),
                'on'  => __('On', 'modern-slider'),
            ),
            'default' => 'off',
        ));

        $metabox->add_field(array(
            'name'    => __('Caption Position', 'modern-slider'),
            'desc'    => __('If you have a caption on any of your slides, you can dictate where they are positioned here.', 'modern-slider'),
            'id'      => $this->field_prefix . 'caption_pos',
            'type'    => 'radio_inline',
            'options' => array(
                'bottom-right' => __('Bottom Right', 'modern-slider'),
                'bottom-left'  => __('Bottom Left', 'modern-slider'),
                'top-right'    => __('Top Right', 'modern-slider'),
                'top-left'     => __('Top Left', 'modern-slider'),
            ),
            'default' => 'bottom-right',
        ));

        $image_group = $metabox->add_field(array(
            'id'      => $this->field_prefix . 'image_group',
            'type'    => 'group',
            'options' => array(
                'group_title'   => __('Image {#}', 'modern-slider'),
                'sortable'      => true,
                'add_button'    => __('Add Slide', 'modern-slider'),
                'remove_button' => __('Remove Slide', 'modern-slider'),
                'closed'        => true,
            ),
        ));

        $metabox->add_group_field($image_group, array(
            'name'       => __('Slide Background', 'modern-slider'),
            'desc'       => __('The slide\'s background image.', 'modern-slider'),
            'id'         => 'img',
            'type'       => 'file',
            'query_args' => array(
                'type' => array(
                    'image/gif',
                    'image/jpeg',
                    'image/png',
                ),
            ),
        ));

        $metabox->add_group_field($image_group, array(
            'name' => __('Caption', 'modern-slider'),
            'desc' => __('Specify a caption for the slide if you\'d like.'),
            'id'   => 'caption_text',
            'type' => 'text',
        ));

        $metabox->add_group_field($image_group, array(
            'name' => __('Caption Link', 'modern-slider'),
            'desc' => __('The link you\'d like your user to go if the caption is clicked.', 'modern-slider'),
            'id'   => 'caption_url',
            'type' => 'text_url',
        ));

        $metabox->add_group_field($image_group, array(
            'name'    => __('Toggle Overlay', 'modern-slider'),
            'desc'    => __('Would you like to enable an overlay over the slide? (This could help with readability if text is on top of the slider', 'modern-slider'),
            'id'      => 'overlay',
            'type'    => 'radio_inline',
            'options' => array(
                'off' => __('Off', 'modern-slider'),
                'on'  => __('On', 'modern-slider'),
            ),
            'default' => 'off',
            'classes' => 'ms-overlay-field',
        ));

        $metabox->add_group_field($image_group, array(
            'name'    => __('Overlay Color', 'modern-slider'),
            'id'      => 'overlay_color',
            'desc'    => __('Specify the overlay color.', 'modern-slider'),
            'type'    => 'colorpicker',
            'classes' => 'ms-overlay-color-field',
        ));

        $metabox->add_group_field($image_group, array(
            'name'        => __('Overlay Transparancy', 'modern-slider'),
            'desc'        => __('Specify the overlay transparency.', 'modern-slider'),
            'id'          => 'overlay_alpha',
            'type'        => 'own_slider',
            'min'         => '1',
            'max'         => '99',
            'default'     => '30',
            'value_label' => __('Percent: ', 'modern-slider'),
            'classes'     => 'ms-overlay-alpha-field',
        ));

    }
}

$pushlabs_modern_slider_admin_init = new PushLabs_Modern_Slider_Admin;
