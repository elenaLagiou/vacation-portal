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

## Prerequisites

Before you begin, ensure you have the following installed:
- PHP (>= 8.1)
- Composer
- MySQL

## Installation and Setup

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/elagiou/vacation-portal.git
    cd vacation-portal
    ```

2.  **Install dependencies:**
    ```bash
    composer install
    ```

3.  **Set up your environment:**
    - Create a `.env` file by copying `.env.example`:
      ```bash
      cp .env.example .env
      ```
    - Open the `.env` file and update the database credentials (`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) to match your local MySQL setup.

4.  **Set up the database:**
    - Make sure your MySQL server is running.
    - Create the database you specified in the `.env` file.
    - Run the migrations and seeders to create the necessary tables and populate them with initial data:
      ```bash
      ./cli db:migrate
      ```

## Usage

To run the application, use the `serve` command:

```bash
./cli serve
```

The application will be available at `http://localhost:8080`.

### Available Commands

| Command | Description |
|---|---|
| `./cli serve` | Starts the development server. |
| `./cli db` | Runs the database migrations and seeders. |

## Main Dependencies

- [nikic/fast-route](https://github.com/nikic/FastRoute) for routing.
- [symfony/http-foundation](https://symfony.com/doc/current/components/http_foundation.html) for handling HTTP requests and responses.
- [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) for managing environment variables.
- [spatie/data-transfer-object](https://github.com/spatie/data-transfer-object) for Data Transfer Objects.
- [respect/validation](https://github.com/Respect/Validation) for validation.
- [monolog/monolog](https://github.com/Seldaek/monolog) for logging.
- [phpmailer/phpmailer](https://github.com/PHPMailer/PHPMailer) for sending emails.