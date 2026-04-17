<?php

if ( ! defined( 'ABSPATH' ) ) exit;
 
/* ── AJAX: procesar login ── */
add_action( 'wp_ajax_nopriv_lm_do_login', function() {
 
if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'lm_login_nonce' ) ) {
    wp_send_json_error( [ 'message' => 'Nonce inválido: ' . ($_POST['nonce'] ?? 'vacío') ] );
}
 
    $log = sanitize_text_field( $_POST['log'] ?? '' );
    $pwd = $_POST['pwd'] ?? '';
 
    if ( empty( $log ) || empty( $pwd ) ) {
        wp_send_json_error( [ 'message' => 'Completa todos los campos.' ] );
    }
 
    $user = wp_signon( [
        'user_login'    => $log,
        'user_password' => $pwd,
        'remember'      => false,
    ], false );
 
    if ( is_wp_error( $user ) ) {
        wp_send_json_error( [ 'message' => 'Usuario o contraseña incorrectos.' ] );
    }
 
    wp_send_json_success( [ 'redirect' => mass_redirect_por_rol( $user->roles ) ] );
} );
 
/* ── Filtro: redirección login nativo WordPress ── */
add_filter( 'login_redirect', function( $redirect_to, $request, $user ) {
    if ( ! isset( $user->roles ) || empty( $user->roles ) ) return $redirect_to;
    return mass_redirect_por_rol( $user->roles );
}, 10, 3 );
 
/* ── Helper: URL según rol ── */
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