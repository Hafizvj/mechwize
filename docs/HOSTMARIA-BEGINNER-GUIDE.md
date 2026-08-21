# Mechwize Website — Beginner Deployment Guide (Hostmaria)

This guide explains, step by step, how to put the Mechwize website online on **Hostmaria shared hosting** and connect it to a **Hostmaria MySQL database**.

You do **not** need advanced coding knowledge. Follow the steps in order.

---

## What you will finish with

When you complete this guide, you will have:

1. Website files live on Hostmaria
2. MySQL database created on Hostmaria
3. Website connected to that database
4. Admin login working
5. Contact form saving enquiries into the database

---

## Before you start (checklist)

Make sure you have:

- [ ] Hostmaria hosting account login (cPanel)
- [ ] Domain `mechwize.com` pointed to Hostmaria (or ready to point)
- [ ] GitHub account access to: https://github.com/Hafizvj/mechwize
- [ ] A computer with internet
- [ ] About 30–60 minutes

Useful links:

- Website repo: https://github.com/Hafizvj/mechwize
- Pull request / latest work branch: https://github.com/Hafizvj/mechwize/pull/1
- Branch name to use: `cursor/mechwize-php-site-9b78`

---

## Important rules (read once)

1. **Do not use a local XAMPP database for the live website.**  
   Live site must use Hostmaria MySQL only.
2. **Never upload your database password to GitHub.**  
   Keep passwords only inside Hostmaria / `config.local.php` on the server.
3. If something fails, note the exact error message before changing many things at once.

---

# PART A — Put the website files on Hostmaria

You can use **Method 1 (recommended: Git)** or **Method 2 (ZIP upload)**.

## Method 1 — Deploy with Git (recommended)

### A1. Log in to Hostmaria

1. Open your Hostmaria client area / hosting login page.
2. Find and open **cPanel**.
3. You should see icons like Files, Databases, Domains, Softaculous, etc.

### A2. Open Git Version Control

1. In cPanel search box, type: `Git`
2. Click **Git Version Control**
3. Click **Create**

### A3. Clone the Mechwize repository

Fill the form carefully:

1. **Clone a Repository**: turn this **ON**
2. **Clone URL**:
   ```text
   https://github.com/Hafizvj/mechwize.git
   ```
3. **Repository Path**:
   - If this is the main website for `mechwize.com`, use your web root, usually:
     ```text
     public_html
     ```
   - If `public_html` already has old WordPress files, either:
     - back them up and clear `public_html`, or
     - deploy to a subfolder first (example: `public_html/newsite`) for testing
4. **Repository Name**: `mechwize`
5. Click **Create**

### A4. Switch to the correct branch

1. Open the Git repository you just created
2. Look for **Pull or Deploy** / branch settings
3. Select branch:
   ```text
   cursor/mechwize-php-site-9b78
   ```
4. Pull/update so Hostmaria downloads the latest files

> If your host only shows `main`, merge the PR on GitHub first, then pull `main`.

### A5. Confirm files are present

In cPanel → **File Manager** → open `public_html` (or your chosen folder).

You should see files/folders like:

- `index.php`
- `admin`
- `app`
- `assets`
- `database`
- `uploads`
- `.htaccess`
- `README.md`

If these are missing, the clone/path is wrong. Fix path and redeploy before continuing.

---

## Method 2 — Upload ZIP (if Git is unavailable)

### A2-1. Download code from GitHub

1. Open: https://github.com/Hafizvj/mechwize
2. Click the branch dropdown → choose `cursor/mechwize-php-site-9b78`
3. Click green **Code** → **Download ZIP**
4. Save the ZIP to your computer

### A2-2. Upload to Hostmaria

1. cPanel → **File Manager**
2. Open `public_html`
3. (Recommended) Rename old site folder/files for backup, example:
   - create folder `old-site-backup`
   - move old WordPress files into it
4. Click **Upload**
5. Upload the ZIP
6. Right-click ZIP → **Extract**
7. If files extract into a subfolder like `mechwize-...`, move all contents up into `public_html`

Result: `index.php` must be directly inside the web root used by your domain.

