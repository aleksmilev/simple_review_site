# ReviewHub - Company Review Platform

A full-stack web application for reviewing companies, featuring a PHP backend with a custom MVC architecture and a modern React frontend. Users can browse companies, leave reviews, search by tags, and administrators can manage all content through dedicated admin panels. The platform supports both traditional server-rendered pages and a modern single-page application (SPA) interface.

## Features

### User Features
- **Company Browsing**: View all companies with ratings and reviews in responsive layouts
- **Company Search**: Search companies by name or description with real-time results
- **Tag Filtering**: Filter companies by tags with color-coded badges
- **Review System**: Leave reviews with ratings (1-5 stars), titles, and detailed feedback
- **User Authentication**: Register, login, and manage your profile with secure token-based authentication
- **User Profiles**: View your own reviews and manage account settings
- **Anonymous Reviews**: Option to leave reviews without an account
- **Dynamic Page Titles**: Automatic page title updates (React frontend)

### Admin Features
- **Dashboard**: Overview with statistics and recent feedback
- **Company Management**: Create, edit, and delete companies with tag assignment
- **User Management**: Manage users, change roles, delete accounts (with self-protection in React)
- **Review Management**: View and moderate all reviews
- **Tag Management**: Create and manage tags with custom colors and descriptions
- **Feedback Viewing**: View all contact form submissions
- **Route Protection**: Automatic redirect to login for unauthorized admin access (React)

### API Features
- **RESTful API**: Complete API for all platform features
- **Authentication**: Token-based authentication system
- **Company Operations**: Browse, search, create, update, and delete companies
- **Review Operations**: Create, read, and delete reviews
- **Tag Management**: Full CRUD operations for tags
- **User Management**: User operations including admin functions
- **CORS Support**: Cross-origin requests supported for frontend integration

### Technical Features
- **Single Page Application**: Fast navigation without page reloads (React frontend)
- **Dynamic Routing**: URL-based component loading with React Router
- **API Integration**: RESTful API communication with token-based authentication
- **Responsive Design**: Mobile-friendly interface with modern CSS
- **Component-Based Architecture**: Reusable React components
- **State Management**: Component-level state management with React hooks and class components

## Tech Stack

### Backend
- **Backend**: PHP 8.x
- **Database**: MySQL 8.0
- **Web Server**: Apache with mod_rewrite
- **Containerization**: Docker & Docker Compose
- **Database Management**: Adminer (optional)
- **Architecture**: Custom MVC pattern with Load class for dependency management
- **API**: RESTful API with token-based authentication and CORS support

### Frontend
- **Frontend Framework**: React 19.2.0
- **Build Tool**: Vite 7.3.1
- **Routing**: React Router DOM 7.13.1
- **Styling**: CSS Modules (organized in `src_react/style/`)
- **Development**: ESLint, Hot Module Replacement (HMR)

## Project Structure

