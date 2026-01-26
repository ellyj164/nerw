# Tutor Stripe Plugin - Composer Dependencies

## Overview

This plugin requires Composer dependencies to function. The `vendor/` directory is excluded from Git (see root `.gitignore`) to follow best practices.

## Quick Start

### After Cloning from GitHub

If you've just cloned this repository, the `vendor/` directory will be missing. Run:

```bash
cd wp-content/plugins/tutor-stripe/
composer install --no-dev
```

### Dependencies

This plugin requires:
- `stripe/stripe-php` (^14.9) - Official Stripe PHP library

See `composer.json` for full dependency list.

## Safety Features

As of the latest update, this plugin includes safety guards to prevent fatal errors:

1. ✅ **File existence check** - Checks if `vendor/autoload.php` exists before loading
2. ✅ **Graceful degradation** - Returns early if dependencies are missing
3. ✅ **Admin notice** - Shows clear error message in WordPress admin panel
4. ✅ **No crashes** - Site remains functional even without this plugin active

## For Deployment

See `/DEPLOYMENT.md` in the repository root for complete deployment instructions.

### Production Deployment

**Option 1:** Install Composer on production server and run `composer install --no-dev`

**Option 2:** Build locally with dependencies, then deploy the complete package including `vendor/`

**Option 3:** Use pre-built plugin packages from the official source

## Development

### Installing Dependencies

```bash
composer install
```

### Updating Dependencies

```bash
composer update
```

Always commit `composer.lock` to ensure consistent versions across environments.

## Troubleshooting

**Problem:** Site shows "Composer dependencies are missing" notice

**Solution:** Run `composer install --no-dev` in this plugin directory, or deploy a complete package

**Problem:** Plugin doesn't appear in WordPress plugins list

**Solution:** Check file permissions and ensure all plugin files are present

---

**Important:** Never commit the `vendor/` directory to Git. It's already excluded in `.gitignore`.
