<?php

/* Theme setup
*/
if (!function_exists('solaristheme_setup')) {
    
    function solaristheme_setup() {
        
        add_theme_support("title-tag");
        
        // Enable automatic feed Links
        // add_theme_support('automatic-feed-links' );
        
        // Enable featured image
        // add_theme_support('post-thumbnails' );

        // Thumbnail sizes
        //add_image_size('solaristheme_single', 800, 493, true); //(cropped)
        //add_image_size('solari _big', 1400, 928, true); //(cropped)

        add_image_size( 'hero_full_hd', 1920, 1080, true );

        add_image_size( 'locandinabig', 299, 448, true );
        add_image_size( 'locandinamedium', 178, 267, true );

        add_image_size( 'locandinasmall', 143, 215, true );
        add_image_size( 'locandinaextrasmall', 178, 267, true );

        add_image_size( 'newsimagefullpc', 697, 392, true );
        add_image_size( 'newsimagepc', 457, 257, true );
        add_image_size( 'newsimagemobile', 352, 198, true );


        // Load theme Languages
        load_theme_textdomain( 'solaristheme', get_template_directory().'/Languages' );
    }
}

add_action('after_setup_theme', 'solaristheme_setup');

/*  Styles (css) and script (javascript)
*/
if (!function_exists('solaristheme_style_scripts') ) {

    function solaristheme_style_scripts() {

        // per inserire il js
        wp_enqueue_script('solaristheme-scripts', get_template_directory_uri() . '/js/scripts.js', NULL, /*array('jquery'),*/'', true); // true = nel footer

        wp_enqueue_style('gstatic', '//fonts.gstatic.com');
        wp_enqueue_style('gapis', '//fonts.googleapis.com');

        wp_enqueue_style('Roboto', "https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"); //questo è da sistemare in termini di link
        
        wp_enqueue_style('solaristheme_style', get_template_directory_uri().'/style.css');
   
    }
}
add_action('wp_enqueue_scripts', 'solaristheme_style_scripts');

/* per inseire il colore 
*/
function render_acf_color_data() {

    if ( !is_singular('film') ) { //blocca la function nel caso in cui non siamo in una pagina film
            return;
        }
    global $post;
    $post_id = get_the_ID();

    $attiva   = get_field('colore_dinamico', $post_id);
    $auto     = get_field('colore_automatico', $post_id);
    $img      = get_field('copertina_frame_iconico', $post_id);
    $custom   = get_field('colore_personalizzato', $post_id);
    $sfondoneutro = get_field('sfondo_neutro', $post_id);

    // Se l'immagine è un array o un ID, otteniamo l'URL
    $img_url = '';
    if ($img) {
        $img_url = is_array($img) ? $img['sizes']['thumbnail'] : (is_numeric($img) ? wp_get_attachment_image_url($img, 'thumbnail') : $img);
    }

    // Assicuriamoci che il colore custom sia un HEX valido o nero come fallback
    $custom_hex = ($custom) ? $custom : '#000000';

    echo '<div id="acf-color-bridge" 
            style="display:none;"
            data-attiva="' . ($attiva ? 'true' : 'false') . '"
            data-auto="' . ($auto ? 'true' : 'false') . '"
            data-sfondoneutro="' . ($sfondoneutro ? 'true' : 'false') . '"
            data-img="' . esc_url($img_url) . '"
            data-custom="' . esc_attr($custom_hex) . '">
          </div>';
}
add_action('wp_footer', 'render_acf_color_data');

// x modificare in automatico lo status

function cron_aggiorna_status_film() {
    $oggi = date('Ymd'); 
    
    $args = array(
        'post_type'      => 'film', 
        'posts_per_page' => -1,
        'meta_query'     => array(
            array(
                'key'     => 'data', 
                'value'   => $oggi,
                'compare' => '<=',
                'type'    => 'NUMERIC',
            ),
        ),
        'tax_query'      => array(
            array(
                'taxonomy' => 'status-film', 
                'field'    => 'slug',
                'terms'    => 'in-arrivo',
            ),
        ),
    );

    $query_film = new WP_Query($args);

    if ($query_film->have_posts()) {
        while ($query_film->have_posts()) {
            $query_film->the_post();
            // Cambia la tassonomia in 'al-cinema'
            wp_set_object_terms(get_the_ID(), 'al-cinema', 'status-film', false);
        }
        wp_reset_postdata();
    }
}

