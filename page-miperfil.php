<?php
/* Template Name: Mi perfil*/

get_header();

$args = array(
    'post_type' => 'courses',
    'posts_per_page' => -1
);

$courses = new WP_Query($args);

if ($courses->have_posts()) :
    while ($courses->have_posts()) : $courses->the_post();
        ?>
        <div class="mi-card-curso">
            <h2><?php the_title(); ?></h2>
            <a href="<?php the_permalink(); ?>">Ver curso</a>
        </div>
        <?php
    endwhile;
    wp_reset_postdata();
endif;

php get_footer(); ?>