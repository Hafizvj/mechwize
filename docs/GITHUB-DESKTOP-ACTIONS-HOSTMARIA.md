# Mechwize — GitHub Desktop + GitHub Actions → Hostmaria

This guide is for beginners who want to:

1. Edit the website on their computer (XAMPP optional)
2. Use **GitHub Desktop** to send code to GitHub
3. Use **GitHub Actions** to auto-upload files to **Hostmaria**

---

## Important concept (read this)

On Hostmaria shared hosting, GitHub Actions usually **cannot run `git pull` on the server**.

What we set up instead:

```text
Your PC (GitHub Desktop)
    → pushes code to GitHub (main branch)
        → GitHub Actions runs
            → uploads files to Hostmaria by FTP/FTPS
```

So:

- GitHub = source of truth for code
- Hostmaria = live website files + MySQL database
- `app/config.local.php` stays only on Hostmaria (never in GitHub)
- uploaded images in `uploads/` stay on Hostmaria (not wiped by deploy)

---

## Setup status checklist

| Item | Status / action |
|---|---|
| Website PHP code | Already in repo |
| MySQL schema | `database/schema.sql` (import once in Hostmaria) |
| GitHub Actions workflow | `.github/workflows/deploy-hostmaria.yml` |
| GitHub Desktop | Install + open this repo |
| Hostmaria FTP secrets in GitHub | You must add these once |
| Hostmaria DB config file | Create `app/config.local.php` once on server |

---

# PART 1 — One-time Hostmaria preparation

Do these once (not every deploy).

## 1.1 Create MySQL database and import schema

Follow:

- [`HOSTMARIA-BEGINNER-GUIDE.md`](HOSTMARIA-BEGINNER-GUIDE.md) Parts B and C

Result:

- DB created
- tables imported from `database/schema.sql`

## 1.2 Create `app/config.local.php` on Hostmaria

In File Manager on Hostmaria:

1. Go to your site folder (usually `public_html/app/`)
2. Copy `config.example.php` → `config.local.php`
3. Put your real DB host/name/user/password
4. Save

This file is excluded from GitHub Actions uploads, so later deploys will not overwrite it.

## 1.3 Create FTP account in Hostmaria cPanel

1. Login Hostmaria → **cPanel**
2. Open **FTP Accounts**
3. Create an FTP account for deployment (recommended) or use main FTP account
4. Write down:

```text
FTP server/host:   (example: ftp.mechwize.com OR your server hostname)
FTP username:      (full username shown by cPanel)
FTP password:      (the password you set)
FTP port:          usually 21
FTP protocol:      ftps   (if that fails, use ftp)
Server directory:  /public_html/   (or the folder where index.php should live)
```

### How to confirm server directory

1. Connect with FileZilla using the same FTP login
2. Find the folder that already contains (or should contain) `index.php`
3. That path is your `FTP_SERVER_DIR`  
   Common values:
   - `/public_html/`
   - `/home/USERNAME/public_html/`
   - `/`

Use the path relative to where the FTP user lands.

---

# PART 2 — Add GitHub Secrets (required for Actions)

1. Open: https://github.com/Hafizvj/mechwize
2. Go to **Settings** → **Secrets and variables** → **Actions**
3. Click **New repository secret** and add each:

| Secret name | Example value | Notes |
|---|---|---|
| `FTP_SERVER` | `ftp.mechwize.com` | Hostmaria FTP host |
| `FTP_USERNAME` | `user@mechwize.com` | Exact cPanel FTP username |
| `FTP_PASSWORD` | `********` | FTP password |
| `FTP_PORT` | `21` | Usually 21 |
| `FTP_PROTOCOL` | `ftps` | Try `ftp` if FTPS fails |
| `FTP_SERVER_DIR` | `/public_html/` | Must end with `/` |

> Do not put these values into code files. Secrets only.

---

# PART 3 — Install and use GitHub Desktop

## 3.1 Install

1. Download GitHub Desktop: https://desktop.github.com/
2. Sign in with your GitHub account
3. Choose **File → Clone repository**
4. Select `Hafizvj/mechwize`
5. Local path example:
   ```text
   C:\xampp\htdocs\mechwize
   ```
6. Clone

## 3.2 Use branch `main` for live deploys

In GitHub Desktop:

1. Click current branch
2. Choose **main**
3. Click **Fetch origin** / **Pull origin**

The deploy Action runs only when `main` is updated.

## 3.3 Daily edit → publish flow

1. Edit files in Cursor (or any editor) inside the repo folder
2. Open GitHub Desktop
3. You will see changed files listed
4. Bottom-left:
   - Summary: short message, example `Update contact page text`
5. Click **Commit to main**
6. Click **Push origin**

That push triggers GitHub Actions → Hostmaria upload.

---

# PART 4 — Watch the deploy

1. Open: https://github.com/Hafizvj/mechwize/actions
2. Click the newest **Deploy to Hostmaria** run
3. Wait until it is green (success)

Then check:

- https://mechwize.com
- https://mechwize.com/admin/login.php

---

# PART 5 — What gets uploaded / what does not

## Uploaded from GitHub

- PHP pages (`index.php`, `services.php`, etc.)
- `admin/`
- `app/` code (except `config.local.php`)
- `assets/`
- `.htaccess`
- `robots.txt`
- empty `uploads` placeholders

## Never uploaded / preserved on Hostmaria

- `app/config.local.php` (your DB password file)
- real files inside `uploads/projects`, `uploads/clients`, `uploads/services`
- MySQL data (projects, clients, enquiries stay in database)

---

# PART 6 — First successful end-to-end test

1. Confirm GitHub secrets are set
2. Confirm Hostmaria DB + `config.local.php` exist
3. In GitHub Desktop, make a tiny change (example: add a space in README)
4. Commit to `main` → Push
5. Wait for Actions success
6. Refresh live site
7. Login admin and submit a test enquiry

---

# PART 7 — Troubleshooting

## Actions fails: login incorrect

- Recheck `FTP_SERVER`, `FTP_USERNAME`, `FTP_PASSWORD`
- Test same credentials in FileZilla first

## Actions fails: TLS / certificate / FTPS

- Set secret `FTP_PROTOCOL` to `ftp` (temporary)
- Or ask Hostmaria for correct FTPS host/port

## Actions success, but website unchanged

- Wrong `FTP_SERVER_DIR` (uploaded to different folder)
- Domain document root points elsewhere
- Browser cache → hard refresh

## Admin / enquiry says DB not configured

- `app/config.local.php` missing on Hostmaria
- wrong DB name/user/password
- schema not imported

## GitHub Desktop conflict / diverged branches

1. Click **Fetch origin**
2. **Pull origin** first
3. Then commit and push your changes

---

# PART 8 — Recommended beginner workflow forever

```text
1. Edit locally in Cursor
2. Commit + Push with GitHub Desktop (main)
3. GitHub Actions deploys to Hostmaria automatically
4. Manage content (projects/clients/contact) in Admin on live site
5. Manage DB credentials only on Hostmaria config.local.php
```

---

## Related docs

- Full Hostmaria DB/files beginner guide: [`HOSTMARIA-BEGINNER-GUIDE.md`](HOSTMARIA-BEGINNER-GUIDE.md)
- Project overview: [`../README.md`](../README.md)
