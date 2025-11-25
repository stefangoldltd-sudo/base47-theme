<?php get_header(); ?>

<?php
    // NO WRAPPERS - Mivon templates must be completely raw
    if ( have_posts() ) :
        while ( have_posts() ) : the_post();
            the_content();
        endwhile;
    endif;
?>

<?php get_footer(); ?>