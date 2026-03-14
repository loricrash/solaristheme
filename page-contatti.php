<?php get_header(); ?>

<main>
    <section class="element contatti">
        <div>
            <?php 
            $info_id = get_info_globali_id();
            if ($info_id) {
                ?><p>P.iva: <?php echo get_field('partita_iva', $info_id);?></p>
                <b>Contatti:</b>
                <p><?php echo get_field('contatti', $info_id);?></p><?php
            }
            ?>
        </div>
        <div>
            <nav>
                <?php
                wp_nav_menu( array(
                    'theme_location' => 'footer',
                    'depth'          => 2,
                    'container'      => false,
                    'menu_class'     => 'contatti-menu' //qui da aggiungere il tag dell' header
                ) );
                ?>
            </nav>
        </div>
    </section>
    <section class="element archivio-registi">
        <h3>Persone chiave</h3>
        <div class="risultati-archivio">
            <?php
                $ids_selezionati = get_field('membri_del_team', false, false);
                $args = array(
                    'post_type' => array('regista', 'team'),
                    'post__in'  => $ids_selezionati, // <--- FILTRO: Mostra SOLO questi ID
                    'orderby'   => 'post__in',
                );

                $query_team = new WP_Query($args);

                if ( $query_team->have_posts() ) : ?>
                    <div class="griglia-risultati">
                        <?php while ( $query_team->have_posts() ) : $query_team->the_post(); ?>
                            <?php 
                            get_template_part('/contents/registacard'); 
                            ?>
                        <?php endwhile; ?>
                    </div>
                <?php else : ?>
                    <div class="no-results">
                        <p>Nessun memebro del team trovato.</p>
                    </div>
                <?php endif; wp_reset_postdata(); ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>