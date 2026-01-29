# Modern Booking Calendar System - Admin Guide

## Overview
The French Practice Hub theme includes a professional Calendly-style booking calendar system for scheduling French learning sessions.

## Features
- **3-Panel Calendly-Style Layout**: Instructor info, calendar, and time slots
- **Responsive Design**: Works perfectly on desktop, tablet, and mobile devices
- **Dark Mode Support**: Automatically adapts to light/dark theme
- **Timezone Support**: Multiple timezone options for international students
- **Automatic Emails**: Sends confirmation to both admin and customer
- **AJAX Booking**: Smooth, no-reload booking experience
- **Session Types**: Supports both kids and adults sessions

## Setup Instructions

### 1. Create a Booking Calendar Page
1. Go to **Pages > Add New** in WordPress admin
2. Title: "Book a Session" or "Booking Calendar"
3. In **Page Attributes > Template**, select **"Modern Booking Calendar"**
4. Set the permalink to `/booking-calendar/` (Settings > Permalinks)
5. Publish the page

### 2. Configure Booking Settings

#### Appearance > Customize > Booking Calendar Settings

1. **Instructor Name**: Enter the instructor's name (default: "Fidele FLE")
2. **Instructor Photo**: Upload a square photo (recommended: 300x300px)
3. **Session Description**: Customize the session description text

#### Appearance > Customize > Newsletter Settings (for email)

1. **Booking Notification Email**: Set the email address to receive booking notifications (default: booking@frenchpracticehub.com)

### 3. Customize Availability Schedule

The booking calendar uses a predefined schedule defined in `/assets/js/modern-booking.js`:

**Kids Sessions** (typically for younger learners):
- 06:00-07:30
- 08:00-09:30
- 11:00-12:30
- 14:00-15:30
- 17:00-18:30
- 19:15-20:45
- 19:30-21:00

**Adults Sessions**:
- 05:30-07:30
- 08:00-10:00
- 11:00-13:00
- 14:00-16:00
- 17:00-19:00
- 19:30-21:30

**Break Time** (unavailable):
- 16:00-17:00 (daily pause)

To modify these schedules, edit `AVAILABILITY_SCHEDULE` in `/wp-content/themes/them-main/assets/js/modern-booking.js`.

### 4. View Bookings in Admin

1. Go to **Bookings** in the WordPress admin menu
2. View all bookings with details:
   - Customer name and contact
   - Date and time
   - Session type (Kids/Adults)
   - Status (Pending/Confirmed/Cancelled)
3. Click on a booking to see full details including notes

### 5. Email Notifications

When a booking is made:

1. **Admin Email** (to booking@frenchpracticehub.com or your configured email):
   - Full booking details
   - Link to view/edit booking in admin
   - Customer contact information

2. **Customer Email** (to the customer):
   - Booking confirmation
   - Session date, time, and timezone
   - Note that video conferencing details will be sent later

## Customization

### Styling
- **Main styles**: `/wp-content/themes/them-main/assets/css/modern-booking.css`
- Colors use CSS variables defined in `/assets/css/main.css`
- Dark mode automatically supported

### Functionality
- **JavaScript**: `/wp-content/themes/them-main/assets/js/modern-booking.js`
- **PHP Handler**: Functions in `/wp-content/themes/them-main/functions.php`
- **Template**: `/wp-content/themes/them-main/page-booking-calendar.php`

### Timezone Options
Default timezones available:
- Central Africa Time (CAT) - Kigali
- Eastern Time (ET)
- Central Time (CT)
- Pacific Time (PT)
- British Time (GMT)
- Central European Time (CET)
- Gulf Standard Time (GST)

To add more timezones, edit the `timezone-select` dropdown in `page-booking-calendar.php`.

## Integration with Booking Plugins

### Option 1: Use Built-in System (Current)
The theme has a complete built-in booking system that:
- Creates booking posts in WordPress
- Sends automatic emails
- Stores booking data
- Displays in admin

### Option 2: Integrate with Third-Party Plugins

If you prefer to use WordPress booking plugins:

1. **WooCommerce Bookings**:
   - Install WooCommerce + WooCommerce Bookings
   - Replace the booking calendar page content with shortcode: `[booking]`

2. **Amelia**:
   - Install Amelia booking plugin
   - Replace page content with Amelia shortcode
   - Configure Amelia settings in its own panel

3. **Calendly Embed**:
   - Get your Calendly embed code
   - Replace the modern booking calendar with Calendly iframe

To replace: Edit `page-booking-calendar.php` and replace the entire content area with your plugin's shortcode.

## Troubleshooting

### Bookings Not Appearing
- Check that the booking post type is registered (Bookings menu should appear)
- Verify WordPress permalinks are set (Settings > Permalinks > Save Changes)

### Emails Not Sending
- Check WordPress email functionality with a plugin like WP Mail SMTP
- Verify the notification email address in Customizer settings
- Check spam folder
- Test with a different email service

### Calendar Not Displaying
- Ensure JavaScript is enabled
- Check browser console for errors (F12)
- Clear browser cache
- Verify the page template is set to "Modern Booking Calendar"

### Timezones Not Working
- Ensure PHP timezone is set correctly on server
- Check WordPress timezone setting (Settings > General)

## Security
- All form submissions use WordPress nonces for CSRF protection
- Input is sanitized and validated
- Email addresses are verified
- SQL injection prevention via WordPress APIs

## Support
For technical support or custom development:
- Contact: contact@frenchpracticehub.com
- Review theme documentation
- Check WordPress.org support forums

## Updates
When updating the theme:
1. Always backup your site first
2. Customizations to booking schedules may need to be reapplied
3. Custom CSS/JS should be added via child theme or Customizer

---

**Version**: 1.0
**Last Updated**: January 2026
