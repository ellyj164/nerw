<?php
/**
 * Template Name: Modern Booking Calendar
 * Description: Calendly-style professional booking calendar with 3-panel layout
 *
 * @package French_Practice_Hub
 */

get_header();
?>

<main class="modern-booking-page">
    <div class="modern-booking-container">
        <!-- Left Panel: Instructor Info -->
        <div class="booking-instructor-panel">
            <div class="instructor-avatar">
                <?php
                $instructor_avatar = get_theme_mod('fph_instructor_avatar', '');
                if ( ! empty( $instructor_avatar ) ) {
                    echo '<img src="' . esc_url( $instructor_avatar ) . '" alt="' . esc_attr__('Instructor', 'french-practice-hub') . '">';
                } else {
                    // SVG placeholder
                    echo '<svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="60" cy="60" r="60" fill="#E8F0FE"/>
                        <circle cx="60" cy="45" r="20" fill="#0056D2"/>
                        <path d="M30 95C30 78 43 65 60 65C77 65 90 78 90 95" fill="#0056D2"/>
                    </svg>';
                }
                ?>
            </div>
            
            <h3 class="instructor-name"><?php echo esc_html(get_theme_mod('fph_instructor_name', 'Fidele FLE')); ?></h3>
            
            <div class="session-info">
                <h4><?php esc_html_e('Real-time French Class / Meeting', 'french-practice-hub'); ?></h4>
                <div class="session-detail">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span id="session-duration-display" data-duration="1 Class session = 1h30 max (Kids) / 2h max (Adults)"><?php esc_html_e('1 Class session = 1h30 max (Kids) / 2h max (Adults)', 'french-practice-hub'); ?></span>
                </div>
                <div class="session-detail">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                    </svg>
                    <span><?php esc_html_e('Video Conference', 'french-practice-hub'); ?></span>
                </div>
                <p class="session-description">
                    <?php echo esc_html(get_theme_mod('fph_session_description', __('Web conferencing details provided upon confirmation. Join us for an interactive French learning experience tailored to your level.', 'french-practice-hub'))); ?>
                </p>
                
                <!-- JOIN Button -->
                <div class="session-join-wrapper">
                    <a href="https://meet34.webex.com/meet/frenchpracticehub" class="btn-join-session" target="_blank" rel="noopener noreferrer">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M23 7l-7 5 7 5V7z"></path>
                            <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                        </svg>
                        <?php esc_html_e('JOIN SESSION', 'french-practice-hub'); ?>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Center Panel: Calendar -->
        <div class="booking-calendar-panel">
            <h2 class="panel-title"><?php esc_html_e('Select a Date & Time', 'french-practice-hub'); ?></h2>
            
            <!-- Session Type Selector -->
            <div class="session-type-selector">
                <div class="session-type-label">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span><?php esc_html_e('Session Type:', 'french-practice-hub'); ?></span>
                </div>
                <div class="session-type-options">
                    <button type="button" class="session-type-btn active" data-session-type="adults" data-duration="2h">
                        <span class="type-icon">👨‍🎓</span>
                        <span class="type-label"><?php esc_html_e('Adults', 'french-practice-hub'); ?></span>
                        <span class="type-duration"><?php esc_html_e('2 hours', 'french-practice-hub'); ?></span>
                    </button>
                    <button type="button" class="session-type-btn" data-session-type="kids" data-duration="1h30min">
                        <span class="type-icon">👦</span>
                        <span class="type-label"><?php esc_html_e('Kids', 'french-practice-hub'); ?></span>
                        <span class="type-duration"><?php esc_html_e('1h 30min', 'french-practice-hub'); ?></span>
                    </button>
                </div>
            </div>
            
            <!-- Month Navigation -->
            <div class="month-navigation">
                <button class="month-nav-btn" id="prev-month" aria-label="<?php esc_attr_e('Previous month', 'french-practice-hub'); ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                </button>
                <h3 class="current-month" id="current-month"></h3>
                <button class="month-nav-btn" id="next-month" aria-label="<?php esc_attr_e('Next month', 'french-practice-hub'); ?>">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </button>
            </div>
            
            <!-- Calendar Grid -->
            <div class="calendar-grid">
                <div class="calendar-weekdays">
                    <div class="weekday"><?php esc_html_e('Mon', 'french-practice-hub'); ?></div>
                    <div class="weekday"><?php esc_html_e('Tue', 'french-practice-hub'); ?></div>
                    <div class="weekday"><?php esc_html_e('Wed', 'french-practice-hub'); ?></div>
                    <div class="weekday"><?php esc_html_e('Thu', 'french-practice-hub'); ?></div>
                    <div class="weekday"><?php esc_html_e('Fri', 'french-practice-hub'); ?></div>
                    <div class="weekday"><?php esc_html_e('Sat', 'french-practice-hub'); ?></div>
                    <div class="weekday"><?php esc_html_e('Sun', 'french-practice-hub'); ?></div>
                </div>
                <div class="calendar-dates" id="calendar-dates">
                    <!-- Dates will be populated by JavaScript -->
                </div>
            </div>
            
            <!-- Timezone Selector -->
            <div class="timezone-selector">
                <label for="timezone-select">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M2 12h20"></path>
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                    </svg>
                    <span><?php esc_html_e('Timezone:', 'french-practice-hub'); ?></span>
                </label>
                <select id="timezone-select">
                    <option value="Africa/Kigali" selected><?php esc_html_e('Central Africa Time (CAT)', 'french-practice-hub'); ?></option>
                    <option value="America/New_York"><?php esc_html_e('Eastern Time (ET)', 'french-practice-hub'); ?></option>
                    <option value="America/Chicago"><?php esc_html_e('Central Time (CT)', 'french-practice-hub'); ?></option>
                    <option value="America/Los_Angeles"><?php esc_html_e('Pacific Time (PT)', 'french-practice-hub'); ?></option>
                    <option value="Europe/London"><?php esc_html_e('British Time (GMT)', 'french-practice-hub'); ?></option>
                    <option value="Europe/Paris"><?php esc_html_e('Central European Time (CET)', 'french-practice-hub'); ?></option>
                    <option value="Asia/Dubai"><?php esc_html_e('Gulf Standard Time (GST)', 'french-practice-hub'); ?></option>
                </select>
            </div>
        </div>
        
        <!-- Right Panel: Time Slots -->
        <div class="booking-timeslots-panel">
            <div class="selected-date-display" id="selected-date-display">
                <span><?php esc_html_e('Select a date', 'french-practice-hub'); ?></span>
            </div>
            
            <div class="timeslots-container" id="timeslots-container">
                <div class="no-date-selected">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    <p><?php esc_html_e('Please select a date from the calendar', 'french-practice-hub'); ?></p>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Booking Confirmation Modal -->
