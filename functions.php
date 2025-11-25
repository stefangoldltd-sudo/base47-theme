<?php

/* ---------------------------------------------
   Base47 Theme – Minimal + Useful
   Optimized for raw HTML templates and Mivon HTML Editor
   Version 2.0.1 - Canvas Mode (Like Elementor Canvas)
--------------------------------------------- */

// Disable Gutenberg everywhere (we are raw HTML people)
add_filter('use_block_editor_for_post', '__return_false', 10);

// Disable WP emojis (performance)
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// Disable WP's default frontend styles that break designs
add_action('wp_enqueue_scripts', function () {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('global-styles');
    
    // FIX: Remove classic-theme-styles that breaks mobile layout
    wp_dequeue_style('classic-theme-styles');
    wp_deregister_style('classic-theme-styles');
}, 100);


/* ---------------------------------------------
   Load our minimal CSS resets + helpers + main style
--------------------------------------------- */

add_action('wp_enqueue_scripts', function () {

    // MAIN THEME STYLESHEET – keep it ultra-minimal
    wp_enqueue_style(
        'base47-style',
        get_stylesheet_uri(),
        [],
        '2.0.1'
    );
});


/* ---------------------------------------------
   Theme support
--------------------------------------------- */

add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails' );


/* ---------------------------------------------
   Add "Canvas Mode" meta box to pages
   Let users manually enable canvas mode per page
--------------------------------------------- */

add_action('add_meta_boxes', function() {
    add_meta_box(
        'base47_canvas_mode',
        'Base47 Canvas Mode',
        'base47_canvas_mode_callback',
        'page',
        'side',
        'high'
    );
});

function base47_canvas_mode_callback($post) {
    wp_nonce_field('base47_canvas_mode_nonce', 'base47_canvas_mode_nonce');
    $value = get_post_meta($post->ID, '_base47_canvas_mode', true);
    ?>
    <label>
        <input type="checkbox" name="base47_canvas_mode" value="1" <?php checked($value, '1'); ?>>
        Enable Canvas Mode (No WordPress wrappers)
    </label>
    <p style="font-size: 11px; color: #666;">
        Like Elementor Canvas - outputs pure HTML without WordPress theme wrappers.
        Perfect for Mivon HTML Editor templates.
    </p>
    <?php
}

add_action('save_post', function($post_id) {
    if (!isset($_POST['base47_canvas_mode_nonce']) || 
        !wp_verify_nonce($_POST['base47_canvas_mode_nonce'], 'base47_canvas_mode_nonce')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (isset($_POST['base47_canvas_mode'])) {
        update_post_meta($post_id, '_base47_canvas_mode', '1');
    } else {
        delete_post_meta($post_id, '_base47_canvas_mode');
    }
});


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


/* ---------------------------------------------
   FIX: Remove WordPress body classes that trigger mobile CSS
--------------------------------------------- */

add_filter('body_class', function($classes) {
    // Remove WordPress-specific classes that might trigger unwanted CSS
    $remove_classes = array(
        'wp-embed-responsive',
        'wp-custom-logo',
        'wp-block-theme'
    );
    
    return array_diff($classes, $remove_classes);
});


/* ---------------------------------------------
   CANVAS MODE: Auto-detect Mivon pages and use canvas template
   Like Elementor Canvas - NO WordPress wrappers
--------------------------------------------- */

add_filter('template_include', function($template) {
    
    // Check if this is a page
    if (is_page() || is_singular()) {
        global $post;
        
        // Check if canvas mode is manually enabled
        $canvas_enabled = get_post_meta($post->ID, '_base47_canvas_mode', true);
        
        // OR auto-detect Mivon content
        $has_mivon_content = $post && (
            strpos($post->post_content, 'mivon-') !== false ||
            strpos($post->post_content, 'class="header') !== false ||
            strpos($post->post_content, 'data-scroll-container') !== false
        );
        
        // Use canvas template if enabled or Mivon detected
        if ($canvas_enabled || $has_mivon_content) {
            $canvas_template = get_template_directory() . '/template-canvas.php';
            if (file_exists($canvas_template)) {
                return $canvas_template;
            }
        }
    }
    
    return $template;
}, 99);


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
