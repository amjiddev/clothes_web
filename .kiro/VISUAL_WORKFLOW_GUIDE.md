# Orders Management - Visual Workflow Guide

---

## 🎯 Complete Order Lifecycle

### Order Status Progression with Inventory Impact:

```
┌────────────────────────────────────────────────────────────────────┐
│                      ORDER LIFECYCLE WORKFLOW                       │
└────────────────────────────────────────────────────────────────────┘

CREATION
   │
   ├─ Inventory DECREMENTED
   │
   ▼
┌──────────────┐
│   PENDING    │  ← Order created, awaiting confirmation
└──────────────┘
   │
   ▼
┌──────────────┐
│ CONFIRMED    │  ← Customer confirmed the order
└──────────────┘
   │
   ▼
┌──────────────┐
│IN PROGRESS   │  ← Order processing started
└──────────────┘
   │
   ▼
┌──────────────────────┐
│ ASSIGNED TO TAILOR   │  ← Stitching work assigned
└──────────────────────┘
   │
   ▼
┌──────────────────────┐
│STITCHING STARTED     │  ← Tailor working on order
└──────────────────────┘
   │
   ▼
┌──────────────┐
│ COMPLETED    │  ← Stitching work complete
└──────────────┘
   │
   ▼
┌──────────────┐
│QUALITY CHECK │  ← QC process in progress
└──────────────┘
   │
   ▼
┌──────────────────────┐
│ READY FOR DELIVERY   │  ← Ready to ship
└──────────────────────┘
   │
   ▼
┌──────────────┐
│  DELIVERED   │  ← Order delivered to customer
└──────────────┘
   │
   └─ [Status cannot be changed]


ALTERNATIVE: CANCELLATION
   │
   ├─ FROM: Pending or Confirmed only
   │
   ├─ ACTION: Cancel order
   │
   ▼
┌──────────────┐
│  CANCELLED   │  ← Order cancelled
└──────────────┘
   │
   └─ Inventory RESTORED

```

---

## 📊 UI Status Flow Display

### How Status Flow Diagram Looks:

```
ORDER DETAILS PAGE - STATUS FLOW SECTION
═════════════════════════════════════════════════════════════════

┌─────────────────────────────────────────────────────────────┐
│  ✓  1    ✓  2    ✓  3    ✓  4    ✓  5    ⭕ 6    ○  7    ○  8    ○  9  │
│  Pending Conf  Progress Assign Stitch Complete  QC   Ready Deliver │
│  ─────────────────────────────────────────●──────────────────  │
│                                             ↑ Current Status   │
└─────────────────────────────────────────────────────────────┘

Legend:
  ✓  = Completed step (green checkmark)
  ⭕  = Current step (blue circle)
  ○  = Pending step (gray circle)
  ─  = Flow connector
  ─  = Completed connector
```

### For Cancelled Orders:

```
ORDER DETAILS PAGE - STATUS FLOW SECTION
═════════════════════════════════════════════════════════════════

  ⛔ ORDER CANCELLED
  
  This order is no longer active. Inventory has been restored.
  
  (Status flow diagram hidden)
```

---

## 🔍 Search & Filter Interface

### Orders List Page Layout:

