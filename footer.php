    <div id="fullImageOverlay" onclick="this.style.display='none'">
        <span class="close-btn">&times;</span>
        <img id="fullImage" src="">
    </div>
    <section>
        <section class="element">
            <div class="linehero"></div>
        </section>
    </section>
    <footer>
        <section class="element">
            <div>
                <div>
                    <b>Solaris</b>
                    <?php 
                    $info_id = get_info_globali_id();
                    if ($info_id) {
                        ?><p>P.iva: <?php echo get_field('partita_iva', $info_id);?></p>
                        <p><?php echo get_field('contatti', $info_id);?></p><?php
                    }
                    ?>
                </div>
                <nav>
                    <?php /* Main Navigation */
                    wp_nav_menu( array(
                        'theme_location' => 'footer',
                        'depth'          => 2,
                        'container'      => false,
                        'menu_class'     => 'footer_menu' //qui da aggiungere il tag dell' header
                    ) );
                    ?>
                </nav>
            </div>
            <div class="linevertical"></div>
            <div>
                <nav>
                    <?php /* Main Navigation */
                    wp_nav_menu( array(
                        'theme_location' => 'header',
                        'depth'          => 2,
                        'container'      => false,
                        'menu_class'     => 'header_menu' //qui da aggiungere il tag dell' header
                    ) );
                    ?>
                </nav>
            </div>
        </section>
    </footer>
    <?php wp_footer(); ?>
</body>

</html>