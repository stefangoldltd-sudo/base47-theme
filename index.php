<?php get_header(); ?>

<main id="base47-main">
    <?php
        if ( have_posts() ) :
            while ( have_posts() ) : the_post();
                the_content();
            endwhile;
        endif;
    ?>
</main>

<?php get_footer(); ?>