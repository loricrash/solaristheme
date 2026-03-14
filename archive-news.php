<?php get_header(); ?>

<?php
/**
 * LOGICA DI FILTRAGGIO E CALCOLO
 */
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

// 1. Recupero parametri dall'URL
$ordine_completo = $_GET['ordine'] ?? 'date-DESC'; 
$parti           = explode('-', $ordine_completo);
$ordina_per      = $parti[0] ?? 'date'; // 'date' invece di 'data'
$senso_ordine    = $parti[1] ?? 'DESC';
$cerca           = $_GET['cerca'] ?? '';
$tag_slug        = $_GET['tag'] ?? ''; // Slug del tag dalla select

// 2. Costruzione Query WP_Query
$args = array(
    'post_type'      => 'news',
    'posts_per_page' => 20,
    'paged'          => $paged,
    'order'          => $senso_ordine,
    'tax_query'      => array('relation' => 'AND'),
);

// Ordinamento Nativo
if ($ordina_per === 'title') {
    $args['orderby'] = 'title';
} else {
    // Usiamo la data di pubblicazione nativa di WP
    $args['orderby'] = 'date'; 
}

// Filtro Ricerca Testuale
if (!empty($cerca)) {
    $args['s'] = sanitize_text_field($cerca);
}

// Filtro Tassonomia corretta (tag-news)
if (!empty($tag_slug)) {
    $args['tax_query'][] = array(
        'taxonomy' => 'tag-news', // <--- Corretto slug tassonomia
        'field'    => 'slug',
        'terms'    => $tag_slug,
    );
}

$query_news = new WP_Query($args);

// URL per notizia casuale (corretto per funzionare con la query standard)
$random_news_url = add_query_arg([
    'post_type' => 'news',
    'orderby'   => 'rand',
    'posts_per_page' => 1
], home_url('/'));
?>

<main>
    <section class="element archivio-news">
        <h3>Archivio Notizie completo</h3>
        
        <div class="filtri">
            <form action="<?php echo get_post_type_archive_link('news'); ?>" method="GET">
                
                <div class="filtririga riga1">
                    <div class="filtro ricerca">
                        <input type="text" name="cerca" placeholder="Cerca una notizia..." value="<?php echo esc_attr($cerca); ?>">
                        <button type="submit" class="btn-search-submit">FILTRA</button>
                    </div>
                    
                    <div class="filtro ordinefiltro">
                        <label>Ordine</label>
                        <select name="ordine">
                            <option value="date-DESC" <?php selected($ordine_completo, 'date-DESC'); ?>>Data di pubblicazione (più recenti)</option>
                            <option value="date-ASC" <?php selected($ordine_completo, 'date-ASC'); ?>>Data di pubblicazione (meno recenti)</option>
                            <option value="title-ASC" <?php selected($ordine_completo, 'title-ASC'); ?>>Alfabetico (A-Z)</option>
                            <option value="title-DESC" <?php selected($ordine_completo, 'title-DESC'); ?>>Alfabetico (Z-A)</option>
                        </select>
                    </div>
                </div>

                <div class="filtririga riga2">
                    <div class="filtro">
                        <label>Etichette</label>
                        <select name="tag">
                            <option value="">Tutte le etichette</option>
                            <?php 
                            // Usiamo lo slug corretto 'tag-news'
                            $tags = get_terms(['taxonomy' => 'tag-news', 'hide_empty' => true]);
                            if($tags && !is_wp_error($tags)){
                                foreach($tags as $g) {
                                    echo '<option value="'.$g->slug.'" '.selected($tag_slug, $g->slug, false).'>'.$g->name.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="filtririga riga3">
                    <div class="filtroextra filtroreset">
                        <a href="<?php echo get_post_type_archive_link('news'); ?>" class="btn-reset">RESET</a>
                    </div>
                    <div class="filtroextra filtrocasual">
                       <a href="<?php echo esc_url($random_news_url); ?>" class="btn-casual">Notizia a caso</a>
                    </div>
                </div>
            </form>
        </div>

        <?php if ($query_news->have_posts()) : ?>
            
            <?php get_template_part('contents/numeripagina', null, ['query' => $query_news, 'paged' => $paged]); ?>
            
            <div class="griglia-risultati">
                <?php while ($query_news->have_posts()) : $query_news->the_post(); ?>
                    <?php get_template_part('contents/newscard'); ?>
                <?php endwhile; ?>
            </div>

            <?php get_template_part('contents/numeripagina', null, ['query' => $query_news, 'paged' => $paged]); ?>

        <?php else : ?>
            <div class="no-results">
                <p>Nessuna notizia corrisponde ai filtri selezionati.</p>
            </div>
        <?php endif; wp_reset_postdata(); ?>
    </section>
</main>

<?php get_footer(); ?>