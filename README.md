# Electronics Store Management System

## Description
This project is a comprehensive **Electronics Store Management System** built with **Laravel 11** and **PHP 8.2.12**. It is designed for managing an electronics store's operations, including:

- Monitoring sales and user statistics.
- Adding, updating, and deleting products.
- Managing sales.
- User management and role control.

The system features an intuitive admin dashboard with sales analytics and a minimalistic design supporting both light and dark modes. Tailwind CSS is used for styling to ensure a modern and responsive UI.

## Features

### Admin Features
- **Dashboard**: Monitor sales statistics and store performance.
- **Product Management**: Add, edit, and delete products.
- **User Management**: Manage users with role-based access control.
- **Sales Management**: Record and track sales data.

### General Features
- **Responsive Design**: Optimized for all devices using Tailwind CSS.
- **Dark/Light Mode**: User-friendly interface with theme options.
- **Navigation**: 
  - Main screen navigation (`nav`).
  - Sidebar for system management.

## Technologies Used
- **Backend**: Laravel 11 (PHP 8.2.12).
- **Frontend**: Tailwind CSS.
- **Database**: phpMyAdmin/MySQL.

## Requirements
- PHP 8.2.12 or higher.
- Composer.
- MySQL or MariaDB.
- Laravel 11.
- Node.js (for Tailwind CSS integration).

## Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/liassasel/ventas-laravel.git
   cd electronics-store-management
   ```

2. Install dependencies:
   ```bash
   composer install
   npm install
   npm run build
   ```

3. Configure environment variables:
   - Duplicate the `.env.example` file and rename it to `.env`.
   - Set up your database credentials and other configuration variables.

4. Run migrations and seed the database:
   ```bash
   php artisan migrate --seed
   ```

5. Serve the application:
   ```bash
   php artisan serve
   ```

6. Open the application in your browser:
   - Default Laravel server: `http://localhost:8000`
   - Using XAMPP: `http://localhost:8037/ventas/public/`

## Usage
1. Log in as an administrator to access the dashboard.
2. Use the sidebar to navigate through different management modules:
   - **Products**: Add, edit, and remove products.
   - **Sales**: Monitor and record sales.
   - **Users**: Manage users and assign roles.

## Future Enhancements
- Integration with payment gateways.
- Customer-facing view for online product browsing and purchasing.
- Advanced analytics for sales trends.

## License
This project is licensed under the [MIT License](LICENSE).

## Copyright and Legal Notice
- **Copyright © liassasel/Aggelos.CA, 2024.**
- Unauthorized use, distribution, or modification of this software is strictly prohibited.
- This system is the intellectual property of liassasel/Aggelos.CA. Any use without explicit permission may result in legal action.

---

Feel free to contribute to the project by submitting issues or pull requests!
