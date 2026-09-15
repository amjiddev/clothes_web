# Fix: Error Creating Tailor - Missing Database Columns

## Problem

**Error:** `SQLSTATE[42S22]: Column not found: 1054 Unknown column 'phone' in 'field list'`

This error occurs when trying to create a new tailor because the `tailors` table is missing several columns that the Tailor model expects.

## Root Cause

The `tailors` table migration was missing these columns:
- `phone`
- `skills`
- `profile_image`
- `completed_orders`
- `pending_orders`

But the `Tailor` model's fillable array includes these fields, and the `TailorController` tries to save data to them.

## Solution

A new migration has been created to add the missing columns.

### Step 1: Run the Migration

```bash
php artisan migrate
```

This will run the new migration file:
`database/migrations/2024_12_15_000000_add_missing_columns_to_tailors_table.php`

### What the Migration Does

Adds the following columns to the `tailors` table:
- `phone` (string, nullable) - Tailor's phone number
- `skills` (json, nullable) - Array of tailor's skills
- `profile_image` (string, nullable) - Path to profile image
- `completed_orders` (integer, default 0) - Count of completed orders
- `pending_orders` (integer, default 0) - Count of pending orders

### Step 2: Test Tailor Creation

After running the migration, you should be able to create a tailor without errors.

## Files Created

- `database/migrations/2024_12_15_000000_add_missing_columns_to_tailors_table.php`

## Verification

After running the migration, the tailor creation form should work properly and allow you to:
1. Select a user to assign as tailor
2. Enter phone number
3. Enter specialization
4. Add skills
5. Upload profile image
6. Set hourly rate
7. Enter experience years
8. Set status (active/inactive/on_leave)

## Rollback (if needed)

If you need to rollback the migration:

```bash
php artisan migrate:rollback
```

This will remove the added columns (only affects the `tailors` table).
