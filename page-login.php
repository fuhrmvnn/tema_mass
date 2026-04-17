<?php
/**
 * Template Name: Login
 * Página de inicio de sesión MASS
 */

// Redirigir si ya está logueado
if ( is_user_logged_in() ) {
    $user  = wp_get_current_user();
    $roles = $user->roles;

    if ( in_array( 'administrator', $roles ) ) {
        // no redirigir — puede ver la página
    } elseif ( in_array( 'supervisor_empresa', $roles ) || in_array( 'tutor_instructor', $roles ) ) {
        wp_redirect( home_url( '/panel-empresa/' ) );
        exit;
    } elseif ( in_array( 'subscriber', $roles ) ) {
        wp_redirect( home_url( '/mi-perfil/' ) );
        exit;
    }
}

// Imagen de fondo desde Media Library
$img_url = '';
$query = new WP_Query([
    'post_type'      => 'attachment',
    'post_status'    => 'inherit',
    'posts_per_page' => 1,
    'meta_query'     => [[
        'key'     => '_wp_attached_file',
        'value'   => 'inicio.jpg',
        'compare' => 'LIKE',
    ]],
]);
if ( $query->have_posts() ) {
    $img_url = wp_get_attachment_url( $query->posts[0]->ID );
}
if ( empty( $img_url ) ) {
    $upload  = wp_upload_dir();
    $img_url = $upload['baseurl'] . '/2026/03/inicio.jpg';
}

get_header();
?>

<div class="lm-wrap">

    <!-- Hero imagen -->
    <div class="lm-hero" style="background-image: url('<?php echo esc_url( $img_url ); ?>');"></div>

    <!-- Tarjeta -->
    <div class="lm-card-wrap">
        <div class="lm-card">

            <h2 class="lm-titulo">Bienvenido a capacitaciones MASS</h2>

            <div class="lm-field">
                <label class="lm-label" for="lm_usuario">Ingresa tu nombre de usuario</label>
                <input type="text" id="lm_usuario" class="lm-input" placeholder="Nombre de usuario" autocomplete="username">
            </div>

            <div class="lm-field">
                <label class="lm-label" for="lm_pass">Contraseña</label>
                <input type="password" id="lm_pass" class="lm-input" placeholder="••••••••" autocomplete="current-password">
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

<!-- Variables para JS -->
<script>
var LM = {
    ajaxUrl : '<?php echo esc_url( admin_url( "admin-ajax.php" ) ); ?>',
    nonce   : '<?php echo wp_create_nonce( "lm_login_nonce" ); ?>'
};
</script>

<?php get_footer(); ?>