<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<div class="b47-page">
    <div class="b47-container b47-page-inner">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'b47-post-item' ); ?>>
                    <h2 class="b47-post-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <div class="b47-post-excerpt">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
            <?php endwhile; ?>
        <?php else : ?>
            <p><?php esc_html_e( 'No content found.', 'base47-theme' ); ?></p>
        <?php endif; ?>
    </div>
</div>

<?php
get_footer();