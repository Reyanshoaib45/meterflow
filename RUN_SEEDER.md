# Quick Start - Run Database Seeder

## Step 1: Fresh Migration
Run this command to drop all tables and recreate them with the fixed schema:

```bash
php artisan migrate:fresh
```

## Step 2: Run Seeder
```bash
php artisan db:seed
```

## OR - Do Both in One Command
```bash
php artisan migrate:fresh --seed
```

## What Was Fixed

1. ✅ Added `email` column to users table
2. ✅ Removed `unique` constraint from `role` column
3. ✅ Made `username`, `email`, and `subdivision_id` nullable
4. ✅ Set default value for `role` column
5. ✅ Updated User model to include `email` in fillable

## Expected Result

After running the seeder, you should see:

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
   Meters: ~45
   Bills: ~135
   Complaints: ~36
   Tariffs: 4
```

## Login After Seeding

### Admin Login
- URL: http://localhost:8000/login
- Username: `admin`
- Password: `password`

### LS Login
- URL: http://localhost:8000/ls/select-subdivision
- Select any subdivision
- Username: `mepco_ls1` (or `lesco_ls1`, `fesco_ls1`, etc.)
- Password: `password`

## Start Development Server

```bash
php artisan serve
```

Then visit: http://localhost:8000

---

**All Fixed! Ready to seed! 🚀**
