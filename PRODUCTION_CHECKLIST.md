# 🚀 Production Deployment Checklist

## ✅ Pre-Deployment Checklist

### 1. Environment Configuration
- [ ] **APP_ENV** set to `production` in `.env`
- [ ] **APP_DEBUG** set to `false` in `.env`
- [ ] **APP_URL** set to production domain (e.g., `https://yourdomain.com`)
- [ ] **APP_KEY** generated and set (run `php artisan key:generate`)
- [ ] **APP_TIMEZONE** configured correctly (currently UTC)

### 2. Database Configuration
- [ ] **DB_CONNECTION** set to `mysql` (not sqlite) for production
- [ ] **DB_HOST**, **DB_PORT**, **DB_DATABASE**, **DB_USERNAME**, **DB_PASSWORD** configured
- [ ] Database created and migrations run (`php artisan migrate --force`)
- [ ] Database seeders run (if needed) with proper configuration
- [ ] Database backup strategy in place

### 3. Security Settings
- [ ] **Password hashing** using Laravel's Hash facade (✅ Already configured)
- [ ] **Security headers middleware** enabled (✅ Already configured)
  - HSTS (Strict-Transport-Security)
  - X-Frame-Options
  - X-Content-Type-Options
  - Content-Security-Policy
- [ ] **HTTPS** enabled and SSL certificate configured
- [ ] **Session encryption** enabled if needed
- [ ] **CSRF protection** enabled (✅ Laravel default)
- [ ] **Authentication middleware** properly configured (✅ Already configured)

### 4. Performance Optimization
- [ ] **Cache driver** configured (currently `database`, consider `redis` for production)
- [ ] **Session driver** configured (currently `database`, consider `redis` for production)
- [ ] **Queue driver** configured (currently `sync`, consider `redis` or `database` for production)
- [ ] **Config cache** enabled: `php artisan config:cache`
- [ ] **Route cache** enabled: `php artisan route:cache`
- [ ] **View cache** enabled: `php artisan view:cache`
- [ ] **Event cache** enabled: `php artisan event:cache`
- [ ] **Optimized autoloader**: `composer install --optimize-autoloader --no-dev`
- [ ] **Assets compiled**: `npm run build` (for production build)

### 5. Logging & Monitoring
- [ ] **LOG_CHANNEL** configured (currently `stack`)
- [ ] **LOG_LEVEL** set appropriately (use `error` or `warning` for production)
- [ ] Log rotation configured (daily logs enabled)
- [ ] Error tracking service configured (if needed)
- [ ] **Audit logs** table created and functional (✅ Already configured)

