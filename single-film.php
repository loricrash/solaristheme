<?php get_header(); ?>

<?php
$generi = get_the_terms(get_the_ID(), 'genere');
$regista_id = get_field('regista');
// Pulizia dell'ID se è un array
if( is_array($regista_id) ) {
    $regista_id = $regista_id[0];
}
// echo wp_kses_post($bio_regista);
/*<img src="<?php echo esc_url($foto_regista['url']); ?>" alt="<?php echo esc_attr($nome_regista); ?>">*/
$nome_regista = get_the_title($regista_id);
$foto_regista = get_field('foto_regista', $regista_id);
$bio_regista  = get_field('bio', $regista_id);
$link_regista = get_permalink($regista_id);

$premi = get_field("premi");
$data_film = get_field('data');
$solo_anno = get_field('solo_anno');
$date_obj = DateTime::createFromFormat('d/m/Y', $data_film);
?>

<main>
    <section class="hero">
        <div class="sfondohero gradienthero">
        </div>
        <div class="sfondohero">
            <?php 
            $imghero = get_field('copertina_frame_iconico'); 
            $desktop = $imghero['sizes']['hero_full_hd']; 
            $tablet  = $imghero['sizes']['large'];        
            $mobile  = $imghero['sizes']['medium_large']; 
            if( $imghero ):
                 ?>
                 <picture>
                    <source media="(min-width: 1025px)" srcset="<?php echo esc_url($desktop); ?>">
                    <source media="(min-width: 769px)" srcset="<?php echo esc_url($tablet); ?>">
                    <img 
                        src="<?php echo esc_url($mobile); ?>" 
                        alt="<?php echo esc_attr($imghero['alt']); ?>" 
                        class="hero-img-fissa"
                        fetchpriority="high"
                        loading="eager"
                    >
                </picture>
            <?php endif; ?>
        </div>
        <div class="contenthero">
            <div class="updividerhero">
                <div class="testohero">
                    <h1><?php echo get_the_title();?></h1>
                    <div class="sottotitolohero">
                        <h3>di <a href="<?php echo $link_regista;?>"><?php echo $nome_regista;?></a></h3>
                    </div>
                </div>
                <?php 
                if ( has_term( 'in-arrivo', 'status-film' ) ) {
                    ?><div class="status-hero status-arrivo"> 
                        <div>
                            <h4>
                                <?php 
                                    if ( $solo_anno && $data_film ) {
                                        // Usiamo questo metodo che è più robusto di strtotime
                                        
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
                    <?php
                } 
                else if ( has_term( 'al-cinema', 'status-film' ) ) {
                    ?><div class="status-hero status-cinema"> 
                        <div>
                            <h4>Al cinema</h4>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <div class="box-infohero">
                    <div class="infohero">
                    <div>
                        <h4><?php echo get_field('durata');?>
                        </h4>
                        <h4><?php echo get_field('luogo');?>
                        </h4>
                             <?php 
                                $data = get_field('data');
                                if( $data ) {
                                    // Converte la stringa in data e formatta restituendo solo l'anno ('Y')
                                    ?><h4><?php 
                                            if ($date_obj) {
                                            echo $date_obj->format('Y'); 
                                            } else {
                                                // Se il formato non è d/m/Y, prova a pulirlo
                                                echo date("Y", strtotime(str_replace('/', '-', $data_film)));
                                            } ?>
                                    </h4><?php
                                }
                            ?>
                    </div>
                    <div class="linehero"></div>
                    <div>
                        <?php
                        // 2. Controlla che ci siano termini e che non ci siano errori
                        if ( $generi && ! is_wp_error( $generi ) ) : 
                            // 3. Prendi solo i primi 3 elementi dell'array
                            $generi_limitati = array_slice($generi, 0, 3);
                            foreach ( $generi_limitati as $genere ) : ?>
                                <h4><?php echo esc_html($genere->name); ?></h4>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
                </div>
            </div>
            <div class="dividerhero">
                <div class="linehero"></div>
                <a class="arrowhero" href="#portagiu">
                    <svg viewBox="0 0 24 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 1L12 11L23 1" stroke="currentColor" stroke-width="var(--line2)" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                <div class="linehero"></div>
            </div>
        </div>
    </section>
    <section class="element keytext" id="portagiu" >
        <div>
            <h3><?php echo get_field('testo_chiave');?></h3>
        </div>
    </section>
    <section class="element schedafilm megablocco">
        <div>
            <div class="poster">
                <?php 
                    $imgposter = get_field('locandina'); 
                    $sizeposter = $imgposter['sizes']['locandinabig']; 
                    if( $imgposter ):
                        ?>
                            <img 
                                src="<?php echo esc_url($sizeposter); ?>" 
                                alt="<?php echo esc_attr($imgposter['alt']); ?>" 
                                fetchpriority="high"
                                loading="eager"
                                onclick="openFullImage(this.src)"
                            >
                    <?php endif; ?>
            </div>
            <div>
                <h4>Sinossi</h4>
                <?php 
                    $testo = get_field('sinossi'); 
                    ?><p><?php echo nl2br($testo);?></p><?php
                ?>
            </div>
            <div class="linevertical"></div>
            <div>
                <h4 class="titolo-in-scheda"><?php echo get_the_title();?></h4>
                <p>un film di <a href="<?php echo $link_regista;?>"><?php echo $nome_regista;?></a></p>
                <div class="extrainfofilm">
                    <?php
                    if ($premi) : 
                        ?><p><?php echo nl2br($premi);?></p>
                    <?php endif; ?>
                    <p>
                        <?php
                        if ( $generi && ! is_wp_error( $generi ) ) : 
                            $total_generi = count($generi); // Conta quanti generi ci sono
                            $i = 0; // Inizializza un contatore
                            foreach ( $generi as $genere ) : 
                                $i++; // Incrementa il contatore a ogni giro
                                ?>
                                <?php echo esc_html($genere->name); ?>
                                <?php 
                                // Aggiunge <br> solo se NON è l'ultimo elemento
                                if ($i < $total_generi) {
                                    echo '<br>';
                                }
                                ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </p>
                    <div class="linehorizontal"></div>
                    <p><?php echo get_field('luogo');?></p>
                    <p><?php echo get_field('data');?></p>
                    <p><?php echo get_field('durata');?></p>
                </div>
            </div>
        </div>
        <div class="teamcastfilm">
            <div class="teamfilm">
                <?php
                    $credits_raw = get_field('credits_lista');

                    if ($credits_raw) {
                        // Dividiamo il testo in righe e puliamo spazi bianchi
                        $righe = explode("\n", str_replace("\r", "", $credits_raw));
                        $righe = array_filter(array_map('trim', $righe));
                        
                        // Riazzera gli indici dell'array dopo il filtro
                        $righe = array_values($righe);

                        // Cicliamo l'array saltando di 2 in 2
                        for ($i = 0; $i < count($righe); $i += 2) {
                            $ruolo = $righe[$i];
                            $nome = isset($righe[$i+1]) ? $righe[$i+1] : ''; // Controllo se esiste il nome dopo il ruolo

                            echo '<div class="ruolo">';
                                echo '<p>' . esc_html($ruolo) . '</p>';
                                if ($nome) {
                                    echo '<p class="nomeruolo">' . esc_html($nome) . '</p>';
                                }
                            echo '</div>';
                        }
                    }
                ?>
            </div>
            <div class="linevertical"></div>
            <div class="castfilm">
                <?php
                    $cast_raw = get_field('cast_lista');

                    if ($cast_raw) {
                        // Dividiamo il testo in righe e puliamo spazi bianchi
                        $righe = explode("\n", str_replace("\r", "", $cast_raw));
                        $righe = array_filter(array_map('trim', $righe));
                        
                        // Riazzera gli indici dell'array dopo il filtro
                        $righe = array_values($righe);

                        // array saltando di 2 in 2
                        for ($i = 0; $i < count($righe); $i += 2) {
                            $ruolo = $righe[$i];
                            $nome = isset($righe[$i+1]) ? $righe[$i+1] : ''; // Controllo se esiste il nome dopo il ruolo

                            echo '<div class="ruolo">';
                                echo '<p>' . esc_html($ruolo) . '</p>';
                                if ($nome) {
                                    echo '<p class="nomeruolo">' . esc_html($nome) . '</p>';
                                }
                            echo '</div>';
                        }
                    }
                ?>
            </div>
        </div>
    </section>
    <?php echo get_template_part('/contents/trailergalleria'); ?>
    <section class="element schedaregista megablocco">
        <h3>Il Regista</h3>
        <?php echo get_template_part('/contents/schedaregista'); ?>
        <div class="altrifilm">
            <?php 
                // ID del regista dal campo ACF 'regista' del film corrente
                $regista_corrente = get_field('regista', false, false); 

                get_template_part('/contents/carouselfilm', null, [
                    'titolo'        => 'Altri film di questo regista',
                    'regista_id'    => $regista_corrente,
                    'escludi_film'  => get_the_ID(), // Evita di mostrare il film attuale
                    'numero_poster' => 4
                ]); 
            ?>
        </div>
        <aside  class="contenitore-btn-scheda">
            <a href="<?php echo $link_regista;?>" class="btn-scheda"><h4>Scheda completa del regista</h4></a>
        </aside>
    </section>
</main>

<?php get_footer(); ?>