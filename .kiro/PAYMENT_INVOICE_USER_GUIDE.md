# Payment & Invoice Management - User Guide

## Quick Start for Receptionists

---

## PAYMENT MANAGEMENT

### Recording a Payment

#### Method 1: From Order Page
1. Navigate to **Orders** → Select an order
2. Scroll to **Payment Section**
3. Click **"Record Payment"** button
4. Fill in the form:
   - **Amount**: Enter payment amount in PKR
   - **Method**: Select from dropdown:
     - 💵 Cash
     - 💳 Card
     - 🏦 Bank Transfer
     - 📱 Online Payment
   - **Transaction ID** (Optional): Reference number
     - For card: Card reference
     - For bank: Cheque number
     - For online: Transaction ID
5. Review the summary showing:
   - Order total
   - Already paid amount
   - Remaining balance
6. Click **"Record Payment"**
7. Payment confirmed ✓

#### Method 2: From Payment Management Page
1. Navigate to **Receptionist Panel → Payments**
2. This shows all payments recorded in the system
3. To record new payment, go to order and use Method 1

### Viewing Payments

#### Payment List Page
**URL**: `/receptionist/payments`

**What You See**:
- List of all payments (15 per page)
- Payment ID
- Related order number
- Customer name and email
- Payment amount (in green)
- Payment method badge
- Payment date
- Payment status (Completed/Pending/Failed)
- Quick action buttons

**Search & Filter**:
1. **Search Box**: Type order# or customer name
2. **Status Filter**: All / Completed / Pending / Failed
3. **Method Filter**: All / Cash / Card / Bank / Online
4. **Date Range**: From Date and To Date
5. Click **"Filter"** to apply
6. Click **"Reset"** to clear all filters

**Statistics Cards**:
- Total Payments: Count of all payments
- This Page: Payments on current page
- Total Amount: Sum of current page payments

#### Viewing Payment Details
1. Click **"View"** button on payment row
2. See detailed payment information:
   - Payment ID
   - Status with icon
   - Amount in PKR
   - Payment method details
   - Processing date and time
   - Transaction ID reference
   - Recorded by (receptionist name)

**Sections on Details Page**:
- **Payment Information**: Core payment details
- **Related Order Information**: Order details
- **Customer Information**: Contact details
- **Payment Summary**: Balance tracking with progress bar
- **Quick Actions**: View order, View invoice, Back to list

### Understanding Payment Status

| Status | Meaning | Icon |
|--------|---------|------|
| ✓ Completed | Payment received and recorded | ✅ |
| ⏳ Pending | Awaiting payment processing | ⏳ |
| ✗ Failed | Payment failed or rejected | ❌ |

### Understanding Payment Methods

| Method | Used For | Icon |
|--------|----------|------|
| Cash | Direct cash payment | 💵 |
| Card | Credit/Debit card | 💳 |
| Bank Transfer | Cheque or bank transfer | 🏦 |
| Online | Online payment gateway | 📱 |

---

## INVOICE MANAGEMENT

### Viewing Invoices

#### Invoice List Page
**URL**: `/receptionist/invoices`

**What You See**:
- List of all invoices (20 per page)
- Invoice/Order ID
- Customer name
- Order date
- Total amount
- Order status badge
- Payment status badge
- Action buttons

**Search & Filter**:
1. Type order ID or customer name in search box
2. Select order status from dropdown
3. Click **"Filter"** to apply
4. Results update automatically

#### Viewing Invoice Details
1. Click invoice number link or **"View"** button
2. See full invoice details:
   - Order information
   - Customer details
   - Order items list
   - Payment summary
   - Payment status

### Generating Invoices

#### Download as PDF
1. Go to invoice details page
2. Click **"Download PDF"** button (⬇️)
3. Invoice saves to downloads folder
4. Named as: `invoice-[order-id].pdf`

**When to Use**:
- Email to customer
- Store as backup
- Accounting records
- Digital archive

#### Print Invoice
1. Go to invoice details page
2. Click **"Print"** button (🖨️)
3. New tab opens with printable invoice
4. **Option A**: Browser print (Ctrl+P or Cmd+P)
   - Select printer
   - Choose print settings
   - Click Print
5. **Option B**: Click "Print Invoice" button in page
   - Opens print dialog
   - Select printer
   - Print

