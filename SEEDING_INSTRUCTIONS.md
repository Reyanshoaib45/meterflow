# MEPCO Database Seeding Instructions

## Overview
This document provides instructions for seeding the MEPCO database with comprehensive test data.

## What Gets Seeded

The seeder populates the following data:

### 1. **Users**
- 1 Admin user
- 9 LS (Line Superintendent) users (3 per company)
- All with password: `password`

### 2. **Companies** (3)
- MEPCO - Multan Electric Power Company
- LESCO - Lahore Electric Supply Company
- FESCO - Faisalabad Electric Supply Company

### 3. **Subdivisions** (9)
- 3 subdivisions per company
- Each assigned to an LS user

### 4. **Tariffs** (4)
- Domestic - Slab 1 (0-100 units @ Rs. 5.50/unit)
- Domestic - Slab 2 (101-300 units @ Rs. 8.50/unit)
- Commercial (Rs. 12.50/unit)
- Industrial (Rs. 10.00/unit)

### 5. **Consumers** (90)
- 10 consumers per subdivision
- With realistic names, CNICs, phones, and addresses

### 6. **Applications** (90)
- One application per consumer
- Mixed statuses: pending, approved, rejected, closed
- Approved applications have fee amounts

### 7. **Meters** (~40-50)
- Created only for approved applications
- Various statuses: active, faulty, disconnected
- With meter numbers, makes, and readings

### 8. **Bills** (~120-150)
- 3 bills per active meter
- Calculated based on tariffs
- Mixed payment statuses: paid, unpaid, overdue

### 9. **Complaints** (~35-40)
- 40% of consumers have complaints
- Various subjects and priorities
- Assigned to respective LS users

### 10. **Application Histories**
- Tracks all application status changes
- Created for each application

### 11. **Complaint Histories**
- Tracks complaint status changes

### 12. **Extra Summaries** (9)
- One per subdivision
- Contains application counts

### 13. **Global Summaries** (~15)
- For approved applications

### 14. **Audit Logs** (20)
- Sample audit trail entries

### 15. **System Settings** (7)
- Company information
- GST, TV fee, meter rent settings

## Prerequisites

Before running the seeder, ensure:

1. **Database is created**
   ```bash
   # Create database in MySQL
   CREATE DATABASE mepco;
   ```

2. **Environment is configured**
   - Update `.env` file with database credentials
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=mepco
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

3. **Migrations are ready**
   - All migration files should be in `database/migrations/`

## Running the Seeder

### Step 1: Fresh Migration (Recommended)
This will drop all tables and recreate them:

```bash
php artisan migrate:fresh
```

### Step 2: Run the Seeder

```bash
php artisan db:seed
```

### Alternative: Fresh Migration + Seed (One Command)
```bash
php artisan migrate:fresh --seed
```

## Expected Output

When the seeder runs successfully, you'll see:

```
🌱 Starting database seeding...
👤 Creating admin user...
🏢 Creating companies...
👥 Creating LS users and subdivisions...
💰 Creating tariffs...
📋 Creating consumers, applications, and meters...
💵 Creating bills...
📞 Creating complaints...
📝 Creating audit logs...
⚙️ Creating system settings...
📊 Creating global summaries...
✅ Database seeding completed successfully!

📌 Login Credentials:
   Admin: admin / password
   LS Users: mepco_ls1, lesco_ls1, fesco_ls1 / password

📊 Summary:
   Companies: 3
   Subdivisions: 9
   LS Users: 9
   Consumers: 90
   Applications: 90
   Meters: 45
   Bills: 135
   Complaints: 36
   Tariffs: 4
```

## Login Credentials

### Admin Panel
- **URL**: `http://localhost:8000/login`
- **Username**: `admin`
- **Password**: `password`

### LS Login
- **URL**: `http://localhost:8000/ls/select-subdivision`
- **Usernames**: 
  - `mepco_ls1`, `mepco_ls2`, `mepco_ls3`
  - `lesco_ls1`, `lesco_ls2`, `lesco_ls3`
  - `fesco_ls1`, `fesco_ls2`, `fesco_ls3`
- **Password**: `password` (for all)

## Testing the System

After seeding, you can test:

### 1. **Admin Features**
- Login as admin
- View dashboard with statistics
- Manage subdivisions, users, consumers
- View meters, bills, tariffs
- Handle complaints
- View analytics and reports
- Check audit logs

### 2. **LS Features**
- Select a subdivision
- Login with LS credentials
- View LS dashboard
- Manage applications
- Update application statuses
- Add/edit extra summaries
- View meter store
- Handle complaints

### 3. **User Features**
- Submit new application (no login required)
- Track application status
- View application details
- Close pending applications
- Generate invoice (for approved applications with fee)

## Sample Test Data

### Sample Application Numbers
- `APP-MEPCO-SUB1-0001` to `APP-MEPCO-SUB1-0010`
- `APP-LESCO-SUB1-0001` to `APP-LESCO-SUB1-0010`
- `APP-FESCO-SUB1-0001` to `APP-FESCO-SUB1-0010`

### Sample Meter Numbers
- Format: `MTR-########` (8 random digits)
- Example: `MTR-12345678`

### Sample Bill Numbers
- Format: `BILL-##########` (10 random digits)
- Example: `BILL-1234567890`

### Sample Complaint IDs
- Format: `CMP-########` (8 random digits)
- Example: `CMP-12345678`

## Troubleshooting

### Error: "Class not found"
**Solution**: Run composer autoload
```bash
composer dump-autoload
```

### Error: "Table already exists"
**Solution**: Use fresh migration
```bash
php artisan migrate:fresh --seed
```

### Error: "SQLSTATE[42S02]: Base table or view not found"
**Solution**: Run migrations first
```bash
php artisan migrate
php artisan db:seed
```

### Error: "Foreign key constraint fails"
**Solution**: Ensure migrations are in correct order
- Check migration file timestamps
- Companies should be created before subdivisions
- Users should be created before subdivisions (for ls_id)
- Subdivisions before applications
- Applications before meters
- Consumers before bills

### Slow Seeding
The seeder creates ~500+ records, so it may take 30-60 seconds. This is normal.

## Re-seeding

To re-seed the database:

```bash
# Option 1: Fresh migration + seed
php artisan migrate:fresh --seed

# Option 2: Truncate and seed (faster, but may have foreign key issues)
php artisan db:seed --class=DatabaseSeeder
```

## Customizing Seed Data

To modify the seeded data, edit:
- `database/seeders/DatabaseSeeder.php`

You can change:
- Number of consumers per subdivision (line 138)
- Number of bills per meter (line 220)
- Complaint probability (line 254)
- Company names and codes (lines 45-49)
- Tariff rates (lines 87-124)

## Production Warning

⚠️ **NEVER run this seeder in production!**

This seeder is for **development and testing only**. It will:
- Create test users with weak passwords
- Generate fake data
- Potentially overwrite existing data

For production:
1. Use proper migrations
2. Create users manually with strong passwords
3. Import real data using proper data import tools

## Next Steps

After seeding:

1. **Start the development server**
   ```bash
   php artisan serve
   ```

2. **Access the application**
   - Landing page: `http://localhost:8000`
   - Admin login: `http://localhost:8000/login`
   - LS login: `http://localhost:8000/ls/select-subdivision`

3. **Test all features**
   - Submit applications
   - Track applications
   - Login as admin and LS
   - Manage data
   - Generate reports

## Support

If you encounter issues:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Enable debug mode in `.env`: `APP_DEBUG=true`
3. Check database connection
4. Verify all migrations ran successfully

---

**Happy Testing! 🚀**
