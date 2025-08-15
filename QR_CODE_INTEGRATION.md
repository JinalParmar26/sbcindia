# QR Code Integration Documentation

## Overview
The QR code system now supports dual functionality:
- **Regular QR scanners/cameras**: Display full HTML order details page
- **Mobile app**: Return JSON data with order information

## How It Works
The system detects the source of the request by checking the `User-Agent` header:
- If `User-Agent` contains `sbccIndia` → Returns JSON
- Otherwise → Returns HTML page

## URL Structure
```
https://erp.sbccindia.com/order/details/{order-uuid}
```

## Mobile App Implementation

### 1. iOS (Swift)
```swift
var request = URLRequest(url: url)
request.setValue("YourAppName sbccIndia/1.0", forHTTPHeaderField: "User-Agent")

URLSession.shared.dataTask(with: request) { data, response, error in
    // Handle JSON response
}.resume()
```

### 2. Android (Java/Kotlin)
```java
HttpURLConnection connection = (HttpURLConnection) url.openConnection();
connection.setRequestProperty("User-Agent", "YourAppName sbccIndia/1.0");

// For OkHttp
Request request = new Request.Builder()
    .url(url)
    .header("User-Agent", "YourAppName sbccIndia/1.0")
    .build();
```

### 3. React Native
```javascript
fetch(url, {
  headers: {
    'User-Agent': 'YourAppName sbccIndia/1.0'
  }
})
.then(response => response.json())
.then(data => console.log(data));
```

### 4. Flutter
```dart
final response = await http.get(
  Uri.parse(url),
  headers: {
    'User-Agent': 'YourAppName sbccIndia/1.0',
  },
);

if (response.statusCode == 200) {
  final data = json.decode(response.body);
  // Handle JSON data
}
```

### 5. WebView Integration
If using a WebView in your app:
```javascript
// Set custom User-Agent for WebView
webView.setUserAgent("YourAppName sbccIndia/1.0");
```

## JSON Response Format
When accessed from mobile app:
```json
{
  "order_id": "950bf0e4-f93b-49e5-9aa9-1c801cc1a3aa",
  "order_title": "Sample Order Title",
  "customer_name": "Customer Name",
  "created_at": "2025-08-15 10:30:00",
  "status": "success"
}
```

## Testing

### Command Line Testing
```bash
# Test regular browser behavior (returns HTML)
curl -H "User-Agent: Mozilla/5.0" "https://erp.sbccindia.com/order/details/YOUR_ORDER_UUID"

# Test mobile app behavior (returns JSON)
curl -H "User-Agent: sbccIndia/1.0" "https://erp.sbccindia.com/order/details/YOUR_ORDER_UUID"
```

### Browser Testing
1. Open browser developer tools
2. Navigate to Network tab
3. Visit the QR URL
4. Modify User-Agent in browser settings or use browser extensions
5. Reload page to test different behaviors

## QR Code Generation
The QR codes should contain the full URL:
```
https://erp.sbccindia.com/order/details/{order-uuid}
```

## Error Handling
- Invalid UUID: Returns 404 error
- Order not found: Returns 404 error
- Server errors: Returns appropriate HTTP error codes

## Security Considerations
- No authentication required for public order details
- Only basic order information is exposed in JSON
- Sensitive information remains protected in the full HTML view

## Implementation Notes
1. The `sbccIndia` string in User-Agent is case-sensitive
2. You can include additional information in User-Agent (app version, platform, etc.)
3. The check uses `strpos()` so `sbccIndia` can appear anywhere in the User-Agent string
4. Recommended User-Agent format: `AppName sbccIndia/Version Platform`

## Benefits
1. **Backward Compatibility**: Existing QR codes continue to work
2. **App Integration**: Mobile apps get structured data
3. **Flexibility**: Same QR code works for both use cases
4. **Simple Implementation**: Just modify User-Agent header
