# MEPCO Database Seeders Guide

## 📚 Overview

The database seeders have been organized into separate, modular files for easy management. Each seeder can be run independently or as part of the main `DatabaseSeeder`.

---

## 📂 Available Seeders

| Seeder | File | Description | Dependencies |
|--------|------|-------------|--------------|
| **Companies** | `CompanySeeder.php` | Power companies (MEPCO, LESCO, etc.) | None |
| **Subdivisions** | `SubdivisionSeeder.php` | Subdivisions with LS users | Companies |
| **Users** | `UserSeeder.php` | Admin & additional LS users | Subdivisions (optional) |
| **Tariffs** | `TariffSeeder.php` | Electricity tariff rates | None |
| **Consumers** | `ConsumerSeeder.php` | Consumer accounts | Subdivisions |
| **Applications** | `ApplicationSeeder.php` | New meter applications | Consumers |
| **Meters** | `MeterSeeder.php` | Installed meters | Applications |
| **Bills** | `BillSeeder.php` | Electricity bills | Meters, Tariffs |
| **Complaints** | `ComplaintSeeder.php` | Customer complaints | Consumers |
| **Summaries** | `SummarySeeder.php` | Global & Extra summaries | Applications |
| **Audit Logs** | `AuditLogSeeder.php` | System audit logs | Users, Applications |
| **System Settings** | `SystemSettingSeeder.php` | System configuration | None |

---

## 🚀 Usage

### Option 1: Run All Seeders (Recommended for Fresh Setup)

```bash
php artisan db:seed
```

### Option 2: Run Specific Seeder Only

```bash
# Companies only
php artisan db:seed --class=CompanySeeder

# Subdivisions only
php artisan db:seed --class=SubdivisionSeeder

# Consumers only
php artisan db:seed --class=ConsumerSeeder

# Applications only
php artisan db:seed --class=ApplicationSeeder

# Meters only
php artisan db:seed --class=MeterSeeder

# Bills only
php artisan db:seed --class=BillSeeder

# Complaints only
php artisan db:seed --class=ComplaintSeeder

# Tariffs only
php artisan db:seed --class=TariffSeeder

# Summaries only
php artisan db:seed --class=SummarySeeder

# Audit Logs only
php artisan db:seed --class=AuditLogSeeder

# System Settings only
php artisan db:seed --class=SystemSettingSeeder
```

### Option 3: Fresh Migration with Seeding

```bash
php artisan migrate:fresh --seed
```

---

## ⚙️ Customization

### Enable/Disable Seeders

Edit `database/seeders/DatabaseSeeder.php` and toggle seeders:

```php
$seeders = [
    'companies'       => true,   // Set to false to skip
    'subdivisions'    => true,
    'users'           => false,  // Disabled by default
    'tariffs'         => true,
    'consumers'       => true,
    'applications'    => true,
    'meters'          => true,
    'bills'           => true,
    'complaints'      => true,
    'summaries'       => true,
    'audit_logs'      => true,
    'system_settings' => true,
];
```

### Adjust Seeder Quantities

Each seeder has configurable quantities. Edit the specific seeder file:

#### ConsumerSeeder
```php
$consumersPerSubdivision = 15; // Change to your desired number
```

#### BillSeeder
```php
$monthsToGenerate = 3; // Number of months of bills
```

#### ComplaintSeeder
```php
$complaintChance = 30; // Percentage of consumers with complaints
```

---

## 📋 Seeding Order (IMPORTANT!)

**Dependencies matter!** Always follow this order when running individual seeders:

1. **CompanySeeder** - Must run first
2. **SubdivisionSeeder** - Creates subdivisions + LS users
3. **UserSeeder** - (Optional) Additional users
4. **TariffSeeder** - Required for bills
5. **ConsumerSeeder** - Requires subdivisions
6. **ApplicationSeeder** - Requires consumers
7. **MeterSeeder** - Requires approved applications
8. **BillSeeder** - Requires meters & tariffs
9. **ComplaintSeeder** - Requires consumers
10. **SummarySeeder** - Requires applications
11. **AuditLogSeeder** - Requires users & data
12. **SystemSettingSeeder** - Can run anytime

---

## 🎯 Common Scenarios

### Scenario 1: Fresh Database Setup
```bash
php artisan migrate:fresh --seed
```

### Scenario 2: Add More Consumers
```bash
php artisan db:seed --class=ConsumerSeeder
```

### Scenario 3: Add More Applications
```bash
php artisan db:seed --class=ApplicationSeeder
```

### Scenario 4: Generate More Bills
```bash
php artisan db:seed --class=BillSeeder
```

### Scenario 5: Reset Everything
```bash
php artisan migrate:fresh
php artisan db:seed
```

### Scenario 6: Only Setup Companies and Subdivisions
Edit `DatabaseSeeder.php`:
```php
$seeders = [
    'companies'       => true,
    'subdivisions'    => true,
    'users'           => false,
    'tariffs'         => false,
    'consumers'       => false,
    'applications'    => false,
    'meters'          => false,
    'bills'           => false,
    'complaints'      => false,
    'summaries'       => false,
    'audit_logs'      => false,
    'system_settings' => false,
];
```
Then run: `php artisan db:seed`

