<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/* ── Registrar AJAX para logueados y no logueados ── */
add_action( 'wp_ajax_nopriv_lm_do_login', 'lm_do_login' );
add_action( 'wp_ajax_lm_do_login', 'lm_do_login' );

/* ── Función principal de login ── */
function lm_do_login() {

    // Seguridad: verificar nonce
    if ( ! isset($_POST['nonce']) || ! wp_verify_nonce( $_POST['nonce'], 'lm_login_nonce' ) ) {
        wp_send_json_error([
            'message' => 'Error de seguridad (nonce inválido).'
        ]);
    }

    // Sanitizar datos
    $log = sanitize_text_field( $_POST['log'] ?? '' );
    $pwd = $_POST['pwd'] ?? '';

    // Validación básica
    if ( empty( $log ) || empty( $pwd ) ) {
        wp_send_json_error([
            'message' => 'Completa todos los campos.'
        ]);
    }

    // Intentar login
    $user = wp_signon([
        'user_login'    => $log,
        'user_password' => $pwd,
        'remember'      => false,
    ], false);

    // Error en login
    if ( is_wp_error( $user ) ) {
        wp_send_json_error([
            'message' => 'Usuario o contraseña incorrectos.'
        ]);
    }

    // Login exitoso → devolver redirección
    wp_send_json_success([
        'redirect' => mass_redirect_por_rol( $user->roles )
    ]);

    wp_die();
}

/* ── Redirección login nativo WP ── */
add_filter( 'login_redirect', function( $redirect_to, $request, $user ) {
    if ( ! isset( $user->roles ) || empty( $user->roles ) ) {
        return $redirect_to;
    }
    return mass_redirect_por_rol( $user->roles );
}, 10, 3 );

/* ── Helper: redirección por rol ── */
function mass_redirect_por_rol( array $roles ): string {

    // Roles empresa
    if (
        in_array( 'administrator',      $roles ) ||
        in_array( 'supervisor_empresa', $roles ) ||
        in_array( 'tutor_instructor',   $roles )
    ) {
        return site_url( '/mass/panel-empresa/' );
    }

    // Suscriptor
    if ( in_array( 'subscriber', $roles ) ) {
        return site_url( '/mass/mi-perfil/' );
    }

    // Fallback
    return site_url( '/mass/' );
}