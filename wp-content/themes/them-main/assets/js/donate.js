/**
 * Donation Page JavaScript
 * Handles amount selection and payment integration
 */

(function() {
    'use strict';

    // Check if we're on the donation page
    if (!document.querySelector('.donate-page')) {
        return;
    }

    // DOM Elements
    const amountButtons = document.querySelectorAll('.amount-btn');
    const customAmountInput = document.getElementById('custom-amount');
    const selectedAmountDisplay = document.getElementById('selected-amount-display');
    const displayAmount = document.getElementById('display-amount');
    const proceedButton = document.getElementById('proceed-btn');
    const donationForm = document.getElementById('donation-form');

    // State
    let selectedAmount = 0;

    // Initialize
    function init() {
        setupEventListeners();
    }

    // Setup Event Listeners
    function setupEventListeners() {
        // Amount button clicks
        amountButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const amount = parseFloat(this.getAttribute('data-amount'));
                selectPresetAmount(amount, this);
            });
        });

        // Custom amount input
        if (customAmountInput) {
            customAmountInput.addEventListener('input', function() {
                handleCustomAmountInput(this.value);
            });

            customAmountInput.addEventListener('focus', function() {
                // Clear preset selection when focusing custom amount
                clearPresetSelection();
            });
        }

        // Form submission
        if (donationForm) {
            donationForm.addEventListener('submit', handleFormSubmit);
        }
    }

    // Select Preset Amount
    function selectPresetAmount(amount, button) {
        // Clear all active buttons
        amountButtons.forEach(btn => btn.classList.remove('active'));
        
        // Set active button
        button.classList.add('active');
        
        // Clear custom amount input
        if (customAmountInput) {
            customAmountInput.value = '';
        }
        
        // Update selected amount
        selectedAmount = amount;
        updateAmountDisplay();
        enableProceedButton();
    }

    // Handle Custom Amount Input
    function handleCustomAmountInput(value) {
        const amount = parseFloat(value);
        
        if (isNaN(amount) || amount <= 0) {
            selectedAmount = 0;
            disableProceedButton();
            hideAmountDisplay();
        } else {
            selectedAmount = amount;
            updateAmountDisplay();
            enableProceedButton();
        }
    }

    // Clear Preset Selection
    function clearPresetSelection() {
        amountButtons.forEach(btn => btn.classList.remove('active'));
    }

    // Update Amount Display
    function updateAmountDisplay() {
        if (displayAmount && selectedAmountDisplay) {
            displayAmount.textContent = '$' + selectedAmount.toFixed(2);
            selectedAmountDisplay.style.display = 'block';
        }
    }

    // Hide Amount Display
    function hideAmountDisplay() {
        if (selectedAmountDisplay) {
            selectedAmountDisplay.style.display = 'none';
        }
    }

    // Enable Proceed Button
    function enableProceedButton() {
        if (proceedButton) {
            proceedButton.disabled = false;
        }
    }

    // Disable Proceed Button
    function disableProceedButton() {
        if (proceedButton) {
            proceedButton.disabled = true;
        }
    }

    // Handle Form Submission
    function handleFormSubmit(e) {
        e.preventDefault();
        
        if (selectedAmount <= 0) {
            showErrorMessage('Please select or enter a donation amount.');
            return;
        }

        // Show loading state
        proceedButton.textContent = 'Processing...';
        proceedButton.disabled = true;

        // Detect payment method and process
        detectPaymentMethodAndProcess();
    }
    
    // Show Error Message
    function showErrorMessage(message) {
        if (selectedAmountDisplay) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'donation-error-message';
            errorDiv.style.cssText = 'background: #FEE2E2; border-left: 4px solid #DC2626; padding: 15px; border-radius: 8px; margin: 15px 0; color: #991B1B;';
            errorDiv.textContent = message;
            
            // Remove existing error messages
            const existingError = donationForm.querySelector('.donation-error-message');
            if (existingError) {
                existingError.remove();
            }
            
            // Insert before proceed button
            proceedButton.parentNode.insertBefore(errorDiv, proceedButton);
            
            // Auto-remove after 5 seconds
            setTimeout(() => errorDiv.remove(), 5000);
        }
    }

    // Detect Payment Method and Process
    function detectPaymentMethodAndProcess() {
        // Check if we're in WordPress admin context and have AJAX URL
        if (typeof fphDonation !== 'undefined' && fphDonation.ajaxurl) {
            // Try to detect which payment plugin is active via AJAX
            fetch(fphDonation.ajaxurl + '?action=fph_detect_payment_method', {
                method: 'GET',
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    processPayment(data.data.method, data.data);
                } else {
                    // Fallback to PayPal.me
                    processFallbackPayment();
                }
            })
            .catch(error => {
                console.error('Error detecting payment method:', error);
                processFallbackPayment();
            });
        } else {
            // No AJAX available, use fallback
            processFallbackPayment();
        }
    }

    // Process Payment based on detected method
    function processPayment(method, methodData) {
        switch(method) {
            case 'woocommerce':
                processWooCommercePayment(methodData);
                break;
            case 'stripe':
                processStripePayment(methodData);
                break;
            case 'paypal':
                processPayPalPayment(methodData);
                break;
            default:
                processFallbackPayment();
        }
    }

    // WooCommerce Payment
    function processWooCommercePayment(methodData) {
        // Create donation product and add to cart via AJAX
        const formData = new FormData();
        formData.append('action', 'fph_add_donation_to_cart');
        formData.append('amount', selectedAmount);
        formData.append('nonce', document.querySelector('[name="donation_nonce"]').value);

        fetch(fphDonation.ajaxurl, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to checkout
                window.location.href = data.data.checkout_url;
            } else {
                const errorMsg = data.data && data.data.message ? data.data.message : 'Error processing donation. Please try again.';
                showErrorMessage(errorMsg);
                resetProceedButton();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorMessage('Network error. Please check your connection and try again.');
            resetProceedButton();
        });
    }

    // Stripe Payment
    function processStripePayment(methodData) {
        // This would integrate with Stripe's payment form
        // For now, show message and fallback
        showErrorMessage('Stripe integration is not yet configured. Redirecting to PayPal...');
        setTimeout(() => processFallbackPayment(), 2000);
    }

    // PayPal Payment
    function processPayPalPayment(methodData) {
        // Redirect to PayPal with donation amount
        if (methodData.paypal_email) {
            const paypalUrl = `https://www.paypal.com/donate?business=${encodeURIComponent(methodData.paypal_email)}&amount=${selectedAmount}&currency_code=USD&item_name=Donation to French Practice Hub`;
            window.location.href = paypalUrl;
        } else {
            processFallbackPayment();
        }
    }

    // Fallback Payment (PayPal.me or donation button)
    function processFallbackPayment() {
        // Use PayPal.me link - get from localized data or use default
        const paypalMeUsername = (typeof fphDonation !== 'undefined' && fphDonation.paypalMeUsername) 
            ? fphDonation.paypalMeUsername 
            : 'frenchpracticehub';
        const paypalMeUrl = `https://www.paypal.me/${paypalMeUsername}/${selectedAmount}`;
        
        // Show success message
        if (selectedAmountDisplay) {
            const successDiv = document.createElement('div');
            successDiv.className = 'donation-success-message';
            successDiv.style.cssText = 'background: #D1FAE5; border-left: 4px solid #10B981; padding: 15px; border-radius: 8px; margin: 15px 0; color: #065F46;';
            successDiv.textContent = 'Thank you! A new tab will open with PayPal to complete your donation.';
            proceedButton.parentNode.insertBefore(successDiv, proceedButton);
        }
        
        // Open in new tab
        window.open(paypalMeUrl, '_blank');
        
        // Reset button
        setTimeout(() => {
            resetProceedButton();
        }, 2000);
    }

    // Reset Proceed Button
    function resetProceedButton() {
        if (proceedButton) {
            proceedButton.textContent = 'Proceed to Payment';
            proceedButton.disabled = false;
        }
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
