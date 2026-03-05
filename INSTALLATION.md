# Employee Attendance Management System

A full-featured attendance tracking system built with Laravel 12.x, featuring role-based access control, geolocation validation for check-ins, and attendance export functionalities.

## Requirements
- PHP 8.2+
- Composer
- MySQL

## Features
- **Authentication**: Secure login utilizing Laravel standard auth with Admin and Employee roles.
- **Role-based Access Control**: 
  - **Admin**: Has full access. Manages employees, configures locations, and monitors attendances.
  - **Employee**: Can view their own dashboard, log check-ins/check-outs, and view personal attendance history.
- **Location-based Check In**: Automatically captures employee's browser GPS coordinates and only permits check-in if within the assigned location's radius.
- **Time Validation**: Ensures checks for early and late check-ins, prompting for a mandatory reason if outside a standard 30-min to 20-min work window constraint.
- **Exporting**: Export attendance list natively into Excel using Maatwebsite Excel.

## Tech Stack
- Laravel 12.x
- MySQL
- Blade Templating (Laravel Breeze)
- Tailwind CSS

## Installation Instructions

1. **Clone or Extract the Repository:**
   Make sure you are in the project folder `Employee Attendance Management System`.

2. **Install Dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup:**
   Ensure the `.env` file is appropriately populated with your database credentials. For example:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=employee_attendance
   DB_USERNAME=root
   DB_PASSWORD=root
   ```

4. **Database Setup & Seed:**
   Run the following artisan command to create the necessary tables and populate the DB with demo data:
   ```bash
   php artisan migrate:fresh --seed
   ```
   **Note**: This command automatically creates:
   - 1 Admin (`admin@admin.com`, password: `password`)
   - 2 Employees (`employee1@test.com`, `employee2@test.com`, both passwords: `password`)
   - 2 Demo Office Locations

5. **Build Assets:**
   ```bash
   npm run build
   ```
   *or* run `npm run dev` to start a continuous asset building process.

6. **Serve the Application:**
   ```bash
   php artisan serve
   ```

7. **Access the Application:**
   Visit `http://localhost:8000` to interact with the system.
   
## Usage Workflow

**Admin Workflow:**
- Login with `admin@admin.com` / `password`.
- Use the navigation bar to visit **Employees** to manage employee data, create new credentials, and assign them locations.
- Under **Locations**, define acceptable parameters for offices (needs correct Latitude and Longitude).
- Monitor global stats under **Attendances** and test exporting logic!

**Employee Workflow:**
- Login with an employee credential (`employee1@test.com` / `password`).
- The Dashboard lets you "Clock In" natively. A browser prompt will request location permissions. Provide location access for the function to resolve properly.
- If checking in, the system determines the offset between your real location and the preset company location. If allowed, attendance triggers!

## Notes on Testing Geolocation Locally:
If testing locally via browser, `localhost` permits the invocation of HTML5 Geolocation API without `https`. However, for deployment, it must operate on a server with an active SSL certificate (`HTTPS`), otherwise modern browsers will outright decline fetching GPS data for the "Clock In" logic.
