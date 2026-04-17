<?php

require_once get_template_directory() . '/includes/enqueue.php';
require_once get_template_directory() . '/includes/panel-empresa-ajax.php';

function mass_enqueue_styles() {
    wp_enqueue_style(
        'inter-font',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap',
        array(),
        null
    );
    wp_enqueue_style(
        'mass-main',
        get_template_directory_uri() . '/assets/css/main.css',
        array('inter-font'),
        '1.0'
    );
}
add_action('wp_enqueue_scripts', 'mass_enqueue_styles');