**When to Use**:
- Give to customer
- Receipt printing
- Thermal printer printing
- Shop records

### Invoice Information

**What's Included**:

✅ **Shop Information**
- Shop name
- Address
- Phone & email
- Business hours

✅ **Invoice Details**
- Invoice number
- Date and time
- Unique reference

✅ **Customer Details**
- Name
- Email
- Phone
- Delivery address

✅ **Order Information**
- Order number
- Order type (Cloth/Stitching/Combined)
- Order status
- Delivery date

✅ **Line Items**
- Product names
- Quantities
- Unit prices
- Total per item
- Stitching charges (if applicable)
- Measurement profile

✅ **Payment Information**
- Subtotal
- Stitching charges
- Tax/GST/VAT
- Discounts
- **TOTAL AMOUNT**
- Payment method
- Payment status

✅ **Payment History**
- All payments made
- Dates and times
- Methods used
- Amounts
- Status

✅ **Terms & Conditions**
- Payment terms
- Return policy
- Collection deadline

### Invoice Customization

The invoice includes a placeholder shop area. To customize:
1. Contact your system administrator
2. Provide:
   - Shop name
   - Address
   - Phone number
   - Email
   - Business hours
   - Logo or branding
3. Administrator updates template files

---

## COMMON TASKS

### Task 1: Record a Payment for an Order

**Step-by-Step**:
1. Navigate to **Orders**
2. Click the order
3. Scroll to **Payment Section**
4. Click **"Record Payment"**
5. Enter payment amount
6. Select payment method
7. Add transaction ID (optional)
8. Review summary
9. Click **"Record Payment"**
10. ✓ Payment recorded

**Expected Result**:
- Success message appears
- Page refreshes
- Payment shown in history
- Order payment status updates

### Task 2: View All Payments for a Date Range

**Step-by-Step**:
1. Go to **Payments** page
2. Enter "From Date"
3. Enter "To Date"
4. Click **"Filter"**
5. Results show payments in date range
6. View, search, filter as needed

### Task 3: Download Invoice as PDF

**Step-by-Step**:
1. Navigate to **Invoices**
2. Find invoice you want
3. Click order number or **"View"**
4. Click **"Download PDF"** button
5. File downloads
6. Can email or store as backup

### Task 4: Print Invoice from Thermal Printer

**Step-by-Step**:
1. Go to **Invoices**
2. Click **"Print"** button
3. New tab opens (invoice page)
4. Click **"Print Invoice"** button
5. Select your thermal printer
6. Adjust settings if needed
7. Click Print
8. Invoice prints on thermal paper

### Task 5: Track Payment Progress

**Step-by-Step**:
1. Go to **Payments**
2. Click **"View"** on a payment
3. Scroll to **"Payment Summary"**
4. See progress bar showing:
   - Order total
   - Amount paid
   - Remaining balance
   - Percentage complete
5. If bar is full → Fully paid ✓
6. If bar partial → Outstanding balance

### Task 6: Export Payment Report

**Step-by-Step**:
1. Go to **Payments** page
2. Optionally filter by date range
3. Click **"Export Report"** button
4. CSV file downloads
5. Open in Excel
6. Contains:
   - Order ID
   - Customer name
   - Payment date
   - Payment method
   - Amount
   - Status

---

## PAYMENT STATUS MEANINGS

### For Individual Payments

| Status | Means | Action |
|--------|-------|--------|
| ✓ Completed | Fully recorded | None needed |
| ⏳ Pending | Not yet processed | Wait or update |
| ✗ Failed | Payment rejected | Record new payment |

### For Orders

| Status | Means | Action |
|--------|-------|--------|
| 💚 Paid | Order fully paid | Ready to deliver |
| 🟡 Pending | Partial or no payment | Collect remaining |
| ❌ Failed | Payment issue | Contact customer |

---

## PAYMENT METHOD REFERENCE

### Cash (💵)
- **How to Use**: Customer pays in cash
- **Transaction ID**: Optional (receipt number)
- **Best For**: Direct payments
- **Record**: Amount, cash payment, receipt #

### Card (💳)
- **How to Use**: Card payment (credit/debit)
- **Transaction ID**: Card reference number (last 4 digits, auth code)
- **Best For**: Secure payments
- **Record**: Amount, card method, reference #