if (!wp_next_scheduled('hook_cron_status_film')) {
    wp_schedule_event(time(), 'daily', 'hook_cron_status_film');
}
add_action('hook_cron_status_film', 'cron_aggiorna_status_film');


//Rimuove la voce 'Articoli' dal menu della bacheca
add_action('admin_menu', 'custom_remove_default_post_type');

function custom_remove_default_post_type() {
    remove_menu_page('edit.php');
}

// Rimuove i Commenti dal menu e disabilita il supporto
add_action('admin_init', function () {
    // Reindirizza gli utenti che provano ad accedere direttamente alla pagina commenti
    global $pagenow;
    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url());
        exit;
    }

    // Rimuove il supporto ai commenti nei post type esistenti
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});

// Rimuove la voce dal menu della bacheca
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});

// Rimuove i commenti dalla barra superiore (Admin Bar)
add_action('wp_before_admin_bar_render', function () {
    global $wp_admin_bar;
    $wp_admin_bar->remove_node('comments');
});


/**
 * Crea il Post Type per le Info Globali
 */
function register_info_globali_cpt() {
    $args = array(
        'label'               => 'Info globali',
        'public'              => false,      // Non serve un URL pubblico
        'show_ui'             => true,       // Lo vogliamo nel menu
        'menu_icon'           => 'dashicons-info', // La tua icona (cerchio con la "i")
        'supports'            => array('title'),   // Ci basta il titolo
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'menu_position'       => 20,         // Posizionato sotto "Pagine"
    );
    register_post_type('info_globali', $args);
}
add_action('init', 'register_info_globali_cpt');

// Blocca la creazione di più di un post per Info Globali
function limit_info_globali_creation() {
    $count_posts = wp_count_posts('info_globali');
    $published_posts = $count_posts->publish;

    if ($published_posts >= 1) {
        // Nasconde il tasto "Aggiungi nuovo" dal menu laterale
        remove_submenu_page('edit.php?post_type=info_globali', 'post-new.php?post_type=info_globali');
        
        // Nasconde il tasto "Aggiungi nuovo" tramite CSS nella lista post
        add_action('admin_head', function() {
            $screen = get_current_screen();
            if ($screen && $screen->post_type == 'info_globali') {
                echo '<style>.page-title-action { display: none !important; }</style>';
            }
        });
    }
}
add_action('admin_menu', 'limit_info_globali_creation');

/**
 * Recupera l'ID dell'unico post in Info Globali
 */
function get_info_globali_id() {
    $posts = get_posts(array(
        'post_type'   => 'info_globali',
        'numberposts' => 1,
        'fields'      => 'ids',
        'post_status' => array('publish', 'private', 'draft')
    ));
    return !empty($posts) ? $posts[0] : null;
}

/**
 * Reindirizzamento automatico a un film casuale
 */
add_action('template_redirect', function() {
    if (isset($_GET['random']) && $_GET['random'] == 1 && isset($_GET['post_type']) && $_GET['post_type'] == 'film') {
        $random_post = get_posts([
            'post_type'      => 'film',
            'posts_per_page' => 1,
            'orderby'        => 'rand',
            'fields'         => 'ids' // Recuperiamo solo l'ID per velocità
        ]);

        if (!empty($random_post)) {
            wp_redirect(get_permalink($random_post[0]));
            exit;
        }
    }
});

//per avere 2 menu diversi per header e menu
function registra_miei_menu() {
  register_nav_menus(
    array(
      'header' => __( 'Header Menu' ),
      'footer' => __( 'Footer Menu' ) // Questa è la riga che ti manca!
    )
  );
}
add_action( 'init', 'registra_miei_menu' );

// per la favicon
function aggiungi_favicon_svg_personalizzata() {
    // Ottiene l'URL della cartella del tema attivo
    $favicon_url = get_stylesheet_directory_uri() . '/img/solarisfavicon.svg';
    
    echo '<link rel="icon" href="' . $favicon_url . '" type="image/svg+xml">';
}
add_action('wp_head', 'aggiungi_favicon_svg_personalizzata');
add_action('admin_head', 'aggiungi_favicon_svg_personalizzata');

?>