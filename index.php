<?php
/**
 * Base47 – Ultra Minimal Shell Theme
 */

if ( ! defined( 'ABSPATH' ) ) exit;

get_header();
?>

<main id="base47-main">
    <?php
        // Output page/post content rendered by plugins (Mivon HTML Editor)
        while ( have_posts() ) : the_post();
            the_content();
        endwhile;
    ?>
</main>

<?php get_footer(); ?>