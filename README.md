# VCard Studio (PHP)

A web-based virtual business card generator built for Apache/Linux using **PHP + CSS + JavaScript only**.

## Features

- Create and store multiple virtual business cards.
- Optional logo/image upload.
- Up to 3 phone numbers.
- 1 WhatsApp number (click-to-chat via `wa.me`).
- Up to 2 email addresses.
- Multiple custom web links (label + URL).
- Social links: Facebook, LinkedIn, YouTube, Instagram, TikTok.
- Public card view with quick action buttons.
- Top-right share button with:
  - native Web Share (when supported)
  - share modal fallback
  - copy link
  - QR code generation
- Download contact as `.vcf` file.

## Project Structure

- `index.php` — dashboard and card creation UI.
- `save_card.php` — form processing and persistence.
- `card.php` — public card rendering.
- `download_vcard.php` — vCard file generation.
- `lib.php` — helper functions and storage utilities.
- `assets/styles.css` — styles.
- `assets/app.js` — interactive frontend logic.
- `data/cards.json` — card storage.
- `uploads/` — uploaded images.

## Quick Start (Apache/Linux)

1. Copy this folder into your Apache document root.
2. Ensure webserver can write to:
   - `data/`
   - `uploads/`
3. Open `index.php` in browser and create cards.

## Local Development

```bash
php -S 0.0.0.0:8000 -t .
```

Then visit `http://localhost:8000/index.php`.
