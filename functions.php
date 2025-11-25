<?php

/* ---------------------------------------------
   Base47 Theme – Minimal + Useful
   Optimized for raw HTML templates and Mivon HTML Editor
--------------------------------------------- */

// Disable Gutenberg everywhere (we are raw HTML people)
add_filter('use_block_editor_for_post', '__return_false', 10);

// Disable WP emojis (performance)
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// Disable WP’s default frontend styles that break designs
add_action('wp_enqueue_scripts', function () {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('global-styles');
}, 100);


/* ---------------------------------------------
   Load our minimal CSS resets + helpers + main style
--------------------------------------------- */

add_action('wp_enqueue_scripts', function () {

    // OPTIONAL: if you want normalize, leave this.
    // If you want 100% identical to live editor, comment it out.
    /*
    wp_enqueue_style(
        'base47-normalize',
        get_template_directory_uri() . '/assets/css/normalize.css',
        [],
        '8.0'
    );
    */

    // DO NOT LOAD base47-core anymore – it’s the one that changes layout.
    // wp_enqueue_style(
    //     'base47-core',
    //     get_template_directory_uri() . '/assets/css/base47-core.css',
    //     [],
    //     '1.0'
    // );

    // MAIN THEME STYLESHEET – keep it ultra-minimal
    wp_enqueue_style(
        'base47-style',
        get_stylesheet_uri(),
        [],
        '1.5.3'
    );
});


/* ---------------------------------------------
   Theme support
--------------------------------------------- */

add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails' );


/* ---------------------------------------------
   Remove automatic <p> and <br> from content
   (raw HTML should stay RAW)
--------------------------------------------- */

remove_filter('the_content', 'wpautop');
remove_filter('the_excerpt', 'wpautop');


/* ---------------------------------------------
   Disable srcset to avoid layout break
--------------------------------------------- */

add_filter('wp_calculate_image_srcset', '__return_false');

// -------------------------------
// Base47 GitHub Theme Updater (Corrected)
// -------------------------------
function base47_github_updater( $transient ) {

    if ( empty( $transient->checked ) ) {
        return $transient;
    }

    // Theme folder MUST match your theme directory name
    $theme_slug = 'base47-theme';

    $theme = wp_get_theme( $theme_slug );
    $current_version = $theme->get( 'Version' );

    // GitHub API for latest release
    $response = wp_remote_get( 'https://api.github.com/repos/stefangoldltd-sudo/base47-theme/releases/latest' );
    
    if ( is_wp_error( $response ) ) {
        return $transient;
    }

    $data = json_decode( wp_remote_retrieve_body( $response ) );

    if ( isset( $data->tag_name ) ) {

        $latest_version = ltrim( $data->tag_name, 'v' );

        if ( version_compare( $current_version, $latest_version, '<' ) ) {

            // Must match your theme slug
            $transient->response[$theme_slug] = array(
                'theme'       => $theme_slug,
                'new_version' => $latest_version,
                'package'     => $data->zipball_url,
                'url'         => 'https://github.com/stefangoldltd-sudo/base47-theme',
            );
        }
    }

    return $transient;
}
add_filter( 'pre_set_site_transient_update_themes', 'base47_github_updater' );