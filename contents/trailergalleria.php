<?php 
    $trailer = get_field('trailer');
    if( $trailer ):
        ?>
            <section class="element trailerbox">
                <h3>Trailer</h3>
                <div class="trailer">
                    <?php the_field('trailer'); ?>
                </div>
            </section>    
<?php endif; ?>
<?php 
    $video = get_field('video');
    if( $video ):
        ?>
            <section class="element trailerbox">
                <h3>video</h3>
                <div class="trailer">
                    <?php the_field('video'); ?>
                </div>
            </section>    
<?php endif; ?>
<?php 
    $gruppo_galleria = get_field('galleria');

    //  Controllo di esistenza: cerchiamo se c'è almeno un'immagine nell'array del gruppo
    $esiste_almeno_una = false;
    if (!empty($gruppo_galleria)) {
        foreach ($gruppo_galleria as $valore) {
            if (!empty($valore)) {
                $esiste_almeno_una = true;
                break; 
            }
        }
    }

    //  Se almeno una foto esiste, procediamo con la stampa
    if ($esiste_almeno_una) : ?>
        <section class="element galleria">
            <h3>Galleria</h3>
            <div>
                <?php 
                    // Definiamo la struttura delle righe
                    $config_righe = [
                        ['immagine1', 'immagine2', 'immagine3'],
                        ['immagine4', 'immagine5', 'immagine6'],
                        ['immagine7', 'immagine8', 'immagine9']
                    ];

                    foreach ($config_righe as $nomi_campi) :
                        $esistenti_in_riga = [];
                        
                        foreach ($nomi_campi as $nome) {
                            // Verifichiamo se il campo specifico esiste nel gruppo
                            if (!empty($gruppo_galleria[$nome])) {
                                $img = $gruppo_galleria[$nome];
                                $esistenti_in_riga[] = [
                                    'url' => is_array($img) ? $img['url'] : wp_get_attachment_url($img),
                                    'alt' => is_array($img) ? $img['alt'] : get_post_meta($img, '_wp_attachment_image_alt', true),
                                    'classe' => $nome
                                ];
                            }
                        }

                        // Se la riga contiene immagini, la creiamo
                        if (!empty($esistenti_in_riga)) : ?>
                            <div class="riga-galleria">
                                <?php foreach ($esistenti_in_riga as $foto) : ?>
                                    <div class="grid-item <?php echo esc_attr($foto['classe']); ?>">
                                        <img src="<?php echo esc_url($foto['url']); ?>" alt="<?php echo esc_attr($foto['alt']); ?>" onclick="openFullImage(this.src)">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; 
                    endforeach; ?>
            </div>
        </section>
<?php endif;?>