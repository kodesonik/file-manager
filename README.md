# Laravel File Manager API

A robust file management system built with Laravel that provides secure file upload, storage, and management capabilities through a RESTful API.

## Technologies Used

- PHP 8.0.3
- Laravel 9.x
- Voyager Admin Panel 1.6
- SQLite Database
- Intervention Image (for image compression)

## Features

- Single and multiple file uploads
- Image compression and resizing
- Project-based file organization
- API key authentication
- Role-based access control
- Automatic folder creation
- Secure file deletion
- Admin panel for project management

## Requirements

- PHP >= 8.0.3
- Composer
- SQLite
- PHP GD Library (for image manipulation)

## Installation

1. Clone the repository:

```bash
git clone <repository-url>
cd <project-folder>
```

2. Install dependencies:
```bash
composer install
```

3. Create environment file:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Create SQLite database:
```bash
touch database/database.sqlite
```

6. Configure database in .env:
```env
DB_CONNECTION=sqlite
```

7. Install Dependencies:
```bash
composer install
```

8. Run migrations and seed the database:
```bash
php artisan migrate
php artisan db:seed
```
or 
```bash
php artisan migrate:fresh --seed
```

9. Create storage link:
```bash
php artisan storage:link
```

## API Documentation

Detailed API documentation is available at two locations:

- `GET /` - Interactive HTML documentation
- `GET /api` - JSON documentation

### API Endpoints

- `POST /api/upload` - Single file upload
- `POST /api/upload-multiple` - Multiple files upload
- `POST /api/upload-multiple-compressed` - Multiple files upload with image compression
- `DELETE /api/files` - File deletion

### Admin Panel

The admin panel is accessible at:

- `GET /admin` - Voyager admin interface

Default admin credentials:
```
Email: admin@admin.com
Password: password
```

## Project Structure

- `app/Http/Controllers/Api/FileController.php` - API endpoints implementation
- `app/Http/Controllers/Admin/ProjectController.php` - Admin panel customizations
- `app/Http/Middleware/ValidateProjectApiKey.php` - API authentication
- `app/Models/Project.php` - Project model with relationships
- `resources/views/welcome.blade.php` - API documentation view

## Features in Detail

### File Upload
- Supports any input name as subfolder
- Automatic timestamp-based file naming
- Configurable max file size (default 10MB)
- Supports nested folder structures

### Image Compression
- Automatic image resizing
- Configurable quality settings
- Maintains aspect ratio
- Supports multiple image formats (JPG, PNG, GIF, WebP)

### Security
- API key authentication
- Project-based access control
- Secure file path validation
- Protected storage access

### Admin Panel
- Project management (BREAD operations)
- User assignment to projects
- API key generation

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details