<?php
// Database connection test for ERP application
echo "<h2>ERP Application Diagnostic Test</h2>";

// Test 1: PHP Version
echo "<h3>1. PHP Version Check</h3>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Required: PHP 8.1+ for Laravel 9<br>";
if (version_compare(phpversion(), '8.1.0', '>=')) {
    echo "<span style='color: green;'>✅ PHP version is compatible</span><br>";
} else {
    echo "<span style='color: red;'>❌ PHP version is too old</span><br>";
}

// Test 2: Required PHP Extensions
echo "<h3>2. Required PHP Extensions</h3>";
$required_extensions = ['pdo', 'pdo_mysql', 'mbstring', 'json', 'curl', 'openssl', 'tokenizer', 'xml', 'ctype', 'fileinfo'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<span style='color: green;'>✅ $ext</span><br>";
    } else {
        echo "<span style='color: red;'>❌ $ext (MISSING)</span><br>";
    }
}

// Test 3: Database Connection
echo "<h3>3. Database Connection Test</h3>";
try {
    $host = 'localhost';
    $dbname = 'sbccindi_erp';
    $username = 'sbccindi_erp';
    $password = '9Os)1dVqQKf=';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<span style='color: green;'>✅ Database connection successful</span><br>";
    
    // Test query
    $stmt = $pdo->query("SELECT VERSION() as version");
    $version = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "MySQL Version: " . $version['version'] . "<br>";
    
} catch (PDOException $e) {
    echo "<span style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</span><br>";
    echo "Please check:<br>";
    echo "- MySQL service is running<br>";
    echo "- Database 'sbccindi_erp' exists<br>";
    echo "- User 'sbccindi_erp' has proper permissions<br>";
}

// Test 4: File Permissions
echo "<h3>4. File Permissions Check</h3>";
$directories_to_check = ['storage', 'bootstrap/cache'];
foreach ($directories_to_check as $dir) {
    if (is_dir($dir)) {
        $perms = substr(sprintf('%o', fileperms($dir)), -4);
        if (is_writable($dir)) {
            echo "<span style='color: green;'>✅ $dir/ - writable (permissions: $perms)</span><br>";
        } else {
            echo "<span style='color: red;'>❌ $dir/ - not writable (permissions: $perms)</span><br>";
        }
    } else {
        echo "<span style='color: red;'>❌ $dir/ - directory not found</span><br>";
    }
}

// Test 5: Laravel Bootstrap Test
echo "<h3>5. Laravel Bootstrap Test</h3>";
try {
    if (file_exists('bootstrap/app.php')) {
        echo "<span style='color: green;'>✅ bootstrap/app.php exists</span><br>";
        
        // Try to include the bootstrap file
        $app = require_once 'bootstrap/app.php';
        echo "<span style='color: green;'>✅ Laravel application bootstrapped successfully</span><br>";
        
        // Test if we can create a kernel
        $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
        echo "<span style='color: green;'>✅ HTTP Kernel created successfully</span><br>";
        
    } else {
        echo "<span style='color: red;'>❌ bootstrap/app.php not found</span><br>";
    }
} catch (Exception $e) {
    echo "<span style='color: red;'>❌ Laravel bootstrap failed: " . $e->getMessage() . "</span><br>";
    echo "Error file: " . $e->getFile() . " line " . $e->getLine() . "<br>";
}

// Test 6: Environment Configuration
echo "<h3>6. Environment Configuration</h3>";
if (file_exists('.env')) {
    echo "<span style='color: green;'>✅ .env file exists</span><br>";
} else {
    echo "<span style='color: red;'>❌ .env file missing</span><br>";
}

// Test 7: Vendor Directory
echo "<h3>7. Composer Dependencies</h3>";
if (is_dir('vendor')) {
    echo "<span style='color: green;'>✅ vendor/ directory exists</span><br>";
    if (file_exists('vendor/autoload.php')) {
        echo "<span style='color: green;'>✅ Composer autoloader exists</span><br>";
    } else {
        echo "<span style='color: red;'>❌ Composer autoloader missing</span><br>";
    }
} else {
    echo "<span style='color: red;'>❌ vendor/ directory missing - run 'composer install'</span><br>";
}

echo "<h3>Recommended Actions:</h3>";
echo "<ol>";
echo "<li>If database connection failed, run the MySQL setup commands from the deployment guide</li>";
echo "<li>If file permissions failed, run: <code>sudo chmod -R 755 storage/ bootstrap/cache/ && sudo chown -R www-data:www-data storage/ bootstrap/cache/</code></li>";
echo "<li>If Laravel bootstrap failed, run: <code>composer install && php artisan cache:clear</code></li>";
echo "<li>Ensure the web server document root points to the 'public/' directory</li>";
echo "</ol>";

?>
