#!/bin/bash

# SBC India QR Code Deployment Script
# This script will deploy the QR code functionality to the server

echo "=== SBC India QR Code Deployment ==="
echo "Deploying to: sbccindia@162.215.253.97"
echo "Date: $(date)"
echo

# Check if we can connect to the server
echo "Testing SSH connection..."
if ssh -o ConnectTimeout=10 sbccindia@162.215.253.97 "echo 'SSH connection successful'"; then
    echo "✅ SSH connection established"
else
    echo "❌ SSH connection failed"
    exit 1
fi

echo
echo "=== Deploying Changes ==="

# Pull the latest changes from Git
echo "1. Pulling latest changes from Git..."
ssh sbccindia@162.215.253.97 "cd public_html/erp/ && git pull origin main"

# Clear Laravel caches
echo "2. Clearing Laravel caches..."
ssh sbccindia@162.215.253.97 "cd public_html/erp/ && php artisan optimize:clear"

# Optimize Laravel
echo "3. Optimizing Laravel..."
ssh sbccindia@162.215.253.97 "cd public_html/erp/ && php artisan optimize"

echo
echo "=== Deployment Commands Summary ==="
echo "The following commands will be executed on the server:"
echo "cd public_html/erp/"
echo "git pull origin main"
echo "php artisan optimize:clear"
echo "php artisan optimize"

echo
echo "=== Manual Deployment Instructions ==="
echo "If automatic deployment fails, run these commands manually:"
echo
echo "1. SSH to server:"
echo "   ssh sbccindia@162.215.253.97"
echo
echo "2. Navigate to project directory:"
echo "   cd public_html/erp/"
echo
echo "3. Pull latest changes:"
echo "   git pull origin main"
echo
echo "4. Clear caches:"
echo "   php artisan optimize:clear"
echo
echo "5. Optimize:"
echo "   php artisan optimize"
echo
echo "=== Testing the QR Code Functionality ==="
echo "After deployment, test with:"
echo
echo "Regular browser (should return HTML):"
echo 'curl -H "User-Agent: Mozilla/5.0" "https://erp.sbccindia.com/order/details/YOUR_ORDER_UUID"'
echo
echo "Mobile app (should return JSON):"
echo 'curl -H "User-Agent: sbccIndia/1.0" "https://erp.sbccindia.com/order/details/YOUR_ORDER_UUID"'
echo
echo "=== Files Modified ==="
echo "✅ app/Http/Controllers/OrderController.php - Added User-Agent detection"
echo "✅ QR_CODE_INTEGRATION.md - Documentation added"
echo
echo "Deployment completed!"
