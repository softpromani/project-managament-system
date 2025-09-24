# Project Management System

A Laravel-based project management system with role-based access control using Spatie Laravel Permission.

## Features

- User Authentication (Registration, Login, Logout)
- Role-based Access Control (Admin, Manager, User)
- Project Management (CRUD operations)
- Task Management with assignments
- Comments system
- Email notifications for task assignments
- API logging middleware
- Caching for performance
- Comprehensive test coverage

## Installation

1. Clone the repository
2. Install dependencies: `composer install`
3. Copy environment file: `cp .env.example .env`
4. Generate application key: `php artisan key:generate`
5. Configure database in `.env` file
6. Run migrations: `php artisan migrate`
7. Seed database: `php artisan db:seed`
8. Start queue worker: `php artisan queue:work`
9. Start development server: `php artisan serve`

## API Endpoints

### Authentication
- POST `/api/register` - User registration
- POST `/api/login` - User login
- POST `/api/logout` - User logout
- GET `/api/me` - Get current user

### Projects
- GET `/api/projects` - List projects
- GET `/api/projects/{id}` - Get project details
- POST `/api/projects` - Create project (Admin only)
- PUT `/api/projects/{id}` - Update project (Admin only)
- DELETE `/api/projects/{id}` - Delete project (Admin only)

### Tasks
- GET `/api/projects/{project_id}/tasks` - List project tasks
- GET `/api/tasks/{id}` - Get task details
- POST `/api/projects/{project_id}/tasks` - Create task (Manager only)
- PUT `/api/tasks/{id}` - Update task (Manager/Assigned user only)
- DELETE `/api/tasks/{id}` - Delete task (Manager only)

### Comments
- GET `/api/tasks/{task_id}/comments` - List task comments
- POST `/api/tasks/{task_id}/comments` - Add comment

## Testing

Run tests with: `php artisan test`

## Seeded Data

The system comes with pre-seeded data:
- 3 Admin users
- 3 Manager users
- 5 Regular users
- 5 Projects
- 10 Tasks
- 10 Comments

Default password for all seeded users: `password`
