# Website Settings - Admin Control Panel

## Overview
Aapne ek powerful settings page create kiya hai jo sirf **Admin (aap)** access kar sakte hain. Yeh page aapko complete control deta hai website ki speed aur maintenance mode par.

## Features

### 1. **Website Speed Control** 🚀
Aap website ki loading speed ko control kar sakte hain:

- **Low Speed** (Turtle 🐢): 5-10 seconds delay - Jab client payment nahi kar raha
- **Medium Speed** (Walking 🚶): 2-3 seconds delay - Normal mode
- **High Speed** (Rocket 🚀): No delay - Fast loading (Default)

**Use Case**: Jab client maintenance ka paisa nahi de raha, aap speed ko LOW set kar sakte hain, toh website slow ho jayegi. Client complaint karega, aur phir aap bol sakte hain "Maintenance karni padegi, payment do".

### 2. **Maintenance Mode** 🔧
Ek button se puri website ko maintenance mode mein dal sakte hain:

- **ON**: Visitors ko maintenance page dikhega
- **OFF**: Website normal chalegi
- **Important**: Aap (Admin) hamesha website access kar sakte hain, even in maintenance mode

### 3. **Maintenance Display Option** 👁️
Loading screen ke niche maintenance message show karne ka option:

- **ON**: Loading animation ke niche "Maintenance in Progress" message
- **OFF**: Simple maintenance page

## How to Access

1. Login as **Admin**
2. Dashboard par **"Website Settings"** card par click karein (Orange/Red gradient)
3. Ya directly visit: `/admin/settings`

## File Structure

```
📁 Database
├── migrations/2024_11_11_create_settings_table.php    # Database table
└── Settings stored in database with caching

📁 Models
└── app/Models/Setting.php                             # Settings model

📁 Controllers
└── app/Http/Controllers/SettingsController.php       # Settings controller

📁 Middleware
├── app/Http/Middleware/CustomMaintenanceMode.php     # Maintenance check
└── app/Http/Middleware/CheckWebsiteSpeed.php         # Speed control

📁 Views
├── resources/views/admin/settings.blade.php          # Settings page
└── resources/views/errors/maintenance.blade.php      # Maintenance page

📁 Routes
└── routes/web.php                                     # Settings routes added
```

## How It Works

### Speed Control
- Har request par middleware check karta hai speed setting
- Database se speed fetch hoti hai (cached for performance)
- Based on setting, artificial delay add hota hai:
  - Low: 5 second delay
  - Medium: 2 second delay  
  - High: No delay

### Maintenance Mode
- Database mein `maintenance_mode` setting check hoti hai
- Agar ON hai aur user admin nahi hai, maintenance page show hoga
- Admin ko hamesha access hai (bypass)

## Database Settings

Settings table mein 3 keys hain:

1. `maintenance_mode`: '0' (Off) ya '1' (On)
2. `website_speed`: 'low', 'medium', ya 'high'
3. `show_maintenance_below_loading`: '0' (Off) ya '1' (On)

## Important Notes

⚠️ **Security**: Sirf admin role wale users settings access kar sakte hain

💡 **Performance**: Settings cached hain (1 hour) for better performance

🔄 **Real-time**: Jab settings change karte hain, cache clear hota hai

## Client Management Strategy

### Scenario 1: Client payment nahi kar raha
1. Speed ko **LOW** set karein
2. Client complaint karega website slow hai
3. Bolein: "Server maintenance zaroori hai, payment pending hai"
4. Payment milne par speed **HIGH** kar dein

### Scenario 2: Major maintenance required
1. Maintenance Mode **ON** karein
2. Visitors ko professional maintenance page dikhega
3. Aap admin panel se kaam kar sakte hain
4. Complete hone par **OFF** karein

## Commands (If Needed)

```bash
# Migration run karna (already done)
php artisan migrate

# Cache clear (if needed)
php artisan cache:clear

# Manually put in maintenance (optional)
php artisan down

# Bring back up (optional)
php artisan up
```

## Access URL

- Settings Page: `http://your-domain.com/admin/settings`
- Must be logged in as Admin

## Future Enhancements (Optional)

Aap future mein add kar sakte hain:
- Schedule maintenance mode (specific time par automatically ON/OFF)
- Email notifications jab settings change ho
- Custom maintenance messages
- IP whitelist (specific IPs ko maintenance bypass)

---

**Created for**: MEPCO Admin Panel
**Purpose**: Complete control over website performance and availability
**Access**: Admin Only
