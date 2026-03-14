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
?>


<div>
    <div class="fotoregista">
        <?php 
            $sizeregista = $foto_regista['sizes']['locandinabig']; 
            if( $foto_regista ):
                ?>
                    <img 
                        src="<?php echo esc_url($sizeregista); ?>" 
                        alt="<?php echo esc_attr($foto_regista['alt']); ?>" 
                        fetchpriority="high"
                        loading="eager"
                        onclick="openFullImage(this.src)"
                    >
            <?php endif; ?>
    </div>
    <div>
        <h4><?php echo $nome_regista;?></h4>
        <?php 
            ?><p><?php echo nl2br($bio_regista);;?></p><?php
        ?>
    </div>
</div>