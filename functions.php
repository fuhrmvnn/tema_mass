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

add_action('template_redirect', function() {

    if ( ! is_user_logged_in() ) return;

    // No afectar admin ni AJAX
    if ( is_admin() || wp_doing_ajax() ) return;

    $user  = wp_get_current_user();
    $roles = (array) $user->roles;

    // Admin puede navegar libre
    if ( in_array('administrator', $roles) ) return;

    // Supervisor / instructor → panel empresa
    if (
        in_array('supervisor_empresa', $roles) ||
        in_array('tutor_instructor', $roles)
    ) {
        if ( ! is_page('panel-empresa') ) {
            wp_redirect( home_url('/panel-empresa/') );
            exit;
        }
    }

    // Alumno → perfil
    if ( in_array('subscriber', $roles) ) {
        if ( ! is_page('mi-perfil') ) {
            wp_redirect( home_url('/mi-perfil/') );
            exit;
        }
    }

});

add_action('wp_enqueue_scripts', 'mass_enqueue_styles');

