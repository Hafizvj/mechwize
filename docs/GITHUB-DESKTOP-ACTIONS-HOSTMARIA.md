# GitHub Desktop → Hostmaria (minimal)

Flow:

```text
Edit locally → GitHub Desktop push to main → Actions uploads to Hostmaria (FTP)
```

## 1. One-time Hostmaria setup

1. Create MySQL DB + import `database/schema.sql`
2. On server: copy `app/config.example.php` → `app/config.local.php` and fill DB details
3. Create FTP account in cPanel with Directory set to `public_html` (so login already lands in the site root)

## 2. One-time GitHub secrets

Repo → **Settings → Secrets and variables → Actions**

| Secret | Example |
|---|---|
| `FTP_SERVER` | `ftp.mechwize.com` |
| `FTP_USERNAME` | your FTP user |
| `FTP_PASSWORD` | your FTP password |
| `FTP_PORT` | `21` |
| `FTP_PROTOCOL` | `ftps` (or `ftp`) |
| `FTP_SERVER_DIR` | `/` |

## 3. Daily use (GitHub Desktop)

1. Open repo, branch **`main`**
2. Edit files in Cursor
3. **Commit to main** → **Push origin**
4. Check https://github.com/Hafizvj/mechwize/actions until green
5. Open https://mechwize.com

## Notes

- `app/config.local.php` is never uploaded (safe)
- `uploads/` images on server are kept
- DB content stays in Hostmaria MySQL

## If deploy fails

- Wrong FTP secrets → test in FileZilla first
- FTPS error → set `FTP_PROTOCOL` to `ftp`
- Site unchanged → wrong `FTP_SERVER_DIR`. If the FTP account already opens in `public_html`, use `/` (not `/public_html/`, or files land in `public_html/public_html`)