### Bank Transfer (🏦)
- **How to Use**: Customer transfers money to shop bank account
- **Transaction ID**: Cheque number or bank reference
- **Best For**: Large amounts
- **Record**: Amount, bank method, cheque # or reference

### Online (📱)
- **How to Use**: Customer uses online payment gateway
- **Transaction ID**: Payment gateway transaction ID (required)
- **Best For**: Remote payments
- **Record**: Amount, online method, transaction ID

---

## TROUBLESHOOTING

### Problem: Cannot record payment
**Solution**:
- Check if order exists
- Verify customer account
- Ensure payment amount is valid (greater than 0)
- Select payment method from dropdown
- Click "Record Payment"

### Problem: Invoice won't download
**Solution**:
- Check pop-up blocker
- Try different browser
- Ensure order has items
- Clear browser cache
- Try again

### Problem: Invoice won't print
**Solution**:
- Check printer connection
- Select correct printer from dialog
- Ensure paper loaded
- Try "Print to PDF" instead
- Contact IT support

### Problem: Payment not showing in history
**Solution**:
- Refresh page (F5)
- Check payment status (may be "pending")
- Verify order is correct
- Check date filters
- Clear search/filters

### Problem: Invoice shows old shop info
**Solution**:
- Contact system administrator
- Request template update
- Provide new shop information
- Wait for update

---

## QUICK REFERENCE CARDS

### Payment Recording Form Fields

| Field | Type | Required | Notes |
|-------|------|----------|-------|
| Amount | Number | Yes | Greater than 0, 2 decimals |
| Method | Dropdown | Yes | Cash/Card/Bank/Online |
| Transaction ID | Text | No | Reference for payment |

### Invoice View Sections

| Section | Information |
|---------|-------------|
| Shop Info | Logo, address, contact |
| Invoice Details | Number, date, time |
| Customer Info | Name, email, address |
| Order Details | Number, type, status |
| Items | Products, quantities, prices |
| Payment Info | Total, status, method |
| History | Payment timeline |

### Payment Status Icons

| Icon | Meaning |
|------|---------|
| ✓ | Completed/Paid |
| ⏳ | Pending/Awaiting |
| ✗ | Failed/Cancelled |

---

## BEST PRACTICES

### Recording Payments
1. **Always enter transaction ID** for non-cash payments
2. **Verify amount** before confirming
3. **Check payment method** matches customer's actual payment
4. **Record immediately** after receiving payment
5. **Keep receipts** for cash payments

### Managing Invoices
1. **Download invoices** for digital records
2. **Print for customers** on request
3. **Keep invoice copies** for accounting
4. **Reference invoice #** in communications
5. **Use date range** for monthly reconciliation

### Financial Control
1. **Daily reconciliation**: Check payments against orders
2. **Weekly reports**: Export and review payment summary
3. **Monthly statement**: Prepare accounting summary
4. **Customer follow-up**: Check pending payments
5. **Archive records**: Save all invoices and payment receipts

---

## SHORTCUTS & TIPS

### Time-Saving Tips
- Use **"Export Report"** for bulk payment data
- **Filter by date** to find specific periods
- **Search by order#** for quick lookup
- Use **browser print** for faster printing
- Keep **thermal printer** ready for frequent invoicing

### Professional Tips
- Always provide **invoice** to customer
- Print **receipt** as proof of payment
- Keep **digital copies** for records
- Use **transaction IDs** for traceability
- **Record payments immediately** (don't delay)

---

## FAQ

**Q: Can I edit a recorded payment?**
A: No, payments are locked once recorded. Record new payment or contact admin if error.

**Q: What if customer pays partially?**
A: Record the partial payment. Balance remains due. Record additional payment when received.

**Q: Can invoice show different shop information?**
A: Currently it's fixed. Contact admin to customize shop details.

**Q: How long are payments stored?**
A: Indefinitely. They're part of permanent order records.

**Q: Can I export payments?**
A: Yes, use "Export Report" button for CSV export with date filtering.

**Q: What if invoice doesn't print correctly?**
A: Try "Print to PDF" first, then print the PDF. Contact IT if persists.

**Q: Can customer get invoice via email?**
A: Yes, download PDF and email to customer address.

**Q: What payment methods should I support?**
A: At minimum Cash and Card. Add Bank Transfer for larger orders.

---

**User Guide Version**: 1.0
**Last Updated**: July 10, 2026
**For**: Receptionist Users
