<?php
/**
 * Single Tutor Course Template
 * Template for Tutor LMS single course pages (tutor_course post type)
 * 
 * This template provides full compatibility with Tutor LMS by using
 * Tutor's native hooks and template system while maintaining the
 * theme's design and layout structure.
 * 
 * @package French_Practice_Hub
 */

get_header();
?>

<main class="tutor-single-course-main">
    <?php
    while ( have_posts() ) :
        the_post();
        
        /**
         * Hook: tutor_course/single/before/wrap
         * Fires before the course content wrapper
         * 
         * @since 1.0.0
         */
        do_action( 'tutor_course/single/before/wrap' );
        ?>
        
        <div class="tutor-course-wrapper">
            <?php
            /**
             * Hook: tutor_course/single/before/content
             * Fires before the main course content
             * 
             * @since 1.0.0
             */
            do_action( 'tutor_course/single/before/content' );
            
            /**
             * Main course content
             * This hook allows Tutor LMS to render the complete course layout
             * including course header, curriculum, instructor info, reviews, etc.
             * 
             * Hook: tutor_course/single/content
             * 
             * @since 1.0.0
             */
            do_action( 'tutor_course/single/content' );
            
            /**
             * Hook: tutor_course/single/after/content
             * Fires after the main course content
             * 
             * @since 1.0.0
             */
            do_action( 'tutor_course/single/after/content' );
            ?>
        </div>
        
        <?php
        /**
         * Hook: tutor_course/single/after/wrap
         * Fires after the course content wrapper
         * 
         * @since 1.0.0
         */
        do_action( 'tutor_course/single/after/wrap' );
        
    endwhile;
    ?>
</main>

<?php
get_footer();
