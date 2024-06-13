<?php
// Enqueue the parent theme stylesheet
function scholarship_child_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'scholarship_child_enqueue_styles');