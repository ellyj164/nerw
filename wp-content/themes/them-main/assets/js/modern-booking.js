/**
 * Modern Booking Calendar JavaScript
 * Calendly-style booking system with date/time selection
 */

(function() {
    'use strict';

    // Check if we're on the modern booking page
    if (!document.querySelector('.modern-booking-container')) {
        return;
    }

    // Configuration
    const AVAILABILITY_SCHEDULE = {
        // Kids sessions: specific times
        kids: [
            { start: '06:00', end: '07:30' },
            { start: '08:00', end: '09:30' },
            { start: '11:00', end: '12:30' },
            { start: '14:00', end: '15:30' },
            { start: '17:00', end: '18:30' },
            { start: '19:15', end: '20:45' },
            { start: '19:30', end: '21:00' }
        ],
        // Adults sessions: specific times
        adults: [
            { start: '05:30', end: '07:30' },
            { start: '08:00', end: '10:00' },
            { start: '11:00', end: '13:00' },
            { start: '14:00', end: '16:00' },
            { start: '17:00', end: '19:00' },
            { start: '19:30', end: '21:30' }
        ],
        // Break time (unavailable)
        break: [
            { start: '16:00', end: '17:00' }
        ]
    };

    // State
    let currentDate = new Date();
    let selectedDate = null;
    let selectedTime = null;
    let selectedType = null;

    // DOM Elements
    const currentMonthEl = document.getElementById('current-month');
    const calendarDatesEl = document.getElementById('calendar-dates');
    const prevMonthBtn = document.getElementById('prev-month');
    const nextMonthBtn = document.getElementById('next-month');
    const timeslotsContainer = document.getElementById('timeslots-container');
    const selectedDateDisplay = document.getElementById('selected-date-display');
    const timezoneSelect = document.getElementById('timezone-select');
    
    const modal = document.getElementById('booking-confirmation-modal');
    const modalOverlay = modal ? modal.querySelector('.booking-modal-overlay') : null;
    const closeModalBtn = modal ? modal.querySelector('.booking-modal-close') : null;
    const cancelBookingBtn = document.getElementById('cancel-modern-booking');
    const bookingForm = document.getElementById('modern-booking-form');

    // Initialize
    function init() {
        renderCalendar();
        setupEventListeners();
    }

    // Setup Event Listeners
    function setupEventListeners() {
        if (prevMonthBtn) {
            prevMonthBtn.addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() - 1);
                renderCalendar();
            });
        }

        if (nextMonthBtn) {
            nextMonthBtn.addEventListener('click', () => {
                currentDate.setMonth(currentDate.getMonth() + 1);
                renderCalendar();
            });
        }

        if (timezoneSelect) {
            timezoneSelect.addEventListener('change', () => {
                if (selectedDate) {
                    renderTimeSlots(selectedDate);
                }
            });
        }

        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', closeModal);
        }

        if (cancelBookingBtn) {
            cancelBookingBtn.addEventListener('click', closeModal);
        }

        if (modalOverlay) {
            modalOverlay.addEventListener('click', closeModal);
        }

        if (bookingForm) {
            bookingForm.addEventListener('submit', handleBookingSubmit);
        }
    }

    // Render Calendar
    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        
        // Update month/year display
        if (currentMonthEl) {
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                              'July', 'August', 'September', 'October', 'November', 'December'];
            currentMonthEl.textContent = `${monthNames[month]} ${year}`;
        }

        // Get first day of month and total days
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        
        // Get day of week (0 = Sunday, 1 = Monday, etc.)
        // Adjust so Monday is first (0)
        let startDay = firstDay.getDay() - 1;
        if (startDay === -1) startDay = 6; // Sunday becomes 6

        // Clear calendar
        if (calendarDatesEl) {
            calendarDatesEl.innerHTML = '';

            // Add empty cells for days before month starts
            for (let i = 0; i < startDay; i++) {
                const emptyCell = document.createElement('div');
                emptyCell.className = 'calendar-date other-month';
                const prevMonthDay = new Date(year, month, -i);
                emptyCell.textContent = prevMonthDay.getDate();
                calendarDatesEl.appendChild(emptyCell);
            }

            // Add days of month
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            for (let day = 1; day <= daysInMonth; day++) {
                const dateCell = document.createElement('div');
                const cellDate = new Date(year, month, day);
                cellDate.setHours(0, 0, 0, 0);
                
                dateCell.className = 'calendar-date';
                dateCell.textContent = day;
                
                // Mark today
                if (cellDate.getTime() === today.getTime()) {
                    dateCell.classList.add('today');
                }
                
                // Mark selected
                if (selectedDate && cellDate.getTime() === selectedDate.getTime()) {
                    dateCell.classList.add('selected');
                }
                
                // Disable past dates
                if (cellDate < today) {
                    dateCell.classList.add('disabled');
                } else {
                    dateCell.addEventListener('click', () => selectDate(cellDate));
                }

                calendarDatesEl.appendChild(dateCell);
            }

            // Add next month days to fill grid
            const totalCells = startDay + daysInMonth;
            const remainingCells = 7 - (totalCells % 7);
            if (remainingCells < 7) {
                for (let i = 1; i <= remainingCells; i++) {
                    const nextCell = document.createElement('div');
                    nextCell.className = 'calendar-date other-month';
                    nextCell.textContent = i;
                    calendarDatesEl.appendChild(nextCell);
                }
            }
        }
    }

    // Select Date
    function selectDate(date) {
        selectedDate = date;
        renderCalendar();
        renderTimeSlots(date);
        updateSelectedDateDisplay(date);
    }

    // Update Selected Date Display
    function updateSelectedDateDisplay(date) {
        if (selectedDateDisplay) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            selectedDateDisplay.textContent = date.toLocaleDateString('en-US', options);
        }
    }

    // Generate Time Slots
    function generateTimeSlots(startTime, endTime, interval = 15) {
        const slots = [];
        const [startHour, startMin] = startTime.split(':').map(Number);
        const [endHour, endMin] = endTime.split(':').map(Number);
        
        let currentTime = startHour * 60 + startMin;
        const endTimeMin = endHour * 60 + endMin;
        
        while (currentTime <= endTimeMin - 30) { // Minimum 30 min slot
            const hour = Math.floor(currentTime / 60);
            const min = currentTime % 60;
            const timeStr = `${String(hour).padStart(2, '0')}:${String(min).padStart(2, '0')}`;
            slots.push(timeStr);
            currentTime += interval;
        }
        
        return slots;
    }

    // Render Time Slots
    function renderTimeSlots(date) {
        if (!timeslotsContainer) return;
        
        timeslotsContainer.innerHTML = '';
        
        // Get day of week
        const dayOfWeek = date.getDay();
        const isWeekend = (dayOfWeek === 0 || dayOfWeek === 6);
        
        // Generate all available time slots
        const allSlots = [];
        
        // Kids sessions
        AVAILABILITY_SCHEDULE.kids.forEach(session => {
            const slots = generateTimeSlots(session.start, session.end);
            slots.forEach(time => {
                // Weekdays: all days, Weekends: limited
                const available = !isWeekend || (dayOfWeek === 6 || dayOfWeek === 0);
                if (available) {
                    allSlots.push({ time, type: 'kids', start: session.start, end: session.end });
                }
            });
        });
        
        // Adults sessions
        AVAILABILITY_SCHEDULE.adults.forEach(session => {
            const slots = generateTimeSlots(session.start, session.end);
            slots.forEach(time => {
                // Weekdays: Mon-Fri, Weekends: limited
                const available = !isWeekend || (dayOfWeek === 6 || dayOfWeek === 0);
                if (available) {
                    allSlots.push({ time, type: 'adults', start: session.start, end: session.end });
                }
            });
        });
        
        // Remove duplicates and sort
        const uniqueSlots = Array.from(new Map(
            allSlots.map(slot => [`${slot.time}-${slot.type}`, slot])
        ).values());
        
        uniqueSlots.sort((a, b) => {
            const timeA = a.time.split(':').map(Number);
            const timeB = b.time.split(':').map(Number);
            return (timeA[0] * 60 + timeA[1]) - (timeB[0] * 60 + timeB[1]);
        });
        
        // Render slots
        if (uniqueSlots.length === 0) {
            timeslotsContainer.innerHTML = `
                <div class="no-date-selected">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <p>No available time slots for this date</p>
                </div>
            `;
        } else {
            uniqueSlots.forEach(slot => {
                const slotEl = document.createElement('div');
                slotEl.className = 'timeslot';
                slotEl.innerHTML = `
                    ${slot.time}
                    <span class="timeslot-badge ${slot.type}">${slot.type}</span>
                `;
                slotEl.addEventListener('click', () => selectTimeSlot(slot, date));
                timeslotsContainer.appendChild(slotEl);
            });
        }
    }

    // Select Time Slot
    function selectTimeSlot(slot, date) {
        selectedTime = slot.time;
        selectedType = slot.type;
        
        openBookingModal(date, slot);
    }

    // Open Booking Modal
    function openBookingModal(date, slot) {
        if (!modal) return;
        
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const dateStr = date.toLocaleDateString('en-US', options);
        
        // Update summary
        document.getElementById('summary-date').textContent = dateStr;
        document.getElementById('summary-time').textContent = slot.time;
        document.getElementById('summary-type').textContent = slot.type.charAt(0).toUpperCase() + slot.type.slice(1);
        
        // Set hidden fields
        document.getElementById('booking-date-hidden').value = date.toISOString().split('T')[0];
        document.getElementById('booking-time-hidden').value = slot.time;
        document.getElementById('booking-type-hidden').value = slot.type;
        document.getElementById('booking-timezone-hidden').value = timezoneSelect ? timezoneSelect.value : 'Africa/Kigali';
        
        // Show/hide student age field
        const studentAgeRow = document.getElementById('student-age-row');
        if (slot.type === 'kids') {
            studentAgeRow.style.display = 'block';
            document.getElementById('booking-student-age').setAttribute('required', 'required');
        } else {
            studentAgeRow.style.display = 'none';
            document.getElementById('booking-student-age').removeAttribute('required');
        }
        
        // Show modal
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    // Close Modal
    function closeModal() {
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
            if (bookingForm) {
                bookingForm.reset();
            }
            const messageEl = document.getElementById('booking-response-message');
            if (messageEl) {
                messageEl.style.display = 'none';
            }
        }
    }

    // Handle Booking Submission
    function handleBookingSubmit(e) {
        e.preventDefault();
        
        const submitBtn = bookingForm.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        const messageEl = document.getElementById('booking-response-message');
        
        // Show loading state
        submitBtn.textContent = 'Submitting...';
        submitBtn.disabled = true;
        
        // Prepare form data
        const formData = new FormData(bookingForm);
        formData.append('action', 'fph_submit_modern_booking');
        
        // Submit via AJAX
        fetch(fphBooking.ajaxurl, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
            
            if (messageEl) {
                messageEl.textContent = data.message;
                messageEl.className = 'booking-message ' + (data.success ? 'success' : 'error');
                messageEl.style.display = 'block';
            }
            
            if (data.success) {
                setTimeout(() => {
                    closeModal();
                    // Refresh time slots to mark as booked
                    if (selectedDate) {
                        renderTimeSlots(selectedDate);
                    }
                }, 2000);
            }
        })
        .catch(error => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
            
            if (messageEl) {
                messageEl.textContent = 'An error occurred. Please try again.';
                messageEl.className = 'booking-message error';
                messageEl.style.display = 'block';
            }
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
