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

            <?php do_action('tutor_course/single/before/main_content'); ?>

                <h2>Descripción</h2>
                <?php echo apply_filters('the_content', get_the_content()); ?>

        </div>

        <aside class="curso-sidebar">

            <h3>Contenido del curso</h3>

            <?php do_action('tutor_course/courses/lessons'); ?>

        </aside>

        <?php do_action('tutor_course/single/after/main_content'); ?>
          

    </div>

</div>

<?php endwhile;

get_footer();