# Tailor Management Module - Quick Reference Card

## File Locations

### Views
```
resources/views/receptionist/tailors/
├── index.blade.php                 (List tailors)
├── assign-form.blade.php           (Assign order form)
├── show.blade.php                  (Tailor details)
├── assignment-details.blade.php    (Confirmation)
├── tailor-orders.blade.php         (Orders list)
└── tailor-dashboard.blade.php      (Dashboard)
```

### Controllers
```
app/Http/Controllers/Apps/
└── TailorController.php            (9 methods)
```

### Routes
```
routes/receptionist.php             (9 routes)
```

---

## URL Mapping

| Page | URL | Method | Route Name |
|------|-----|--------|-----------|
| List | `/receptionist/tailors` | GET | `receptionist.tailors.index` |
| Details | `/receptionist/tailors/{id}` | GET | `receptionist.tailors.show` |
| Assign Form | `/receptionist/tailors/assign-form` | GET | `receptionist.tailors.assign-form` |
| Assign Order | `/receptionist/tailors/assign-order` | POST | `receptionist.tailors.assign-order` |
| Confirmation | `/receptionist/tailors/{id}/details` | GET | `receptionist.tailors.show-assignment` |
| Orders | `/receptionist/tailors/{id}/orders` | GET | `receptionist.tailors.tailor-orders` |
| Dashboard | `/receptionist/tailors/{id}/dashboard` | GET | `receptionist.tailors.view-dashboard` |
| Workload | `/receptionist/tailors/{id}/workload` | GET | `receptionist.tailors.workload` |
| Availability | `/receptionist/tailors/{id}/availability` | GET | `receptionist.tailors.availability` |

---

## Controller Methods Summary

### TailorController

```php
// List tailors
public function index(Request $request)
→ GET /receptionist/tailors
→ Returns: tailors list, specializations

// Show tailor details
public function show(Tailor $tailor)
→ GET /receptionist/tailors/{id}
→ Returns: profile, stats, recent assignments

// Assignment form
public function assignForm()
→ GET /receptionist/tailors/assign-form
→ Returns: pending orders, active tailors

// Assign order
public function assignOrder(Request $request)
→ POST /receptionist/tailors/assign-order
→ Requires: order_id, tailor_id, delivery_date
→ Returns: redirect to confirmation

// Show assignment
public function showAssignment(StitchingOrder $stitchingOrder)
→ GET /receptionist/tailors/{id}/assignment-details
→ Returns: confirmation page

// Get workload (JSON)
public function getWorkload(Tailor $tailor)
→ GET /receptionist/tailors/{id}/workload
→ Returns: JSON {active, pending, completed, total}

// Get availability (JSON)
public function getAvailability(Tailor $tailor)
→ GET /receptionist/tailors/{id}/availability
→ Returns: JSON {slots_available, is_available}

// Get tailor orders
public function getTailorOrders(Tailor $tailor, Request $request)
→ GET /receptionist/tailors/{id}/orders
→ Query: status (optional)
→ Returns: paginated orders list

// View dashboard
public function viewDashboard(Tailor $tailor)
→ GET /receptionist/tailors/{id}/dashboard
→ Returns: workload breakdown, recent orders, charts
```

---

## Model Relationships

### Tailor Model
```php
Tailor::with('user')              // User relationship
Tailor::where('status', 'active') // Only active
```

### User Model (Tailor)
```php
User::hasRole('tailor')           // Role check
User::tailorAssignments()         // Stitching orders
```

### StitchingOrder Model
```php
StitchingOrder::belongsTo(User::class, 'tailor_id')
StitchingOrder::belongsTo(Order::class)
StitchingOrder::belongsTo(CustomerMeasurement::class)
```

---

## Important Database Fields

### Tailor Table
- `id` (PK)
- `user_id` (FK)
- `phone`
- `specialization`
- `skills` (JSON)
- `status` (active/inactive/on_leave)
- `specialization`
- `experience_years`
- `hourly_rate`
- `average_rating`

### StitchingOrder Table
- `id` (PK)
- `order_id` (FK)
- `tailor_id` (FK to User)
- `measurement_id` (FK)
- `stitching_status` (assigned/accepted/in_progress/completed/etc)
- `assigned_date`
- `start_date`
- `completion_date`
- `estimated_cost`
- `additional_instructions`

---

## Status Values

### Stitching Order Statuses
```
pending              - Not yet assigned
assigned             - Assigned but not accepted
accepted             - Tailor accepted
in_progress          - Work started
ready_for_fitting    - Ready for customer fitting
in_fitting           - Customer fitting
completed            - Work finished
cancelled            - Cancelled
```

### Tailor Statuses
```
active               - Available for work
inactive             - Not available
on_leave             - Temporarily unavailable
```

---

## Validation Rules

### Assignment Form
```php
'stitching_order_id' => 'required|exists:stitching_orders,id'
'tailor_id'          => 'required|exists:users,id'
'delivery_date'      => 'required|date|after:today'
'instructions'       => 'nullable|string|max:500'
```

### Additional Checks
```php
// Order must be pending
if ($order->stitching_status !== 'pending') throw error

// User must be tailor
if (!$user->hasRole('tailor')) throw error

// Tailor must be active
if ($tailor->status !== 'active') throw error
```

---

## View Data Flow

### index.blade.php
```php
$tailors         // Paginated collection (15 per page)
$specializations // Array of active specializations
```

