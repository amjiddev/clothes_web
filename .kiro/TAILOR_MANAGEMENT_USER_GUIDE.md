# Tailor Management Module - User Guide

## Quick Start Guide for Receptionists

---

## Overview

The Tailor Management Module helps you manage tailor workloads and assign stitching orders efficiently. This guide walks you through each section.

---

## Main Menu Access

Navigate to **Receptionist Panel → Tailors** or use the sidebar menu.

---

## 1. TAILOR LIST PAGE

**URL**: `/receptionist/tailors`

### What You See
- List of all active tailors
- 15 tailors per page with pagination

### Column Information
- **Name**: Tailor's full name
- **Phone**: Clickable phone number
- **Specialization**: Type of tailoring expertise (Wedding, Casual, Formal, etc.)
- **Active Orders**: Number of orders currently being worked on
- **Status**: Active, Inactive, or On Leave
- **Actions**: Three buttons
  - 👁️ **View Details** - See tailor profile and statistics
  - 📋 **View Orders** - See all orders assigned to this tailor
  - 📊 **View Dashboard** - See workload breakdown

### Search & Filter
1. **Search Box**: Enter tailor's name or phone number
2. **Specialization Filter**: Select from dropdown (Wedding, Casual, Formal, etc.)
3. **Filter Button**: Apply your search and filters
4. **Reset Button**: Clear all filters and searches

### Quick Tips
- Only active tailors are displayed (Inactive/On Leave not shown)
- Search is case-insensitive
- You can combine search and filter together
- Results update with pagination

---

## 2. TAILOR DETAILS PAGE

**URL**: `/receptionist/tailors/{tailor}`

### What You See
Left side:
- Tailor's profile picture (or default icon)
- Name and ID
- Status badge (Active/On Leave/Inactive)
- Contact information (Phone, Email)
- Specialization and years of experience

Right side:
- **4 Statistics Cards**:
  - 🔵 **Active Orders**: Orders currently being worked on
  - ✅ **Completed Orders**: Total finished orders
  - ⏱️ **Pending Orders**: Orders awaiting start
  - 💼 **Total Assigned**: All orders ever assigned

- **Recent Assignments**: Last 10 orders assigned
- **Quick Action Buttons**:
  - View All Orders
  - View Dashboard

### How to Use
- Review tailor's experience and specialization
- Check active workload before assigning new orders
- Click "View Orders" to see detailed order list
- Click "View Dashboard" for workload analytics

---

## 3. ASSIGN STITCHING ORDER

**URL**: `/receptionist/tailors/assign-form`

### Step-by-Step Process

#### Step 1: Select Order
1. Click **"Assign Order"** button on tailor list page
2. In "Select Stitching Order" section, click dropdown
3. Choose a pending stitching order
4. The order summary card shows:
   - Customer name
   - Garment type
   - Estimated cost

#### Step 2: Select Tailor
1. In "Select Tailor" section, click dropdown
2. Choose a tailor from the list
3. Tailor info appears showing:
   - Specialization
   - Phone number
   - Active orders count
   - Available capacity (max 10 orders)

#### Step 3: Fill Details
1. **Delivery Date**: 
   - Click calendar icon
   - Choose a date (minimum tomorrow)
   - Customer will expect completion by this date

2. **Special Instructions** (optional):
   - Type any special tailoring notes
   - Example: "Add custom buttons", "Shorten sleeves by 2cm"
   - Max 500 characters

#### Step 4: Review & Submit
1. Check the **"Assignment Summary"** on the right
2. Verify order → tailor assignment
3. Click **"Assign Order"** button (enabled when all required fields filled)

### Important Notes
- ⚠️ Order must be in "pending" status
- ⚠️ Tailor must be active
- ⚠️ Tailor must have available capacity
- ⚠️ Date must be in the future

---

## 4. ASSIGNMENT CONFIRMATION

**URL**: `/receptionist/tailors/{stitchingOrder}/assignment-details`

### What Happens After Assignment

