<?php
/**
 * Template Name: Base47 Canvas (Raw HTML Mode)
 * 
 * PURE CANVAS MODE - ZERO WORDPRESS INTERFERENCE
 * - NO wp_head() - NO wp_footer()
 * - NO header.php - NO footer.php
 * - NO content filters
 * - PURE HTML OUTPUT
 * 
 * Assets are inline in the HTML templates, no enqueuing needed.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Disable admin bar
show_admin_bar( false );

// Remove ALL WordPress content filters
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_content', 'wptexturize' );
remove_filter( 'the_content', 'convert_chars' );

// Get the post
global $post;
if ( ! have_posts() ) {
    wp_die( 'No content found.' );
}
the_post();

// Get raw content
$content = $post->post_content;

// Set global flag for PURE canvas mode
$GLOBALS['base47_pure_canvas_mode'] = true;

// ONLY process shortcodes - nothing else
$content = do_shortcode( $content );

// Extract everything between <body> tags if present
if ( preg_match( '#<body\b[^>]*>(.*?)</body>#is', $content, $m ) ) {
    $content = $m[1];
}

// Output PURE HTML - NO WordPress hooks
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* FORCE zero spacing */
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            overflow-x: hidden;
        }
    </style>
</head>
<body>
<?php echo $content; ?>
</body>
</html>
<?php
exit;
?>