```
ReviewHub/
├── src_php/              # PHP Backend (MVC Architecture)
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
├── src_react/           # React Frontend (SPA)
│   ├── assets/         # React assets
│   │   └── react.svg
│   ├── components/     # React components
│   │   ├── common/     # Reusable components
│   │   │   ├── Form/   # Form component
│   │   │   ├── NotFound/ # 404 page component
│   │   │   └── Popup/  # Popup/modal component
│   │   ├── layout/     # Layout components
│   │   │   ├── header.jsx    # Site header with navigation
│   │   │   ├── footer.jsx    # Site footer
│   │   │   └── Layout.jsx    # Main layout wrapper
│   │   └── pages/      # Page components
│   │       ├── admin/  # Admin panel pages
│   │       ├── home/   # Homepage
│   │       ├── legal/  # Legal pages
│   │       ├── review/ # Review-related pages
│   │       └── user/   # User-related pages
│   ├── routing/        # Routing logic
│   │   └── ActiveComponent.jsx  # Dynamic component loader
│   ├── services/       # Service modules
│   │   ├── api.js              # API request handler
│   │   ├── user.js             # User authentication service
│   │   ├── token.js            # Token management
│   │   ├── encryption.js       # Encryption utilities
│   │   └── withRouter.jsx      # Router HOC
│   ├── style/          # Stylesheets
│   │   ├── admin.css      # Admin panel styles
│   │   ├── auth.css       # Authentication pages
│   │   ├── company.css    # Company pages
│   │   ├── contact.css    # Contact form
│   │   ├── footer.css     # Footer styles
│   │   ├── Form.css       # Form components
│   │   ├── header.css     # Header styles
│   │   ├── home.css       # Homepage styles
│   │   ├── layout.css     # Layout styles
│   │   ├── legal.css      # Legal pages
│   │   ├── NotFound.css   # 404 page
│   │   ├── Popup.css      # Popup/modal
│   │   ├── profile.css    # User profile
│   │   └── reviews.css    # Review pages
│   ├── apiSchematic.json # API endpoint definitions
│   ├── App.jsx          # Root component
│   ├── main.jsx         # Application entry point
│   └── index.css        # Global styles
├── database/
│   ├── schematic.sql    # Database schema (DDL)
│   └── seed.sql         # Test data (DML)
├── public/              # Static assets (React)
│   └── vite.svg
├── docker-compose.yml   # Docker services configuration
├── index.html           # HTML template (React)
├── package.json         # Dependencies and scripts (React)
├── vite.config.js       # Vite configuration
├── .env                 # Environment variables
└── README.md            # This file
```

## Architecture

### Backend Architecture (PHP)

#### Custom MVC Pattern

The PHP backend uses a custom MVC architecture with the following components:

- **Router** (`helper/routher.php`): Handles URL routing and dispatches requests to controllers
- **Controller** (`helper/controller.php`): Base controller with common functionality
- **Load** (`helper/load.php`): Manages loading of models and views with automatic data reference
- **Model** (`helper/model.php`): Base model for database operations
- **Database** (`helper/database.php`): PDO-based database abstraction layer
- **Layout** (`helper/layout.php`): Handles view rendering with header/footer

#### Key Design Patterns

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

### Frontend Architecture (React)

#### Component-Based Architecture

The React frontend uses class components and functional components organized by feature:

- **Layout Components**: Header, Footer, and main Layout wrapper
- **Page Components**: Route-specific components in `pages/` directory
- **Common Components**: Reusable components (Form, NotFound, Popup)
- **Service Layer**: API communication, authentication, and utilities

#### Routing System

The application uses a custom dynamic routing system:

- **ActiveComponent**: Dynamically loads components based on URL path
- **Path Parsing**: Converts URLs to `directory/view` format
- **Route Protection**: Admin routes automatically redirect non-admin users
- **Dynamic Titles**: Page titles update automatically based on route

#### API Integration

- **ApiRequest Service**: Centralized API communication handler
- **Token Management**: Automatic token inclusion in authenticated requests
- **Error Handling**: Consistent error handling across API calls
- **API Schema**: Endpoint definitions in `apiSchematic.json`

#### State Management

- **Component State**: Uses React `this.state` and `setState` for class components
- **Local Storage**: User authentication tokens and user data
- **Service Layer**: UserService for authentication state management

## Installation

### Prerequisites

- Docker and Docker Compose installed
- Node.js 18+ and npm (for React frontend)
- Git (for cloning the repository)

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd ReviewHub
   ```

2. **Configure environment variables**
   
   Create a `.env` file in the root directory:
   ```env
   MYSQL_ROOT_PASSWORD=root
   MYSQL_DATABASE=app_db
   MYSQL_USER=app_user
   MYSQL_PASSWORD=secret
   ```

3. **Start Docker containers (Backend)**
   ```bash
   docker-compose up -d --build
   ```
   
   The database will be automatically initialized with the schema and seed data from the `database/` folder. The initialization runs automatically every time you start the containers.

4. **Install React dependencies**
   ```bash
   npm install
   ```

5. **Configure API endpoint (React)**
   
   Update the API base URL in `src_react/services/api.js` if your backend is not running on `http://localhost:8080`:
   ```javascript
   const baseUrl = 'http://localhost:8080/api'
   ```

