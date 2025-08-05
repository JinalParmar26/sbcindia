#!/bin/bash

# SBC India ERP Deployment Script
# This script deploys the application to the production server

echo "🚀 Starting SBC India ERP deployment..."

# Step 1: Connect to server and pull latest changes
echo "📥 Connecting to server and pulling latest code..."
ssh sbccindia@162.215.253.97 << 'ENDSSH'
    cd public_html/erp/
    
    echo "🔄 Pulling latest changes from git..."
    git pull origin main
    
    echo "📦 Installing/updating composer dependencies..."
    composer install --no-dev --optimize-autoloader
    
    echo "🧹 Clearing application cache..."
    php artisan config:clear
    php artisan cache:clear
    php artisan route:clear
    php artisan view:clear
    
    echo "⚡ Optimizing application..."
    php artisan config:cache
    php artisan optimize
    
    echo "✅ Deployment completed successfully!"
ENDSSH

echo "🎉 SBC India ERP deployment finished!"
echo "🌐 Application should now be updated on the server"
