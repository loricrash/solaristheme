<?php get_header(); ?>

<?php
$tag = get_the_terms(get_the_ID(),'tag');

$film_id = get_field('film');

if( is_array($film_id) ) {
    $film_id = $film_id[0];
}

$nome_film = get_the_title($film_id);
$locandina_film = get_field('locandina', $film_id);
$link_film = get_permalink($film_id);

$regista_id = get_field('regista', $film_id);
// Pulizia dell'ID se è un array
if( is_array($regista_id) ) {
    $regista_id = $regista_id[0];
}

$nome_regista = get_the_title($regista_id);
$foto_regista = get_field('foto_regista', $regista_id);
$bio_regista  = get_field('bio', $regista_id);
$link_regista = get_permalink($regista_id);

$data_film = get_field('data');
$solo_anno = get_field('solo_anno');
$date_obj = DateTime::createFromFormat('d/m/Y', $data_film);
?>

<main>
    <section class="element schedanews megablocco">
        <div>
            <div>
                <div class="copertina-news">
                    <?php 
                        $imgcopertina = get_field('copertina'); 
                        $sizecopertina = $imgcopertina['sizes']['newsimagefullpc']; 
                        if( $imgcopertina ):
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
                <?php 
                    if( $film_id ):
                        $sizeposter = $locandina_film['sizes']['locandinabig']; 
                        ?>
                            <div class="linevertical"></div>
                            <div class="film-della-news">
                                <div class="poster">
                                    <img 
                                        src="<?php echo esc_url($sizeposter); ?>" 
                                        alt="<?php echo esc_attr($locandina_film['alt']); ?>" 
                                        fetchpriority="high"
                                        loading="eager"
                                        onclick="openFullImage(this.src)"
                                    >
                                </div>
                                <a href="<?php $link_film; ?>" class="btn-scheda"><h4>Scheda</h4></a>
                            </div>
                    <?php endif;
                ?>
            </div>
            <div class="testo-news">
                <?php 
                    $testo = get_field('testo'); 
                    ?><p><?php echo nl2br($testo);?></p>
            </div>
        </div>
        <?php 
            if( $film_id ):
                $sizeposter = $locandina_film['sizes']['locandinabig']; 
                ?>
                <div class="film-della-news film-della-news-per-mobile">
                    <div class="poster">
                        <img 
                            src="<?php echo esc_url($sizeposter); ?>" 
                            alt="<?php echo esc_attr($locandina_film['alt']); ?>" 
                            fetchpriority="high"
                            loading="eager"
                            onclick="openFullImage(this.src)"
                        >
                    </div>
                    <a href="<?php $link_film; ?>" class="btn-scheda"><h4>Scheda</h4></a>
                </div>
            <?php endif;
        ?>
    </section>
    <?php 
        get_template_part('/contents/carouselnews', null, [
            'titolo'     => 'Ultime notizie',
            'tassonomia' => '',
            'termine'    => '',
            'quantitapost' => 10,
            'escludi_news'  => get_the_ID(), // Evita di mostrare il film attuale
        ]);
    ?>
</main>

<?php get_footer(); ?>