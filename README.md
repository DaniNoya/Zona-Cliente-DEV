# Zona Cliente - Client Management Portal

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

## 📋 Overview

Zona Cliente is a comprehensive client management portal built with Laravel and AdminLTE. It provides a robust platform for managing users, leisure centers (centros ocio), providers, products, and orders. The application features role-based access control, user profile management, and a modern, responsive interface.

## ✨ Features

- **User Management**
  - Role-based access control (Admin, User, etc.)
  - User status tracking (Active, Pending, Inactive)
  - Profile picture management
  - Secure password management
  - Location-based user assignment

- **Provider Management**
  - Track provider details (name, location, contact info)
  - Provider status monitoring
  - Rating system for providers
  - View top products by provider

- **Leisure Centers (Centros Ocio)**
  - Manage leisure center information
  - Track center locations and contact details

- **Product Catalog**
  - Categorized product listings
  - Filter products by category, brand, and other attributes
  - Product details and availability

- **Order Management**
  - Track order status (Delivered, In Transit, Processing, Cancelled)
  - Order history and details

- **Modern UI with AdminLTE**
  - Responsive dashboard
  - Clean and intuitive interface
  - Data tables with sorting and filtering
  - Interactive charts and statistics

## 🔧 Requirements

- PHP 8.2+
- Composer
- Laravel 12.x
- Node.js & NPM (for frontend assets)
- Database (MySQL, PostgreSQL, or SQLite)

## 🚀 Installation

### Step 1: Clone the repository

```bash
git clone https://github.com/your-username/Zona-Cliente-DEV.git
cd Zona-Cliente-DEV
```

### Step 2: Install dependencies

```bash
composer install
npm install
npm run build
```

### Step 3: Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit the `.env` file to configure your database connection and other settings.

### Step 4: Run migrations and seeders

```bash
php artisan migrate
php artisan db:seed
```

### Step 5: Start the development server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser to access the application.

## 🏗️ Project Structure

- **app/Models**: Contains all the data models (User, Provider, CentroOcio, etc.)
- **app/Http/Controllers**: Contains controllers for handling requests
- **resources/views**: Contains Blade templates for the UI
- **database/migrations**: Database structure definitions
- **database/seeders**: Sample data for development
- **public/assets**: Static assets like images and 3D models
- **config/adminlte.php**: AdminLTE configuration

## 🔐 Authentication

The application uses Laravel's built-in authentication system with customizations for role-based access. Default users are created through the seeders:

- Admin: admin@example.com (password: password)
- User: user@example.com (password: password)

## 🧩 Key Components

### Models

- **User**: Represents system users with roles and permissions
- **Role**: Defines user roles (Admin, User, etc.)
- **Status**: Tracks status of various entities (Active, Inactive, etc.)
- **Location**: Represents physical locations for users and providers
- **Provider**: Represents service providers with contact details and ratings
- **CentroOcio**: Represents leisure centers with location and contact info

### Controllers

- **UserController**: Handles user management, profile updates, and authentication
- **HomeController**: Manages dashboard and home page content

### Views

- **home.blade.php**: Dashboard with order statistics and summaries
- **profile.blade.php**: User profile management
- **users.blade.php**: User management for administrators
- **providers.blade.php**: Provider management interface
- **products.blade.php**: Product catalog and management
- **pedidos.blade.php**: Order management and tracking

## 🎨 Frontend Assets

The application includes several 3D models and images for product visualization:

- 3D models: PS4, Xbox, TV, Bike
- Logos and branding assets
- Default profile pictures

## 🔄 Development Workflow

1. Make changes to the codebase
2. Run tests: `php artisan test`
3. Build assets if needed: `npm run build`
4. Commit changes with descriptive messages

## 📚 Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [AdminLTE Documentation](https://adminlte.io/docs/3.1/)

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 👥 Contributors

- Your Name - Initial work and maintenance

---

<p align="center">Built with ❤️ using Laravel and AdminLTE</p>
