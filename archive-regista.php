<?php get_header(); ?>

<?php
/**
 * LOGICA DI FILTRAGGIO E CALCOLO - ARCHIVIO REGISTI
 */
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

// 1. Recupero parametri dall'URL
$ordine_completo = $_GET['ordine'] ?? 'title-ASC'; // Default Alfabetico A-Z
$parti           = explode('-', $ordine_completo);
$ordina_per      = $parti[0] ?? 'title';
$senso_ordine    = $parti[1] ?? 'ASC';
$cerca           = $_GET['cerca'] ?? '';

// 2. Costruzione Query WP_Query
$args = array(
    'post_type'      => 'regista',
    'posts_per_page' => 20,
    'paged'          => $paged,
    'orderby'        => 'title',
    'order'          => ($senso_ordine === 'DESC') ? 'DESC' : 'ASC',
);

// Filtro Ricerca Testuale
if (!empty($cerca)) {
    $args['s'] = sanitize_text_field($cerca);
}

$query_registi = new WP_Query($args);
?>

<main>
    <section class="element archivio-registi">
        <h3>Archivio Registi</h3>
        
        <div class="filtri">
            <form action="<?php echo get_post_type_archive_link('regista'); ?>" method="GET">
                
                <div class="filtririga riga1">
                    <div class="filtro ricerca">
                        <input type="text" name="cerca" placeholder="Cerca un regista..." value="<?php echo esc_attr($cerca); ?>">
                        <button type="submit" class="btn-search-submit">FILTRA</button>
                    </div>
                    
                    <div class="filtro ordinefiltro">
                        <label>Ordine</label>
                        <select name="ordine">
                            <option value="title-ASC" <?php selected($ordine_completo, 'title-ASC'); ?>>Alfabetico (A-Z)</option>
                            <option value="title-DESC" <?php selected($ordine_completo, 'title-DESC'); ?>>Alfabetico (Z-A)</option>
                        </select>
                    </div>
                </div>

                <div class="filtririga riga3">
                    <div class="filtroextra filtroreset">
                        <a href="<?php echo get_post_type_archive_link('regista'); ?>" class="btn-reset">RESET</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="risultati-archivio">
            <?php if ($query_registi->have_posts()) : ?>
                
                <?php get_template_part('contents/numeripagina', null, ['query' => $query_registi, 'paged' => $paged]); ?>
                
                <div class="griglia-risultati">
                    <?php while ($query_registi->have_posts()) : $query_registi->the_post(); ?>
                        <?php 
                        get_template_part('/contents/registacard'); 
                        ?>
                    <?php endwhile; ?>
                </div>

                <?php get_template_part('contents/numeripagina', null, ['query' => $query_registi, 'paged' => $paged]); ?>

            <?php else : ?>
                <div class="no-results">
                    <p>Nessun regista trovato.</p>
                </div>
            <?php endif; wp_reset_postdata(); ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>