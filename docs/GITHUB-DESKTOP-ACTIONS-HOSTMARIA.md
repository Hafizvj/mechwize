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
- Public UI assets (`assets/css`, `assets/js`, `assets/images`) deploy with each push

## If deploy fails

- **`530 Login failed` / FTP locking** (most common on Hostmaria): GitHub Actions uses a different IP than your PC. Unlock FTP **by time**, not by your home IP.
  1. Log in to https://stackcp.com
  2. **Manage Hosting** → your package → **Manage**
  3. On the FTP panel, **Unlock FTP by time** (pick the longest option, e.g. 24 hours)
  4. Copy **FTP Server / Username / Password** from that same FTP Details box into GitHub secrets (no extra spaces)
  5. Re-run the failed workflow (Actions → failed run → **Re-run jobs**)
- Unlocking by IP will still fail: that only allows *your* computer, not GitHub.
- Wrong FTP secrets → test in FileZilla first using the StackCP FTP Details
- FTPS error → set `FTP_PROTOCOL` to `ftp`
- Site unchanged → wrong `FTP_SERVER_DIR`. If the FTP account already opens in `public_html`, use `/` (not `/public_html/`, or files land in `public_html/public_html`)
