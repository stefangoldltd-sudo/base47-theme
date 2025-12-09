<?php
/**
 * Base47 Theme – Dual Mode (Canvas + Classic)
 * Optimized for raw HTML templates and Base47 HTML Editor
 * 
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Helper: get current theme version for cache-busting.
 */
function base47_theme_get_version() {
    $theme = wp_get_theme();
    // If this is a child theme, get parent version
    if ( $theme->parent() ) {
        $theme = $theme->parent();
    }
    $version = $theme->get( 'Version' );
    return $version ? $version : '2.1.0';
}

/* ---------------------------------------------
 * Theme setup
 * ------------------------------------------ */

function base47_theme_setup() {

    // Let WordPress handle <title> tag
    add_theme_support( 'title-tag' );

    // Thumbnails if needed
    add_theme_support( 'post-thumbnails' );

    // HTML5 markup support
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    // Primary navigation (for classic mode header)
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'base47-theme' ),
    ) );
}
add_action( 'after_setup_theme', 'base47_theme_setup' );

/* ---------------------------------------------
 * Disable Gutenberg (Block Editor)
 * We keep your original behavior: disable for everything.
 * If you ever want it ONLY for pages, you can change the condition.
 * ------------------------------------------ */

function base47_disable_block_editor_for_all( $use_block_editor, $post_type ) {
    // Return false for all post types (pages, posts, custom)
    return false;
}
add_filter( 'use_block_editor_for_post_type', 'base47_disable_block_editor_for_all', 10, 2 );

/* ---------------------------------------------
 * Disable WP emojis (extended version of your original)
 * ------------------------------------------ */

function base47_disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'base47_disable_emojis' );

/* ---------------------------------------------
 * Remove WP block/global styles that break designs
 * (keeps your original behavior)
 * ------------------------------------------ */

function base47_theme_dequeue_wp_styles() {
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'global-styles' );
    // FIX: Remove classic-theme-styles that breaks mobile layout
    wp_dequeue_style( 'classic-theme-styles' );
    wp_deregister_style( 'classic-theme-styles' );
}
add_action( 'wp_enqueue_scripts', 'base47_theme_dequeue_wp_styles', 100 );

/* ---------------------------------------------
 * Enqueue our CSS + JS
 * - style.css (shell)
 * - optional normalize.css
 * - Soft-UI CSS for classic pages
 * - theme.js
 * ------------------------------------------ */