You'll see a success page showing:

- ✅ **Success Confirmation**: Green alert at top
- **Assignment Summary** (green header):
  - Order number and status
  - Full order details
  - Assigned tailor information
  - Assignment date and time

- **Measurement Profile**:
  - All customer measurements
  - Design image (if uploaded)
  - Special instructions

- **Timeline**:
  - ✅ Order Assigned (completed)
  - ⏱️ Awaiting Tailor Response (current)
  - ⏳ In Progress (next)

### Quick Actions
- **View Full Order**: See complete order details
- **View Tailor Profile**: Go back to tailor details
- **Back to Tailors**: Return to tailor list

---

## 5. VIEW TAILOR'S ORDERS

**URL**: `/receptionist/tailors/{tailor}/orders`

### What You See
- All stitching orders assigned to this tailor
- Filter by status

### Order Table Columns
- **Order ID**: Order number
- **Customer**: Customer name and email
- **Garment**: Type of garment (Shirt, Trouser, etc.)
- **Assigned Date**: When order was assigned
- **Status**: Current stage (Assigned, In Progress, Completed, etc.)
- **Cost**: Estimated stitching cost
- **Actions**: View order link

### Status Meanings
- 🔵 **Assigned**: Just assigned, waiting for tailor to accept
- 🤝 **Accepted**: Tailor accepted the order
- ⚙️ **Started**: Stitching work has begun
- ⏳ **In Progress**: Work ongoing (multiple status included)
- ✅ **Completed**: Stitching finished
- ❌ **Cancelled**: Order cancelled

### Using Filters
1. Select status from dropdown (or "All Statuses")
2. Click **Filter** button
3. View filtered results
4. Click **Reset** to clear filter

### View Order Details
1. Click info icon on any row
2. Modal popup shows:
   - Customer information
   - Order status
   - Garment type
   - Cost
   - Assigned and start dates
   - Special instructions
   - Tailor notes

---

## 6. TAILOR WORKLOAD DASHBOARD

**URL**: `/receptionist/tailors/{tailor}/dashboard`

### What You See

#### Top Statistics (4 Cards)
- 🔵 **Assigned Orders**: Not yet accepted by tailor
- 🤝 **Accepted Orders**: Tailor accepted but not started
- ⚙️ **In Progress**: Stitching work is happening
- ✅ **Completed**: Finished orders

#### Workload Breakdown Chart
- **Doughnut Chart** showing:
  - Color-coded distribution
  - Percentage of each status
  - Legend at bottom

#### Status Summary Table
- Detailed count for each status
- Total orders count
- Easy reference for quick numbers

#### Recent Orders List
- Latest 10 orders
- Same columns as Orders List
- "View All" link for complete list

### How to Use
- **Quick Assessment**: Glance at top cards to see tailor's current load
- **Identify Bottlenecks**: Check chart for status with high numbers
- **Drill Down**: Click order to see details
- **Plan Assignments**: Use stats to decide if tailor can take more orders

---

## Common Tasks

### Task 1: Find a Specific Tailor
1. Go to Tailor List
2. Type name or phone in Search box
3. Click Filter
4. View results

### Task 2: Check Tailor's Workload
1. Go to Tailor List
2. Click "View Dashboard" for desired tailor
3. Review statistics and charts
4. Decide if they can take new orders

### Task 3: Assign an Order
1. Go to Tailor List
2. Click "Assign Order" button
3. Select pending order
4. Select available tailor
5. Set delivery date
6. Click "Assign Order"

### Task 4: View All Orders for a Tailor
1. Go to Tailor List
2. Click "View Orders" for desired tailor
3. Optionally filter by status
4. View complete order list

### Task 5: Check Order Details
1. Go to Tailor List
2. Click "View Orders"
3. Click info icon on any order row
4. Review modal details

---

## Important Rules to Remember

### Tailor Selection Rules
- ✅ Only active tailors can receive assignments
- ✅ Cannot assign if tailor has max capacity (10 orders)
- ✅ Tailor must have 'tailor' role in system

