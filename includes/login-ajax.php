<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_ajax_nopriv_lm_do_login', 'lm_do_login' );
add_action( 'wp_ajax_lm_do_login', 'lm_do_login' );

function lm_do_login() {

    // 🔥 Si ya está logueado → redirigir directamente
    if ( is_user_logged_in() ) {
        $user = wp_get_current_user();

        wp_send_json_success([
            'redirect' => mass_redirect_por_rol( $user->roles )
        ]);
    }

    // Seguridad nonce
    if ( ! isset($_POST['nonce']) || ! wp_verify_nonce( $_POST['nonce'], 'lm_login_nonce' ) ) {
        wp_send_json_error([
            'message' => 'Error de seguridad (nonce inválido).'
        ]);
    }

    $log = sanitize_text_field( $_POST['log'] ?? '' );
    $pwd = $_POST['pwd'] ?? '';

    if ( empty( $log ) || empty( $pwd ) ) {
        wp_send_json_error([
            'message' => 'Completa todos los campos.'
        ]);
    }

    $user = wp_signon([
        'user_login'    => $log,
        'user_password' => $pwd,
        'remember'      => false,
    ], false);

    if ( is_wp_error( $user ) ) {
        wp_send_json_error([
            'message' => 'Usuario o contraseña incorrectos.'
        ]);
    }

    wp_send_json_success([
        'redirect' => mass_redirect_por_rol( $user->roles )
    ]);

    wp_die();
}

/* Redirección */
function mass_redirect_por_rol( array $roles ): string {

    if (
        in_array( 'administrator',      $roles ) ||
        in_array( 'supervisor_empresa', $roles ) ||
        in_array( 'tutor_instructor',   $roles )
    ) {
        return home_url( '/panel-empresa/' );
    }

    if ( in_array( 'subscriber', $roles ) ) {
        return home_url( '/mi-perfil/' );
    }

    return home_url( '/' );
}