function base47_theme_scripts() {
    $ver = base47_theme_get_version();
    $dir = get_template_directory_uri();

    // Optional normalize/reset if you add it later
    $normalize_path = get_template_directory() . '/assets/css/normalize.css';
    if ( file_exists( $normalize_path ) ) {
        wp_enqueue_style(
            'base47-normalize',
            $dir . '/assets/css/normalize.css',
            array(),
            $ver
        );
        $deps = array( 'base47-normalize' );
    } else {
        $deps = array();
    }

    // MAIN THEME STYLESHEET – your original "base47-style"
    wp_enqueue_style(
        'base47-style',
        get_stylesheet_uri(),
        $deps,
        $ver
    );

    // Soft-UI for classic mode ONLY (not for Canvas template)
    $softui_path = get_template_directory() . '/assets/css/base47-softui.css';
    if ( ! is_page_template( 'template-canvas.php' ) && file_exists( $softui_path ) ) {
        wp_enqueue_style(
            'base47-softui',
            $dir . '/assets/css/base47-softui.css',
            array( 'base47-style' ),
            $ver
        );
    }

    // Optional theme JS – safe even if file is empty
    $js_path = get_template_directory() . '/assets/js/theme.js';
    if ( file_exists( $js_path ) ) {
        wp_enqueue_script(
            'base47-theme',
            $dir . '/assets/js/theme.js',
            array( 'jquery' ),
            $ver,
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'base47_theme_scripts' );

/* ---------------------------------------------
 * RAW HTML: Remove automatic <p> / <br> wrappers
 * (your original behavior – very important for Mivon/HTML editor)
 * ------------------------------------------ */

remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );

/* ---------------------------------------------
 * Disable srcset to avoid layout break (your original)
 * ------------------------------------------ */

add_filter( 'wp_calculate_image_srcset', '__return_false' );

/* ---------------------------------------------
 * FIX: Remove specific body classes that can trigger unwanted CSS
 * (keeps your original behavior)
 * ------------------------------------------ */

add_filter( 'body_class', function( $classes ) {
    $remove_classes = array(
        'wp-embed-responsive',
        'wp-custom-logo',
        'wp-block-theme',
    );

    return array_diff( $classes, $remove_classes );
} );

/* ---------------------------------------------
 * Canvas Mode meta box (your original feature)
 * Lets you enable Canvas Mode on a per-page basis.
 * ------------------------------------------ */

add_action( 'add_meta_boxes', function() {
    add_meta_box(
        'base47_canvas_mode',
        'Base47 Canvas Mode',
        'base47_canvas_mode_callback',
        'page',
        'side',
        'high'
    );
} );

function base47_canvas_mode_callback( $post ) {
    wp_nonce_field( 'base47_canvas_mode_nonce', 'base47_canvas_mode_nonce' );
    $value = get_post_meta( $post->ID, '_base47_canvas_mode', true );
    ?>
    <label>
        <input type="checkbox" name="base47_canvas_mode" value="1" <?php checked( $value, '1' ); ?>>
        <?php esc_html_e( 'Enable Canvas Mode (No WordPress wrappers)', 'base47-theme' ); ?>
    </label>
    <p style="font-size: 11px; color: #666;">
        <?php esc_html_e( 'Like Elementor Canvas – outputs pure HTML without WordPress header/footer. Perfect for Base47 HTML Editor / Mivon templates.', 'base47-theme' ); ?>
    </p>
    <?php
}

add_action( 'save_post', function( $post_id ) {
    if ( ! isset( $_POST['base47_canvas_mode_nonce'] ) ||
         ! wp_verify_nonce( wp_unslash( $_POST['base47_canvas_mode_nonce'] ), 'base47_canvas_mode_nonce' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( isset( $_POST['base47_canvas_mode'] ) ) {
        update_post_meta( $post_id, '_base47_canvas_mode', '1' );
    } else {
        delete_post_meta( $post_id, '_base47_canvas_mode' );
    }
} );

/* ---------------------------------------------
 * CANVAS MODE: Auto-detect Mivon/Base47 pages and use canvas template
 * (keeps your original smart detection)
 * ------------------------------------------ */

add_filter( 'template_include', function( $template ) {

    // If a page is explicitly set to "Base47 Canvas" template, respect that.
    if ( is_page_template( 'template-canvas.php' ) ) {
        return $template;
    }

    if ( is_page() || is_singular() ) {
        global $post;

        if ( ! $post ) {
            return $template;
        }

        // Manual Canvas Mode via meta box
        $canvas_enabled = get_post_meta( $post->ID, '_base47_canvas_mode', true );

        // Auto-detect Mivon / Base47 HTML templates in content
        $content = $post->post_content;
        $has_mivon_content = (
            strpos( $content, 'mivon-' ) !== false ||
            strpos( $content, 'class="header' ) !== false ||
            strpos( $content, 'data-scroll-container' ) !== false
        );

        if ( $canvas_enabled || $has_mivon_content ) {
            $canvas_template = get_template_directory() . '/template-canvas.php';
            if ( file_exists( $canvas_template ) ) {
                return $canvas_template;
            }
        }
    }

    return $template;
}, 99 );

/* ---------------------------------------------
 * GitHub Theme Updater (kept intact, just wrapped neatly)
 * Checks latest release on GitHub and tells WP if an update exists.
 * ------------------------------------------ */

function base47_github_updater( $transient ) {

    if ( empty( $transient->checked ) ) {
        return $transient;
    }

    // Theme folder MUST match your theme directory name
    $theme_slug = 'base47-theme';

    $theme           = wp_get_theme( $theme_slug );
    $current_version = $theme->get( 'Version' );

    // GitHub API for latest release
    $response = wp_remote_get( 'https://api.github.com/repos/stefangoldltd-sudo/base47-theme/releases/latest' );

    if ( is_wp_error( $response ) ) {
        return $transient;
    }

    $body = wp_remote_retrieve_body( $response );
    $data = json_decode( $body );

    if ( empty( $data ) || empty( $data->tag_name ) || empty( $data->zipball_url ) ) {
        return $transient;
    }

    $latest_version = ltrim( $data->tag_name, 'v' );

    if ( version_compare( $current_version, $latest_version, '<' ) ) {
        // Must match your theme slug
        $transient->response[ $theme_slug ] = array(
            'theme'       => $theme_slug,
            'new_version' => $latest_version,
            'package'     => $data->zipball_url,
            'url'         => 'https://github.com/stefangoldltd-sudo/base47-theme',
        );
    }

    return $transient;
}
add_filter( 'pre_set_site_transient_update_themes', 'base47_github_updater' );

/* ---------------------------------------------
 * (Optional) Load extra hooks file if you create inc/hooks.php later.
 * This does nothing if the file does not exist.
 * ------------------------------------------ */

$hooks_file = get_template_directory() . '/inc/hooks.php';
if ( file_exists( $hooks_file ) ) {
    require_once $hooks_file;
}