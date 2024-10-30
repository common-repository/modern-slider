<?php
/*
Plugin Name: Modern Slider
Plugin URI: https://pushlabs.co/docs/modern-slider
Description: A modern slider for WordPress. Place it anywhere.
Author: Push Labs
Version: 1.0.0
Author URI: https://pushlabs.co
Text Domain: modern-slider
Domain Path: /languages
 */

define('MODERN_SLIDER_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('MODERN_SLIDER_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MODERN_SLIDER_PLUGIN_VERSION', '1.0.0');

class PushLabs_Modern_Slider
{

    public function __construct()
    {
        if (is_admin() === true) {
            include MODERN_SLIDER_PLUGIN_PATH . 'inc/classes/admin.php';
        }

        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        add_action('wp_footer', array($this, 'render'));
    }

    public function enqueue_scripts()
    {
        $modern_slider_deps = array('jquery', 'swiper');

        wp_enqueue_style('modern-slider', MODERN_SLIDER_PLUGIN_URL . 'assets/css/style.css', array(), MODERN_SLIDER_PLUGIN_VERSION);
        wp_enqueue_script('swiper', MODERN_SLIDER_PLUGIN_URL . 'assets/js/swiper.min.js', array('jquery'), '4.3.0', true);
        wp_enqueue_script('modern-slider', MODERN_SLIDER_PLUGIN_URL . 'assets/js/modernSlider.js', $modern_slider_deps, MODERN_SLIDER_PLUGIN_VERSION, true);
    }

    public function render()
    {

        // Determine the page ID
        $id = null;
        if (is_page() || is_single()) {
            $id = get_the_ID();
        } elseif (is_home() && get_option('show_on_front') == 'page') {
            $id = get_option('page_for_posts');
        }

        // There is no post ID to pull post meta from, quit.
        if (is_null($id)) {
            return;
        }

        $field_prefix = 'modernslider_';

        // Get our post meta
        $container        = get_post_meta($id, $field_prefix . 'container', true);
        $images           = get_post_meta($id, $field_prefix . 'image_group', true);
        $slide_speed      = get_post_meta($id, $field_prefix . 'speed', true);
        $transition_speed = get_post_meta($id, $field_prefix . 'transition_speed', true);
        $navigation       = get_post_meta($id, $field_prefix . 'navigation', true);
        $caption_pos      = get_post_meta($id, $field_prefix . 'caption_pos', true);
        $overlay          = get_post_meta($id, $field_prefix . 'overlay', true);
        $overlay_color    = get_post_meta($id, $field_prefix . 'overlay_color', true);
        $overlay_alpha    = get_post_meta($id, $field_prefix . 'overlay_alpha', true);

        // If the slide does not have an image attached to it, remove it.
        foreach ($images as $image_key => $image) {
            if (!array_key_exists('img', $image)) {
                unset($images[$image_key]);
            }
        }

        // Create our jQuery plugin args
        $slider_params = array();

        // Set our params
        $slider_params['slides']          = $images;
        $slider_params['slideDelay']      = (int) $slide_speed;
        $slider_params['transitionSpeed'] = (int) $transition_speed;
        $slider_params['navigation']      = ($navigation === 'on' ? true : false);
        $slider_params['captionPos']      = $caption_pos;

        // Remove any array params if their values were null or empty
        foreach ($slider_params as $key => $value) {
            if (is_null($value) || $value == '') {
                unset($slider_params[$key]);
            }
        }

        // If the container is empty or there are no images, quit.
        if (empty($container) || !is_array($images) || empty($images)) {
            return;
        }

        // Init our plugin
        $init = "
            jQuery(function($) {
                $('" . $container . "').modernSlider(" . json_encode($slider_params) . ");
            });
        ";

        // Add the plugin init call as an inline script
        wp_add_inline_script('modern-slider', $init);
    }
}

$pushlabs_modern_slider_init = new PushLabs_Modern_Slider;
