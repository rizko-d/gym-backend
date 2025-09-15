# Gym Management System API

A comprehensive Laravel-based REST API for managing gym operations including member registration, class scheduling, trainer allocation, and attendance tracking.

## ✨ Features

### 🔐 Authentication & Member Management
- **Member Registration**: Complete sign-up system with validation
- **JWT Authentication**: Secure token-based authentication using Laravel Sanctum
- **Profile Management**: Update member information, membership types, and status
- **Membership Types**: Basic, Premium, and VIP membership tiers

### 📅 Class Scheduling System
- **Class Management**: Create and manage different types of gym classes
- **Schedule Creation**: Flexible scheduling with date/time management
- **Capacity Control**: Set maximum participants per class
- **Room Assignment**: Assign classes to specific rooms
- **Booking System**: Members can book and cancel class reservations

### 👨‍🏋️ Trainer Management
- **Trainer Profiles**: Complete trainer information with specializations
- **Availability Tracking**: Prevent double-booking conflicts
- **Specialization Tags**: Track trainer expertise areas
- **Rate Management**: Hourly rate tracking for trainers

### 📊 Attendance Tracking
- **Check-in/Check-out System**: Real-time attendance logging
- **Attendance History**: Comprehensive attendance reports
- **Duration Tracking**: Calculate workout session lengths
- **Status Updates**: Automatic status updates for class participation

## 🛠 Tech Stack

- **Framework**: Laravel 10.x
- **Language**: PHP 8.2+
- **Database**: PostgreSQL 13+
- **Authentication**: Laravel Sanctum
- **API**: RESTful API design
- **Validation**: Laravel Form Requests
- **ORM**: Eloquent ORM

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

- **PHP** >= 8.2
- **Composer** >= 2.0
- **PostgreSQL** >= 13.0
- **Git**

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/gym-management-system.git
cd gym-management-system
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Environment Configuration

```bash
cp .env.example .env
```

Edit the `.env` file with your database credentials:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=gym_management
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Install Laravel Sanctum

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

## 🗄️ Database Setup

### 1. Create Database

```sql
CREATE DATABASE gym_management;
```

### 2. Run Migrations

```bash
php artisan migrate
```

### 3. Seed Sample Data

```bash
php artisan db:seed --class=TrainerSeeder
php artisan db:seed --class=GymClassSeeder
```

### 4. Start Development Server

```bash
php artisan serve
```

The API will be available at `http://localhost:8000/api`

## 📚 API Documentation

### Authentication Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/register` | Register new member |
| POST | `/api/login` | Member login |
| POST | `/api/logout` | Member logout |

### Member Management

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/members` | Get all members | ✅ |
| POST | `/api/members` | Create new member | ✅ |
| GET | `/api/members/{id}` | Get specific member | ✅ |
| PUT | `/api/members/{id}` | Update member | ✅ |
| DELETE | `/api/members/{id}` | Delete member | ✅ |

### Class Scheduling

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/class-schedules` | Get class schedules | ✅ |
| POST | `/api/class-schedules` | Create new schedule | ✅ |
| GET | `/api/class-schedules/{id}` | Get specific schedule | ✅ |
| POST | `/api/class-schedules/{id}/book` | Book a class | ✅ |
| DELETE | `/api/class-schedules/{id}/cancel` | Cancel booking | ✅ |

### Attendance Management

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/api/attendance/check-in` | Check-in to class | ✅ |
| POST | `/api/attendance/check-out` | Check-out from class | ✅ |
| GET | `/api/attendance/history` | Get attendance history | ✅ |

### Example API Requests

#### Register a New Member

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john.doe@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "membership_type": "premium"
  }'
```

#### Book a Class

```bash
curl -X POST http://localhost:8000/api/class-schedules/1/book \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json"
```

## 🧪 Testing

### Using Postman

1. Import the provided Postman collection
2. Set up environment variables:
   - `base_url`: `http://localhost:8000/api`
   - `token`: (will be set automatically after login)

### Manual Testing

1. **Register a new member**
2. **Login and obtain token**
3. **Create trainers and gym classes**
4. **Schedule classes**
5. **Book classes as member**
6. **Test check-in/check-out system**

### Test Data

Use the included seeders to populate test data:

```bash
php artisan db:seed
```

## 📁 Project Structure

```
gym-management-system/
├── app/
│   ├── Http/Controllers/API/
│   │   ├── AuthController.php
│   │   ├── MemberController.php
│   │   ├── TrainerController.php
│   │   ├── GymClassController.php
│   │   ├── ClassScheduleController.php
│   │   └── AttendanceController.php
│   └── Models/
│       ├── Member.php
│       ├── Trainer.php
│       ├── GymClass.php
│       ├── ClassSchedule.php
│       ├── MemberClass.php
│       └── AttendanceLog.php
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   └── api.php
└── README.md
```

## 🚀 Deployment

### Production Setup

1. **Install dependencies**:
```bash
composer install --prefer-dist --no-dev -o
```

2. **Set environment**:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. **Set proper permissions**:
```bash
chmod -R 755 storage bootstrap/cache
```

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## 👥 Authors

- **Rizko Rachmayadi** - *Initial work* - [YourGitHub](https://github.com/rizko-d)

## 🙏 Acknowledgments

- Laravel Framework for the robust foundation
- Laravel Sanctum for authentication
- PostgreSQL for reliable data storage
- The open-source community for inspiration


**Built with ❤️ using Laravel and PostgreSQL**
