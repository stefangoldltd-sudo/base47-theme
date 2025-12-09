<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Base47 Theme – extra hooks
 * This file is intentionally not empty so uploads work.
 */

/**
 * Add a mode class to <body>:
 *  - base47-canvas  → when using Canvas template
 *  - base47-classic → everywhere else
 */
function base47_theme_body_class( $classes ) {
    if ( is_page_template( 'template-canvas.php' ) ) {
        $classes[] = 'base47-canvas';
    } else {
        $classes[] = 'base47-classic';
    }

    return $classes;
}
add_filter( 'body_class', 'base47_theme_body_class' );