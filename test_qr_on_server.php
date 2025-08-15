<?php
/**
 * QR Code Functionality Test Script
 * Run this on your server to test the dual QR code functionality
 * 
 * Usage: php test_qr_on_server.php
 */

echo "🧪 QR Code Functionality Test\n";
echo "=============================\n\n";

// Test URL - replace with actual order UUID
$test_uuid = '950bf0e4-f93b-49e5-9aa9-1c801cc1a3aa';
$base_url = 'https://erp.sbccindia.com/order/details/';
$test_url = $base_url . $test_uuid;

echo "Testing URL: {$test_url}\n\n";

// Test 1: Regular browser User-Agent (should return HTML)
echo "Test 1: Regular Browser/QR Scanner\n";
echo "-----------------------------------\n";
$ch1 = curl_init();
curl_setopt($ch1, CURLOPT_URL, $test_url);
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch1, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15');
curl_setopt($ch1, CURLOPT_TIMEOUT, 10);
curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);

$response1 = curl_exec($ch1);
$http_code1 = curl_getinfo($ch1, CURLINFO_HTTP_CODE);
$error1 = curl_error($ch1);
curl_close($ch1);

if ($error1) {
    echo "❌ Error: {$error1}\n";
} else {
    echo "✅ HTTP Status: {$http_code1}\n";
    if ($http_code1 == 200) {
        if (strpos($response1, '<!DOCTYPE html') !== false) {
            echo "✅ Response: HTML page (correct for regular browser)\n";
            echo "✅ Page contains: " . (strpos($response1, 'SBC Cooling Systems') !== false ? 'SBC branding' : 'content') . "\n";
        } else {
            echo "❌ Expected HTML but got something else\n";
        }
    } else {
        echo "❌ HTTP error code: {$http_code1}\n";
    }
}

echo "\n";

// Test 2: Mobile app User-Agent (should return JSON)
echo "Test 2: Mobile App with sbccIndia User-Agent\n";
echo "--------------------------------------------\n";
$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL, $test_url);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_USERAGENT, 'MyApp sbccIndia/1.0 (iPhone; iOS 14.0)');
curl_setopt($ch2, CURLOPT_TIMEOUT, 10);
curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);

$response2 = curl_exec($ch2);
$http_code2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
$error2 = curl_error($ch2);
curl_close($ch2);

if ($error2) {
    echo "❌ Error: {$error2}\n";
} else {
    echo "✅ HTTP Status: {$http_code2}\n";
    if ($http_code2 == 200) {
        $json_data = json_decode($response2, true);
        if ($json_data && isset($json_data['order_id'])) {
            echo "✅ Response: JSON data (correct for mobile app)\n";
            echo "✅ Order ID: {$json_data['order_id']}\n";
            echo "✅ Status: {$json_data['status']}\n";
            if (isset($json_data['order_title'])) {
                echo "✅ Order Title: {$json_data['order_title']}\n";
            }
            if (isset($json_data['customer_name'])) {
                echo "✅ Customer: {$json_data['customer_name']}\n";
            }
        } else {
            echo "❌ Expected JSON but got: " . substr($response2, 0, 100) . "...\n";
        }
    } else {
        echo "❌ HTTP error code: {$http_code2}\n";
    }
}

echo "\n";

// Summary
echo "📋 Test Summary\n";
echo "===============\n";
if ($http_code1 == 200 && $http_code2 == 200) {
    if (strpos($response1, '<!DOCTYPE html') !== false && 
        json_decode($response2, true) && 
        isset(json_decode($response2, true)['order_id'])) {
        echo "🎉 SUCCESS: QR code dual functionality is working correctly!\n";
        echo "✅ Regular scanners get HTML page\n";
        echo "✅ Mobile app gets JSON data\n";
    } else {
        echo "⚠️  PARTIAL: Some functionality may not be working as expected\n";
    }
} else {
    echo "❌ FAILED: Check your deployment and server configuration\n";
}

echo "\n";
echo "💡 Notes:\n";
echo "- Replace the test UUID with an actual order UUID from your database\n";
echo "- Make sure the order exists in your database\n";
echo "- Check that your web server (Apache/Nginx) is properly configured\n";
echo "- Verify that Laravel routes are working: php artisan route:list\n";

?>
