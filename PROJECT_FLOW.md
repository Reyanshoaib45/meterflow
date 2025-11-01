# MEPCO Project - Complete Flow Documentation

## Overview
This is a comprehensive MEPCO (Multan Electric Power Company) management system that handles meter applications, billing, and administrative tasks across multiple roles.

## User Roles

### 1. Admin
- **Access**: Full system access
- **Dashboard**: `/admin/dashboard`
- **Responsibilities**:
  - Manage companies, subdivisions
  - Create and manage users (LS, SDC, RO, SDO)
  - Manage meters (create, edit, view, soft delete)
  - Manage billing
  - View analytics and audit logs
  - Manage tariffs
  - Handle complaints

### 2. LS (Line Superintendent)
- **Access**: Subdivision-specific access
- **Login**: Select subdivision → Login with credentials
- **Dashboard**: `/ls/dashboard`
- **Responsibilities**:
  - View and manage applications in their subdivisions
  - Create global summaries for applications
  - Create extra summaries
  - Manage meter store
  - View applications and their status

### 3. SDC (Smart Data Center)
- **Access**: All subdivisions
- **Login**: Select subdivision → Login with credentials
- **Dashboard**: `/sdc/dashboard`
- **Responsibilities**:
  - View all global summaries (moved from admin)
  - Create global summaries for applications
  - Edit global summaries
  - Manage data analytics
  - Switch between subdivisions

### 4. RO (Revenue Officer)
- **Access**: All subdivisions
- **Login**: Select subdivision → Login with credentials
- **Dashboard**: `/ro/dashboard`
- **Responsibilities**:
  - View summaries sent by LS (with SEO number)
  - View summary details with history
  - Manage billing for consumers
  - Handle billing operations

### 5. SDO (Sub-Divisional Officer)
- **Access**: Similar to admin but limited
- **Dashboard**: Admin dashboard access

## Complete Application Flow

### Phase 1: Application Submission
1. **Consumer submits application**
   - Application created with status: `pending`
   - Fields: Application No, Customer Name, Address, Phone, CNIC, Meter Type, Load Demand
   - Assigned to: Subdivision and Company

### Phase 2: LS Review and Processing
2. **LS logs in**
   - Selects their subdivision
   - Views applications in their subdivision
   - Reviews applications
   - Can approve/reject applications

3. **LS creates Global Summary** (for approved applications)
   - SIM Number (not SIM Date)
   - Consumer Address
   - Date on Draft on Store
   - Date Received by LM/Consumer
   - Customer Mobile No
   - Customer SC No
   - Date Return to SDC for Billing

4. **LS creates Application History**
   - When creating history, can add SEO number
   - Can mark as `sent_to_ro = true`
   - This sends the summary to RO for billing management

### Phase 3: SDC Management
5. **SDC accesses Global Summaries**
   - Views all global summaries (from all subdivisions)
   - Can create new global summaries
   - Can edit existing global summaries
   - Manages data analytics

### Phase 4: RO Billing Management
6. **RO views summaries**
   - Only sees summaries where `sent_to_ro = true`
   - Views summary details including:
     - Application information
     - History with SEO numbers
     - Consumer details

7. **RO manages billing**
   - Accesses billing management for each summary
   - Creates bills
   - Manages billing operations
   - Handles payment status

### Phase 5: Meter Management
8. **Admin creates meters**
   - Meter Number (required)
   - Subdivision (required)
   - Meter Make, Reading, SIM Number (optional)
   - Installation Date, Remarks (optional)
   - **Meter Image** (upload)
   - Consumer field is **nullable** (not required)
   - **No status field** in create form

9. **Meter operations**
   - View meter details (including image)
   - Edit meter (including image update)
   - **Soft delete** (not hard delete)
   - Meter image displayed in edit and view pages

### Phase 6: Billing
10. **Billing process**
    - Admin or RO creates bills
    - Bills linked to meters and consumers
    - Calculates charges based on tariff
    - Bill verification process
    - Payment status tracking

## Database Structure Changes

### Meters Table
- `consumer_id`: **Nullable** (not required)
- `status`: Removed from create form (still in DB for backward compatibility)
- `meter_image`: String field for image path
- `deleted_at`: Soft delete support

### Global Summaries Table
- `sim_date` → `sim_number` (string, not date)
- Added `consumer_address` (text field)
- Removed `sim_date`

### Application Histories Table
- Added `seo_number` (string, nullable)
- Added `sent_to_ro` (boolean, default false)

## Key Features

### 1. Dark Mode
- Site-wide dark mode toggle in navbar
- All forms support dark mode
- Consistent styling across all pages

### 2. Standardized Input Designs
- All input fields use consistent Tailwind CSS classes:
  - `w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition`

