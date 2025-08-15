# Manual Deployment Instructions for SBC India QR Code Functionality

## 🚀 Quick Deployment Guide

### Step 1: Connect to Server
```bash
ssh sbccindia@162.215.253.97
# Password: chintansbc@2022
```

### Step 2: Navigate to Project Directory
```bash
cd public_html/erp/
```

### Step 3: Pull Latest Changes
```bash
git pull origin main
```

### Step 4: Clear Laravel Caches
```bash
php artisan optimize:clear
```

### Step 5: Optimize Laravel
```bash
php artisan optimize
```

## ✅ What Was Changed

### Files Modified:
1. **`app/Http/Controllers/OrderController.php`**
   - Added User-Agent detection in `publicOrderDetails()` method
   - Returns JSON for mobile apps (User-Agent contains 'sbccIndia')
   - Returns HTML for regular browsers/QR scanners

2. **`QR_CODE_INTEGRATION.md`** (NEW)
   - Complete documentation for mobile app integration
   - Examples for iOS, Android, React Native, Flutter
   - Testing instructions

## 🧪 Testing After Deployment

### Test 1: Regular Browser/QR Scanner (should return HTML)
```bash
curl -H "User-Agent: Mozilla/5.0" "https://erp.sbccindia.com/order/details/950bf0e4-f93b-49e5-9aa9-1c801cc1a3aa"
```

### Test 2: Mobile App (should return JSON)
```bash
curl -H "User-Agent: sbccIndia/1.0" "https://erp.sbccindia.com/order/details/950bf0e4-f93b-49e5-9aa9-1c801cc1a3aa"
```

Expected JSON response:
```json
{
  "order_id": "950bf0e4-f93b-49e5-9aa9-1c801cc1a3aa",
  "order_title": "Order Title",
  "customer_name": "Customer Name",
  "created_at": "2025-08-15 10:30:00",
  "status": "success"
}
```

## 📱 Mobile App Integration

Your mobile app needs to set User-Agent header containing 'sbccIndia':

### iOS (Swift)
```swift
var request = URLRequest(url: url)
request.setValue("YourApp sbccIndia/1.0", forHTTPHeaderField: "User-Agent")
```

### Android (Java)
```java
connection.setRequestProperty("User-Agent", "YourApp sbccIndia/1.0");
```

### React Native
```javascript
fetch(url, {
  headers: { 'User-Agent': 'YourApp sbccIndia/1.0' }
})
```

## 🔍 Troubleshooting

### If deployment fails:
1. Check Git repository access:
   ```bash
   git status
   git remote -v
   ```

2. Check Laravel permissions:
   ```bash
   ls -la storage/
   chmod -R 775 storage/
   ```

3. Check PHP version:
   ```bash
   php --version
   ```

### If QR codes don't work:
1. Check web server configuration (Apache/Nginx)
2. Verify the route exists:
   ```bash
   php artisan route:list | grep "order/details"
   ```
3. Check Laravel logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```

## 🎯 Key Benefits

✅ **Same QR Code**: Works for both mobile app and browsers  
✅ **No Breaking Changes**: Existing functionality preserved  
✅ **Simple Integration**: Just modify User-Agent header  
✅ **Backward Compatible**: All existing QR codes continue working  

## 📋 Deployment Checklist

- [ ] SSH to server: `ssh sbccindia@162.215.253.97`
- [ ] Navigate to project: `cd public_html/erp/`
- [ ] Pull changes: `git pull origin main`
- [ ] Clear caches: `php artisan optimize:clear`
- [ ] Optimize: `php artisan optimize`
- [ ] Test regular QR scan (browser)
- [ ] Test mobile app QR scan (JSON)
- [ ] Verify existing functionality still works

## 🆘 Emergency Rollback

If something goes wrong:
```bash
git log --oneline -5
git reset --hard HEAD~1  # Go back one commit
php artisan optimize:clear
php artisan optimize
```

---
**Deployment Date**: August 15, 2025  
**Git Repository**: https://github.com/JinalParmar26/sbcindia  
**Server**: sbccindia@162.215.253.97
