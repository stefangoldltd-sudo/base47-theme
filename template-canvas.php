<?php
/**
 * Template Name: Base47 Canvas (No WordPress Wrappers)
 * Description: Pure HTML output - like Elementor Canvas mode
 * 
 * This template completely bypasses WordPress wrappers
 * Perfect for Mivon HTML Editor templates
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    
    <!-- Enhanced viewport for mobile - iOS Safari fix -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- iOS-specific optimizations -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="format-detection" content="telephone=no">

    <?php wp_head(); ?>
</head>
<body <?php body_class('base47-canvas'); ?>>

<?php
// PURE HTML OUTPUT - NO WRAPPERS AT ALL
while ( have_posts() ) : the_post();
    the_content();
endwhile;
?>

<?php wp_footer(); ?>
</body>
</html>
