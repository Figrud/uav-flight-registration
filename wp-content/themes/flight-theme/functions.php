<?php
// functions.php

// Enqueue styles and scripts for the theme
function flight_theme_enqueue_scripts() {
    wp_enqueue_style('flight-theme-style', get_stylesheet_uri());
    wp_enqueue_script('flight-theme-script', get_template_directory_uri() . '/assets/js/script.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'flight_theme_enqueue_scripts');

// Register a custom menu for the theme
function flight_theme_setup() {
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'flight-theme'),
    ));
}
add_action('after_setup_theme', 'flight_theme_setup');

// Include custom post types or other theme functionalities here
// For example, you can include the flight registration form template
function flight_registration_form_shortcode() {
    ob_start();
    include(get_template_directory() . '/templates/flight-form.php');
    return ob_get_clean();
}
add_shortcode('flight_registration_form', 'flight_registration_form_shortcode');

// Function to display the list of flights
function flight_list_shortcode() {
    ob_start();
    include(get_template_directory() . '/templates/flight-list.php');
    return ob_get_clean();
}
add_shortcode('flight_list', 'flight_list_shortcode');
?>