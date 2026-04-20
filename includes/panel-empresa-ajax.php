<?php

add_action('wp_ajax_pe_agregar_usuario', function() {
    check_ajax_referer('pe_nonce', 'nonce');

    if (!is_user_logged_in()) wp_send_json_error(['mensaje' => 'No autorizado.']);

    $nombre   = sanitize_text_field($_POST['nombre']   ?? '');
    $rut      = sanitize_text_field($_POST['rut']      ?? '');
    $email    = sanitize_email($_POST['email']         ?? '');
    $login    = sanitize_user($_POST['login']          ?? '');
    $password = $_POST['password']                     ?? '';
    $cargo    = sanitize_text_field($_POST['cargo']    ?? '');
    $estado   = sanitize_text_field($_POST['estado']   ?? 'activo');

    if (!$nombre || !$email || !$login || !$password)
        wp_send_json_error(['mensaje' => 'Faltan campos obligatorios.']);

    if (email_exists($email))    wp_send_json_error(['mensaje' => 'El correo ya está registrado.']);
    if (username_exists($login)) wp_send_json_error(['mensaje' => 'El usuario ya existe.']);

    // Obtener empresa y sede del supervisor actual
    $current_user = wp_get_current_user();
    $empresa      = get_field('nombre_empresa', 'user_' . $current_user->ID);
    $sede         = get_field('sede',           'user_' . $current_user->ID);

    $user_id = wp_create_user($login, $password, $email);
    if (is_wp_error($user_id))
        wp_send_json_error(['mensaje' => $user_id->get_error_message()]);

    wp_update_user([
        'ID'           => $user_id,
        'display_name' => $nombre,
        'first_name'   => $nombre,
    ]);

    // Guardar campos ACF
    update_field('rut',            $rut,     'user_' . $user_id);
    update_field('rol',            $cargo,   'user_' . $user_id);
    update_field('estado',         $estado,  'user_' . $user_id);
    update_field('nombre_empresa', $empresa, 'user_' . $user_id);
    update_field('sede',           $sede,    'user_' . $user_id);

    // También como user_meta para que get_users() lo encuentre
    update_user_meta($user_id, 'nombre_empresa', $empresa);

    wp_send_json_success(['mensaje' => 'Trabajador agregado correctamente.']);

    $user_id = wp_create_user($login, $password, $email);
    if (is_wp_error($user_id))
        wp_send_json_error(['mensaje' => $user_id->get_error_message()]);

    // Aprobar usuario en WP User Manager
    update_user_meta($user_id, 'wpu_user_status', 'approved');
});


add_action('wp_ajax_pe_modificar_usuario', function() {
    check_ajax_referer('pe_nonce', 'nonce');

    if (!is_user_logged_in()) wp_send_json_error(['mensaje' => 'No autorizado.']);

    $user_id  = intval($_POST['user_id'] ?? 0);
    $nombre   = sanitize_text_field($_POST['nombre']   ?? '');
    $rut      = sanitize_text_field($_POST['rut']      ?? '');
    $email    = sanitize_email($_POST['email']         ?? '');
    $password = $_POST['password']                     ?? '';
    $cargo    = sanitize_text_field($_POST['cargo']    ?? '');
    $estado   = sanitize_text_field($_POST['estado']   ?? 'activo');

    if (!$user_id) wp_send_json_error(['mensaje' => 'Usuario no válido.']);

    $args = [
        'ID'           => $user_id,
        'display_name' => $nombre,
        'first_name'   => $nombre,
        'user_email'   => $email,
    ];

    if (!empty($password)) $args['user_pass'] = $password;

    $result = wp_update_user($args);
    if (is_wp_error($result))
        wp_send_json_error(['mensaje' => $result->get_error_message()]);

    update_field('rut',    $rut,   'user_' . $user_id);
    update_field('rol',    $cargo, 'user_' . $user_id);
    update_field('estado', $estado,'user_' . $user_id);

    wp_send_json_success(['mensaje' => 'Trabajador actualizado correctamente.']);
});


add_action('wp_ajax_pe_eliminar_usuario', function() {
    check_ajax_referer('pe_nonce', 'nonce');

    if (!is_user_logged_in()) wp_send_json_error(['mensaje' => 'No autorizado.']);

    $user_id = intval($_POST['user_id'] ?? 0);
    if (!$user_id) wp_send_json_error(['mensaje' => 'Usuario no válido.']);

    require_once ABSPATH . 'wp-admin/includes/user.php';
    wp_delete_user($user_id);

    wp_send_json_success(['mensaje' => 'Trabajador eliminado.']);
});