### 3. Soft Delete for Meters
- Meters use soft delete (not permanently deleted)
- Can be restored if needed
- Billing records prevent deletion if meter has bills

### 4. Image Upload
- Meter images stored in `storage/app/public/meter_images`
- Max file size: 5MB
- Supported formats: JPEG, PNG, JPG, GIF
- Old images deleted when new one uploaded

### 5. Dynamic Views
- LS login/subdivision selection views work for SDC and RO
- Route-based dynamic content rendering
- Shared templates for similar functionality

## Navigation Flow

### Landing Page (`/`)
- Shows 4 cards: LS Login, SDC Portal, RO Portal, Admin Login
- Clicking cards leads to respective login/selection pages

### Role Selection (`/auth/role-select`)
- Shows 5 cards: LS Login, SDC Login, RO Login, Admin Login
- Each leads to subdivision selection or login page

### Login Flow
1. Select role (LS/SDC/RO)
2. Select subdivision
3. Enter credentials
4. Access dashboard

## Admin Quick Actions

### Current Actions:
- Subdivisions
- SDO Users
- Companies
- Meters
- Billing
- Tariffs
- Complaints
- Analytics
- Consumers
- Audit Logs
- Edit Applications
- **SDC Users** (NEW)
- **RO Users** (NEW)
- LS Management

### Removed:
- ~~Global Summaries~~ (Moved to SDC dashboard)

## Routes Summary

### Admin Routes
- `/admin/dashboard` - Admin dashboard
- `/admin/meters` - Meter management (CRUD)
- `/admin/billing` - Billing management
- `/admin/users` - User management (with role filter: `?role=sdc` or `?role=ro`)
- `/admin/subdivisions` - Subdivision management
- `/admin/companies` - Company management

### LS Routes
- `/ls/dashboard` - LS dashboard
- `/ls/applications/{subdivision}` - View applications
- `/ls/global-summaries/{application}/create` - Create global summary
- `/ls/extra-summaries` - Extra summaries

### SDC Routes
- `/sdc/dashboard` - SDC dashboard
- `/sdc/global-summaries` - View all global summaries
- `/sdc/global-summaries/{applicationId}/create` - Create global summary
- `/sdc/global-summaries/{id}/edit` - Edit global summary

### RO Routes
- `/ro/dashboard` - RO dashboard
- `/ro/summary/{id}` - View summary details
- `/ro/billing/{summaryId}/manage` - Manage billing

## File Structure

```
app/
├── Http/Controllers/
│   ├── AdminController.php
│   ├── LsController.php
│   ├── SDCController.php (NEW)
│   ├── ROController.php (NEW)
│   ├── MeterController.php
│   ├── BillingController.php
│   └── GlobalSummaryController.php
├── Models/
│   ├── Meter.php (with SoftDeletes)
│   ├── GlobalSummary.php
│   ├── ApplicationHistory.php
│   └── User.php (with isSDC(), isRO())
└── Http/Middleware/
    ├── EnsureUserIsSDC.php (NEW)
    └── EnsureUserIsRO.php (NEW)

resources/views/
├── admin/
│   ├── dashboard.blade.php (updated)
│   ├── meters/
│   │   ├── create.blade.php (updated)
│   │   ├── edit.blade.php (updated)
│   │   └── show.blade.php (updated)
│   └── billing/
│       └── show.blade.php (NEW)
├── sdc/
│   ├── dashboard.blade.php (NEW)
│   └── global-summaries/
│       ├── index.blade.php (NEW)
│       ├── create.blade.php (NEW)
│       └── edit.blade.php (NEW)
└── ro/
    ├── dashboard.blade.php (NEW)
    ├── summary-details.blade.php (NEW)
    └── manage-billing.blade.php (NEW)
```

## Important Notes

1. **Consumer field in Meters**: Made nullable - meters can exist without immediate consumer assignment
2. **Status field in Meters**: Removed from create form - can be managed separately if needed
3. **Soft Delete**: Meters use soft delete, so they're recoverable
4. **Image Storage**: Use `php artisan storage:link` to create symlink for public access
5. **SEO Number**: Added by LS when creating application history and marking `sent_to_ro = true`
6. **Global Summary**: Moved from Admin to SDC dashboard - admin no longer has direct access
7. **Standardized Forms**: All forms now use consistent styling and dark mode support

## Migration Checklist

Run these migrations in order:
```bash
php artisan migrate
```

Key migrations:
- `add_meter_image_to_meters_table`
- `add_deleted_at_to_meters_table`
- `modify_global_summaries_table_fix_sim_fields`
- `add_seo_number_to_application_histories_table`

## Seeding

```bash
php artisan db:seed
```

This creates:
- Admin user (admin / password@123)
- SDC users (sdc1, sdc2 / password)
- RO users (ro1, ro2 / password)
- LS users (based on subdivisions)
- Sample data for all modules

