# Restaurant Management System

A Laravel-based web application for managing restaurant operations, employees, orders, kitchen workflow, payments and daily sales reports.

The main goal of this project was to build more than a standard CRUD application by implementing a complete restaurant workflow with role-based permissions, order status management, payment processing, reporting and automated testing.

## Features

### Authentication & User Management

- User authentication and session management
- Role-based access control
- Forced password change on first login
- Employee creation and editing
- Employee account deactivation
- Department assignment
- Multiple roles per user
- Manager permission restrictions
- Demo account for testing the application

### Roles

The application supports multiple employee roles with different permissions:

- Director
- Manager
- Waiter
- Cook
- Kitchen Assistant

Users can have multiple roles through a many-to-many relationship.

Access to application features is protected using Laravel middleware.

### Menu Management

Managers and directors can manage the restaurant menu:

- Create dishes
- Edit dishes
- Assign dishes to categories
- Set net price and VAT rate
- Enable or disable dish availability
- Search dishes by name
- Sort dishes directly from table headers

Unavailable dishes cannot be added to new orders.

### Order Management

Waiters can create orders using an interface similar to a shopping cart.

Each order can contain multiple items with:

- Dish
- Quantity
- Additional notes
- Unit price
- VAT rate
- Net value
- Gross value

Price information is stored with each order item so historical orders remain consistent even if menu prices change later.

Order creation is handled inside a database transaction to maintain data consistency.

### Order Workflow

Orders move through a defined lifecycle:

```text
Accepted
   ↓
In Preparation
   ↓
Ready
   ↓
Collected
   ↓
Closed
```

Different roles are responsible for different stages of the workflow.

**Waiter**
- Creates an order
- Sees active orders
- Collects prepared orders
- Processes payment for their own orders

**Kitchen**
- Sees orders waiting for preparation
- Starts preparation
- Marks orders as ready

The application prevents unauthorized users from performing operations outside their role.

### Order Status History

Every order status change is recorded.

The history stores:

- Order
- Previous status
- New status
- User responsible for the change
- Timestamp

This provides an audit trail and makes it possible to analyze the lifecycle of an order.

### Payments

Orders can be settled using:

- Cash
- Card / BLIK
- Voucher

The system also supports mixed payments.

Example:

```text
Order total: 100 PLN

Voucher: 20 PLN
Card:    50 PLN
Cash:    30 PLN

Remaining: 0 PLN
→ Order closed
```

An order is automatically closed when the complete amount has been paid.

The backend validates payments to prevent the paid amount from exceeding the remaining order value.

### Discounts

Discounts can be applied before payment processing begins.

Each discount contains:

- Discount percentage
- Discount amount
- Reason

The application automatically recalculates:

- Net total
- VAT total
- Gross total

Original subtotal values are preserved for reporting purposes.

Once payment processing has started, the discount can no longer be modified.

### Daily Reports

Managers and directors have access to daily restaurant reports.

Reports include:

- Gross sales
- Net sales
- VAT
- Total discounts
- Number of completed orders
- Revenue by payment method
- Most popular dishes
- Sales by waiter

Reports can be generated for a selected date.

### Search & Sorting

The application uses GET query parameters for searching and sorting data.

Available functionality includes:

- Employee search by first name, last name or email
- Employee sorting
- Dish search
- Dish sorting
- Daily report date filtering

Search and sorting parameters are preserved in the URL, allowing filtered views to be refreshed or shared.

## Database

The application uses a relational database structure with Laravel Eloquent ORM.

Main entities include:

- Users
- Departments
- Roles
- Categories
- Dishes
- Orders
- Order Items
- Payments
- Order Status History

The project uses multiple relationship types, including:

```text
belongsTo
hasMany
belongsToMany
```

A pivot table is used for the many-to-many relationship between users and roles.

Foreign keys and database constraints are used to maintain relational integrity.

## Technologies

### Backend

- PHP
- Laravel
- Eloquent ORM
- MySQL

### Frontend

- Blade
- HTML
- CSS

### Development & Tooling

- Composer
- Git
- GitHub
- GitHub Actions
- SQLite for automated tests

## Testing

The project includes automated Laravel Feature Tests.

Tests cover important application behavior such as authentication and protected routes, and the test suite is designed to be expanded alongside application functionality.

Testing uses an isolated database environment with Laravel utilities such as:

```text
RefreshDatabase
Model Factories
actingAs()
HTTP Assertions
```

Tests can be executed with:

```bash
php artisan test
```

## Continuous Integration

The repository includes a GitHub Actions CI workflow.

The pipeline runs automatically on pushes and pull requests to the main branch.

The workflow:

```text
Push / Pull Request
        ↓
Checkout repository
        ↓
Setup PHP environment
        ↓
Install Composer dependencies
        ↓
Prepare Laravel environment
        ↓
Create test database
        ↓
Run database migrations
        ↓
Run automated tests
        ↓
PASS / FAIL
```

This ensures that automated tests are executed against the application in a clean environment after repository changes.

## Demo Account

The project contains a seeded demo account that can be used to explore the application without manually creating multiple employees.

The demo user has access to multiple roles, allowing the complete restaurant workflow to be tested from a single account.

```text
Email: demo@restaurant.test
Password: password
```

> Demo credentials are intended only for local/demo environments.

## Installation

### Requirements

Make sure the following are installed:

- PHP
- Composer
- MySQL
- Git

### 1. Clone the repository

```bash
git clone <repository-url>
cd restaurant-management
```

### 2. Install dependencies

```bash
composer install
```

### 3. Create environment configuration

```bash
cp .env.example .env
```

On Windows, the file can also be copied manually.

### 4. Generate application key

```bash
php artisan key:generate
```

### 5. Configure the database

Set your database connection inside `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restaurant_managment
DB_USERNAME=root
DB_PASSWORD=
```

Adjust the values according to your local environment.

### 6. Run migrations and seeders

```bash
php artisan migrate --seed
```

This creates the database structure and required initial/demo data.

### 7. Start the application

```bash
php artisan serve
```

The application will be available at:

```text
http://127.0.0.1:8000
```

### 8. Run tests

```bash
php artisan test
```

## Architecture

The application follows Laravel's MVC architecture.

```text
HTTP Request
     ↓
Routes
     ↓
Middleware
     ↓
Controller
     ↓
Eloquent Models
     ↓
Database
     ↓
Controller
     ↓
Blade View
     ↓
HTTP Response
```

Responsibilities are separated between:

- **Models** – database entities and relationships
- **Controllers** – application and business logic
- **Middleware** – authentication and authorization
- **Blade Views** – user interface
- **Migrations** – database structure
- **Seeders** – initial and demo data
- **Tests** – automated verification of application behavior

## Project Purpose

This project was created as a portfolio project focused on learning and applying backend development concepts in a realistic business workflow.

Instead of focusing only on CRUD operations, the application implements interconnected business processes including:

- Authentication
- Authorization
- Role-based permissions
- Relational database design
- Order lifecycle management
- Transactional operations
- Payments
- Discounts
- Audit history
- Reporting
- Automated testing
- Continuous Integration

## Future Development

Possible future improvements include:

- Dockerized environment
- Extended automated test coverage
- Additional reporting and analytics
- Pagination for larger datasets
- REST API
- Real payment gateway integration
- Production deployment pipeline

## Status

**Active Development**

Core application functionality, UI/UX, reporting, search/sorting and CI are implemented.

Docker support and further automated test coverage are planned as the next improvements.
