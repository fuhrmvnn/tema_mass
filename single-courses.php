<?php
$course_id = get_the_ID();
$topics = tutor_utils()->get_topics($course_id);

if ($topics) :
    foreach ($topics as $topic) :
?>

<div class="accordion-item">

    <div class="accordion-header">
        <?php echo esc_html($topic->post_title); ?>
        <span class="arrow">⌄</span>
    </div>

    <div class="accordion-content">

        <?php
        $contents = tutor_utils()->get_topic_contents($topic->term_id);

        if ($contents) :
            foreach ($contents as $item) :
        ?>

            <div class="leccion-item">
                <?php echo esc_html($item->post_title); ?>
            </div>

        <?php endforeach; endif; ?>

        <!-- botón quiz (simulado o dinámico) -->
        <a href="#" class="btn-quiz">Ir al Quiz</a>

    </div>

</div>

<?php
    endforeach;
endif;
?>