<?php get_header(); ?>

<?php 
$pagina_contatti = get_page_by_path('contatti');
?>

<main>
    <?php 
        $imgcopertina = get_field('copertina'); 
        if( $imgcopertina ):
            $sizecopertina = $imgcopertina['sizes']['hero_full_hd']; 
            ?>
                <section class="element galleria">
                    <div>
                        <div class="riga-galleria">
                            <div class="grid-item">
                                <img 
                                    src="<?php echo esc_url($sizecopertina); ?>" 
                                    alt="<?php echo esc_attr($imgcopertina['alt']); ?>" 
                                    fetchpriority="high"
                                    loading="eager"
                                    onclick="openFullImage(this.src)"
                                >
                            </div>
                        </div>
                    </div>       
                </section>
        <?php endif;
    ?>
    <?php 
        $testo = get_field('testo'); 
        if( $testo ):
            ?>
                <section class="element">
                    <h2><?php echo get_bloginfo('name');?>  </h2>
                    <p> <?php echo nl2br($testo);?> </p>
                </section>
                <section class="element">
                    <div class="linehero"></div>
                </section>
        <?php endif;
    ?>
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
                $ids_selezionati = get_field('membri_del_team', $pagina_contatti->ID, false);
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