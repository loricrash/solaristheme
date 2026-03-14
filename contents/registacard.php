<div class="card-lista regista-card">
    <a href="<?php the_permalink(); ?>">
        <div class="poster">
            <?php 
            // Recuperiamo il campo ACF 'foto_regista'
            $foto = get_field('foto_regista'); 

            if( $foto ):
                $size = 'locandinamedium'; // Default desktop

                if ( wp_is_mobile() ) { 
                    $size = 'locandinasmall'; 
                }

                $url_ridimensionato = $foto['sizes'][$size];
            ?>
                <img src="<?php echo esc_url($url_ridimensionato); ?>" alt="<?php echo esc_attr($foto['alt']); ?>" />
            <?php else: ?>
                <div class="placeholder-regista" style="background: #333; height: 100%; width: 100%;"></div>
            <?php endif; ?>
        </div>

        <div class="infocarousel">
            <h3><?php the_title(); ?></h3>
            <?php 
                if ( is_page('contatti') ) : 
                    $testo = get_field('contatti'); 
                    if ( $testo ) : // Verifica anche che il campo non sia vuoto ?>
                        <p><?php echo nl2br($testo); ?></p>
                    <?php endif; 
                    ?><p><?php echo get_field('ruolo'); ?></p><?php
                endif; 
            ?>
        </div>
    </a>
</div>