### Scenario 7: Add Only Test Data (No Companies/Subdivisions)
Edit `DatabaseSeeder.php`:
```php
$seeders = [
    'companies'       => false,
    'subdivisions'    => false,
    'users'           => false,
    'tariffs'         => true,
    'consumers'       => true,
    'applications'    => true,
    'meters'          => true,
    'bills'           => true,
    'complaints'      => true,
    'summaries'       => true,
    'audit_logs'      => true,
    'system_settings' => true,
];
```

---

## 🔐 Default Credentials

After seeding, you'll have these default credentials:

### Admin Account
- **Username:** `admin`
- **Password:** `password@123`
- **Email:** admin@mepco.gov.pk

### LS Users (per company)
- **Format:** `{company_code}_ls{number}`
- **Password:** `password`
- **Examples:**
  - `mepco_ls1` / password
  - `lesco_ls1` / password
  - `fesco_ls1` / password

---

## 📊 Expected Data Volumes

When all seeders are enabled with default settings:

| Entity | Approximate Count |
|--------|------------------|
| Companies | 5 |
| Subdivisions | 15 (3 per company) |
| LS Users | 15 (1 per subdivision) |
| Admin Users | 1 |
| Tariffs | 6 |
| Consumers | 225 (15 per subdivision) |
| Applications | 225 (1 per consumer) |
| Meters | ~150 (only for approved apps) |
| Bills | ~450 (3 months per active meter) |
| Complaints | ~65 (30% of consumers) |
| Global Summaries | ~20 |
| Extra Summaries | ~30 |
| Audit Logs | ~70 |
| System Settings | 14 |

---

## 🛠️ Troubleshooting

### Error: "No companies found"
**Solution:** Run `CompanySeeder` first
```bash
php artisan db:seed --class=CompanySeeder
```

### Error: "No subdivisions found"
**Solution:** Run `SubdivisionSeeder` after companies
```bash
php artisan db:seed --class=SubdivisionSeeder
```

### Error: "No consumers found"
**Solution:** Run `ConsumerSeeder` after subdivisions
```bash
php artisan db:seed --class=ConsumerSeeder
```

### Error: "Duplicate entry"
**Solution:** Either:
1. Fresh migration: `php artisan migrate:fresh --seed`
2. Or clear specific tables before seeding

### Seeder Takes Too Long
**Solution:** Reduce quantities in individual seeders:
- Reduce `$consumersPerSubdivision` in ConsumerSeeder
- Reduce `$monthsToGenerate` in BillSeeder
- Disable heavy seeders in DatabaseSeeder

---

## 🔄 Resetting Data

### Reset Everything
```bash
php artisan migrate:fresh --seed
```

### Reset Specific Table
```bash
php artisan migrate:refresh --path=/database/migrations/[migration_file]
php artisan db:seed --class=[SpecificSeeder]
```

---

## 💡 Pro Tips

1. **Test Data First:** Disable production seeders when testing
2. **Incremental Seeding:** Run seeders one by one for debugging
3. **Backup First:** Always backup production database before seeding
4. **Check Logs:** Review Laravel logs if seeding fails
5. **Custom Quantities:** Adjust quantities based on your testing needs
6. **Dependencies:** Always respect the seeding order

---

## 📝 Quick Reference Commands

```bash
# Fresh start
php artisan migrate:fresh --seed

# See all seeders
php artisan db:seed --list

# Run specific seeder
php artisan db:seed --class=CompanySeeder

# Multiple seeders in sequence
php artisan db:seed --class=CompanySeeder
php artisan db:seed --class=SubdivisionSeeder
php artisan db:seed --class=ConsumerSeeder

# Check what was seeded
php artisan tinker
>>> App\Models\Company::count()
>>> App\Models\Subdivision::count()
>>> App\Models\Consumer::count()
```

---

## 🎨 Customization Examples

### Custom Company Data
Edit `database/seeders/CompanySeeder.php`:
```php
$companies = [
    ['name' => 'Your Company Name', 'code' => 'YOURCODE'],
    // Add more companies
];
```

### Custom Tariff Rates
Edit `database/seeders/TariffSeeder.php`:
```php
[
    'name' => 'Custom Tariff',
    'category' => 'Domestic',
    'rate_per_unit' => 10.00,
    'fixed_charges' => 200,
]
```

---

## 🚨 Important Notes

1. **Production Warning:** Never run seeders on production without backing up first!
2. **Data Integrity:** Seeders use `firstOrCreate` where possible to avoid duplicates
3. **Dependencies:** Some seeders require others to run first
4. **Performance:** Large datasets may take several minutes to seed
5. **Testing:** Always test seeders on a development database first

---

## 📞 Need Help?

If you encounter issues:
1. Check the error message carefully
2. Verify seeding order is correct
3. Ensure migrations are up to date
4. Check Laravel logs: `storage/logs/laravel.log`
5. Review individual seeder files for specific requirements

---

*Last Updated: October 30, 2025*
*Version: 2.0*

