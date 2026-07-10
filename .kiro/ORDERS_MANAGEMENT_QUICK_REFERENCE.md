# Orders Management Module - Quick Reference Guide

## 🎯 Quick Navigation

### URLs:
- **Orders List**: `/receptionist/orders`
- **View Order**: `/receptionist/orders/{id}`
- **Edit Order**: `/receptionist/orders/{id}/edit`
- **Create Order**: `/receptionist/orders/create`

### Actions Available:

#### From Orders Index (`/receptionist/orders`)
| Action | Button | Result |
|--------|--------|--------|
| View Details | 👁️ Eye | Opens order details page |
| Edit Order | ✏️ Pencil | Opens edit form (delivery date, notes) |
| Update Status | 🔄 Sync | Modal to change status |
| Print Invoice | 🖨️ Print | Print order invoice |

#### From Order Details (`/receptionist/orders/{id}`)
| Action | Button | Result |
|--------|--------|--------|
| Print Invoice | 🖨️ Print | Print order invoice |
| Edit Order | ✏️ Edit | Edit delivery date and notes |
| Update Status | 🔄 Update Status | Modal to change status |
| Cancel Order | ❌ Cancel | Modal to cancel and restore inventory |
| Record Payment | ➕ Record Payment | Modal to record payment |
| Back to Orders | ⬅️ Back | Return to orders list |

---

## 📋 Order Status Flow

### Complete Status Progression:
```
Pending → Confirmed → In Progress → Assigned → Stitching → Completed → QC → Ready → Delivered
```

### Status Descriptions:
- **Pending**: Order created, awaiting confirmation
- **Confirmed**: Customer confirmed the order
- **In Progress**: Order is being processed
- **Assigned to Tailor**: Stitching work assigned to tailor
- **Stitching Started**: Tailor started working on order
- **Completed**: Stitching work is complete
- **Quality Check**: Order undergoing quality check
- **Ready for Delivery**: Order ready to be shipped
- **Delivered**: Order delivered to customer
- **Cancelled**: Order cancelled, inventory restored

---

## 🔍 Search & Filter Guide

### Search:
- Search box accepts: Order number OR Customer name
- Example searches:
  - "ORD-20260710-00001" (order number)
  - "Ahmed" (customer name)
  - "test@example.com" (customer email)

### Filter by Order Status (10 options):
- Pending
- Confirmed
- In Progress
- Assigned to Tailor
- Stitching Started
- Completed
- Quality Check
- Ready for Delivery
- Delivered
- Cancelled

### Filter by Payment Status (3 options):
- Pending (Not fully paid)
- Paid (Fully paid)
- Failed (Payment failed)

### Combining Filters:
- You can apply search + order status + payment status simultaneously
- Example: Search "Ahmed" + Filter "Pending" status + Filter "Paid" payment
- Click "Reset" to clear all filters and see all orders

---

## ✏️ Editing Orders

### What You Can Edit:
- ✅ Delivery Date (must be future date)
- ✅ Order Notes (special instructions)

### What You CANNOT Edit:
- ❌ Order Number
- ❌ Order Type
- ❌ Customer
- ❌ Items/Products
- ❌ Total Amount
- ❌ Payment Status
- ❌ Current Status

### To Edit:
1. Click Edit button or Edit icon
2. Modify delivery date and/or notes
3. Click "Save Changes"
4. Will redirect to order details page

---

## 🔄 Updating Order Status

### Method 1: From Index Page
1. Locate order in table
2. Click Sync icon (🔄) in actions
3. Select new status from dropdown
4. Optional: Add notes
5. Click "Update"

### Method 2: From Order Details
1. Click "Update Status" button
2. Select new status from dropdown
3. Optional: Add notes
4. Click "Update Status"

### Important Notes:
- ⚠️ Cannot update DELIVERED or CANCELLED orders
- ⚠️ Any status can transition to any other status
- ✅ Status auto-updates in table immediately
- ✅ Timeline automatically updated

---

## ❌ Cancelling Orders

### Prerequisites:
- Order status must be "Pending" or "Confirmed"
- Cannot cancel if already delivered or cancelled

### Steps:
1. From order details page, click "Cancel Order" button
2. Modal appears with warning
3. Enter cancellation reason (required)
4. Click "Cancel Order" to confirm

### What Happens:
- ✅ Order status changes to "Cancelled"
- ✅ All products returned to inventory
- ✅ Cancellation reason added to order notes
- ✅ Order no longer editable via edit button

---

## 📊 Status Badges & Colors

