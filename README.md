# Timesheet API

A Laravel RESTful API for managing users, projects, and timesheets with authentication and CRUD operations.

## Setup
1. **Clone**: `git clone https://github.com/<your-username>/<repository-name>.git`
2. **Install**: `composer install`
3. **Env**: Copy `.env.example` to `.env`, update DB settings, run `php artisan key:generate`
4. **Migrate & Seed**: `php artisan migrate --seed`
5. **Serve**: `php artisan serve` (runs at `http://localhost:8000/api`)

## Files
- **Postman Collection**: `Timesheet API Collection.postman_collection.json` (root folder)
- **Database**: `database/project_timesheet.sql`

## Features
- Models: User, Project, Timesheet
- Authentication: Laravel Sanctum (token-based)
- Endpoints: CRUD for all models with filtering
- Standardized Responses: `success`, `message`, `data`, `errors`

## API Usage
- **Auth**: `POST /register`, `POST /login`, `POST /logout`
- **Users**: `POST /users`, `GET /users/{id}`, `GET /users`, `POST /users/update`, `POST /users/delete`
- **Projects**: `POST /projects`, `GET /projects/{id}`, `GET /projects`, `POST /projects/update`, `POST /projects/delete`
- **Timesheets**: `POST /timesheets`, `GET /timesheets/{id}`, `GET /timesheets`, `POST /timesheets/update`, `POST /timesheets/delete`

### Sample User
- **Email**: `admin@example.com`
- **Password**: `password123`

## Testing
Import the Postman collection and set `base_url` to `http://localhost:8000/api`.
