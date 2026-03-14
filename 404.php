<?php get_header(); ?>

<main>
    <section class="hero">
        <div class="sfondohero gradienthero">
        </div>
        <div class="sfondohero">
            <?php 
                $image_slug = '404-2'; 
                $args = array(
                    'post_type'      => 'attachment',
                    'name'           => $image_slug,
                    'posts_per_page' => 1,
                    'post_status'    => 'inherit',
                );
                $_img_query = get_posts($args);
                $attachment_id = $_img_query ? $_img_query[0]->ID : null;

                if ( $attachment_id ) :
                    // 2. Recuperiamo le URL delle diverse dimensioni caricate su WP
                    // Assicurati che 'hero_full_hd' sia registrato nel tuo functions.php
                    $desktop = wp_get_attachment_image_src($attachment_id, 'hero_full_hd')[0]; 
                    $tablet  = wp_get_attachment_image_src($attachment_id, 'large')[0];        
                    $mobile  = wp_get_attachment_image_src($attachment_id, 'medium_large')[0]; 
                    $alt     = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
                    ?>

                    <picture>
                        <source media="(min-width: 1025px)" srcset="<?php echo esc_url($desktop); ?>">
                        <source media="(min-width: 769px)" srcset="<?php echo esc_url($tablet); ?>">
                        <img 
                            src="<?php echo esc_url($mobile); ?>" 
                            alt="<?php echo esc_attr($alt); ?>" 
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
                    <h1>Pagina non trovata,<br>Ti sei perso su Solaris?</h1>
                    <div class="sottotitolohero">
                        <h3>torna alla homepage o scendi per vedere i nostri film</h3>
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
    <?php 
        get_template_part('/contents/carouselfilm', null, [
            'titolo'     => 'Prossimamente',
            'tassonomia' => 'status-film',
            'termine'    => 'in-arrivo',
            'posts_per_page' => 10,
        ]);
    ?>
    <?php 
        get_template_part('/contents/carouselfilm', null, [
            'titolo'     => 'Ultime uscite',
            'tassonomia' => 'status-film',
            'termine'    => 'al-cinema',
            'posts_per_page' => 10,
        ]);
    ?>
    <?php 
        get_template_part('/contents/carouselfilm', null, [
            'titolo'     => 'Tutti',
            'tassonomia' => '',
            'termine'    => '',
            'posts_per_page' => 10,
        ]);
    ?>
</main>

<?php get_footer(); ?>