6. **Start the React development server**
   ```bash
   npm run dev
   ```

7. **Access the application**
   
   - PHP Backend (Server-rendered): http://localhost:8080
   - React Frontend (SPA): http://localhost:5173 (or URL shown in terminal)
   - Adminer (Database UI): http://localhost:8081

### Quick Setup Script

You can use the provided setup script:
```bash
sudo bash set_up.sh
```

This script will:
- Start Docker containers
- Install npm dependencies (if not already installed)
- Start the React development server

### Build for Production

#### React Frontend
```bash
npm run build
```

The production build will be in the `dist/` directory. Serve it with any static file server:
```bash
npm run preview
```

## Configuration

### API Configuration

The API endpoint configuration is in `src_react/services/api.js`. Update the `baseUrl` constant to point to your backend:

```javascript
const baseUrl = 'http://your-api-domain.com/api'
```

### API Schema

API endpoint definitions are stored in `src_react/apiSchematic.json`. This file defines:
- Available endpoints
- Required HTTP methods
- Required parameters for each endpoint

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
- `/review/search?query={query}` - Search by query
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
- `/legal/terms` - Terms of Service
- `/legal/privacy` - Privacy Policy
- `/legal/about` - About page
- `/legal/contact` - Contact page

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

## Development

### Available Scripts (React)

- `npm run dev` - Start development server with HMR
- `npm run build` - Build for production
- `npm run preview` - Preview production build
- `npm run lint` - Run ESLint

### Code Organization

#### Backend (PHP)
- **Controllers**: Business logic in `src_php/controller/`
- **Models**: Data access in `src_php/model/`
- **Views**: Presentation templates in `src_php/view/`
- **API**: API controllers in `src_php/api/controller/`

#### Frontend (React)
- **Components**: Organized by feature in `src_react/components/pages/`
- **Services**: Business logic and API calls in `src_react/services/`
- **Styles**: Modular CSS files in `src_react/style/`
- **Routing**: Dynamic routing in `src_react/routing/ActiveComponent.jsx`

### Adding New Pages (React)

1. Create component in appropriate `src_react/components/pages/{directory}/{view}.jsx`
2. Component will be automatically loaded based on URL path
3. Add styles to appropriate CSS file in `src_react/style/`
4. Update `ActiveComponent.jsx` title mapping if needed

### Styling (React)

- CSS files are organized by feature in `src_react/style/`
- Import styles in component files: `import '../../style/feature.css'`
- Follow existing naming conventions (BEM-like classes)

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Dependencies

### Production Dependencies (React)
- `react` ^19.2.0 - React library
- `react-dom` ^19.2.0 - React DOM rendering
- `react-router-dom` ^7.13.1 - Client-side routing

### Development Dependencies (React)
- `vite` ^7.3.1 - Build tool and dev server
- `@vitejs/plugin-react` ^5.1.1 - Vite React plugin
- `eslint` ^9.39.1 - Code linting
- `@types/react` ^19.2.7 - TypeScript types for React

## Contributing

1. Follow existing code style and component structure
2. Use class components for complex state management (React)
3. Keep components focused and reusable
4. Add appropriate error handling
5. Update API schema if adding new endpoints

## License

This project is part of the ReviewHub platform.

## Acknowledgments

- Built with PHP and MySQL for the backend
- Uses React and Vite for the frontend
- Uses Docker for containerization
- Custom MVC architecture for flexibility and learning
- React Router for client-side routing
- Modern CSS for responsive design

