<?php get_header(); ?>

<?php
$regista_id = get_the_ID();

$nome_regista = get_the_title($regista_id);
$foto_regista = get_field('foto_regista', $regista_id);
$bio_regista  = get_field('bio', $regista_id);
$link_regista = get_permalink($regista_id);
$altre_produzioni = get_field('altre_produzioni')
?>

<main class="pagina-regista">
    <section class="element schedaregista megablocco">
        <?php echo get_template_part('/contents/schedaregista'); ?>
    </section>
    <?php 
    get_template_part('/contents/carouselfilm', null, [
        'titolo'        => 'Filmografia completa',
        'regista_id'    => get_the_ID(), 
        'numero_poster' => -1             // -1 significa "mostrali tutti"
    ]); 
    ?>
<?php
    if ($altre_produzioni) {
        ?>
            <section class="element no-solaris-film megablocco">
                <div>
                    <div>
                        <h4>Altre produzioni</h4>
        <?php
        // Dividiamo il testo in righe e puliamo spazi bianchi
        $righe = explode("\n", str_replace("\r", "", $altre_produzioni));
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
        ?>
                    </div>
                </div>
            </section>
        <?php
    }
?>
</main>

<?php get_footer(); ?>