---

# PART B — Create the Hostmaria MySQL database

## B1. Create database

1. In cPanel, open **MySQL® Databases**
2. Under **Create New Database**, type a name, example:
   ```text
   mechwize
   ```
3. Click **Create Database**
4. Write down the full database name.  
   Hostmaria often prefixes it, example:
   ```text
   youruser_mechwize
   ```

## B2. Create database user

1. Still in **MySQL® Databases**
2. Under **Add New User**:
   - Username example: `mechwize`
   - Password: click password generator and save it somewhere safe
3. Click **Create User**
4. Write down the full username, example:
   ```text
   youruser_mechwize
   ```

## B3. Add user to database

1. Find **Add User To Database**
2. Select your user and your database
3. Click **Add**
4. Tick **ALL PRIVILEGES**
5. Click **Make Changes**

## B4. Note your DB connection details

Copy this into a notes file:

```text
DB Host: localhost
DB Name: youruser_mechwize
DB User: youruser_mechwize
DB Pass: (your strong password)
DB Port: 3306
```

> On most Hostmaria shared plans, host is `localhost`.  
> If Hostmaria support gives a different host, use that instead.

---

# PART C — Import website tables (schema)

## C1. Open phpMyAdmin

1. cPanel → **phpMyAdmin**
2. In the left sidebar, click your database name (`youruser_mechwize`)

## C2. Import schema.sql

1. Click top tab **Import**
2. Click **Choose File**
3. Select this file from your project:
   ```text
   database/schema.sql
   ```
   - If you only have files on Hostmaria already, download `schema.sql` from GitHub first:
     - open repo → `database/schema.sql` → download
4. Leave defaults
5. Click **Go** / **Import**

## C3. Confirm import success

In the left sidebar, open your database. You should now see tables:

- `admins`
- `site_settings`
- `services`
- `service_features`
- `projects`
- `project_images`
- `clients`
- `enquiries`

If tables are missing, import failed. Re-check you selected the correct database before importing.

---

# PART D — Connect PHP website to Hostmaria database

This is the most important connection step.

## D1. Create config file on the server

1. cPanel → **File Manager**
2. Go to:
   ```text
   public_html/app/
   ```
3. Find:
   ```text
   config.example.php
   ```
4. Copy/rename method:
   - Right-click `config.example.php` → **Copy**
   - Paste in same folder
   - Rename the copy to:
     ```text
     config.local.php
     ```

> Final file must be exactly: `app/config.local.php`

## D2. Edit config.local.php

1. Right-click `config.local.php` → **Edit**
2. Replace placeholder values with your real Hostmaria DB details:

```php
<?php

declare(strict_types=1);

return [
    'database' => [
        'host' => 'localhost',
        'port' => 3306,
        'name' => 'youruser_mechwize',
        'user' => 'youruser_mechwize',
        'password' => 'YOUR_REAL_PASSWORD_HERE',
        'charset' => 'utf8mb4',
    ],
    'mail' => [
        'to' => 'info@mechwize.com',
        'from' => 'website@mechwize.com',
    ],
];
```

3. Save changes

### Common beginner mistakes

- Using database name without the Hostmaria prefix (`youruser_...`)
- Leaving example password text unchanged
- Creating the file in wrong folder (`public_html/config.local.php` instead of `public_html/app/config.local.php`)
- Uploading `config.local.php` to GitHub (do not do this)

---

# PART E — Folder permissions for image uploads

Admin can upload project/client/service images into `uploads/`.

1. File Manager → open `public_html/uploads`
2. Ensure these folders exist:
   - `uploads/projects`
   - `uploads/clients`
   - `uploads/services`
3. Right-click `uploads` → **Change Permissions**
4. Set to `755` (or `775` if uploads fail)
5. Apply similar permissions to the 3 subfolders

---

# PART F — Domain and SSL

## F1. Confirm domain document root

1. cPanel → **Domains** (or **Addon Domains**)
2. Find `mechwize.com`
3. Confirm document root points to the folder that contains `index.php`  
   Usually: `public_html`

## F2. Turn on HTTPS

