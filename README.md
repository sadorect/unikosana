# Unikosa North America — Website

Official website and content-management platform for the North America Unit of Unikosa.
Built with **Laravel 13**, **Filament v3** (admin), **Tailwind CSS** (public site), and
**Spatie** packages (Media Library, Permission/Shield, Settings, Tags, Backup).

## Requirements

- PHP 8.3 with extensions: `pdo`, `mbstring`, `intl`, `gd`, `zip`, and a DB driver
  (`pdo_sqlite` for the default setup, or `pdo_mysql` / `pdo_pgsql` for production).
- Composer 2, Node 20 + npm.

## Local setup

```bash
composer install
npm install && npm run build        # or `npm run dev` while developing
cp .env.example .env                # (already configured for SQLite in this repo)
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan shield:generate --all --panel=admin
php artisan storage:link
php artisan serve
```

The public site is at `/`, the admin dashboard at `/admin`.

### Demo accounts (from the seeder)

| Role          | Email                     | Password   | Access                                            |
|---------------|---------------------------|------------|---------------------------------------------------|
| Super Admin   | `admin@unikosa-na.test`   | `password` | Everything, incl. settings & user/role management |
| Content Admin | `editor@unikosa-na.test`  | `password` | Events, News, Gallery, Members, Resources only    |

> Change these credentials before any real deployment.

## Database

The project ships configured for **SQLite** for zero-friction setup. It is fully
DB-agnostic via Eloquent — to use **MySQL/MariaDB** or **PostgreSQL** in production,
update the `DB_*` values in `.env` (a commented MySQL block is included), install the
matching PHP driver, then re-run `php artisan migrate --seed`. Also set the backup
`databases` list in `config/backup.php` back to your connection name.

## Admin guide (managing content)

All content is managed from **`/admin`**:

- **Content group:** Events (flyer, gallery, documents, videos, speakers, status),
  Gallery Albums (photo/video, by event & year), News & Announcements (news / article /
  announcement / press release, tags, featured image, publish date), Resources (PDF/doc
  downloads by category).
- **Organization group:** Leadership profiles (drag to reorder), Members directory.
- **Administration group:** Contact-form messages (read/unread, with an unread badge).
- **Settings group** (Super Admin only):
  - **Theme & Branding** — site name, logo, favicon, and primary/secondary/accent colors
    that restyle the public site instantly.
  - **Contact & Social** — email, phone, address, social links, Google Map embed.
  - **Homepage Content** — hero, intro, mission, vision.
  - **About Page** — history, mission, vision, objectives, org structure, constitution PDF.
  - **Security & Captcha** — toggle the public-form image captcha and its difficulty.

## Public site

Home, About, Leadership, Members (search + filters), Events (+ detail & yearly archive),
Gallery (albums), News (+ article with social sharing), Resources, Contact
(captcha-protected), and a unified `/search` across members, events, news and resources.

## Phase 2 features

- **Member portal** — public self-registration (`/member/register`) creates a *pending* member for admin approval; members log in at `/member/login` to edit their own directory profile and photo. Admins approve/reject from the Members resource.
- **National SSO (Laravel Passport)** — the NA site is an OAuth2 client of `unikosa.sadorect.com`. A "Continue with your Unikosa account" button appears on the member login once configured. Set in `.env`: `NATIONAL_OAUTH_CLIENT_ID`, `NATIONAL_OAUTH_CLIENT_SECRET`, `NATIONAL_OAUTH_BASE_URL`, `NATIONAL_OAUTH_REDIRECT` (register the client on the national Passport with redirect `https://unikosana.sadorect.com/auth/national/callback`).
- **Event registration** — per-event on-site RSVP form with optional capacity; admins see the roster (with CSV export) via the Registrations relation on each event.
- **Newsletter** — footer signup, admin subscriber list with CSV export, tokenised unsubscribe links.
- **Donations** — `/donate` shows bank/transfer details always; **online card payments (Stripe) are behind an admin toggle** (Settings → Donations) and only activate when `STRIPE_KEY`/`STRIPE_SECRET` are set in `.env`. Donations are recorded in the admin.
- **FAQ, Testimonials, Blog** — admin-managed; FAQ accordion page, testimonials on the homepage, blog lists article-type posts.
- **Integrations** — floating WhatsApp button (uses the WhatsApp link in Contact settings), Add-to-Calendar `.ics` per event + an `/events/feed.ics` subscription feed, and a live-stream embed field on events (auto-embeds YouTube/Vimeo).
- **Email notifications** — sent for contact submissions, event registrations, member registrations, newsletter signups, and donation receipts. Uses `MAIL_MAILER=log` in dev; set real SMTP in `.env` for production.

## Scheduled tasks

Daily database + files backup via `spatie/laravel-backup`. Add this cron entry on the
server so the scheduler runs:

```
* * * * * cd /path/to/app && php artisan schedule:run >> /dev/null 2>&1
```

## Testing

```bash
php artisan test
```