```
RECEPTIONIST PANEL > ORDERS
═════════════════════════════════════════════════════════════════

┌─────────────────────────────────────────────────────────────┐
│  📋 ORDERS                                    ➕ CREATE ORDER │
│  Manage and track all customer orders                        │
└─────────────────────────────────────────────────────────────┘

┌─ FILTER & SEARCH ──────────────────────────────────────────┐
│                                                             │
│  ┌─────────────────────────┐  ┌──────────────────────────┐ │
│  │ 🔍 Search              │  │ 📊 Order Status         │ │
│  │ Order # or Customer   │  │ All Status ▼             │ │
│  │ [________________]    │  │                          │ │
│  └─────────────────────────┘  └──────────────────────────┘ │
│                                                             │
│  ┌──────────────────────────┐                              │
│  │ 💳 Payment Status        │                              │
│  │ All ▼                    │                              │
│  │                          │  [🔍 Filter] [↻ Reset]      │
│  └──────────────────────────┘                              │
│                                                             │
└─────────────────────────────────────────────────────────────┘

RESULTS FOUND: 42 orders

┌─────────────────────────────────────────────────────────────┐
│ Order ID      │ Customer  │ Type    │ Amount │ Status │ ... │
├─────────────────────────────────────────────────────────────┤
│ ORD-20260710- │ Ahmed     │ Cloth   │ ₹5000  │ 🟡 ... │ 👁 │
│ 00001         │ Ahmed@..  │         │        │ Pend.. │ ✏ 🔄│
├─────────────────────────────────────────────────────────────┤
│ ORD-20260710- │ Fatima    │ Stitch  │ ₹2500  │ 🔵 ... │ 👁 │
│ 00002         │ Fatima@.. │         │        │ Conf.. │ ✏ 🔄│
├─────────────────────────────────────────────────────────────┤
│ ORD-20260710- │ Hassan    │ Both    │ ₹8000  │ 🟢 ... │ 👁 │
│ 00003         │ Hassan@.. │         │        │ Deliv..│ ✏ 🔄│
└─────────────────────────────────────────────────────────────┘

Showing 1-15 of 42 orders
[◀ 1 2 3 ▶] Next
```

---

## 🔄 Status Update Modal

### How Status Update Works:

```
ORDERS LIST
═════════════════════════════════════════════════════════════════

Click on 🔄 icon in actions column
             │
             ▼
┌────────────────────────────────────┐
│     UPDATE ORDER STATUS            │
│  ORD-20260710-00001                │
├────────────────────────────────────┤
│                                    │
│  New Status *                      │
│  ┌──────────────────────────────┐  │
│  │ Select Status ▼              │  │
│  │ • Pending                    │  │
│  │ • Confirmed                  │  │
│  │ • In Progress                │  │
│  │ • Assigned to Tailor         │  │
│  │ • Stitching Started          │  │
│  │ • Completed                  │  │
│  │ • Quality Check              │  │
│  │ • Ready for Delivery         │  │
│  │ • Delivered                  │  │
│  │ • Cancelled                  │  │
│  └──────────────────────────────┘  │
│                                    │
│  Notes (Optional)                  │
│  ┌──────────────────────────────┐  │
│  │ Add notes about this change  │  │
│  │ [_________________________]  │  │
│  └──────────────────────────────┘  │
│                                    │
│  [CANCEL]  [UPDATE STATUS]         │
└────────────────────────────────────┘
             │
             ▼
        Status Updated!
        Table reloads
```

---

## ❌ Cancel Order Modal

### Cancellation Flow:

```
ORDER DETAILS PAGE
═════════════════════════════════════════════════════════════════

Click "❌ Cancel Order" button
           │
           ▼
      Order Status:
      Pending or Confirmed?
           │
        ┌──┴──┐
        │     │
       YES    NO
        │     │
        ▼     └─→ Button disabled/hidden
    SHOW
    MODAL
        │
        ▼
┌────────────────────────────────────┐
│ ⚠️  CANCEL ORDER                   │
│ ORD-20260710-00001                 │
├────────────────────────────────────┤
│                                    │
│ ⚠️ WARNING                         │
│ This will cancel the order and     │
│ restore product inventory.         │
│                                    │
│ Cancellation Reason *              │
│ ┌──────────────────────────────┐   │
│ │ Why cancel this order?       │   │
│ │ (max 500 characters)         │   │
│ │                              │   │
│ │ [Customer requested...]      │   │
│ └──────────────────────────────┘   │
│                                    │
│ [KEEP ORDER]  [CANCEL ORDER]       │
└────────────────────────────────────┘
        │          │
        │          ▼
        │      Process:
        │      1. Validate reason
        │      2. Check status
        │      3. Restore inventory
        │      4. Update status
        │      5. Append reason
        │      6. Save
        │          │
        │          ▼
        │      Order Cancelled! ✓
        │
        └─→ Modal closes
            Back to order details
```

---

## ✏️ Edit Order Modal

