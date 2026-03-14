<?php get_header(); ?>

<?php
/**
 * LOGICA DI FILTRAGGIO E CALCOLO
 */
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

// 1. Recupero parametri dall'URL
$ordine_completo = $_GET['ordine'] ?? 'data-DESC'; 
$parti           = explode('-', $ordine_completo);
$ordina_per      = $parti[0] ?? 'data';
$senso_ordine    = $parti[1] ?? 'DESC';
$cerca           = $_GET['cerca'] ?? '';
$genere_slug     = $_GET['genere'] ?? '';
$status_slug     = $_GET['status'] ?? '';
$regista_id      = $_GET['regista_id'] ?? '';
$anno_selezionato = $_GET['anno'] ?? '';

// 2. Calcolo Dinamico Range Anni (Min e Max dai campi ACF "data")
global $wpdb;
$range_anni = $wpdb->get_row("
    SELECT 
        MIN(meta_value) as anno_min, 
        MAX(meta_value) as anno_max 
    FROM {$wpdb->postmeta} 
    WHERE meta_key = 'data' AND meta_value != ''
");

// 3. Costruzione Query WP_Query
$args = array(
    'post_type'      => 'film',
    'posts_per_page' => 20,
    'paged'          => $paged,
    'order'          => $senso_ordine,
    'tax_query'      => array('relation' => 'AND'),
    'meta_query'     => array('relation' => 'AND'),
);

// Ordinamento
if ($ordina_per === 'title') {
    $args['orderby'] = 'title';
} else {
    $args['meta_key'] = 'data';
    $args['orderby']  = 'meta_value_num'; 
}

// Filtro Ricerca Testuale
if (!empty($cerca)) {
    $args['s'] = sanitize_text_field($cerca);
}

// Filtri Tassonomie
if (!empty($genere_slug)) {
    $args['tax_query'][] = ['taxonomy' => 'genere', 'field' => 'slug', 'terms' => $genere_slug];
}
if (!empty($status_slug)) {
    $args['tax_query'][] = ['taxonomy' => 'status-film', 'field' => 'slug', 'terms' => $status_slug];
}

// Filtro Regista (Relazione ACF)
if (!empty($regista_id)) {
    $args['meta_query'][] = [
        'key'     => 'regista',
        'value'   => '"' . sanitize_text_field($regista_id) . '"',
        'compare' => 'LIKE',
    ];
}

// Filtro Anno (Basato su campo ACF "data")
if (!empty($anno_selezionato)) {
    $args['meta_query'][] = [
        'key'     => 'data',
        'value'   => $anno_selezionato,
        'compare' => 'LIKE',
    ];
}

$query_film = new WP_Query($args);

// 4. Link per Film Casuale
$random_film_url = add_query_arg([
    'post_type' => 'film',
    'random'    => '1'
], home_url('/'));
?>


<main>
    <section class="element archivio-film">
        <h3>Archivio film completo</h3>
        
        <div class="filtri">
            <form action="<?php echo get_post_type_archive_link('film'); ?>" method="GET">
                
                <div class="filtririga riga1">
                    <div class="filtro ricerca">
                        <input type="text" name="cerca" placeholder="Cerca un film..." value="<?php echo esc_attr($cerca); ?>">
                        <button type="submit" class="btn-search-submit">FILTRA</button>
                    </div>
                    
                    <div class="filtro ordinefiltro">
                        <label>Ordine</label>
                        <select name="ordine">
                            <option value="data-DESC" <?php selected($ordine_completo, 'data-DESC'); ?>>Data di pubblicazione (discendente)</option>
                            <option value="data-ASC" <?php selected($ordine_completo, 'data-ASC'); ?>>Data di pubblicazione (crescente)</option>
                            <option value="title-ASC" <?php selected($ordine_completo, 'title-ASC'); ?>>Alfabetico (A-Z)</option>
                            <option value="title-DESC" <?php selected($ordine_completo, 'title-DESC'); ?>>Alfabetico (Z-A)</option>
                        </select>
                    </div>
                </div>

                <div class="filtririga riga2">
                    <div class="filtro">
                        <label>Genere</label>
                        <select name="genere">
                            <option value="">Tutti i generi</option>
                            <?php 
                            $generi = get_terms(['taxonomy' => 'genere', 'hide_empty' => true]);
                            foreach($generi as $g) echo '<option value="'.$g->slug.'" '.selected($genere_slug, $g->slug, false).'>'.$g->name.'</option>';
                            ?>
                        </select>
                    </div>
                    
                    <div class="filtro">
                        <label>Status</label>
                        <select name="status">
                            <option value="">Qualsiasi</option>
                            <?php 
                            $status_list = get_terms(['taxonomy' => 'status-film', 'hide_empty' => true]);
                            foreach($status_list as $s) echo '<option value="'.$s->slug.'" '.selected($status_slug, $s->slug, false).'>'.$s->name.'</option>';
                            ?>
                        </select>
                    </div>

                    <div class="filtro">
                        <label>Regista</label>
                        <select name="regista_id">
                            <option value="">Tutti</option>
                            <?php 
                            $registi = get_posts(['post_type' => 'regista', 'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC']);
                            foreach($registi as $r) echo '<option value="'.$r->ID.'" '.selected($regista_id, $r->ID, false).'>'.$r->post_title.'</option>';
                            ?>
                        </select>
                    </div>

                    <div class="filtro">
                        <label>Anno</label>
                        <select name="anno">
                            <option value="">Tutti</option>
                            <?php 
                            if ($range_anni && $range_anni->anno_min) {
                                $anno_max_db = intval(substr($range_anni->anno_max, 0, 4));
                                $anno_min_db = intval(substr($range_anni->anno_min, 0, 4));
                                
                                for ($i = $anno_max_db; $i >= $anno_min_db; $i--) {
                                    echo '<option value="'.$i.'" '.selected($anno_selezionato, $i, false).'>'.$i.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="filtririga riga3">
                    <div class="filtroextra filtroreset">
                        <a href="<?php echo get_post_type_archive_link('film'); ?>" class="btn-reset">RESET</a>
                    </div>
                    <div class="filtroextra filtrocasual">
                       <a href="<?php echo esc_url($random_film_url); ?>" class="btn-casual">Film a caso</a>
                    </div>
                </div>
            </form>
        </div>
        <?php if ($query_film->have_posts()) : ?>
            
            <?php get_template_part('contents/numeripagina', null, ['query' => $query_film, 'paged' => $paged]); ?>
            
            <div class="griglia-risultati">
                <?php while ($query_film->have_posts()) : $query_film->the_post(); ?>
                    <?php get_template_part('/contents/filmcard'); ?>
                <?php endwhile; ?>
            </div>

            <?php get_template_part('contents/numeripagina', null, ['query' => $query_film, 'paged' => $paged]); ?>

        <?php else : ?>
            <div class="no-results">
                <p>Nessun film corrisponde ai filtri selezionati.</p>
            </div>
        <?php endif; wp_reset_postdata(); ?>
    </section>
</main>

<?php get_footer(); ?>