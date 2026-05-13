# Newsletter Application

A Laravel-based bulk newsletter sending application with queue-driven email dispatch, real-time progress tracking, and scheduled delivery support.

## Features

- Create, edit, and delete newsletters
- Bulk email sending to all registered users via Laravel Queues
- Real-time sending progress tracking (sent count / total recipients)
- Scheduled delivery support (`scheduled_at`)
- Status lifecycle: `pending` → `start` → `started` → `completed`
- Per-recipient delivery tracking (`newsletter_recipients` table)
- Atomic dispatch to prevent duplicate sends

---

## Requirements

| Requirement | Version  |
|-------------|----------|
| PHP         | >= 8.3   |
| Laravel     | ^13.7    |
| Composer    | >= 2.x   |
| Node.js     | >= 18.x  |
| npm         | >= 9.x   |
| Database    | MySQL / PostgreSQL / SQLite |
| Queue driver| `database` (default) or Redis |

---

## Project Setup — Step by Step

### Step 1 — Clone the Repository

```bash
git clone https://github.com/devangdataaro/newsletters.git
cd newsletters
```

---

### Step 2 — Install PHP Dependencies

```bash
composer install
```

---

### Step 3 — Create the Environment File

```bash
cp .env.example .env
```

---

### Step 4 — Generate the Application Key

```bash
php artisan key:generate
```

---

### Step 5 — Configure the Database

Open `.env` and set your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=newsletters
DB_USERNAME=root
DB_PASSWORD=your_password
```

> For SQLite, set `DB_CONNECTION=sqlite` and create the file: `touch database/database.sqlite`

---

### Step 6 — Configure the Mail Driver

In `.env`, set your mail provider. Example for SMTP:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=no-reply@example.com
MAIL_FROM_NAME="Newsletter App"
```

> To test locally without sending real emails, use `MAIL_MAILER=log` (emails are written to `storage/logs/laravel.log`).

---

### Step 7 — Configure the Queue Driver

In `.env`, set:

```env
QUEUE_CONNECTION=database
```

---

### Step 8 — Run Database Migrations

```bash
php artisan migrate
```

This creates the following tables:
- `users`
- `newsletters`
- `newsletter_recipients`
- `jobs` (queue table)
- `cache`

---

### Step 9 — Seed the Database (Optional)

Seed sample users to have recipients available for testing:

```bash
php artisan db:seed --class=UserSeeder
```

---

### Step 10 — Install Frontend Dependencies

```bash
npm install
```

---

### Step 11 — Build Frontend Assets

For development (with hot reload):

```bash
npm run dev
```

For production:

```bash
npm run build
```

---

### Step 12 — Start the Application

In separate terminal windows, run:

**Web server:**
```bash
php artisan serve
```

**Queue worker** (required for sending emails):
```bash
php artisan queue:work
```

The application will be available at: `http://127.0.0.1:8000`

---

### Quick Setup (All in One)

Alternatively, run the built-in setup script:

```bash
composer run setup
```

Then start everything together:

```bash
composer run dev
```

---

## Application Routes

| Method | URL                               | Description                        |
|--------|-----------------------------------|------------------------------------|
| GET    | `/`                               | Redirects to newsletter list       |
| GET    | `/newsletters`                    | List all newsletters               |
| GET    | `/newsletters/create`             | Create newsletter form             |
| POST   | `/newsletters`                    | Store new newsletter               |
| GET    | `/newsletters/{id}`               | View newsletter details            |
| GET    | `/newsletters/{id}/edit`          | Edit newsletter form               |
| PUT    | `/newsletters/{id}`               | Update newsletter                  |
| DELETE | `/newsletters/{id}`               | Delete newsletter                  |
| POST   | `/newsletters/{id}/start`         | Start sending the newsletter       |
| GET    | `/newsletters/{id}/progress`      | Get sending progress (JSON)        |

---

## Newsletter Status Lifecycle

```
pending  →  start  →  started  →  completed
```

| Status      | Description                                      |
|-------------|--------------------------------------------------|
| `pending`   | Created, not yet dispatched                      |
| `start`     | Dispatch triggered by the user                   |
| `started`   | Queue worker is actively sending emails          |
| `completed` | All recipients have been processed               |

---

## Running Tests

```bash
php artisan test
```

---

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
