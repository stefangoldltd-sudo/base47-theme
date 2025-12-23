<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- iOS optimizations -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="format-detection" content="telephone=no">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<a class="skip-link" href="#base47-main">
    <?php esc_html_e( 'Skip to content', 'base47-theme' ); ?>
</a>

<div id="page" class="base47-site">

    <?php 
    // Hide header in canvas mode OR if page has Base47/Mivon shortcodes
    $hide_header = is_page_template( 'template-canvas.php' );
    
    if ( ! $hide_header && is_singular() ) {
        global $post;
        if ( $post ) {
            $content = $post->post_content;
            $has_base47_shortcode = (
                strpos( $content, '[mivon-' ) !== false ||
                strpos( $content, '[base47-' ) !== false
            );
            if ( $has_base47_shortcode ) {
                $hide_header = true;
            }
        }
    }
    
    if ( ! $hide_header ) : 
    ?>

        <header class="b47-header">
            <div class="b47-container b47-header-inner">

                <div class="b47-logo">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <?php bloginfo( 'name' ); ?>
                    </a>
                </div>

                <nav class="b47-nav" aria-label="<?php esc_attr_e( 'Main navigation', 'base47-theme' ); ?>">
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'fallback_cb'    => '__return_false',
                        'menu_class'     => 'b47-nav-list',
                    ) );
                    ?>
                </nav>

            </div>
        </header>

    <?php endif; ?>

    <main id="base47-main" class="b47-main">