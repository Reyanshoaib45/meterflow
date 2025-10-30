# 🌱 Database Seeders - Quick Start Guide

## 🚀 Quick Commands

### Run All Seeders
```bash
php artisan migrate:fresh --seed
```

### Run Individual Seeders
```bash
# In this order if running individually:
php artisan db:seed --class=CompanySeeder
php artisan db:seed --class=SubdivisionSeeder
php artisan db:seed --class=TariffSeeder
php artisan db:seed --class=ConsumerSeeder
php artisan db:seed --class=ApplicationSeeder
php artisan db:seed --class=MeterSeeder
php artisan db:seed --class=BillSeeder
php artisan db:seed --class=ComplaintSeeder
php artisan db:seed --class=SummarySeeder
php artisan db:seed --class=AuditLogSeeder
php artisan db:seed --class=SystemSettingSeeder
```

---

## ⚙️ Enable/Disable Seeders

Edit `database/seeders/DatabaseSeeder.php`:

```php
$seeders = [
    'companies'       => true,   // ✓ Enable
    'subdivisions'    => true,   // ✓ Enable
    'users'           => false,  // ✗ Disable
    'tariffs'         => true,   // ✓ Enable
    'consumers'       => true,   // ✓ Enable
    'applications'    => true,   // ✓ Enable
    'meters'          => true,   // ✓ Enable
    'bills'           => true,   // ✓ Enable
    'complaints'      => true,   // ✓ Enable
    'summaries'       => true,   // ✓ Enable
    'audit_logs'      => true,   // ✓ Enable
    'system_settings' => true,   // ✓ Enable
];
```

---

## 📦 What Each Seeder Does

| Seeder | Creates | Count |
|--------|---------|-------|
| **CompanySeeder** | Power companies | 5 |
| **SubdivisionSeeder** | Subdivisions + LS users | 15 + 15 users |
| **TariffSeeder** | Tariff rates | 6 |
| **ConsumerSeeder** | Consumer accounts | 225 (15 per subdivision) |
| **ApplicationSeeder** | New meter applications | 225 |
| **MeterSeeder** | Installed meters | ~150 |
| **BillSeeder** | Electricity bills | ~450 |
| **ComplaintSeeder** | Customer complaints | ~65 |
| **SummarySeeder** | Global & Extra summaries | ~50 |
| **AuditLogSeeder** | Audit logs | ~70 |
| **SystemSettingSeeder** | System settings | 14 |

---

## 🔐 Default Login Credentials

### Admin
- **Username:** `admin`
- **Password:** `password@123`

### LS Users
- **Format:** `{company}_ls{number}` / `password`
- **Examples:**
  - `mepco_ls1` / `password`
  - `lesco_ls1` / `password`
  - `fesco_ls1` / `password`

---

## 🎯 Common Use Cases

### 1. Fresh Setup (Everything)
```bash
php artisan migrate:fresh --seed
```

### 2. Only Basic Setup (No Test Data)
Edit `DatabaseSeeder.php`, enable only:
- companies ✓
- subdivisions ✓
- tariffs ✓
- system_settings ✓

All others set to `false`, then:
```bash
php artisan db:seed
```

### 3. Add More Consumers
```bash
php artisan db:seed --class=ConsumerSeeder
```

### 4. Add More Bills
```bash
php artisan db:seed --class=BillSeeder
```

---

## ⚡ Adjust Data Quantities

### Consumers Per Subdivision
Edit `database/seeders/ConsumerSeeder.php`:
```php
$consumersPerSubdivision = 15; // Change this number
```

### Bills Per Meter
Edit `database/seeders/BillSeeder.php`:
```php
$monthsToGenerate = 3; // Change this number
```

### Complaint Percentage
Edit `database/seeders/ComplaintSeeder.php`:
```php
$complaintChance = 30; // 30% of consumers
```

---

## 🔄 Seeding Order (Important!)

**Must follow this order when running individual seeders:**

1. Companies (no dependencies)
2. Subdivisions (needs Companies)
3. Tariffs (no dependencies)
4. Consumers (needs Subdivisions)
5. Applications (needs Consumers)
6. Meters (needs Applications)
7. Bills (needs Meters + Tariffs)
8. Complaints (needs Consumers)
9. Summaries (needs Applications)
10. Audit Logs (needs Users + Data)
11. System Settings (no dependencies)

---

## 🛠️ Troubleshooting

### "No companies found" error
```bash
php artisan db:seed --class=CompanySeeder
```

### "No subdivisions found" error
```bash
php artisan db:seed --class=CompanySeeder
php artisan db:seed --class=SubdivisionSeeder
```

### Reset everything
```bash
php artisan migrate:fresh --seed
```

---

## 📊 Check What Was Seeded

```bash
php artisan tinker
```

Then in tinker:
```php
App\Models\Company::count()
App\Models\Subdivision::count()
App\Models\User::where('role', 'admin')->count()
App\Models\User::where('role', 'ls')->count()
App\Models\Consumer::count()
App\Models\Application::count()
App\Models\Meter::count()
App\Models\Bill::count()
exit
```

---

## 📁 File Locations

```
database/seeders/
├── DatabaseSeeder.php         (Main seeder - configure here)
├── CompanySeeder.php
├── SubdivisionSeeder.php
├── UserSeeder.php
├── TariffSeeder.php
├── ConsumerSeeder.php
├── ApplicationSeeder.php
├── MeterSeeder.php
├── BillSeeder.php
├── ComplaintSeeder.php
├── SummarySeeder.php
├── AuditLogSeeder.php
├── SystemSettingSeeder.php
└── README.md                  (Detailed documentation)
```

---

## 💡 Pro Tips

✓ **Always backup** before seeding production
✓ **Test locally** before deploying
✓ **Adjust quantities** for your needs
✓ **Follow the order** when running individual seeders
✓ **Check logs** if something fails
✓ **Use fresh migration** for clean start

---

## 📝 Quick Reference

```bash
# Fresh start
php artisan migrate:fresh --seed

# Run all seeders only (no migration)
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=CompanySeeder

# Check seeder output
php artisan db:seed --verbose

# Reset and seed
php artisan migrate:refresh --seed
```

---

**That's it! You're ready to seed your database! 🎉**

For detailed documentation, see: `database/seeders/README.md`

