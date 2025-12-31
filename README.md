## Sentuh ID Realtime Media Demo

Two separate Laravel 12 apps power this demo:

- `admin-page`: dashboard for uploading images/videos. Every upload is stored on the public disk and broadcast via Reverb.
- `client-page`: display page that listens through Laravel Echo and shows the latest media in real time.

Both apps share the same database and public storage, so run the steps below in each project.

### Requirements

- PHP 8.2+, Composer
- Node.js 18+ and npm
- MySQL (or compatible) database named `sentuh-image-video`
- Laravel Reverb (installed via `php artisan reverb:install`)

### Environment Setup

1. In `admin-page`, copy `.env.example` to `.env` and configure:
   ```ini
   APP_URL=http://127.0.0.1:8000
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3307
   DB_DATABASE=sentuh-image-video
   DB_USERNAME=root
   DB_PASSWORD=

   BROADCAST_CONNECTION=reverb
   FILESYSTEM_DISK=public

   REVERB_APP_ID=790515
   REVERB_APP_KEY=mjnpntoysxidpovmv3px
   REVERB_APP_SECRET=nfudtqedje44ai1blhfm
   REVERB_HOST=127.0.0.1
   REVERB_PORT=8080
   REVERB_SCHEME=http
   ```

2. In `client-page`, do the same and ensure DB + Reverb credentials match:
   ```ini
   APP_URL=http://127.0.0.1:9001
   # DB_* same as admin
   BROADCAST_CONNECTION=reverb
   FILESYSTEM_DISK=public

   # REVERB_* same as admin
   MEDIA_BASE_URL=http://127.0.0.1:8000   # points to admin URL for serving storage files
   ```

3. After editing `.env`, run `php artisan config:clear` inside both projects.

### Running the Apps

Use separate terminals to keep long-running processes alive.

**Admin Page**

```bash
# terminal 1
php artisan serve --host=127.0.0.1 --port=8000

# terminal 2
npm run dev

# terminal 3 (start once and keep running)
php artisan reverb:start

# terminal 4 (needed because the event uses ShouldBroadcast)
php artisan queue:work
```

**Client Page**

```bash
# terminal 1
php artisan serve --host=127.0.0.1 --port=9001

# terminal 2
npm run dev
```

### Verification Flow

1. Open `http://127.0.0.1:8000`, upload an image/video from the admin dashboard.
2. Open `http://127.0.0.1:9001`, check the browser console to confirm Echo connects.
3. Every new upload should immediately replace the media on the client page without a manual refresh.

### Note

- **Media missing on client**: verify `php artisan storage:link` was run in admin and `MEDIA_BASE_URL` points to the admin URL serving `storage`.
