<?php get_header(); ?>

<main>
    <section class="hero hero-homepage">
        <?php 
        $film_hp = get_field('film_homepage');
        if( $film_hp ): 
            $count = 0;
            foreach( $film_hp as $post ): setup_postdata($post); 
                $imghero = get_field('copertina_frame_iconico');
                $regista = get_field('regista'); // Esempio: recupera il regista associato
                $count++;
        ?>
            <div class="hp-slide <?php echo $count === 1 ? 'active' : ''; ?>" data-slide="<?php echo $count; ?>">
                <div class="sfondohero gradienthero"></div>
                <div class="sfondohero">
                    <img src="<?php echo esc_url($imghero['sizes']['hero_full_hd']); ?>" class="hero-img-fissa" loading="eager">
                </div>

                <div class="contenthero">
                    <div class="updividerhero">
                        <div class="testohero">
                            <h1><?php the_title(); ?></h1>
                            <div class="sottotitolohero">
                                <?php 
                                // Recuperiamo il campo relazione "regista" dal film corrente
                                    $registi_correlati = get_field('regista', $post->ID); 
                                    
                                    if( $registi_correlati ): 
                                        // Prendiamo il primo regista dell'array
                                        $regista_principale = $registi_correlati[0]; 
                                        ?>
                                        <h3>di <a href="<?php echo get_permalink($regista_principale->ID); ?>">
                                            <?php echo get_the_title($regista_principale->ID); ?>
                                        </a></h3>
                                <?php endif; ?>
                                <div>
                                    <a href="<?php the_permalink(); ?>" class="btn-scheda"><h4>Scheda</h4></a>
                                    <?php if ( has_term( 'in-arrivo', 'status-film', $post->ID ) ) : ?>
                                        <div class="status-hero status-arrivo"> 
                                            <div>
                                                <h4>
                                                    <?php 
                                                    $data_film = get_field('data', $post->ID);
                                                    $solo_anno = get_field('solo_anno', $post->ID);

                                                    if ( $solo_anno && $data_film ) {
                                                        // Usiamo questo metodo che è più robusto di strtotime
                                                        $date_obj = DateTime::createFromFormat('d/m/Y', $data_film);
                                                        
                                                        if ($date_obj) {
                                                            echo $date_obj->format('Y'); 
                                                        } else {
                                                            // Se il formato non è d/m/Y, prova a pulirlo
                                                            echo date("Y", strtotime(str_replace('/', '-', $data_film)));
                                                        }
                                                    } else {
                                                        echo $data_film;
                                                    }
                                                    ?>
                                                </h4>
                                            </div>
                                        </div>
                                    <?php elseif ( has_term( 'al-cinema', 'status-film', $post->ID ) ) : ?>
                                        <div class="status-hero status-cinema"> 
                                            <div>
                                                <h4>Al cinema</h4>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php if($count === 1): ?>
                        <div class="box-infohero cardhero-container">
                            <?php 
                            $card_count = 0;
                            foreach($film_hp as $card_post): 
                                $card_count++;
                                $card_img = get_field('locandina', $card_post->ID);
                            ?>
                                <div class="cardhero <?php echo $card_count === 1 ? 'active' : ''; ?>" data-target="<?php echo $card_count; ?>">
                                    <img src="<?php echo esc_url($card_img['sizes']['medium']); ?>" alt="">
                                    <span class="cardhero-text"><?php echo get_the_title($card_post->ID); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="dividerhero">
                        <div class="linehero"></div>
                        <a class="arrowhero" href="#portagiu">
                            <svg viewBox="0 0 24 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L12 11L23 1" stroke="currentColor" stroke-width="var(--line2)" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                        <div class="linehero"></div>
                    </div>
                </div>
            </div>
        <?php endforeach; wp_reset_postdata(); endif; ?>
    </section>
    <section class="element keytext" id="portagiu" >
        <div>
            <h3><?php echo get_field('testo_chiave');?></h3>
        </div>
    </section>
    <?php 
        get_template_part('/contents/carouselfilm', null, [
            'titolo'     => 'Prossimamente',
            'tassonomia' => 'status-film',
            'termine'    => 'in-arrivo',
            'quantitapost' => 10,
        ]);
    ?>
    <?php 
        get_template_part('/contents/carouselfilm', null, [
            'titolo'     => 'Ultime uscite',
            'tassonomia' => 'status-film',
            'termine'    => 'al-cinema',
            'quantitapost' => 10,
        ]);
    ?>
    <?php 
        get_template_part('/contents/carouselfilm', null, [
            'titolo'     => 'Tutti',
            'tassonomia' => '',
            'termine'    => '',
            'quantitapost' => 10,
        ]);
    ?>
    <?php 
        get_template_part('contents/carouselnews', null, [
            'titolo'        => 'Ultime notizie',
            'tassonomia'    => '', // Lascia vuoto per vedere tutto
            'termine'       => '', // Lascia vuoto per vedere tutto
            'quantitapost' => 10,
        ]);
    ?>
</main>

<?php get_footer(); ?>