<div id="booking-confirmation-modal" class="booking-modal" style="display: none;">
    <div class="booking-modal-overlay"></div>
    <div class="booking-modal-content modern-modal">
        <button class="booking-modal-close" aria-label="<?php esc_attr_e('Close', 'french-practice-hub'); ?>">&times;</button>
        
        <h2><?php esc_html_e('Confirm Your Booking', 'french-practice-hub'); ?></h2>
        
        <div class="booking-summary">
            <div class="summary-item">
                <strong><?php esc_html_e('Date:', 'french-practice-hub'); ?></strong>
                <span id="summary-date"></span>
            </div>
            <div class="summary-item">
                <strong><?php esc_html_e('Time:', 'french-practice-hub'); ?></strong>
                <span id="summary-time"></span>
            </div>
            <div class="summary-item">
                <strong><?php esc_html_e('Session Type:', 'french-practice-hub'); ?></strong>
                <span id="summary-type"></span>
            </div>
        </div>
        
        <form id="modern-booking-form" class="modern-booking-form">
            <?php wp_nonce_field('fph_modern_booking_nonce', 'booking_nonce'); ?>
            
            <input type="hidden" id="booking-date-hidden" name="booking_date">
            <input type="hidden" id="booking-time-hidden" name="booking_time">
            <input type="hidden" id="booking-type-hidden" name="booking_type">
            <input type="hidden" id="booking-timezone-hidden" name="booking_timezone">
            
            <div class="form-row">
                <div class="form-group">
                    <label for="booking-name"><?php esc_html_e('Full Name', 'french-practice-hub'); ?> *</label>
                    <input type="text" id="booking-name" name="booking_name" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="booking-email"><?php esc_html_e('Email Address', 'french-practice-hub'); ?> *</label>
                    <input type="email" id="booking-email" name="booking_email" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="booking-phone"><?php esc_html_e('Phone Number', 'french-practice-hub'); ?> *</label>
                    <input type="tel" id="booking-phone" name="booking_phone" required>
                </div>
            </div>
            
            <div class="form-row" id="student-age-row" style="display: none;">
                <div class="form-group">
                    <label for="booking-student-age"><?php esc_html_e('Student Age', 'french-practice-hub'); ?></label>
                    <input type="number" id="booking-student-age" name="booking_student_age" min="1" max="100">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="booking-notes"><?php esc_html_e('Additional Notes', 'french-practice-hub'); ?></label>
                    <textarea id="booking-notes" name="booking_notes" rows="4" placeholder="<?php esc_attr_e('Any specific requirements or questions?', 'french-practice-hub'); ?>"></textarea>
                </div>
            </div>
            
            <div id="booking-response-message" class="booking-message" style="display: none;"></div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" id="cancel-modern-booking"><?php esc_html_e('Cancel', 'french-practice-hub'); ?></button>
                <button type="submit" class="btn btn-primary"><?php esc_html_e('Confirm Booking', 'french-practice-hub'); ?></button>
            </div>
        </form>
    </div>
</div>

<?php
get_footer();
