<?php

function capacitaciones_assets() {

    wp_enqueue_style(
        'panel-empresa-css',
        get_template_directory_uri() . '/assets/css/panel-empresa.css',
        array(),
        '2.0'
    );
   
    wp_enqueue_script(
        'panel-empresa-js',
        get_template_directory_uri() . '/assets/js/panel-empresa.js',
        array(),
        '1.0',
        true
    );

    // 🔥 ESTO reemplaza tu script PHP del nonce
    wp_localize_script('panel-empresa-js', 'pe_ajax', [
        'url'   => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('pe_nonce')
    ]);
}
add_action('wp_enqueue_scripts', 'capacitaciones_assets');

if ( is_page_template( 'page-login.php' ) ) {
    wp_enqueue_style(
        'mass-login',
        get_template_directory_uri() . '/assets/css/login.css',
        [ 'mass-main' ],
        '1.0'
    );
    wp_enqueue_script(
        'mass-login-js',
        get_template_directory_uri() . '/assets/js/login.js',
        [],
        '1.0',
        true // footer
    );
}