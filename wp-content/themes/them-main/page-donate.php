<?php
/**
 * Template Name: Donation Page
 * Description: Donation page with amount selection and payment integration
 *
 * @package French_Practice_Hub
 */

get_header();
?>

<main class="donate-page">
    <section class="donate-hero">
        <div class="container">
            <h1><?php esc_html_e('Make a Donation', 'french-practice-hub'); ?></h1>
            <p><?php esc_html_e('Support French Practice Hub and help us continue providing quality French learning experiences', 'french-practice-hub'); ?></p>
        </div>
    </section>

    <section class="donate-content-section">
        <div class="container">
            <div class="donate-container">
                <!-- Donation Amount Selection -->
                <div class="donation-panel">
                    <h2><?php esc_html_e('Choose Your Donation Amount', 'french-practice-hub'); ?></h2>
                    <p class="donate-description"><?php esc_html_e('Your generous donation helps us maintain our platform, create new content, and reach more French learners worldwide.', 'french-practice-hub'); ?></p>
                    
                    <form id="donation-form" class="donation-form">
                        <?php wp_nonce_field('fph_donation_nonce', 'donation_nonce'); ?>
                        
                        <!-- Preset Amount Buttons -->
                        <div class="amount-selection">
                            <h3><?php esc_html_e('Select Amount (USD)', 'french-practice-hub'); ?></h3>
                            <div class="amount-grid">
                                <button type="button" class="amount-btn" data-amount="1">
                                    <span class="amount-currency">$</span>
                                    <span class="amount-value">1</span>
                                </button>
                                <button type="button" class="amount-btn" data-amount="5">
                                    <span class="amount-currency">$</span>
                                    <span class="amount-value">5</span>
                                </button>
                                <button type="button" class="amount-btn" data-amount="10">
                                    <span class="amount-currency">$</span>
                                    <span class="amount-value">10</span>
                                </button>
                                <button type="button" class="amount-btn" data-amount="25">
                                    <span class="amount-currency">$</span>
                                    <span class="amount-value">25</span>
                                </button>
                                <button type="button" class="amount-btn" data-amount="50">
                                    <span class="amount-currency">$</span>
                                    <span class="amount-value">50</span>
                                </button>
                                <button type="button" class="amount-btn" data-amount="100">
                                    <span class="amount-currency">$</span>
                                    <span class="amount-value">100</span>
                                </button>
                            </div>
                        </div>

                        <!-- Custom Amount Input -->
                        <div class="custom-amount-section">
                            <h3><?php esc_html_e('Or Enter Custom Amount', 'french-practice-hub'); ?></h3>
                            <div class="custom-amount-wrapper">
                                <span class="currency-symbol">$</span>
                                <input type="number" id="custom-amount" name="custom_amount" min="1" step="0.01" placeholder="<?php esc_attr_e('Enter amount', 'french-practice-hub'); ?>">
                            </div>
                        </div>

                        <!-- Selected Amount Display -->
                        <div class="selected-amount-display" id="selected-amount-display" style="display: none;">
                            <p><?php esc_html_e('Selected Amount:', 'french-practice-hub'); ?> <strong id="display-amount">$0</strong></p>
                        </div>

                        <!-- Proceed Button -->
                        <button type="submit" class="btn btn-primary btn-proceed" id="proceed-btn" disabled>
                            <?php esc_html_e('Proceed to Payment', 'french-practice-hub'); ?>
                        </button>
                    </form>
                </div>

                <!-- Payment Info Panel -->
                <div class="payment-info-panel">
                    <div class="info-card">
                        <div class="info-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path>
                                <path d="M9 12l2 2 4-4"></path>
                            </svg>
                        </div>
                        <h3><?php esc_html_e('Secure Payment', 'french-practice-hub'); ?></h3>
                        <p><?php esc_html_e('All donations are processed securely through trusted payment providers.', 'french-practice-hub'); ?></p>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <h3><?php esc_html_e('Tax Deductible', 'french-practice-hub'); ?></h3>
                        <p><?php esc_html_e('Your donation may be tax deductible. Please consult your tax advisor.', 'french-practice-hub'); ?></p>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                            </svg>
                        </div>
                        <h3><?php esc_html_e('Questions?', 'french-practice-hub'); ?></h3>
                        <p><?php esc_html_e('Contact us at', 'french-practice-hub'); ?> <a href="mailto:contact@frenchpracticehub.com">contact@frenchpracticehub.com</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
