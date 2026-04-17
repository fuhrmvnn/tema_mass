<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/* ── Registrar AJAX ── */
add_action( 'wp_ajax_nopriv_lm_do_login', 'lm_do_login' );
add_action( 'wp_ajax_lm_do_login', 'lm_do_login' );

/* ── Función principal ── */
function lm_do_login() {

    // 🔥 Si ya está logueado → redirigir
    if ( is_user_logged_in() ) {
        $user = wp_get_current_user();

        wp_send_json_success([
            'redirect' => mass_redirect_por_rol( $user )
        ]);
    }

    // 🔐 Seguridad nonce
    if ( ! isset($_POST['nonce']) || ! wp_verify_nonce( $_POST['nonce'], 'lm_login_nonce' ) ) {
        wp_send_json_error([
            'message' => 'Error de seguridad (nonce inválido).'
        ]);
    }

    // 🧹 Sanitizar
    $log = sanitize_text_field( $_POST['log'] ?? '' );
    $pwd = $_POST['pwd'] ?? '';

    // ⚠ Validación
    if ( empty( $log ) || empty( $pwd ) ) {
        wp_send_json_error([
            'message' => 'Completa todos los campos.'
        ]);
    }

    // 🔑 Intento de login
    $user = wp_signon([
        'user_login'    => $log,
        'user_password' => $pwd,
        'remember'      => false,
    ], false);

    // ❌ Error login
    if ( is_wp_error( $user ) ) {
        wp_send_json_error([
            'message' => 'Usuario o contraseña incorrectos.'
        ]);
    }

    // ✅ Login correcto
    wp_send_json_success([
        'redirect' => mass_redirect_por_rol( $user )
    ]);

    wp_die();
}

/* ── Helper: redirección por capacidades ── */
function mass_redirect_por_rol( $user ): string {

    // 🔥 Alumno (subscriber)
    if ( in_array('subscriber', $user->roles) ) {
        return home_url('/mi-perfil/');
    }

    // 🔥 Usuarios con permisos de gestión → panel empresa
    if (
        user_can($user, 'edit_posts') || 
        user_can($user, 'manage_options')
    ) {
        return home_url('/panel-empresa/');
    }

    // 🔚 fallback
    return home_url('/');
}