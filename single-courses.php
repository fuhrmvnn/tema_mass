<?php
get_header();

while (have_posts()) : the_post();

    $course_id = get_the_ID();
    $thumb = get_the_post_thumbnail_url($course_id, 'full');
?>

<div class="curso-container">

    <!-- portada -->
    <div class="curso-header" style="background-image:url('<?php echo $thumb; ?>');">
        <div class="overlay">
            <h1><?php the_title(); ?></h1>
        </div>
    </div>

    <div class="curso-content">

        <!-- contenido principal -->
        <div class="curso-main">

            <?php
            // Hook antes del contenido (por si Tutor lo necesita)
            do_action('tutor_course/single/before/content');

            the_content();

            // Hook después del contenido
            do_action('tutor_course/single/after/content');
            ?>

        </div>

        <!-- sidebar -->
        <aside class="curso-sidebar">

            <div class="card-info">
                <h3>Información del curso</h3>

            </div>

        </aside>

    </div>

</div>

<?php endwhile;

get_footer();