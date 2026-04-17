<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <header class="site-header">
        <div class="header-inner">
            <div class="brand">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/mass-logo-1.png" alt="logo escudo azul de capacitaciones MASS" class="logo">
                <span class="brand-name">capacitaciones MASS</span>
            </div>
            <nav class="header-nav">
                <a href="<?php echo get_permalink(get_page_by_path('cursos')); ?>">Cursos</a>
                <a href="<?php echo get_permalink(get_page_by_path('miperfil')); ?>">Perfil</a>
            </nav>
        </div>
    </header>