1. cPanel → **SSL/TLS Status** or **Let’s Encrypt**
2. Issue/enable SSL for `mechwize.com` and `www.mechwize.com`
3. Force HTTPS if the option exists

Then open:

- https://mechwize.com

---

# PART G — First login and website checks

## G1. Open the website

Visit:

- https://mechwize.com

You should see the Mechwize homepage (dark black/green/gold design).

## G2. Open admin panel

Visit:

- https://mechwize.com/admin/login.php

Default login (from seed data):

```text
Email: admin@mechwize.com
Password: Mechwize@2026
```

## G3. Change password immediately

For beginners, easiest first method:

1. cPanel → phpMyAdmin → `admins` table
2. Or ask a developer to add a “Change Password” page later
3. At minimum, do not leave the default password for long

## G4. Update contact details

In admin:

1. Open **Contact & SEO Settings**
2. Update:
   - phone numbers
   - WhatsApp
   - emails
   - address
   - site URL = `https://mechwize.com`
3. Save

## G5. Test enquiry form

1. Open https://mechwize.com/contact
2. Submit a test message
3. Go to Admin → **Enquiries**
4. Confirm the message appears

If enquiry fails, almost always one of these is wrong:

- `config.local.php` values
- schema not imported
- DB user privileges missing

---

# PART H — Keep website updated from GitHub

Whenever code is improved in GitHub:

## If using Git Version Control

1. cPanel → Git Version Control
2. Open mechwize repository
3. Click **Update** / **Pull**
4. Recheck website

## Important

- Pulling code will **not** normally overwrite `app/config.local.php` (it is ignored by Git)
- Uploaded images in `uploads/` stay on the server
- Database content (projects/clients/enquiries) stays in MySQL

---

# PART I — Beginner troubleshooting

## Website shows old WordPress site

- Old files are still in `public_html`
- Backup and remove/replace old files, then redeploy Mechwize files

## Homepage loads, but admin says DB not configured

- `app/config.local.php` missing, misnamed, or in wrong folder
- password/name/user incorrect

## Contact form error

- Import `schema.sql` again into the correct DB
- Confirm `enquiries` table exists
- Recheck DB credentials

## CSS/design looks broken

- Confirm `assets/css/styles.css` exists
- Hard refresh browser (`Ctrl + F5`)
- Check you deployed complete project, not only `index.php`

## Pretty links like `/services/...` not working

- `.htaccess` missing
- Apache rewrite not enabled (ask Hostmaria support to enable `mod_rewrite`)

## Images not uploading in admin

- `uploads/` permissions too strict
- folder missing
- PHP upload limit too low (ask Hostmaria support)

---

# PART J — Security basics after go-live

- [ ] Change default admin password
- [ ] Keep `config.local.php` only on server
- [ ] Do not share DB password in WhatsApp/email casually
- [ ] Take a Hostmaria backup after first successful setup
- [ ] Submit sitemap later: `https://mechwize.com/sitemap.xml` in Google Search Console

---

# Quick “done” checklist

| Step | Done? |
|---|---|
| Files are in Hostmaria web root | ☐ |
| MySQL DB + user created | ☐ |
| `schema.sql` imported | ☐ |
| `app/config.local.php` filled with Hostmaria DB details | ☐ |
| `uploads/` writable | ☐ |
| https://mechwize.com opens | ☐ |
| Admin login works | ☐ |
| Test enquiry appears in admin | ☐ |
| Contact details updated in admin | ☐ |

---

# Need help from Hostmaria support?

You can send them this short message:

```text
Hello, please help confirm:
1) my domain document root for mechwize.com
2) that PHP and MySQL are enabled
3) that Apache mod_rewrite is enabled for .htaccess
4) the correct MySQL host name for my account (usually localhost)
Thank you.
```

---

# Local XAMPP note (optional, for practice only)

If you are practicing on your PC with XAMPP:

- That is fine for learning
- For the **live mechwize.com site**, still use Hostmaria MySQL only
- Do not point the live site to your laptop database

---

Document version: beginner Hostmaria guide for Mechwize PHP/MySQL website.
