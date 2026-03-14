<?php get_header(); ?>

<main>
    <section class="element policy">
        <?php 
            $info_id = get_info_globali_id();
            $testo = get_field('privacy_policy', $info_id); 
            if ($info_id) {
                ?><p><?php echo nl2br($testo);;?></p><?php
            }
        ?>
    </section>
</main>


<?php get_footer(); ?>