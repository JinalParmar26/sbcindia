#!/bin/bash

# PERMANENT DomPDF Installation Fix
# This script will install DomPDF properly and stop the recurring error

echo "=== PERMANENT DomPDF Installation Fix ==="
echo "Server: $(hostname)"
echo "Current directory: $(pwd)"

# Step 1: Check if we're in the right directory
if [ ! -f "composer.json" ]; then
    echo "❌ ERROR: composer.json not found. Please run this script from your Laravel project root."
    exit 1
fi

# Step 2: Check current composer.json content
echo "1. Checking current composer.json..."
if grep -q "barryvdh/laravel-dompdf" composer.json; then
    echo "✅ DomPDF already in composer.json"
else
    echo "❌ DomPDF not in composer.json"
fi

# Step 3: Check if vendor directory exists
if [ ! -d "vendor" ]; then
    echo "❌ vendor directory missing - running composer install first"
    composer install --no-dev --optimize-autoloader
fi

# Step 4: Check if DomPDF is already installed
if [ -d "vendor/barryvdh/laravel-dompdf" ]; then
    echo "✅ DomPDF package found in vendor directory"
else
    echo "❌ DomPDF package not found in vendor directory"
fi

# Step 5: Install/Update DomPDF
echo "2. Installing DomPDF package..."

# Method 1: Try normal installation
echo "   Trying normal installation..."
if composer require barryvdh/laravel-dompdf:^1.0 --no-interaction; then
    echo "✅ Normal installation successful"
else
    echo "❌ Normal installation failed, trying alternative methods..."
    
    # Method 2: Try with ignore platform requirements
    echo "   Trying with ignore platform requirements..."
    if composer require barryvdh/laravel-dompdf:^1.0 --ignore-platform-reqs --no-interaction; then
        echo "✅ Installation with ignore platform requirements successful"
    else
        echo "❌ Installation with ignore platform requirements failed"
        
        # Method 3: Try with memory limit
        echo "   Trying with increased memory limit..."
        if php -d memory_limit=512M $(which composer) require barryvdh/laravel-dompdf:^1.0 --no-interaction; then
            echo "✅ Installation with increased memory successful"
        else
            echo "❌ All automatic installation methods failed"
            echo "❌ Manual installation required - see instructions below"
        fi
    fi
fi

# Step 6: Update autoloader
echo "3. Updating autoloader..."
composer dump-autoload --optimize

# Step 7: Check installation
echo "4. Verifying installation..."
if [ -d "vendor/barryvdh/laravel-dompdf" ]; then
    echo "✅ DomPDF package successfully installed"
    
    # Step 8: Enable service provider
    echo "5. Enabling DomPDF service provider..."
    
    # Uncomment the service provider
    if grep -q "// Barryvdh" config/app.php; then
        sed -i 's|// Barryvdh\\DomPDF\\ServiceProvider::class, // Uncomment after package installation|Barryvdh\\DomPDF\\ServiceProvider::class,|g' config/app.php
        echo "✅ Service provider enabled"
    else
        echo "⚠️  Service provider already enabled or not found"
    fi
    
    # Uncomment the facade
    if grep -q "// 'PDF'" config/app.php; then
        sed -i "s|// 'PDF' => Barryvdh\\\\DomPDF\\\\Facade::class, // Uncomment after package installation|'PDF' => Barryvdh\\\\DomPDF\\\\Facade::class,|g" config/app.php
        echo "✅ PDF facade enabled"
    else
        echo "⚠️  PDF facade already enabled or not found"
    fi
    
    # Step 9: Clear caches
    echo "6. Clearing caches..."
    php artisan config:clear
    php artisan cache:clear
    php artisan route:clear
    php artisan view:clear
    
    echo ""
    echo "🎉 SUCCESS! DomPDF has been installed successfully!"
    echo ""
    echo "Test the installation:"
    echo "   php artisan tinker"
    echo "   PDF::loadHTML('<h1>Test</h1>')->save('test.pdf')"
    
else
    echo "❌ FAILED: DomPDF package not found after installation"
    echo ""
    echo "MANUAL INSTALLATION REQUIRED:"
    echo "1. Check your composer version: composer --version"
    echo "2. Try: composer update"
    echo "3. Try: composer require barryvdh/laravel-dompdf:^1.0"
    echo "4. Check PHP version: php -v"
    echo "5. Check available memory: php -m"
fi

echo ""
echo "=== Installation Complete ==="
