# Deployment Guide for WordPress Site

This document explains how to properly deploy this WordPress site, especially regarding Composer-based plugins.

## Important: Composer Dependencies

### The Issue

This site includes plugins that use **Composer** for dependency management (e.g., `tutor-stripe`). Composer dependencies are stored in `vendor/` directories, which are **intentionally excluded from Git** to keep the repository clean.

**After pulling from GitHub, the `vendor/` directories will be missing**, which could cause plugin failures.

### The Solution

The plugins have been updated with **safety guards** that prevent fatal errors when dependencies are missing. Instead of crashing the site, the plugins will:

1. **Show an admin notice** explaining that Composer dependencies are missing
2. **Gracefully disable** themselves until dependencies are installed
3. **Prevent site-wide crashes** on public pages

## Deployment Options

### Option 1: Install Composer Dependencies (Recommended for Development)

If you have shell access to your server and Composer is installed:

```bash
# Navigate to the plugin directory
cd wp-content/plugins/tutor-stripe/

# Install dependencies
composer install --no-dev

# Return to site root
cd ../../../
```

**Note:** Use `--no-dev` flag to install only production dependencies, not development tools.

### Option 2: Deploy Pre-Built Packages (Recommended for Production)

For production deployments without Composer:

1. **Before pushing to GitHub**, build a complete package locally:
   ```bash
   cd wp-content/plugins/tutor-stripe/
   composer install --no-dev
   ```

2. **Create a deployment package** that includes the `vendor/` directory:
   ```bash
   # From plugin directory
   tar -czf tutor-stripe-complete.tar.gz ./*
   ```

3. **Upload the complete package** to your production server
4. **Extract directly** into the plugin directory

### Option 3: Use Plugin Installer (For Non-Technical Users)

If Composer is not available on your server:

1. Download the **official plugin package** from the vendor (which includes `vendor/` directory)
2. Install via WordPress Admin → Plugins → Add New → Upload Plugin
3. Activate the plugin

## Plugins Using Composer

The following plugins in this repository use Composer:

- **Tutor Stripe** (`wp-content/plugins/tutor-stripe/`)
  - Dependencies: `stripe/stripe-php` and related libraries
  - Location: `wp-content/plugins/tutor-stripe/vendor/`

## Safety Features

All Composer-based plugins include these safety features:

✅ **File existence checks** before loading autoloader  
✅ **Admin notices** when dependencies are missing  
✅ **Graceful degradation** instead of fatal errors  
✅ **No hard-coded paths** (uses WordPress constants)

## Troubleshooting

### "Composer dependencies are missing" Notice in WordPress Admin

**Cause:** The `vendor/` directory is missing for a plugin.

**Solution:** Choose one of the deployment options above to install dependencies.

### Plugin appears inactive after deployment

**Cause:** Missing dependencies prevent the plugin from initializing.

**Solution:** Install Composer dependencies using one of the methods above.

### How to check if Composer is installed on server

```bash
composer --version
```

If you see a version number, Composer is available. If not, contact your hosting provider or use Option 2 or 3 above.

## Best Practices

1. **Never commit `vendor/` directories** to Git (they are already in `.gitignore`)
2. **Always run `composer install`** after pulling changes that modify `composer.json` or `composer.lock`
3. **Use `composer install --no-dev`** on production servers
4. **Test deployments** in a staging environment first
5. **Keep `composer.lock` in version control** to ensure consistent dependency versions

## WordPress Core Updates

These changes **do NOT modify WordPress core files**. All modifications are contained within the plugin directories. WordPress automatic updates will continue to work normally.

## Questions?

If you encounter issues with deployment or Composer dependencies, please:

1. Check that you're using one of the deployment methods above
2. Verify file permissions on the server
3. Check server error logs for specific error messages
4. Ensure PHP version meets plugin requirements (usually PHP 7.4+)

---

**Last Updated:** January 2026  
**Applies To:** WordPress site with Tutor LMS and payment plugins
