# Inventory Management System - Installation Guide

## Overview

This is a complete PHP-based inventory management system with user authentication, role-based access control, inventory tracking, equipment management, and comprehensive reporting features.

## System Requirements

- PHP 7.4 or higher
- MySQL/MariaDB 5.7 or higher
- Web server (Apache recommended)
- XAMPP/WAMP/MAMP (for local development)

## Features

### Core Functionality
- **User Authentication**: Login, logout, password reset, role-based access
- **Inventory Management**: Add, edit, delete, and track inventory items
- **Equipment Management**: Track equipment, assignments, maintenance
- **User Management**: Admin can manage users, roles, and permissions
- **Alert System**: Low stock alerts, maintenance due notifications
- **Activity Logging**: Comprehensive audit trail of all system activities
- **Reporting**: Export data to CSV, generate reports

### User Roles
1. **Superadmin**: Full system access
2. **Admin**: Manage users, inventory, equipment, reports
3. **Security**: View security-related information
4. **Maintenance**: Manage equipment maintenance
5. **Resident**: View assigned equipment
6. **External**: Limited access

## Installation Steps

### 1. Database Setup

1. Start your MySQL server (via XAMPP/WAMP control panel)
2. Open phpMyAdmin (http://localhost/phpmyadmin)
3. Create a new database named `inventory`
4. Import the SQL schema:

   ```sql
   -- Import the file: dev/inventory_schema.sql
   ```

   Or run the SQL commands manually from the schema file.

### 2. Configuration

1. Update database configuration in `app/config/config.php`:

   ```php
   // Database configuration
   define('DB_HOST', 'localhost:3307');  // Change port if needed
   define('DB_USER', 'root');
   define('DB_PASSWORD', '');
   define('DB_NAME', 'inventory');

   // Application paths
   define('APPROOT', dirname(dirname(__FILE__)));
   define('URLROOT', 'http://localhost/inventory');
   define('SITENAME', 'Inventory Management System');
   ```

2. Ensure the `.htaccess` file in the root directory contains:

   ```apache
   RewriteEngine On
   RewriteCond %{REQUEST_FILENAME} !-f
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteRule ^(.*)$ public/index.php?url=$1 [QSA,L]
   ```

3. Configure Apache mod_rewrite (usually enabled by default in XAMPP)

### 3. Directory Structure

Ensure your directory structure looks like this:

```
inventory/
├── app/
│   ├── config/
│   │   └── config.php
│   ├── controllers/
│   │   ├── Auth.php
│   │   ├── Pages.php
│   │   ├── Inventory.php
│   │   ├── EquipmentController.php
│   │   ├── UserController.php
│   │   ├── Alerts.php
│   │   └── Api.php
│   ├── models/
│   │   ├── User.php
│   │   ├── InventoryItem.php
│   │   ├── Equipment.php
│   │   ├── Category.php
│   │   ├── Alert.php
│   │   └── ActivityLog.php
│   ├── views/
│   │   ├── index.php
│   │   ├── inventory.php
│   │   ├── equipment.php
│   │   ├── alerts.php
│   │   ├── unauthorized.php
│   │   └── users/
│   ├── helpers/
│   │   ├── session_helper.php
│   │   └── url_helper.php
│   ├── libraries/
│   │   ├── Core.php
│   │   ├── Controller.php
│   │   └── Database.php
│   └── bootloader.php
├── public/
│   └── index.php
├── dev/
│   └── inventory_schema.sql
├── .htaccess
└── README.md
```

### 4. Default Login

After installation, you can login with the default superadmin account:

- **Username**: `admin`
- **Password**: `admin123`

**Important**: Change this password immediately after first login!

### 5. Database Upgrades & Seed Data

When you pull new code, check the `dev/migrations` folder for incremental SQL. Apply them in chronological order. For the single-admin update introduced in November 2025:

```bash
mysql -u root -p inventory < dev/migrations/20241112_remove_user_roles.sql
```

This migration removes the legacy `role` / `is_active` columns because the application now runs in single-admin mode. If you already changed the admin password, rerun the update step afterwards.

### 6. Manual Flow Verification

To verify the front‑to‑back flows (signup + activation, dashboard widgets, inventory/equipment CRUD, exports) follow the guided scenarios in `docs/manual_test_plan.md`. The document outlines the exact data to enter, messages to expect, and which role should perform every action.

### 7. Automated Tests

1. Install Composer dependencies:

   ```bash
   composer install
   ```

2. Run the growing PHPUnit suite (currently covering the RBAC/session helpers). The configuration lives in `phpunit.xml`.

   ```bash
   composer test
   ```

Feel free to add more tests under `tests/`—PSR‑4 autoloading is already configured.

## URL Structure

The system uses a custom URL routing system:

- `http://localhost/inventory/` → Dashboard (requires login)
- `http://localhost/inventory/auth/login` → Login page
- `http://localhost/inventory/auth/logout` → Logout
- `http://localhost/inventory/inventory` → Inventory management
- `http://localhost/inventory/equipment` → Equipment management
- `http://localhost/inventory/alerts` → Alerts (admin only)
- `http://localhost/inventory/usercontroller/profile` → User profile

## API Endpoints

For AJAX operations, use these API endpoints:

- `GET /api/getInventoryStats` → Inventory statistics
- `GET /api/getEquipmentStats` → Equipment statistics
- `GET /api/getAlerts` → User alerts
- `POST /api/quickInventoryUpdate` → Quick inventory update
- `GET /api/searchInventory?q=searchterm` → Search inventory
- `GET /api/exportInventory` → Export inventory data

## Security Considerations

1. **Change Default Password**: Immediately change the default admin password
2. **Database Security**: Use strong database passwords in production
3. **File Permissions**: Ensure proper file permissions on sensitive files
4. **HTTPS**: Use HTTPS in production environments
5. **Input Validation**: All inputs are sanitized using PHP filters
6. **Password Hashing**: Passwords are hashed using PHP's `password_hash()`

## Troubleshooting

### Common Issues

1. **404 Errors**: Check that mod_rewrite is enabled and `.htaccess` is working
2. **Database Connection**: Verify database credentials in `config.php`
3. **Session Issues**: Ensure PHP session path is writable
4. **Permission Errors**: Check file/folder permissions

### Debug Mode

To enable debug mode, add this to `app/config/config.php`:

```php
define('DEBUG_MODE', true);
```

This will display PHP errors instead of generic error messages.

## Development Notes

### MVC Architecture

The system follows MVC (Model-View-Controller) pattern:

- **Models**: Handle data logic and database interactions
- **Views**: Handle presentation and user interface
- **Controllers**: Handle user input and coordinate between models and views

### Adding New Features

1. Create a model in `app/models/`
2. Create a controller in `app/controllers/`
3. Create views in `app/views/`
4. Add routes in `app/libraries/Core.php` if needed

### Database Queries

All database queries use prepared statements to prevent SQL injection. The custom Database class provides a simple interface for common operations:

```php
// Example usage
$db = new Database();
$db->query('SELECT * FROM users WHERE id = :id');
$db->bind(':id', $userId);
$result = $db->single();
```

## Support

For issues or questions:
1. Check the troubleshooting section above
2. Review the code comments for detailed explanations
3. Check the browser developer console for JavaScript errors
4. Review server error logs for PHP errors

## License

This project is provided as-is for educational and development purposes.


