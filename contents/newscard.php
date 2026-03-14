<div class="card-lista news-card megablocco">
    <a href="<?php the_permalink(); ?>">
        <div>
            <div>
                <div class="copertina-news">
                    <?php 
                        $imgcopertina = get_field('copertina'); 
                        if( $imgcopertina ):
                            $size = 'newsimagepc'; // Default per desktop

                            // Se è mobile, sovrascriviamo la variabile $size
                            if ( wp_is_mobile() ) { 
                                $size = 'newsimagemobile'; 
                            }
                            $sizecopertina = $imgcopertina['sizes'][$size]; 
                            ?>
                                <img 
                                    src="<?php echo esc_url($sizecopertina); ?>" 
                                    alt="<?php echo esc_attr($imgcopertina['alt']); ?>" 
                                    fetchpriority="high"
                                    loading="eager"
                                    onclick="openFullImage(this.src)"
                                >
                        <?php endif;
                    ?>
                    <div>
                        <?php
                        $termini = get_field('tag');

                        if ( $termini ) : 

                            $array_termini = is_array($termini) ? $termini : array($termini);

                            $limit_tag = array_slice($array_termini, 0, 3);

                            foreach ( $limit_tag as $single_tag ) : ?>
                                <p><?php echo esc_html($single_tag->name); ?></p>
                            <?php endforeach; ?>

                        <?php endif; ?>
                    </div>
                    <h3><?php echo get_the_title();?></h3>
                </div>
            </div>
            <div class="testo-news">
                <?php 
                    $testo = get_field('testo'); 
                    ?><p><?php echo nl2br($testo);?></p>
            </div>
        </div>
    </a>
</div>