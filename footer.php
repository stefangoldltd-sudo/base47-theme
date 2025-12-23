<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

        </main><!-- #base47-main -->

        <?php 
        // Hide footer in canvas mode OR if page has Base47/Mivon shortcodes
        $hide_footer = is_page_template( 'template-canvas.php' );
        
        if ( ! $hide_footer && is_singular() ) {
            global $post;
            if ( $post ) {
                $content = $post->post_content;
                $has_base47_shortcode = (
                    strpos( $content, '[mivon-' ) !== false ||
                    strpos( $content, '[base47-' ) !== false
                );
                if ( $has_base47_shortcode ) {
                    $hide_footer = true;
                }
            }
        }
        
        if ( ! $hide_footer ) : 
        ?>

            <footer class="b47-footer">
                <div class="b47-container b47-footer-inner">
                    <p class="b47-footer-copy">
                        &copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?>
                        <?php bloginfo( 'name' ); ?> —
                        <?php esc_html_e( 'All rights reserved.', 'base47-theme' ); ?>
                    </p>
                </div>
            </footer>

        <?php endif; ?>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>