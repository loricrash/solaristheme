<?php
// Recuperiamo i dati passati dal file principale
$query = $args['query'] ?? null;
$paged = $args['paged'] ?? 1;

if ( $query && $query->max_num_pages > 1 ) : ?>
    <div class="numeripagina">
        <?php 
        echo paginate_links(array(
            'total'        => $query->max_num_pages,
            'current'      => $paged,
            'format'       => '?paged=%#%',
            'add_args'     => $_GET, 
            'mid_size'     => 2, 
            'end_size'     => 1, 
            'prev_text'    => '«',
            'next_text'    => '»',
            'type'         => 'plain',
        )); 
        ?>
    </div>
<?php endif; ?>