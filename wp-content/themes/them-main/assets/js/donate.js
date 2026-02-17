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
            alert('Please select or enter a donation amount.');
            return;
        }

        // Show loading state
        proceedButton.textContent = 'Processing...';
        proceedButton.disabled = true;

        // Detect payment method and process
        detectPaymentMethodAndProcess();
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
                alert('Error processing donation. Please try again.');
                resetProceedButton();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error processing donation. Please try again.');
            resetProceedButton();
        });
    }

    // Stripe Payment
    function processStripePayment(methodData) {
        // This would integrate with Stripe's payment form
        // For now, redirect to a Stripe payment page or show Stripe Elements
        alert('Stripe integration coming soon. Please use the PayPal option.');
        processFallbackPayment();
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
        // Use PayPal.me link or generic PayPal donate button
        const paypalMeUsername = 'frenchpracticehub'; // This should be configurable
        const paypalMeUrl = `https://www.paypal.me/${paypalMeUsername}/${selectedAmount}`;
        
        // Open in new tab
        window.open(paypalMeUrl, '_blank');
        
        // Reset button and show success message
        setTimeout(() => {
            resetProceedButton();
            alert('Thank you for your donation! You will be redirected to PayPal to complete the transaction.');
        }, 1000);
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