### 6. File Permissions
- [ ] `storage/` directory writable (755 or 775)
- [ ] `bootstrap/cache/` directory writable (755 or 775)
- [ ] `public/` directory readable (755)
- [ ] `.env` file secure (600 or 640)
- [ ] `.gitignore` properly configured (don't commit `.env`)

### 7. Server Configuration
- [ ] **PHP version** >= 8.2 (Laravel 11 requirement)
- [ ] **PHP extensions** installed:
  - BCMath
  - Ctype
  - cURL
  - DOM
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PCRE
  - PDO
  - Tokenizer
  - XML
- [ ] **Web server** configured (Apache/Nginx)
- [ ] **Document root** set to `public/` directory
- [ ] **URL rewriting** enabled
- [ ] **PHP-FPM** configured (if using Nginx)

### 8. Application Features
- [ ] **Dark/Light mode** toggle working (✅ Already configured)
- [ ] **Role-based access control** working (Admin, LS, SDC, RO)
- [ ] **Migrations** consolidated and tested (✅ Completed)
- [ ] **Models** match migrations (✅ Verified)
- [ ] **Seeders** configured and tested
- [ ] **Form validation** working
- [ ] **File uploads** working (if applicable)
- [ ] **Email configuration** (if sending emails)

### 9. Data & Backups
- [ ] **Database backup** strategy in place
- [ ] **File storage backup** strategy (if storing files)
- [ ] **Backup testing** - verify backups can be restored
- [ ] **Data retention policy** defined

### 10. Testing
- [ ] **Unit tests** run and passing (if available)
- [ ] **Feature tests** run and passing (if available)
- [ ] **Manual testing** completed:
  - User registration/login
  - Role-based access
  - CRUD operations
  - Form submissions
  - File uploads (if applicable)
  - Dark/light mode toggle

### 11. Documentation
- [ ] **API documentation** (if applicable)
- [ ] **User manual** (if applicable)
- [ ] **Admin documentation**
- [ ] **Deployment documentation**
- [ ] **Environment variables** documented

### 12. Post-Deployment
- [ ] **Health check endpoint** tested (`/up`)
- [ ] **Maintenance mode** tested (`php artisan down/up`)
- [ ] **Error pages** tested (404, 500, etc.)
- [ ] **Monitoring** set up
- [ ] **Alerting** configured
- [ ] **Performance monitoring** set up

---

## 🔧 Production Commands

### Initial Setup
```bash
# Install dependencies (production)
composer install --optimize-autoloader --no-dev

# Build assets
npm install
npm run build

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### Maintenance Mode
```bash
# Enable maintenance mode
php artisan down

# Disable maintenance mode
php artisan up

# With secret (bypass URL)
php artisan down --secret="your-secret-key"
```

### Optimization
```bash
# Optimize autoloader
composer dump-autoload --optimize

# Clear all caches
php artisan optimize:clear

# Re-optimize
php artisan optimize
```

---

## ⚠️ Important Notes

1. **APP_DEBUG must be false** in production to prevent exposing sensitive information
2. **APP_KEY must be unique** and kept secret
3. **Database credentials** should be strong and unique
4. **HTTPS is required** for security headers to work properly
5. **Session security** - consider using `database` or `redis` driver instead of `file`
6. **Cache driver** - consider `redis` for better performance
7. **Queue driver** - use `database` or `redis` instead of `sync` for production

---

## 🔒 Security Recommendations

1. **Use Redis** for cache and sessions (better performance and security)
2. **Enable HTTPS** - SSL certificate required
3. **Regular updates** - Keep Laravel and dependencies updated
4. **Strong passwords** - Enforce password policies
5. **Rate limiting** - Consider implementing rate limiting for API endpoints
6. **SQL injection** - Laravel's Eloquent ORM protects against this (✅ Already using)
7. **XSS protection** - Blade templates escape by default (✅ Already using)
8. **CSRF protection** - Enabled by default (✅ Already configured)

---

## 📊 Current Status

### ✅ Already Configured
- Security headers middleware (SecurityHeaders.php)
- Maintenance mode middleware (CustomMaintenanceMode.php)
- Role-based access control (Admin, LS, SDC, RO)
- Password hashing (Laravel Hash)
- Audit logging (AuditLog model)
- Error handling (Laravel default)
- CSRF protection (Laravel default)
- XSS protection (Blade escaping)
- SQL injection protection (Eloquent ORM)
- Migrations consolidated
- Models verified against migrations

### ⚠️ Needs Configuration
- APP_DEBUG = false (in production .env)
- APP_ENV = production (in production .env)
- Database connection (use MySQL, not SQLite)
- Cache driver (consider Redis)
- Session driver (consider Redis)
- Queue driver (use database or Redis)
- HTTPS/SSL certificate
- Log level (set to error/warning)
- File permissions
- Backup strategy

---

## 🚀 Quick Production Setup

```bash
# 1. Set environment to production
APP_ENV=production
APP_DEBUG=false

# 2. Install production dependencies
composer install --optimize-autoloader --no-dev

# 3. Build assets
npm run build

# 4. Generate key (if not already done)
php artisan key:generate

# 5. Run migrations
php artisan migrate --force

# 6. Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 7. Set proper permissions
chmod -R 755 storage bootstrap/cache
chmod 600 .env
```

---

## 📝 Notes
- Replace `yourdomain.com` with your actual domain
- Adjust file permissions based on your server setup
- Consider using a process manager like Supervisor for queue workers
- Set up monitoring and alerting for production
- Regular backups are essential
- Keep Laravel and dependencies updated for security patches

