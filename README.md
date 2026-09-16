# LSP MKC - Backend API

![Laravel](https://img.shields.io/badge/Laravel-12.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)

Backend API repository for the **LSP MKC (Lembaga Sertifikasi Profesi MKC)** application. This project is built using Laravel 12 and serves as the core backend for handling assessee registrations, certification schemes, document management, and online exams.

## 🚀 Features

- **Authentication & Security:** Secure JWT-based authentication using Laravel Sanctum, including registration, login, email verification, and password reset.
- **Admin Dashboard:** Integrated with [Filament](https://filamentphp.com/) and Filament Shield for robust Role-Based Access Control (RBAC) and administrative management.
- **Assessee (Asesi) Management:** Comprehensive endpoints for profile management, scheme registrations, and education tracking.
- **Dynamic Form Handling:** Processing and state management for competency assessment forms including APL-01, APL-02, AK-01, AK-03, AK-04, and AK-07.
- **Document & Portfolio Management:** Upload, validate, and securely stream basic requirements and portfolio documents with AWS S3 storage support.
- **Online Exams & Signatures:** Dedicated endpoints for exam submissions and digital signature requests.
- **Payment & Invoices:** Registration payment tracking and simulation.
- **PDF Generation:** Automated PDF generation for APL-01 and APL-02 forms using `barryvdh/laravel-dompdf`.

## 🛠️ Prerequisites

Make sure you have the following installed on your local development machine:

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL (or preferred supported database)

## 📦 Installation & Setup

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd lsp-mkc-backend
   ```

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Install Node Dependencies**
   ```bash
   npm install
   ```

4. **Environment Configuration**
   Copy the example `.env` file and set up your database, mail, and AWS credentials.
   ```bash
   cp .env.example .env
   ```

5. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

6. **Run Migrations & Seeders**
   ```bash
   php artisan migrate
   ```
   *(If you have seeders, run `php artisan migrate --seed`)*

7. **Create Storage Link**
   Make the storage directory publicly accessible.
   ```bash
   php artisan storage:link
   ```

## 💻 Running the Application

For local development, you can use the built-in Composer script which concurrently runs the PHP server, Vite, and the queue worker:

```bash
composer run dev
```

Alternatively, you can run them separately:
```bash
php artisan serve
npm run dev
php artisan queue:listen
```

## 📚 API Architecture overview

- All API routes are located in `routes/api.php`
- Web routes (if any) are in `routes/web.php`
- Responses are typically formatted using Laravel Eloquent API Resources located in `app/Http/Resources`
- Controllers are primarily structured around the `Asesi` and `Auth` namespaces.

## 📝 License

This project is licensed under the MIT License.
