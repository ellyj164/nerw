<?php
/**
 * Single Post Template
 * 
 * This is the fallback template for all single posts and custom post types
 * that don't have a specific template (e.g., single-{post-type}.php).
 * 
 * It properly uses the_content() to allow plugins (like Tutor LMS, WooCommerce, etc.)
 * to filter and modify content through WordPress hooks without being blocked.
 *
 * @package French_Practice_Hub
 */

get_header();
?>

<main>
    <?php
    while ( have_posts() ) :
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'content-section' ); ?>>
            <div class="container">
                <h1><?php the_title(); ?></h1>
                
                <div class="entry-meta">
                    <span class="posted-on">
                        <?php echo esc_html( get_the_date() ); ?>
                    </span>
                    <span class="byline">
                        <?php
                        printf(
                            esc_html__( 'by %s', 'french-practice-hub' ),
                            '<span class="author">' . esc_html( get_the_author() ) . '</span>'
                        );
                        ?>
                    </span>
                </div>
                
                <?php
                if ( has_post_thumbnail() ) :
                    the_post_thumbnail( 'large' );
                endif;
                ?>
                
                <?php 
                /**
                 * Output the main content
                 * 
                 * the_content() applies all content filters allowing plugins
                 * to modify or replace content. This is essential for:
                 * - Custom post type plugins (Tutor LMS, WooCommerce, etc.)
                 * - Page builders (Elementor, etc.)
                 * - Content formatting plugins
                 */
                the_content(); 
                ?>
                
                <?php
                wp_link_pages( array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'french-practice-hub' ),
                    'after'  => '</div>',
                ) );
                ?>
            </div>
        </article>
        <?php
    endwhile;
    ?>
</main>

<?php
get_footer();
