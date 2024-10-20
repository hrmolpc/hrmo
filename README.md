
**HRMS** is an open-source web application tailored to streamline employee management and HR processes within organizations.

This concise solution promotes effective workforce management and informed decision-making.

### Built With
* [Laravel](https://laravel.com)
* [Livewire](https://livewire.laravel.com)

## Getting Started

### Requirements
- PHP 8.1 or later.
- Composer.
- MySQL.

### Installation

1. Navigate to the project folder:
   
    ```bash
    cd HRMS

2. Install dependencies using Composer:
   
    ```bash
    composer install
3. Set up the database and necessary configurations:

    - Copy the `.env.example` to `.env` file in the root of your project.
      
    - Open the `.env` file in the root of your project.

    - Set the database connection details, including `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.
      
    - Set the `APP_TIMEZONE` to 'Asia/Istanbul' or whatever timezone you like.

4. Run the key generate command:
   
    ```bash
    php artisan key:generate

5. Run the storage link command:
   
    ```bash
    php artisan storage:link
6. Run the migration command with the seed flag to add some fake data:
   
    ```bash
    php artisan migrate --seed
7. Run the development server:
   
    ```bash
    php artisan serve
8. Open your browser and go to http://localhost:8000 to see the application.

    
### Usage
10. Login:
    
    ```bash
    email: admin@demo.com
    password: admin