### show.blade.php
```php
$tailor                // Tailor object with user loaded
$activeOrders          // Integer count
$completedOrders       // Integer count
$pendingOrders         // Integer count
$recentAssignments     // Collection of 10 orders
```

### assign-form.blade.php
```php
$stitchingOrders // Pending orders collection
$tailors         // Active tailors collection
```

### tailor-orders.blade.php
```php
$tailor  // Tailor object
$orders  // Paginated orders (15 per page)
$status  // Filter status
```

### tailor-dashboard.blade.php
```php
$tailor               // Tailor object
$workloadBreakdown    // Array {assigned, accepted, started, in_progress, completed}
$recentOrders         // Collection of 10 orders
```

---

## JavaScript Functions

### assign-form.blade.php
```js
updateOrderDetails()      // Update order summary when selected
updateTailorInfo()        // Update tailor info when selected
updateAssignmentCheck()   // Update assignment check display
```

### tailor-dashboard.blade.php
```js
new Chart(ctx, {/*...*/}) // Render doughnut chart
```

---

## CSS Classes (Bootstrap)

### Colors
```css
.bg-primary    /* Dark blue */
.bg-secondary  /* Gray */
.bg-success    /* Green */
.bg-danger     /* Red */
.bg-warning    /* Yellow */
.bg-info       /* Light blue */
```

### Badges
```css
.badge.bg-primary    /* Dark blue badge */
.badge.bg-success    /* Green badge */
.badge.bg-warning    /* Yellow badge */
.badge.bg-danger     /* Red badge */
```

### Cards
```css
.card.border-0.shadow  /* Rounded card with shadow */
.card-header           /* Card header section */
.card-body             /* Card body section */
.card-footer           /* Card footer section */
```

---

## Common Queries

### Get Active Tailors
```php
Tailor::where('status', 'active')
  ->with('user')
  ->paginate(15);
```

### Get Tailor Orders
```php
StitchingOrder::where('tailor_id', $tailor->user_id)
  ->with('order', 'order.user')
  ->where('stitching_status', 'in_progress')
  ->paginate(15);
```

### Get Workload Breakdown
```php
[
  'assigned' => StitchingOrder::where('tailor_id', $id)
    ->where('stitching_status', 'assigned')->count(),
  'accepted' => StitchingOrder::where('tailor_id', $id)
    ->where('stitching_status', 'accepted')->count(),
  // ... etc
]
```

---

## Error Handling

### Common Errors

```
"This order has already been assigned"
→ Order stitching_status is not 'pending'

"Selected user is not a tailor"
→ User doesn't have 'tailor' role

"The delivery date field must be a date after today"
→ Delivery date is today or in past

"Invalid stitching_order_id"
→ Order doesn't exist in database

"Invalid tailor_id"
→ Tailor doesn't exist in database
```

---

## Testing Checklist

### Basic Functionality
- [ ] List page loads
- [ ] Search works
- [ ] Filter works
- [ ] Pagination works
- [ ] View details page works
- [ ] Assignment form works
- [ ] Can assign order
- [ ] Confirmation page shows
- [ ] Orders list shows
- [ ] Dashboard loads
- [ ] Charts render

### Access Control
- [ ] Authenticated required
- [ ] Receptionist role required
- [ ] Only active tailors shown
- [ ] No add/edit/delete buttons

### Validation
- [ ] Date validation works
- [ ] Required fields enforced
- [ ] Order status checked
- [ ] Tailor role verified

---

## Performance Tips

### Database Queries
- Always use `->with()` for relationships
- Use pagination for large lists
- Add indexes on common filters
- Use transactions for consistency

### Frontend Optimization
- Lazy load charts
- Use Bootstrap minified CSS
- Use Font Awesome SVG icons
- Minimize JavaScript

### Caching (Optional)
- Cache specializations list
- Cache tailor statistics
- Cache dashboard data

---

## Deployment Steps

1. Backup database
2. Run migrations (if any new fields)
3. Copy new files to production
4. Clear Laravel cache
5. Test all routes
6. Monitor performance
7. Check user access

---

## Troubleshooting

### Route Not Found
```
Check: routes/receptionist.php
Check: Route name matches
Check: URL is correct
```

### View Not Loading
```
Check: File exists in views folder
Check: File name matches exactly
Check: File has proper structure
```

### Data Not Displaying
```
Check: Query returns results
Check: View receives correct data
Check: Database records exist
```

### JavaScript Not Working
```
Check: Chart.js loaded
Check: jQuery loaded (for modals)
Check: Console for errors
```

---

## Quick Help

### Want to...

**Add a new route?**
→ Update `routes/receptionist.php`
→ Add new method to `TailorController`
→ Create corresponding view

**Change styling?**
→ Edit `.blade.php` files
→ Update Bootstrap classes
→ Or add custom CSS

**Add new column to table?**
→ Create migration
→ Update model
→ Update view

**Add new search filter?**
→ Update `TailorController::index()`
→ Add form field to view
→ Update query logic

---

## Contact & Support

- **Issues**: Check TAILOR_MANAGEMENT_MODULE_REPORT.md
- **How-to**: Check TAILOR_MANAGEMENT_USER_GUIDE.md
- **Status**: Check TASK_7_COMPLETION_CHECKLIST.md

---

**Quick Reference Version**: 1.0
**Last Updated**: July 10, 2026
**For**: Developers & System Admins