### Edit Flow:

```
ORDER DETAILS / ORDER LIST
═════════════════════════════════════════════════════════════════

Click "✏️ EDIT" button
        │
        ▼
    REDIRECT TO EDIT PAGE
        │
        ▼
┌─────────────────────────────────────────────────────────────┐
│ ORDER DETAILS - EDIT MODE                                   │
│ Edit Order ORD-20260710-00001                               │
│                                                             │
│ ┌─────────────────────────┐  ┌──────────────────────────┐  │
│ │ ORDER INFO (Read-only)  │  │ SIDEBAR - STATUS INFO    │  │
│ │                         │  │                          │  │
│ │ Order Number: ORD-...   │  │ 🔵 CONFIRMED             │  │
│ │ Order Type: Cloth       │  │ Payment: 🟢 PAID         │  │
│ │ Customer: Ahmed         │  │ Created: Jul 10, 2026    │  │
│ │ Total: ₹5000            │  │ Updated: Now             │  │
│ │                         │  │                          │  │
│ └─────────────────────────┘  └──────────────────────────┘  │
│                                                             │
│ ┌─────────────────────────────────────────────────────────┐ │
│ │ EDITABLE FIELDS                                         │ │
│ │                                                         │ │
│ │ Delivery Date *                                         │ │
│ │ [📅 2026-07-15]                                         │ │
│ │ Must be future date                                     │ │
│ │                                                         │ │
│ │ Order Notes                                             │ │
│ │ ┌──────────────────────────────────────────────────┐   │ │
│ │ │ Add any special instructions or notes...        │   │ │
│ │ │ [Handle with care, no heat...]                  │   │ │
│ │ └──────────────────────────────────────────────────┘   │ │
│ │                                                         │ │
│ │ [💾 SAVE CHANGES]  [❌ CANCEL]                         │ │
│ │                                                         │ │
│ └─────────────────────────────────────────────────────────┘ │
│                                                             │
└─────────────────────────────────────────────────────────────┘
        │
        ├─ [SAVE CHANGES]
        │      │
        │      ▼
        │   Validate inputs
        │   Update database
        │      │
        │      ▼
        │   Order Updated! ✓
        │   Redirect to details
        │
        └─ [CANCEL]
               │
               ▼
            Return without saving
            Back to order details
```

---

## 📊 Order Details Page Structure

### Complete Order Details Layout:

