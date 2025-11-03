# Vacation Portal

A simple employee vacation request portal built with PHP.

## Features

### Employee
- Login
- View their vacation requests
- Submit new vacation requests

### Manager
- Login
- View all vacation requests from their team
- Approve or reject vacation requests
- Create, update, and delete users

## Installation and Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/elagiou/vacation-portal.git
   cd vacation-portal
   ```

2. **Install dependencies:**
   ```bash
   composer install
   ```

3. **Set up your environment:**
   - Create a `.env` file by copying `.env.example`.
   - Update the `.env` file with your database credentials.

4. **Set up the database:**
   - Run the migrations to create the necessary tables.
   ```bash
   php cli.php migrate
   ```
   - Run the seeders to populate the database with initial data.
   ```bash
   php cli.php db:seed
   ```

## Usage

To run the application, use the `serve` command:

```bash
php cli.php serve
```

The application will be available at `http://localhost:8080`.

### Available Commands

| Command         | Description                                       |
|-----------------|---------------------------------------------------|
| `php cli.php serve`     | Starts the development server.                    |
| `php cli.php migrate`   | Runs the database migrations.                     |
| `php cli.php db:seed`   | Runs the database seeders.                        |

## Main Dependencies

- [nikic/fast-route](https://github.com/nikic/FastRoute) for routing.
- [symfony/http-foundation](https://symfony.com/doc/current/components/http_foundation.html) for handling HTTP requests and responses.
- [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) for managing environment variables.
- [spatie/data-transfer-object](https://github.com/spatie/data-transfer-object) for Data Transfer Objects.
- [respect/validation](https://github.com/Respect/Validation) for validation.
- [monolog/monolog](https://github.com/Seldaek/monolog) for logging.
- [phpmailer/phpmailer](https://github.com/PHPMailer/PHPMailer) for sending emails.