### Order Assignment Rules
- ✅ Order must be in "pending" status
- ✅ Delivery date must be in future
- ✅ Special instructions optional but helpful

### What You CANNOT Do
- ❌ Add new tailors (Admin only)
- ❌ Delete tailors (Admin only)
- ❌ Edit tailor information (Admin only)
- ❌ Change tailor status (Admin only)

### What You CAN Do
- ✅ View all active tailors
- ✅ Search and filter tailors
- ✅ View tailor details
- ✅ Assign orders to tailors
- ✅ View tailor workload
- ✅ Track order progress

---

## Tips & Best Practices

### 📌 Organization Tips
1. **Regular Checks**: Check tailor dashboards before peak season
2. **Balanced Load**: Distribute orders across tailors evenly
3. **Specialization**: Match order garment type to tailor specialization
4. **Capacity Planning**: Leave 2-3 slots before tailor hits max capacity

### 💡 Efficiency Tips
1. **Use Search**: Instead of scrolling, search by name/phone
2. **Batch Assignment**: Assign multiple orders in sequence (use browser back)
3. **Review Stats**: Check dashboard before deciding tailor suitability
4. **Clear Instructions**: Provide detailed special instructions for complex orders

### ⚠️ Caution Tips
1. **Always Set Dates**: Set realistic delivery dates to avoid delays
2. **Check Specialization**: Match tailor skill to garment type
3. **Verify Status**: Ensure order is truly pending before assigning
4. **Monitor Load**: Don't overload tailors - check active order count

---

## Troubleshooting

### Problem: Cannot find tailor I'm looking for
- **Solution**: Check if tailor is active (inactive/on leave won't show)
- **Solution**: Try searching by phone instead of name
- **Solution**: Check specialization filter isn't excluding them

### Problem: "Select a Tailor" dropdown appears empty
- **Solution**: No active tailors available - contact admin
- **Solution**: All tailors at capacity - wait for some to complete orders

### Problem: Cannot assign order to specific tailor
- **Solution**: Check tailor doesn't already have 10 active orders
- **Solution**: Check order is in "pending" status (not already assigned)
- **Solution**: Check delivery date is set to future date

### Problem: Assignment says "already been assigned"
- **Solution**: Order might be assigned already - refresh and check again
- **Solution**: Check if another receptionist assigned it concurrently

---

## Support & Help

### If You Need Help:
1. Check this guide (look for your task in "Common Tasks")
2. Review the example in "Tips & Best Practices"
3. Check current page for helpful tips (marked with 💡)
4. Contact your supervisor or admin

### Contact Information:
- **Email**: support@tailorapp.com
- **Internal**: Ask your supervisor
- **Emergency**: Contact system admin

---

## Keyboard Shortcuts (Optional)

| Shortcut | Action |
|----------|--------|
| Ctrl+F | Search within page |
| Enter | Submit form |
| Esc | Close modal/popup |
| Tab | Navigate form fields |

---

## Module Navigation Map

```
Receptionist Dashboard
    ↓
Tailors List (/receptionist/tailors)
    ├─→ Tailor Details (/receptionist/tailors/{id})
    │   ├─→ View Orders
    │   └─→ View Dashboard
    ├─→ Assign Order (/receptionist/tailors/assign-form)
    │   └─→ Assignment Confirmation
    └─→ View Orders (/receptionist/tailors/{id}/orders)
        └─→ Order Details (Modal)
```

---

## Keyboard Symbols Guide

- 🔵 Blue information
- ✅ Completed/Success
- ⏱️ Pending/Waiting
- ⚙️ In Progress
- 🤝 Accepted
- 💼 Work-related
- 📊 Dashboard/Statistics
- 📋 Orders/List
- 👁️ View
- ⚠️ Warning/Important
- ❌ Cannot do
- ✅ Can do

---

**Last Updated**: July 10, 2026
**Version**: 1.0
**For**: Receptionist Panel Users
