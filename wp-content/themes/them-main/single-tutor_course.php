<?php
/**
 * Single Tutor Course Template
 * Template for Tutor LMS single course pages
 * 
 * @package French_Practice_Hub
 * @since 1.1.0
 */

// Let Tutor LMS handle its own complete template rendering
if (function_exists('tutor_load_template')) {
    // Load Tutor LMS's native single-course template which contains the full course layout
    tutor_load_template('single-course');
} else {
    // Fallback if Tutor LMS is not active
    get_header();
    ?>
    <main class="tutor-single-course-main">
        <?php
        while ( have_posts() ) :
            the_post();
            the_content();
        endwhile;
        ?>
    </main>
    <?php
    get_footer();
}