### Order Status Badges:
```
🟡 Pending         (Yellow)
🔵 Confirmed       (Light Blue)
🔵 In Progress     (Blue)
🔵 Assigned        (Light Blue)
🔵 Stitching       (Blue)
🟢 Completed       (Green)
🟡 QC              (Yellow)
🔵 Ready           (Light Blue)
🟢 Delivered       (Green)
🔴 Cancelled       (Red)
```

### Payment Status Badges:
```
🔴 Pending         (Red/Warning)
🟢 Paid            (Green/Success)
🔴 Failed          (Red/Danger)
```

---

## 💳 Recording Payments

### When Available:
- Only when payment_status is NOT "Paid"
- Button appears on order details page

### To Record Payment:
1. Click "Record Payment" button
2. Enter amount (must be ≤ remaining amount)
3. Select payment method:
   - Cash
   - Card
   - Bank Transfer
   - Online Payment
4. Optional: Enter transaction ID
5. Click "Record Payment"

### Auto Updates:
- If total payments ≥ order total → payment_status becomes "Paid"
- If 0 < total payments < order total → payment_status stays "Pending"
- Payment history updated automatically

---

## 🖨️ Printing Invoices

### Method 1: From Index
1. Click Print icon (🖨️) in actions
2. Opens order details in print mode
3. System print dialog appears
4. Select printer and print

### Method 2: From Order Details
1. Click "Print Invoice" button at top or bottom
2. System print dialog appears
3. Select printer and print

### Print Format:
- Customer information
- Order items table
- Stitching details (if applicable)
- Payment information
- Total amount
- Timeline

---

## 📱 Mobile View

### Responsive Features:
- Table scrolls horizontally on small screens
- Status flow diagram stacks vertically
- Buttons stack in actions column
- All functionality preserved on mobile

### Recommended Minimum Width:
- Phone: 375px (all features accessible)
- Tablet: 768px (optimal view)
- Desktop: 1024px+ (full width)

---

## ⚠️ Important Rules

### Status Updates:
- ✅ Any status can go to any other status
- ✅ Status changes are immediate
- ✅ Timeline auto-updates
- ❌ Cannot undo status changes manually (create new order if needed)

### Cancellation:
- ✅ Only "Pending" or "Confirmed" orders can be cancelled
- ✅ Inventory automatically restored
- ❌ Cannot modify cancelled orders
- ❌ Cannot deliver cancelled orders

### Inventory:
- ✅ Automatically decremented when order created
- ✅ Automatically restored when cancelled
- ❌ Manual stock adjustments not available here

### Payments:
- ✅ Can record multiple payments
- ✅ Can record partial payments
- ✅ Payment status auto-updates
- ❌ Cannot undo payment recordings

---

## 🐛 Common Issues & Solutions

### Issue: Can't update status
**Solution**: Check if order is "Delivered" or "Cancelled" (can't update these)

### Issue: Can't cancel order
**Solution**: Order must be "Pending" or "Confirmed" status

### Issue: Can't record payment
**Solution**: Amount must be ≤ remaining unpaid amount

### Issue: Inventory not restored
**Solution**: Only happens when cancelling order (manual cancellation via status change won't restore)

### Issue: Payment status not updating
**Solution**: Only updates when recording payments, check total paid vs order total

---

## 🔒 Permissions

### Required Role:
- Receptionist

### Required Permissions:
- View orders
- Update orders
- Create orders

### Protected Routes:
- All order routes require:
  - ✅ Authentication (must be logged in)
  - ✅ Email verification
  - ✅ Receptionist role

---

## 📞 Support

### For Issues:
- Check the status flow diagram first
- Verify order status eligibility for action
- Check payment amount is valid
- Review error messages (they're descriptive)

### Common Checks:
- Is order delivered/cancelled? (can't update)
- Is order pending/confirmed? (can cancel)
- Is payment amount valid? (≤ remaining)
- Is delivery date in future? (required for edit)

---

## 🎓 Best Practices

1. **Before Updating Status**:
   - Review order details
   - Check payment status
   - Verify with customer if needed

2. **Before Cancelling**:
   - Confirm with customer
   - Note cancellation reason
   - Check if any stitching started

3. **Recording Payments**:
   - Record all payments received
   - Include transaction ID when available
   - Verify amount before submitting

4. **Editing Orders**:
   - Update delivery date if postponed
   - Add notes for special handling
   - Only edit basic details

5. **Printing Invoices**:
   - Print before delivery
   - Keep for records
   - Send to customer if needed

---

## Summary

The Orders Management Module provides:
- ✅ Complete order lifecycle management
- ✅ Professional status workflow (10 statuses)
- ✅ Real-time search and filtering
- ✅ Inventory integration
- ✅ Payment tracking
- ✅ Timeline visualization
- ✅ Mobile-responsive design

**Everything needed to professionally manage customer orders!**
