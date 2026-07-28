# Website Management - Contact Page Setup

## Overview
Admin sidebar mein "Website Management" dropdown add ho gaya hai jisme "Contact Page" option hai. Yahan se contact page ki complete information manage ho sakti hai.

## Features

### ✨ Admin Panel Features:
1. **Dashboard View**
   - Total entries count
   - Published entries
   - Total locations
   - Main contact status

2. **Add Contact Information**
   - Main contact info (email, phone, address)
   - Business hours/timings
   - Google Maps link

3. **Add Multiple Locations**
   - Store/Branch locations
   - Each location ki separate details
   - Display order control

4. **Edit & Delete**
   - Inline edit functionality
   - Delete confirmation
   - Publish/Unpublish toggle

### 🌐 Frontend Display:
Contact page (`/contact`) automatically shows:
- Main contact information
- Contact form
- All store locations (if added)

## Files Created/Modified

### New Files:
1. **Controller:**
   - `app/Http/Controllers/Admin/WebsiteManagementController.php`
   - Methods: contact(), storeContact(), updateContact(), deleteContact()

2. **View:**
   - `resources/views/admin/website-management/contact.blade.php`
   - Complete management interface with modals

### Modified Files:
1. **routes/admin.php**
   - Added Website Management routes

2. **resources/views/admin/components/sidebar.blade.php**
   - Added "Website Management" dropdown
   - Added "Contact Page" menu item

## How to Use

### Step 1: Access Contact Page Management
1. Login to Admin Panel
2. Sidebar → **Website Management** → **Contact Page**

### Step 2: Add Main Contact Info
1. Click "Add Contact Info" button
2. Fill in:
   - Title: "Contact Us"
   - Description: Welcome message
   - Email, Phone, Address
   - Business Hours
   - Google Maps URL (optional)
3. Check "Publish Immediately"
4. Click "Save Contact Info"

### Step 3: Add Store Locations (Optional)
1. Click "Add Location" button
2. Fill in:
   - Location Name: "Main Store", "East Branch", etc.
   - Description
   - Contact details
   - Timings
   - Display Order (1, 2, 3...)
3. Check "Publish Immediately"
4. Click "Save Location"

### Step 4: View on Website
Visit: `http://your-domain.com/contact`

## Routes Available

### Admin Routes:
```php
GET  /admin/website-management/contact              - View all contact entries
POST /admin/website-management/contact/store        - Add new entry
PUT  /admin/website-management/contact/{id}/update  - Update entry
DELETE /admin/website-management/contact/{id}/delete - Delete entry
```

## Benefits

✅ **Centralized Management** - One place to manage all contact info
✅ **No Code Changes** - Admin can update without developer
✅ **Multiple Locations** - Support for multiple stores/branches
✅ **Real-time Updates** - Changes reflect immediately
✅ **Easy to Use** - Clean, intuitive interface
✅ **Bootstrap Modals** - Add/Edit without page reload

## Screenshots

### Admin Sidebar:
```
Dashboard
Products ▼
Orders
Stitching ▼
Customers
Receptionists
Payments
Coupons & Discounts
Reports
Website CMS
Website Management ▼
  └─ Contact Page
User Management ▼
Settings
```

### Contact Page Management:
- Statistics cards (Total, Published, Locations, Main Contact status)
- List of all contact entries
- Add/Edit/Delete buttons
- Publish status badges
- Bootstrap modals for quick add/edit

## Technical Details

### Database:
Uses existing `website_cms` table with:
- `section_type`: 'contact' or 'contact_location'
- `data` JSON field stores: email, phone, address, timings, map_url
- `is_published`: Controls visibility
- `display_order`: For sorting locations

### Frontend Integration:
- HomeController fetches data from database
- Contact page blade uses dynamic data
- Automatic fallback if no data exists

## Future Enhancements (Optional)

- Bulk actions (publish/unpublish multiple)
- Drag-and-drop reordering
- Contact form submission handling
- Email notification system
- Google Maps embed preview
- Social media links section

## Support

For any issues or questions:
- Check Laravel logs: `storage/logs/laravel.log`
- Clear cache: `php artisan cache:clear`
- Check routes: `php artisan route:list | grep website-management`
