<div class="card-lista">
    <a href="<?php the_permalink(); ?>">
        <div class="poster">
            <?php 
            $immaginelc = get_field('locandina'); 

            if( $immaginelc ):
                $size = 'locandinamedium'; // Default per desktop

                // Se è mobile, sovrascriviamo la variabile $size
                if ( wp_is_mobile() ) { 
                    $size = 'locandinasmall'; 
                }

                $url_ridimensionato = $immaginelc['sizes'][$size];
            ?>
                <img src="<?php echo esc_url($url_ridimensionato); ?>" alt="<?php echo esc_attr($$immaginelc['alt']); ?>" />
            <?php endif; ?>
        </div>
        <div class="infocarousel">
            <h3><?php the_title(); ?></h3>
            <?php 
            $registi = get_field('regista');
            if( $registi ): 
                foreach( $registi as $r ): ?>
                    <p class="regista-name">
                        <?php echo get_the_title($r); ?>
                </p>
                <?php endforeach; 
            endif; 
            ?>
            <p><?php the_field('luogo'); ?>, <?php
                $data_film = get_field('data');
                $solo_anno = get_field('solo_anno');
                $date_obj = DateTime::createFromFormat('d/m/Y', $data_film);
                if ($date_obj) {
                    echo $date_obj->format('Y'); 
                } else {
                    // Se il formato non è d/m/Y, prova a pulirlo
                    echo date("Y", strtotime(str_replace('/', '-', $data_film)));
                }
                ?>, <?php the_field('durata'); ?></p>
        </div>
    </a>
</div>