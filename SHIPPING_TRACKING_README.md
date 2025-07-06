# Shipping Tracking Feature

This document explains how to use the shipping tracking feature in the Laravel Fashion Shop application.

## Features

### 1. Track Shipping Status

- View current shipping status (pending, shipped, delivered)
- Visual progress indicator with status timeline
- Shipping cost and courier information
- Shipping address details

### 2. Real-time Tracking

- Integration with RajaOngkir API for live tracking
- Detailed tracking history with timestamps
- Location-based tracking updates

### 3. User Authentication

- Only authenticated users can track orders
- Users can only track their own orders
- Secure access control

## Usage

### For Users

1. **Access Tracking**:
   - Go to your order details page
   - If the order has been shipped, you'll see a "Track Shipping" button
   - Click the button to view tracking information

2. **Track via URL**:
   - Direct URL: `/tracking/{order_id}`
   - Replace `{order_id}` with your actual order ID

### For Administrators

1. **Set Tracking Number**:
   - Access the admin panel
   - Go to order management
   - Add tracking number when marking order as shipped

2. **Update Shipping Status**:
   - Update order status to "shipped" with tracking number
   - Set delivery status when package is delivered

## API Integration

### RajaOngkir API

- **Endpoint**: `https://rajaongkir.komerce.id/api/v1/track/waybill`
- **Method**: POST
- **Authentication**: API Key (configured in `config/rajaongkir_api.php`)

### Required Parameters

- `courier`: Shipping courier (jne, pos, tiki)
- `waybill`: Tracking number

## View Structure

### Main View File

- **Location**: `resources/views/shipping/show.blade.php`
- **Layout**: Extends `user.layout`
- **Features**:
  - Responsive design with Bootstrap
  - Visual status indicators
  - Timeline view for tracking history
  - Mobile-friendly interface

### Key Components

1. **Order Information**: Order ID, status, date
2. **Shipping Details**: Courier, cost, tracking number
3. **Status Progress**: Visual progress bar and timeline
4. **Shipping Address**: Complete delivery address
5. **Tracking Information**: Real-time tracking data
6. **Order Items**: List of items in the order

## Error Handling

### Common Errors

- **No tracking number**: Displays message when tracking number is not available
- **API failure**: Shows error message when RajaOngkir API is unavailable
- **Unauthorized access**: Redirects to login or shows access denied
- **Order not found**: Shows 404 error for non-existent orders

## Security

### Authentication

- Route protected with `auth` middleware
- Controller checks user authentication
- Validates order ownership

### Authorization

- Users can only track their own orders
- Admin routes separated from user routes

## Styling

### CSS Classes

- Custom timeline styling
- Status indicators with color coding
- Responsive card layouts
- Bootstrap integration

### Color Scheme

- **Success**: Green (delivered status)
- **Info**: Blue (shipped status)
- **Warning**: Yellow (pending status)
- **Primary**: Blue (buttons and links)

## Routes

### User Routes

- `GET /tracking/{id}` - View tracking information
- **Route Name**: `shipping.showTracking`
- **Middleware**: `auth`

### Admin Routes

- `POST /admin/shipping/tracking/add/{id}` - Add tracking number
- `GET /admin/shipping/tracking/set-delivered/{id}` - Mark as delivered

## Configuration

### Environment Variables

```env
RAJAONGKIR_API_KEY=your_api_key_here
RAJAONGKIR_ORIGIN=501  # Your origin city ID
```

### Config File

- **Location**: `config/rajaongkir_api.php`
- **Key**: API key for RajaOngkir service

## Database Schema

### Shipping Table

- `order_id`: Foreign key to orders table
- `tracking_number`: Courier tracking number
- `courier`: Shipping courier name
- `status`: Current shipping status
- `shipped_at`: Timestamp when shipped
- `delivered_at`: Timestamp when delivered
- `address`: Complete shipping address
- `phone`: Contact phone number

## Testing

### Manual Testing

1. Create a test order
2. Add shipping information
3. Set tracking number
4. Access tracking URL
5. Verify data display and API integration

### Automated Testing

- Unit tests for controller methods
- Integration tests for API calls
- Feature tests for user flows

## Troubleshooting

### Common Issues

1. **Tracking not working**: Check API key configuration
2. **Access denied**: Verify user authentication and order ownership
3. **Styling issues**: Check Bootstrap CDN and custom CSS
4. **API errors**: Verify RajaOngkir service status

### Debug Steps

1. Check Laravel logs: `storage/logs/laravel.log`
2. Verify API configuration: `config/rajaongkir_api.php`
3. Test API connection manually
4. Check database relationships

## Future Enhancements

### Potential Features

- SMS/Email notifications for status updates
- Push notifications for mobile apps
- Multiple courier support
- Delivery photo uploads
- Customer delivery ratings
- Estimated delivery time predictions

### API Improvements

- Caching for frequently accessed tracking data
- Batch tracking requests
- Webhook integration for real-time updates
