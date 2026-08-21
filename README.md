# Mechwize Group Website V2.1

PHP + MySQL website for [mechwize.com](https://mechwize.com) designed for **Hostmaria shared Linux hosting**, synced from GitHub.

## Stack

- PHP 8+ (plain PHP, no Composer required)
- Remote MySQL only (Hostmaria / cloud DB)
- HTML / CSS / JS frontend
- Admin CMS for services, projects, clients, contact settings and enquiries

**Important:** This project does **not** use a local database. Forms and admin require configured remote MySQL credentials.

## Brand

Logo color tone used throughout:

- Black / near-black backgrounds
- Deep emerald green accents
- Gold / brass CTAs and highlights
- Off-white body text

## Features

- Home, About, Services (detail pages), Projects gallery, Clients, Contact
- SEO: unique meta tags, Open Graph, JSON-LD, pretty URLs, `sitemap.xml`, `robots.txt`
- Enquiry form saved to remote MySQL + optional email notify
- Admin panel at `/admin/`

## Hostmaria deployment

**Beginners — pick your path:**

1. **GitHub Desktop + auto-deploy (recommended):**  
   [`docs/GITHUB-DESKTOP-ACTIONS-HOSTMARIA.md`](docs/GITHUB-DESKTOP-ACTIONS-HOSTMARIA.md)
2. **Manual Hostmaria files + database setup:**  
   [`docs/HOSTMARIA-BEGINNER-GUIDE.md`](docs/HOSTMARIA-BEGINNER-GUIDE.md)

### Auto-deploy overview

```text
GitHub Desktop push to main
  → GitHub Actions
    → FTP/FTPS upload to Hostmaria
```

Required GitHub Action secrets: `FTP_SERVER`, `FTP_USERNAME`, `FTP_PASSWORD`, `FTP_PORT`, `FTP_PROTOCOL`, `FTP_SERVER_DIR`.

### Manual short version

1. Push this repository to GitHub.
2. In Hostmaria / cPanel, deploy or sync the repo into the site document root (usually `public_html`).
3. Create a MySQL database and user in Hostmaria.
4. Import [`database/schema.sql`](database/schema.sql) via phpMyAdmin.
5. Copy config on the server only (never commit secrets):

```bash
cp app/config.example.php app/config.local.php
```

6. Edit `app/config.local.php` with the Hostmaria MySQL host, database name, user and password.
7. Ensure `uploads/` is writable by the web server (`chmod 755` or `775` as required).
8. Confirm `.htaccess` rewrite is enabled (Apache `mod_rewrite`).
9. Visit `https://mechwize.com` and `https://mechwize.com/admin/login.php`.

### Default admin login

- Email: `admin@mechwize.com`
- Password: `Mechwize@2026`

Change this password immediately after first login (update the `admins` table hash or add a password change feature later).

### SEO after go-live

1. Set **Canonical site URL** in Admin → Contact & SEO Settings to `https://mechwize.com`.
2. Submit `https://mechwize.com/sitemap.xml` in Google Search Console.
3. Add the Search Console verification token in the same settings screen.

## Local / env config alternative

If the host supports environment variables:

- `MECHWIZE_DB_HOST`
- `MECHWIZE_DB_PORT`
- `MECHWIZE_DB_NAME`
- `MECHWIZE_DB_USER`
- `MECHWIZE_DB_PASS`
- `MECHWIZE_MAIL_TO`
- `MECHWIZE_MAIL_FROM`

## Directory map

```text
app/            PHP bootstrap, config, DB, SEO, auth, uploads
admin/          CMS pages
assets/         CSS, JS, logo
database/       schema.sql (import on Hostmaria only)
includes/       public layout partials
uploads/        runtime images (not committed)
```

## Security notes

- `app/`, `database/` and `includes/` are blocked via `.htaccess`
- `app/config.local.php` is gitignored
- CSRF protection on public and admin forms
- Enquiry honeypot field
- Image upload MIME/size checks
