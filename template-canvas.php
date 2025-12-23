<?php
/**
 * Template Name: Base47 Canvas (Raw HTML Mode)
 * 
 * PURE HTML MODE - Zero WordPress interference
 * Extracts <head> and <body> content separately for perfect rendering
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Disable admin bar
show_admin_bar( false );

// Get the post content and process shortcodes
while ( have_posts() ) : the_post();
    $content = do_shortcode( get_the_content( null, false, $post ) );
endwhile;

// Extract <head> content
$head_content = '';
if ( preg_match( '#<head\b[^>]*>(.*?)</head>#is', $content, $head_match ) ) {
    $head_content = $head_match[1];
}

// Extract <body> content
$body_content = $content;
if ( preg_match( '#<body\b[^>]*>(.*?)</body>#is', $content, $body_match ) ) {
    $body_content = $body_match[1];
}

// Output PURE HTML - NO WordPress hooks
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo $head_content; ?>
</head>
<body>
<?php echo $body_content; ?>
</body>
</html>
<?php
exit; // Stop WordPress from adding anything
?>
