# Simple Notes Application

This is a simple notes application built with Laravel and Tailwind CSS as part of a developer assessment.

## Features

- User Authentication (Login, Register, Logout) using breeze -blade
- A professional Dashboard with user stats and recent activity.
- Complete CRUD functionality for notes (Create, Read, Update, Delete).
- Advanced filtering with search and sort capabilities.
- A custom-built rich text editor using AlpineJS.
- Modern UI/UX with responsive design interactive modals.
- Comprehensive feature tests covering all core functionalities.

---

## Setup Instructions

To run this project locally, please follow these steps:

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/kiran-1724/notes_application.git
    cd notes_application
    ```

2.  **Install dependencies:**
    ```bash
    composer install
    npm install
    ```

3.  **Setup environment file:**
    ```bash
    cp .env.example .env
    ```
    *Open the `.env` file and configure your database connection (DB_DATABASE, DB_USERNAME, DB_PASSWORD).*

4.  **Generate application key:**
    ```bash
    php artisan key:generate
    ```

5.  **Run database migrations:**
    ```bash
    php artisan migrate
    ```

6.  **Build frontend assets:**
    ```bash
    npm run build
    ```

7.  **Run the development server:**
    ```bash
    php artisan serve
    ```
    The application will be available at `http://127.0.0.1:8000`.

---

## Running Tests

To run the feature tests, use the following command:

```bash
php artisan test
