<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo("charset"); ?>">
    <meta http-euiv="X-Ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php bloginfo( 'description' );?>" >
    <?php wp_head(); ?> <!--per aggiungere il css-->
</head>
<body <?php body_class(); ?>>
     <header>
        <section>
            <?php echo get_template_part('/contents/logosolaris'); ?>
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
            <div class="hamburger">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="36" viewBox="0 0 35 36" fill="none">
                    <path
                        d="M32.8125 15.8125H2.1875C0.979385 15.8125 0 16.7919 0 18C0 19.2081 0.979385 20.1875 2.1875 20.1875H32.8125C34.0206 20.1875 35 19.2081 35 18C35 16.7919 34.0206 15.8125 32.8125 15.8125Z"
                        fill="white" />
                    <path
                        d="M2.1875 9.97925H32.8125C34.0206 9.97925 35 8.99986 35 7.79175C35 6.58363 34.0206 5.60425 32.8125 5.60425H2.1875C0.979385 5.60425 0 6.58363 0 7.79175C0 8.99986 0.979385 9.97925 2.1875 9.97925Z"
                        fill="white" />
                    <path
                        d="M32.8125 26.0208H2.1875C0.979385 26.0208 0 27.0001 0 28.2083C0 29.4164 0.979385 30.3958 2.1875 30.3958H32.8125C34.0206 30.3958 35 29.4164 35 28.2083C35 27.0001 34.0206 26.0208 32.8125 26.0208Z"
                        fill="white" />
                </svg>
            </div>
        </section>
    </header>
    <section class="phonebannertot">
        <div class="phonebanner">
            <div class="contenitorclose">
                <div class="closebanner">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 35 35" fill="none">
                        <path
                            d="M20.593 17.5L34.3591 3.73533C35.2136 2.88081 35.2136 1.49535 34.3591 0.640895C33.5046 -0.213632 32.1191 -0.213632 31.2647 0.640895L17.5 14.407L3.73533 0.640895C2.88081 -0.213632 1.49535 -0.213632 0.640895 0.640895C-0.213563 1.49542 -0.213632 2.88087 0.640895 3.73533L14.407 17.5L0.640895 31.2647C-0.213632 32.1192 -0.213632 33.5046 0.640895 34.3591C1.49542 35.2136 2.88087 35.2136 3.73533 34.3591L17.5 20.593L31.2646 34.3591C32.1191 35.2136 33.5046 35.2136 34.359 34.3591C35.2136 33.5046 35.2136 32.1191 34.359 31.2647L20.593 17.5Z"
                            fill="black" />
                    </svg>
                </div>
            </div>
            <div class="testophonebanner">
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
        </div>
    </section>
