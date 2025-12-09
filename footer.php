<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

        </main><!-- #base47-main -->

        <?php if ( ! is_page_template( 'template-canvas.php' ) ) : ?>

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