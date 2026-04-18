<?php
/**
 * Template Name: Login
 */

// 🔥 Redirección si ya está logueado
if ( is_user_logged_in() ) {
    $user  = wp_get_current_user();
    $roles = $user->roles;

    if ( in_array( 'administrator', $roles ) ) {
        // puede quedarse
    } elseif (
        in_array( 'supervisor_empresa', $roles ) ||
        in_array( 'tutor_instructor', $roles )
    ) {
        wp_redirect( home_url( '/panel-empresa/' ) );
        exit;
    } elseif ( in_array( 'subscriber', $roles ) ) {
        wp_redirect( home_url( '/mi-perfil/' ) );
        exit;
    }
}

/* ── 🔥 Obtener imagen desde WordPress (inicio.jpg) ── */
$img_url = '';

$attachments = get_posts([
    'post_type'      => 'attachment',
    'posts_per_page' => 1,
    'meta_query'     => [
        [
            'key'     => '_wp_attached_file',
            'value'   => 'inicio.jpg',
            'compare' => 'LIKE'
        ]
    ]
]);

if ( ! empty($attachments) ) {
    $img_url = wp_get_attachment_url($attachments[0]->ID);
}

// 🔥 fallback si no existe
if ( empty($img_url) ) {
    $img_url = get_template_directory_uri() . '/assets/img/inicio.jpg';
}

get_header();
?>

<div class="lm-wrap">

    <!-- 🔥 Hero con imagen dinámica -->
    <div class="lm-hero" style="background-image: url('<?php echo esc_url($img_url); ?>');"></div>

    <div class="lm-card-wrap">
        <div class="lm-card">

            <h2 class="lm-titulo">Bienvenido</h2>

            <div class="lm-field">
                <label class="lm-label" for="lm_usuario">Usuario</label>
                <input type="text" id="lm_usuario" class="lm-input">
            </div>

            <div class="lm-field">
                <label class="lm-label" for="lm_pass">Contraseña</label>
                <input type="password" id="lm_pass" class="lm-input">
            </div>

            <div id="lm-error" class="lm-error" style="display:none;"></div>

            <div class="lm-btn-wrap">
                <button class="lm-btn" id="lm-btn" onclick="lmLogin()">
                    <span id="lm-btn-text">Ingresar</span>
                    <span id="lm-btn-loader" style="display:none;">Cargando...</span>
                </button>
            </div>

        </div>
    </div>

</div>

<?php get_footer(); ?>