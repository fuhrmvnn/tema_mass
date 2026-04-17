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
                <?php echo apply_filters('the_content', get_the_content()); ?>

        </div>
        <aside class="curso-sidebar">

    <h3>Contenido del curso</h3>

        <?php
        $topics = tutor_utils()->get_topics($course_id);

        if ($topics) :
            foreach ($topics as $topic) :
        ?>

            <div class="accordion-item">

                <div class="accordion-header">
                    <?php echo esc_html($topic->post_title); ?>
                </div>

                <div class="accordion-content">

                    <?php
                    $contents = tutor_utils()->get_topic_contents($topic->term_id);

                    if ($contents) :
                        foreach ($contents as $item) :

                            $post_type = get_post_type($item->ID);

                            if ($post_type === 'lesson') :
                    ?>

                        <div class="leccion-item">
                            📘 <?php echo esc_html($item->post_title); ?>
                        </div>

                    <?php elseif ($post_type === 'tutor_quiz') : ?>

                        <div class="quiz-item">
                            📝 <?php echo esc_html($item->post_title); ?>
                            <a href="<?php echo get_permalink($item->ID); ?>" class="btn-quiz">
                                Ir al Quiz
                            </a>
                        </div>

                    <?php
                            endif;

                        endforeach;
                    endif;
                    ?>

                </div>

            </div>

        <?php
            endforeach;
        endif;
        ?>

    </aside>
   
          

    </div>

</div>

<?php endwhile;

get_footer();