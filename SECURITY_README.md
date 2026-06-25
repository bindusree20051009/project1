# 🔒 Security Updates & Setup Instructions

## Critical Changes Made

### 1. ✅ Removed Hardcoded Credentials
- **BEFORE**: Admin credentials were hardcoded in PHP files
- **AFTER**: Credentials stored in database with bcrypt hashing

### 2. ✅ Added CSRF Protection
- All forms now include CSRF tokens
- Session-based token validation on all POST requests

### 3. ✅ Password Security
- Passwords now hashed using bcrypt
- Prepared statements prevent SQL injection
- Input validation and sanitization improved

### 4. ✅ Added Security Headers
- Content Security Policy (CSP)
- X-Frame-Options: SAMEORIGIN
- X-Content-Type-Options: nosniff
- X-XSS-Protection enabled

### 5. ✅ Input Validation
- Email format validation
- Phone number validation (10 digits)
- HTML entity encoding for output
- Trim and filter all inputs

---

## 🚀 Setup Instructions

### Step 1: Update Database Schema
1. Go to your cPanel/Admin Panel on profreehosting
2. Open **phpMyAdmin**
3. Create a new database called `hari_carpenter`
4. Go to the **SQL** tab
5. Copy and paste the entire `database.sql` file
6. Click **Go**

### Step 2: Set Admin Password
Run this command in your terminal or phpMyAdmin SQL tab:

```sql
UPDATE admins SET password = '$2y$10$dXVm8HqZU8zIeqBg6P1J3eZxF/2E3C4D5E6F7G8H9I0J1K2L3M4N5O6' 
WHERE username = 'admin';
```

To generate your own password hash:
```bash
php -r "echo password_hash('YourNewSecurePassword123!', PASSWORD_BCRYPT);"
```

Then update the password field with the generated hash.

### Step 3: Update Database Credentials (if needed)
Edit `db_config.php` with your hosting provider's credentials:
```php
$db_host = "your_host";
$db_username = "your_username";
$db_password = "your_password";
$db_name = "hari_carpenter";
```

### Step 4: Add .htaccess Security Headers
Create a `.htaccess` file in your root directory:

```apache
<IfModule mod_headers.c>
    # Prevent clickjacking
    Header always set X-Frame-Options "SAMEORIGIN"
    
    # Prevent MIME type sniffing
    Header always set X-Content-Type-Options "nosniff"
    
    # Enable XSS Protection
    Header always set X-XSS-Protection "1; mode=block"
    
    # Enforce HTTPS
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
    
    # Content Security Policy
    Header always set Content-Security-Policy "default-src 'self'; script-src 'self' https://cdn.jsdelivr.net; style-src 'self' https://cdn.jsdelivr.net 'unsafe-inline'; font-src 'self' https://cdn.jsdelivr.net; img-src 'self' data: https:;"
</IfModule>

# Disable directory listing
Options -Indexes

# Protect sensitive files
<FilesMatch "\.env|\.git|database\.sql|db_config\.php">
    Order allow,deny
    Deny from all
</FilesMatch>

# Enable GZIP compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE text/javascript
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
    AddOutputFilterByType DEFLATE application/x-httpd-php
</IfModule>
```

---

## 🔑 Important Security Notes

1. **Change Default Password Immediately**
   - The default admin password MUST be changed after setup
   - Use a strong password (min 12 characters, mix of upper/lower/numbers/symbols)

2. **Never Commit Credentials**
   - Don't commit `db_config.php` with real credentials to GitHub
   - Use environment variables in production

3. **Enable HTTPS**
   - Contact your hosting provider to enable SSL/TLS
   - Update all links from `http://` to `https://`

4. **Regular Backups**
   - Backup your database regularly
   - Keep a secure copy of admin credentials

5. **Monitor Logins**
   - Check the `last_login` timestamp in the admins table
   - Set up email alerts for failed login attempts

6. **Rate Limiting**
   - Consider adding rate limiting to prevent brute force attacks
   - Implement login attempt tracking

---

## ✅ Testing Security

After deployment:
1. Test admin login with new credentials
2. Verify CSRF tokens are working (disable them and forms should fail)
3. Test form submissions
4. Check browser console for CSP violations
5. Run a security scan at: https://securityheaders.com/

---

## 📞 Support

If you need further security improvements:
- Add 2FA (Two-Factor Authentication)
- Implement rate limiting on bookings
- Add email verification for bookings
- Set up automated backups
- Enable Web Application Firewall (WAF)

---

**Last Updated**: 2026-06-25
**Status**: 🟢 Security Hardened
