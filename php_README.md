# ReviewHub - Company Review Platform

A comprehensive PHP-based web application for reviewing companies, built with a custom MVC architecture. Users can browse companies, leave reviews, search by tags, and administrators can manage all content through a dedicated admin panel.

## Features

### User Features
- **Company Browsing**: View all companies with ratings and reviews
- **Company Search**: Search companies by name or description
- **Tag Filtering**: Filter companies by tags with color-coded badges
- **Review System**: Leave reviews with ratings (1-5 stars), titles, and detailed feedback
- **User Authentication**: Register, login, and manage your profile
- **User Profiles**: View your own reviews and manage account settings
- **Anonymous Reviews**: Option to leave reviews without an account

### Admin Features
- **Dashboard**: Overview with statistics and recent feedback
- **Company Management**: Create, edit, and delete companies with tag assignment
- **User Management**: Manage users, change roles, delete accounts
- **Review Management**: View and moderate all reviews
- **Tag Management**: Create and manage tags with custom colors
- **Feedback Viewing**: View all contact form submissions

### API Features
- **RESTful API**: Complete API for all platform features
- **Authentication**: Token-based authentication system
- **Company Operations**: Browse, search, create, update, and delete companies
- **Review Operations**: Create, read, and delete reviews
- **Tag Management**: Full CRUD operations for tags
- **User Management**: User operations including admin functions
- **CORS Support**: Cross-origin requests supported for frontend integration

## Tech Stack

- **Backend**: PHP 8.x
- **Database**: MySQL 8.0
- **Web Server**: Apache with mod_rewrite
- **Containerization**: Docker & Docker Compose
- **Database Management**: Adminer (optional)
- **Architecture**: Custom MVC pattern with Load class for dependency management
- **API**: RESTful API with token-based authentication and CORS support

## Project Structure

```
php/
├── php_src/
│   ├── assets/
│   │   ├── css/          # Stylesheets (modular CSS files)
│   │   │   ├── admin.css
│   │   │   ├── auth.css
│   │   │   ├── components.css
│   │   │   ├── footer.css
│   │   │   ├── header.css
│   │   │   ├── home.css
│   │   │   ├── layout.css
│   │   │   ├── legal.css
│   │   │   ├── popup.css
│   │   │   ├── reset.css
│   │   │   └── responsive.css
│   │   └── js/           # JavaScript files
│   │       └── popup.js
│   ├── api/             # API controllers and routing
│   │   ├── controller/  # API controllers (ReviewApi, UserApi, LegalApi)
│   │   ├── controllerApi.php  # Base API controller
│   │   ├── routherApi.php     # API router
│   │   ├── validationApi.php  # Token validation
│   │   └── responceApi.php    # API response handler
│   ├── controller/       # Controllers (Home, Review, User, Admin, Legal)
│   ├── helper/          # Core classes
│   │   ├── controller.php    # Base Controller class
│   │   ├── database.php      # Database abstraction layer
│   │   ├── layout.php        # Layout rendering
│   │   ├── load.php          # Load class for models/views
│   │   ├── model.php         # Base Model class
│   │   └── routher.php       # Custom router
│   ├── model/           # Database models
│   │   ├── company.php
│   │   ├── companytag.php
│   │   ├── feedback.php
│   │   ├── review.php
│   │   ├── tag.php
│   │   └── user.php
│   ├── view/            # View templates
│   │   ├── admin/       # Admin panel views
│   │   ├── layout/      # Header and footer
│   │   ├── legal/       # Legal pages
│   │   ├── review/      # Review-related views
│   │   └── user/        # User-related views
│   ├── .htaccess        # URL rewriting rules
│   ├── Dockerfile       # PHP/Apache container config
│   └── index.php        # Application entry point
├── database/
│   ├── schematic.sql    # Database schema (DDL)
│   └── seed.sql         # Test data (DML)
├── docker-compose.yml   # Docker services configuration
├── .env                 # Environment variables
└── README.md            # This file
```

## Architecture

### Custom MVC Pattern

The application uses a custom MVC architecture with the following components:

- **Router** (`helper/routher.php`): Handles URL routing and dispatches requests to controllers
- **Controller** (`helper/controller.php`): Base controller with common functionality
- **Load** (`helper/load.php`): Manages loading of models and views with automatic data reference
- **Model** (`helper/model.php`): Base model for database operations
- **Database** (`helper/database.php`): PDO-based database abstraction layer
- **Layout** (`helper/layout.php`): Handles view rendering with header/footer

