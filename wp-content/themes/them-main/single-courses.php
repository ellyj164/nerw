<?php
/**
 * Single Course Template
 * Template for Tutor LMS single course pages (courses post type)
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
         * @hooked Tutor LMS - Course header components
         * @since Tutor LMS 1.0.0
         */
        do_action( 'tutor_course/single/before/wrap' );
        ?>
        
        <div class="tutor-course-wrapper">
            <?php
            /**
             * Hook: tutor_course/single/before/content
             * Fires before the main course content
             * 
             * @hooked Tutor LMS - Course navigation breadcrumbs
             * @since Tutor LMS 1.0.0
             */
            do_action( 'tutor_course/single/before/content' );
            
            /**
             * Main course content
             * This hook allows Tutor LMS to render the complete course layout
             * including course header, curriculum, instructor info, reviews, etc.
             * 
             * Hook: tutor_course/single/content
             * 
             * @hooked Tutor LMS - Complete course content
             * @since Tutor LMS 1.0.0
             */
            do_action( 'tutor_course/single/content' );
            
            /**
             * Hook: tutor_course/single/after/content
             * Fires after the main course content
             * 
             * @hooked Tutor LMS - Related courses, comments, etc.
             * @since Tutor LMS 1.0.0
             */
            do_action( 'tutor_course/single/after/content' );
            ?>
        </div>
        
        <?php
        /**
         * Hook: tutor_course/single/after/wrap
         * Fires after the course content wrapper
         * 
         * @hooked Tutor LMS - Footer course components
         * @since Tutor LMS 1.0.0
         */
        do_action( 'tutor_course/single/after/wrap' );
        
    endwhile;
    ?>
</main>

<?php
get_footer();
