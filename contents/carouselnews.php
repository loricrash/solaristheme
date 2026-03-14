<section class="carousel carouselnews element">
    <?php 
    // Recupero variabili con fallback
    $titolo        = $args['titolo'] ?? 'Ultime Notizie';
    $tassonomia    = (!empty($args['tassonomia'])) ? $args['tassonomia'] : false;
    $termine       = (!empty($args['termine'])) ? $args['termine'] : false;
    $numero_news   = (int)($args['quantitapost'] ?? 10); 
    $escludi       = $args['escludi_news'] ?? 0;

    // Configurazione Query
    $args_query = array(
        'post_type'      => 'news', // Slug confermato dalle tue foto
        'posts_per_page' => $numero_news,
        'post__not_in'   => array($escludi),
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    );

    // Applichiamo la tax_query SOLO se abbiamo sia tassonomia che termine
    if ( $tassonomia && $termine ) {
        $args_query['tax_query'] = array(
            array(
                'taxonomy' => $tassonomia,
                'field'    => 'slug',
                'terms'    => $termine,
            ),
        );
    }

    $query = new WP_Query($args_query);

    if ($query->have_posts()) : ?>
        
        <?php if ( !empty($titolo) ) : ?>
            <h3><?php echo esc_html($titolo); ?></h3>
        <?php endif; ?>

        <div class="carousel-container">
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <?php get_template_part('contents/newscard'); ?>
            <?php endwhile; wp_reset_postdata(); ?>
            <div class="spaziofinalecarousel"></div>
        </div>

    <?php endif; ?>
</section>