# Switching asset storage to Cloudflare R2 — unikosana

This app stores uploaded assets on a configurable disk. By default that's the
local `public` disk; this runbook moves everything to Cloudflare **R2** with no
code change — only environment + two backfill commands.

> **How assets are stored here:** unikosana uses Spatie MediaLibrary (member,
> leadership, testimonial photos; event flyers/galleries/documents; resource
> files; albums) plus a handful of settings uploads (logo, favicon, hero, about
> images, constitution PDF). All of them resolve their URL from the
> `filesystems.media_disk` config at render time, so switching the disk is
> enough — **except** existing MediaLibrary rows, which store the disk name per
> row and must be repointed (step 4).

## The commands (what each does)

| Command | What it does |
|---|---|
| `php artisan assets:sync-r2` | Copies every file from the local `public` disk to `r2`. Idempotent (skips files already there), resumable. Options: `--dry-run`, `--overwrite`, `--from=`, `--to=`. |
| `php artisan media:repoint-disk` | Updates existing Spatie MediaLibrary rows (`disk` + `conversions_disk`) from `public` to `r2` so their URLs resolve from R2. Options: `--dry-run`, `--from=`, `--to=`. |

Run `php artisan <command> --help` for the full option list at any time.

## Cutover steps

```bash
# 1. Set the R2 credentials in .env (leave MEDIA_DISK=public for now):
#    R2_ACCESS_KEY_ID=...
#    R2_SECRET_ACCESS_KEY=...
#    R2_ENDPOINT=https://<accountid>.r2.cloudflarestorage.com
#    R2_BUCKET=unikosana
#    R2_URL=https://<your-public-r2-domain>   # bucket public URL or custom domain

# 2. Rebuild config cache so the r2 disk picks up the new env.
#    (IMPORTANT: config is cached — changes won't apply without this.)
php artisan optimize

# 3. Copy existing files up. Preview first, then run for real.
php artisan assets:sync-r2 --dry-run
php artisan assets:sync-r2

# 4. Repoint existing MediaLibrary rows to r2. Preview first, then run.
php artisan media:repoint-disk --dry-run
php artisan media:repoint-disk

# 5. Flip the switch, then rebuild cache again.
#    Set MEDIA_DISK=r2 in .env, then:
php artisan optimize
```

**Order matters:** sync files → repoint DB rows → flip `MEDIA_DISK` → rebuild cache.

## Rollback

If something looks wrong after step 5, set `MEDIA_DISK=public` and run
`php artisan optimize`. The local files are untouched by the sync (it only
copies *up*), and you can re-run `media:repoint-disk --from=r2 --to=public` to
move MediaLibrary rows back.

## Notes

- `assets:sync-r2` also copies `storage/app/public/.gitignore` — harmless.
- Settings uploads (logo/favicon/hero/about) store *relative* paths and resolve
  via `media_disk`, so they flip automatically once files are synced — no DB
  rewrite needed.
- The national app (unikosa-mimo) has its own runbook; it additionally rewrites
  absolute-URL columns (`assets:rewrite-urls`) because it stores full URLs.