```
RECEPTIONIST > ORDERS > ORD-20260710-00001
═════════════════════════════════════════════════════════════════

┌─────────────────────────────────────────────────────────────┐
│ 📋 ORD-20260710-00001                                       │
│ Order placed on Jul 10, 2026 at 2:30 PM                     │
│                           [⬅ Back] [🖨 Print] [✏ Edit] [🔄] │
│                                              [⭕ Status] [❌] │
└─────────────────────────────────────────────────────────────┘

┌────────────────────────────────┐  ┌────────────────────────┐
│ 📊 ORDER STATUS                │  │ 💳 PAYMENT STATUS      │
│ ┌─────────────────────────────┐│  │ 🟡 Pending             │
│ │ 🟡 CONFIRMED                ││  │ Payment: 💳 Card       │
│ │                             ││  │                        │
│ │ Type: 👕 Cloth Only        ││  │                        │
│ │ Delivery: Jul 15, 2026      ││  │                        │
│ └─────────────────────────────┘│  │                        │
└────────────────────────────────┘  └────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│ 👤 CUSTOMER INFORMATION                                     │
│ Name: Ahmed Ali                                             │
│ Email: ahmed@example.com                                    │
│ Phone: +971501234567                                        │
│ City: Dubai                  Address: Downtown Dubai        │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│ 📦 ORDER ITEMS (1)                                          │
├─────────────────────────────────────────────────────────────┤
│ Thobe Fabric │ Textiles │ Qty: 5 │ ₹1000 │ Total: ₹5000   │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│ 🪡 STITCHING ORDER DETAILS (Not applicable)                │
│ (Hidden for Cloth-only orders)                             │
└─────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────┐
│ 🧮 ORDER SUMMARY                     │
│ Subtotal         ₹5000               │
│ Stitching        -                   │
│ Tax (18%)        ₹900                │
│ Discount         -                   │
│ ─────────────────────────            │
│ TOTAL            ₹5900               │
└──────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│ 💰 PAYMENT HISTORY                                          │
│                            [➕ RECORD PAYMENT]              │
├─────────────────────────────────────────────────────────────┤
│ Transaction │ Amount │ Method  │ Status │ Date             │
├─────────────────────────────────────────────────────────────┤
│ TXN-001     │ ₹5900  │ Card    │ ✓Done  │ Jul 10 2:30 PM  │
├─────────────────────────────────────────────────────────────┤
│ TXN-002     │ ₹2000  │ Online  │ ✓Done  │ Jul 10 3:00 PM  │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│ ⏱️ ORDER TIMELINE & STATUS FLOW                              │
│                                                             │
│  ✓1   ✓2   ✓3   ✓4   ✓5   ⭕6   ○7   ○8   ○9             │
│  Pend Conf Prog Asgn Stch Cmpl  QC  Redy Deliv             │
│  ─────────────────────────────●──────────────              │
│                              ↑                             │
│                        Current Status                      │
│                                                             │
│ Timeline Events:                                            │
│ • Order Created - Jul 10, 2:30 PM                          │
│ • Order Confirmed - Jul 10, 2:35 PM                        │
│ • Order Processing - Jul 10, 3:00 PM                       │
│                                                             │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│ 📝 ORDER NOTES                                              │
│ Handle with care - Premium fabric. No heat pressing.       │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│ [🖨 PRINT INVOICE] [✏ EDIT] [🔄 UPDATE] [❌ CANCEL]       │
│                              [⬅ BACK TO ORDERS]            │
└─────────────────────────────────────────────────────────────┘
```

---

## 🎨 Status Badge Colors

### Badge Color Reference:

```
ORDER STATUS BADGES:
════════════════════════════════════════════════════════════════

🟡 YELLOW           (Warning)
   • Pending
   • Quality Check

🔵 LIGHT BLUE       (Info)
   • Confirmed
   • Assigned to Tailor
   • Ready for Delivery

🔵 BLUE             (Primary)
   • In Progress
   • Stitching Started

🟢 GREEN            (Success)
   • Completed
   • Delivered

🔴 RED              (Danger)
   • Cancelled


PAYMENT STATUS BADGES:
════════════════════════════════════════════════════════════════

🟡 YELLOW           (Warning)
   • Payment Pending

🟢 GREEN            (Success)
   • Payment Paid

🔴 RED              (Danger)
   • Payment Failed
```

---

## 📱 Mobile Responsive Layout

### How It Looks on Phone (375px):

```
┌─────────────────────────┐
│ 📋 ORDERS               │
│ ┌─────────────────────┐ │
│ │ 🔍 Search      [🔍] │ │
│ └─────────────────────┘ │
│ ┌─────────────────────┐ │
│ │ Status:      [All ▼]│ │
│ ├─────────────────────┤ │
│ │ Payment:     [All ▼]│ │
│ └─────────────────────┘ │
│                         │
│ ┌─────────────────────┐ │
│ │ ORD-00001           │ │
│ │ Ahmed Ali           │ │
│ │ Cloth │ ₹5000       │ │
│ │ 🟡 Pend │ 💳 Paid   │ │
│ │ [👁 ✏ 🔄 🖨]        │ │
│ └─────────────────────┘ │
│ ┌─────────────────────┐ │
│ │ ORD-00002           │ │
│ │ Fatima Khan         │ │
│ │ Stitch │ ₹2500      │ │
│ │ 🔵 Conf │ 🟡 Pend   │ │
│ │ [👁 ✏ 🔄 🖨]        │ │
│ └─────────────────────┘ │
│                         │
│ [◀  1  2  3  ▶]        │
│                         │
└─────────────────────────┘
```

---

**All workflows are now complete and ready to use!**

Receptionists can efficiently manage orders through their entire lifecycle with a professional, intuitive interface.
