<section class="carousel carouselfilm element">
    <?php 
    // Recupero dei parametri dall'array $args
    $titolo          = $args['titolo'] ?? '';
    $regista_id      = $args['regista_id'] ?? null;
    $tassonomia      = $args['tassonomia'] ?? '';
    $termine         = $args['termine'] ?? '';
    $numero_poster   = $args['quantitapost'] ?? 10; // Valore di default se non passato
    $escludi         = $args['escludi_film'] ?? 0;

    // Pulizia regista_id (gestione array ACF)
    if ( is_array($regista_id) ) {
        $regista_id = $regista_id[0];
    }

    // Configurazione della Query
    $args_query = array(
        'post_type'      => 'film',
        'posts_per_page' => (int) $numero_poster, // Gestione dinamica del numero
        'post__not_in'   => array($escludi),
        'tax_query'      => array(),
        'meta_query'     => array(),
    );

    // Filtro Tassonomia
    if ( !empty($tassonomia) && !empty($termine) ) {
        $args_query['tax_query'][] = array(
            'taxonomy' => $tassonomia,
            'field'    => 'slug',
            'terms'    => $termine,
        );
    }

    // Filtro Regista 
    if ( !empty($regista_id) ) {
        $args_query['meta_query'][] = array(
            'key'     => 'regista',
            'value'   => '"' . (string)$regista_id . '"', 
            'compare' => 'LIKE',
        );
    }

    $query = new WP_Query($args_query);
    if ($query->have_posts()) : ?>
        
        <?php if ( !empty($titolo) ) : ?>
            <h3><?php echo esc_html($titolo); ?></h3>
        <?php endif; ?>

        <div>
            <?php while ($query->have_posts()) : $query->the_post();
                get_template_part('/contents/filmcard');
            endwhile; wp_reset_postdata(); ?>
            <div class="spaziofinalecarousel"></div>
        </div>

    <?php endif; ?>
</section>