### Key Design Patterns

1. **Load Class**: Centralized dependency management
   - Automatically references controller's `$data` array
   - Handles model loading with automatic file resolution
   - Simplifies view rendering

2. **Router**: Custom URL routing
   - Maps URLs to controller methods
   - Handles parameters and 404 errors
   - Supports clean URLs via `.htaccess`

3. **Model-View-Controller**: Separation of concerns
   - Controllers handle business logic
   - Models handle data access
   - Views handle presentation

## Installation

### Prerequisites

- Docker and Docker Compose installed
- Git (for cloning the repository)

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/aleksmilev/simple_review_site.git
   cd simple_review_site
   ```

2. **Configure environment variables**
   
   Create a `.env` file in the root directory:
   ```env
   MYSQL_ROOT_PASSWORD=root
   MYSQL_DATABASE=app_db
   MYSQL_USER=app_user
   MYSQL_PASSWORD=secret
   ```

3. **Start Docker containers**
   ```bash
   docker-compose up -d
   ```

4. **Initialize the database**
   
   Access the MySQL container or use Adminer:
   - Adminer: http://localhost:8081
   - MySQL: localhost:3306
   
   Import the database schema and seed data:
   ```bash
   # Option 1: Using Docker exec
   docker exec -i mysql_db mysql -uroot -proot app_db < database/schematic.sql
   docker exec -i mysql_db mysql -uroot -proot app_db < database/seed.sql
   
   # Option 2: Using Adminer web interface
   # Navigate to http://localhost:8081 and import the SQL files
   ```

5. **Access the application**
   
   - Main Application: http://localhost:8080
   - Adminer (Database UI): http://localhost:8081

## Default Credentials

### Admin User
- **Username**: `admin`
- **Password**: `password`
- **Role**: Admin

### Regular User
- **Username**: `john_doe`
- **Password**: `password`
- **Role**: User

### Additional Test Users
- **Username**: `admin_sarah` (Admin)
- **Username**: `jane_smith` (User)
- **Username**: `mike_jones` (User)
- **Password for all users**: `password`

> **Note**: These are test credentials from the seed data. Change them in production!

## Usage

### For Regular Users

1. **Browse Companies**: Navigate to `/review/company` to see all companies
2. **Search**: Use the search bar in the header to find companies
3. **Filter by Tags**: Click on tags to filter companies
4. **Leave Reviews**: Click on a company to view details and leave a review
5. **Create Account**: Register at `/user/register` to track your reviews
6. **Manage Profile**: Access your profile at `/user/profile` (requires login)

### For Administrators

1. **Access Admin Panel**: Navigate to `/admin` (requires admin role)
2. **Manage Companies**: Create, edit, or delete companies at `/admin/companies`
3. **Manage Users**: View and manage users at `/admin/users`
4. **Manage Reviews**: View and delete reviews at `/admin/reviews`
5. **Manage Tags**: Create and manage tags at `/admin/tags`
6. **View Feedback**: Check contact form submissions at `/admin/feedback`

## URL Structure

- `/home` - Homepage
- `/review` - All reviews
- `/review/company` - All companies
- `/review/company/{id}` - Single company with reviews
- `/review/search` - Search companies
- `/review/search?tag={id}` - Filter by tag
- `/user/login` - Login page
- `/user/register` - Registration page
- `/user/profile` - User profile (requires login)
- `/user/reviews` - User's reviews (requires login)
- `/admin` - Admin dashboard (requires admin role)
- `/admin/companies` - Manage companies
- `/admin/company/create` - Create company
- `/admin/company/edit/{id}` - Edit company
- `/admin/users` - Manage users
- `/admin/reviews` - Manage reviews
- `/admin/tags` - Manage tags
- `/admin/tag/create` - Create tag
- `/admin/tag/edit/{id}` - Edit tag
- `/admin/feedback` - View feedback

## API Documentation

The application provides a RESTful API for programmatic access to all features. All API endpoints are prefixed with `/api/{controller}/{method}`.

### Base URL

```
http://localhost:8080/api
```

### Authentication

Most API endpoints require authentication using Bearer tokens. To authenticate:

1. **Login** via `/api/user/login` with username and password
2. Receive a **token** in the response
3. Include the token in subsequent requests:
   - **Header**: `Authorization: Bearer {token}`
   - **Query Parameter**: `?token={token}` (alternative)

**Admin endpoints** require an admin role in addition to authentication.

### API Endpoints

#### User API (`/api/user/`)

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `login` | No | Login and receive authentication token |
| POST | `register` | No | Register a new user account |
| GET | `user` | Yes | Get current user information |
| GET | `reviews` | Yes | Get current user's reviews |
| POST | `changePassword` | Yes | Change user password |
| POST | `changeEmail` | Yes | Change user email |
| GET | `getAllUsers` | Admin | Get all users with review counts |
| POST | `changeUserRole` | Admin | Change a user's role |
| POST | `deleteUser` | Admin | Delete a user account |

**Example Login Request:**
```json
POST /api/user/login
{
  "username": "admin",
  "password": "password"
}
```

**Example Response:**
```json
{
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
}
```

#### Review API (`/api/review/`)

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| GET | `listCompany` | No | Get all companies with ratings |
| POST | `getCompany` | No | Get company details with reviews and tags |
| GET | `getReview` | No | Get all reviews grouped by company |
| POST | `postReview` | Optional | Create a new review (anonymous if no auth) |
| GET | `getTags` | No | Get all tags |
| POST | `searchByCompany` | No | Search companies by query |
| POST | `searchByTag` | No | Get companies by tag ID |
| POST | `deleteReview` | Admin | Delete a review |
| POST | `createCompany` | Admin | Create a new company |
| POST | `updateCompany` | Admin | Update company information |
| POST | `deleteCompany` | Admin | Delete a company |
| POST | `createTag` | Admin | Create a new tag |
| POST | `updateTag` | Admin | Update tag information |
| POST | `deleteTag` | Admin | Delete a tag |

**Example: Get Company Details**
```json
POST /api/review/getCompany
{
  "id": 1
}
```

**Example: Create Review**
```json
POST /api/review/postReview
Authorization: Bearer {token}
{
  "company_id": 1,
  "rating": 5,
  "title": "Great company!",
  "content": "Excellent service and products."
}
```

**Example: Search Companies**
```json
POST /api/review/searchByCompany
{
  "query": "tech"
}
```

**Example: Create Company (Admin)**
```json
POST /api/review/createCompany
Authorization: Bearer {admin_token}
{
  "name": "New Company",
  "description": "Company description",
  "website": "https://example.com",
  "slug": "new-company",
  "tags": [1, 2, 3]
}
```

#### Legal API (`/api/legal/`)

| Method | Endpoint | Auth | Description |
|--------|----------|------|-------------|
| POST | `form` | No | Submit contact/feedback form |
| GET | `list` | Admin | Get all feedback submissions |

**Example: Submit Feedback**
```json
POST /api/legal/form
{
  "name": "John Doe",
  "email": "john@example.com",
  "subject": "Question",
  "message": "I have a question about..."
}
```

### Response Format

All API responses are in JSON format:

**Success Response:**
```json
{
  "message": "Operation successful",
  "data": { ... }
}
```

**Error Response:**
```json
{
  "error": "Error message description"
}
```

### HTTP Status Codes

- `200` - Success
- `201` - Created (for POST requests that create resources)
- `400` - Bad Request (invalid input or validation error)
- `401` - Unauthorized (authentication required or invalid token)
- `404` - Not Found (resource doesn't exist)
- `405` - Method Not Allowed (wrong HTTP method)

### Error Handling

The API uses consistent error responses:

- **400 Bad Request**: Invalid payload or validation errors
- **401 Unauthorized**: Missing or invalid authentication token, or insufficient permissions
- **404 Not Found**: Resource not found
- **405 Method Not Allowed**: HTTP method not allowed for endpoint

**Example Error Response:**
```json
{
  "error": "Company not found"
}
```

### CORS Support

The API supports Cross-Origin Resource Sharing (CORS) for frontend integration. CORS headers are automatically set for all API requests.

## Database Schema

The application uses the following main tables:

- **users**: User accounts and authentication
- **companies**: Company information
- **reviews**: User reviews for companies
- **tags**: Tags for categorizing companies
- **company_tags**: Many-to-many relationship between companies and tags
- **feedback**: Contact form submissions

See `database/schematic.sql` for the complete schema.

## Acknowledgments

- Built with PHP and MySQL
- Uses Docker for containerization
- Custom MVC architecture for flexibility and learning

