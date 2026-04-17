<?php

require_once get_template_directory() . '/includes/enqueue.php';
require_once get_template_directory() . '/includes/panel-empresa-ajax.php';
require_once get_template_directory() . '/includes/login-ajax.php';

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

    if (is_page_template('mi-perfil.php')) {
        wp_enqueue_style(
            'mi-perfil-css',
            get_template_directory_uri() . '/assets/css/mi-perfil.css',
            array('mass-main'),
            '2.0'
        );
    };
     if (is_page_template('single-couses.php')) {
        wp_enqueue_style(
            'curso-css',
            get_template_directory_uri() . '/assets/css/curso.css',
            array('mass-main', 'mi-perfil-css'),
            '2.0'
        );
    }

}
add_action('wp_enqueue_scripts', 'mass_enqueue_styles');

//dejar al final del archivo 
add_filter('template_include', function($template) {
    if (get_post_type() === 'courses') {
        return get_template_directory() . '/single-courses.php';
    }
    return $template;
}, 99);
