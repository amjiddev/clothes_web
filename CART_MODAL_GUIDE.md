# Shopping Cart Modal Implementation Guide

## Overview
A shopping cart modal that opens from the right side when users click the cart icon in the navbar. It displays all products added to the cart with options to update quantities and remove items.

## Features Implemented

### 1. **Cart Sidebar Modal**
- Opens from the right side of the screen
- Displays all products in the cart
- Shows product image, name, price, and quantity
- Beautiful smooth animation

### 2. **Cart Functionality**
- **Add to Cart**: Click "Add to Cart" button on product page
- **Update Quantity**: Use +/- buttons to increase/decrease quantity
- **Remove Item**: Click the "Remove" link to delete product from cart
- **Cart Badge**: Shows the number of items in cart (in navbar)
- **Total Price**: Displays the total cart value

### 3. **Local Storage**
- Cart data is saved to browser's localStorage
- Cart persists even after page refresh
- Automatic synchronization across tabs

### 4. **UI Components**
- **Cart Header**: Shows "Shopping Cart" title with close button
- **Cart Body**: Lists all items with details
- **Cart Footer**: Shows total and action buttons
- **Empty State**: Displays friendly message when cart is empty
- **Overlay**: Semi-transparent background that closes cart when clicked

## How to Use in Your Pages

### Adding Products to Cart

In your product pages, use the `addToCart()` global function:

```javascript
// Call this function when user clicks "Add to Cart" button
addToCart({
    id: product.id,              // Unique product ID (required)
    name: product.name,          // Product name (required)
    price: product.price,        // Product price (required)
    image: product.image_url,    // Product image URL (required)
    quantity: qty,               // Quantity (required, default: 1)
    size: selected_size,         // Optional: selected size
    color: selected_color        // Optional: selected color
});
```

### Example: Product Detail Page

```javascript
function addToCart(productId) {
    const qty = parseInt(document.getElementById('quantity').value);
    const size = document.getElementById('selectedSize')?.value || null;
    const color = document.getElementById('selectedColor')?.value || null;
    
    // Get product details from DOM
    const productName = document.querySelector('h1').textContent;
    const price = parseFloat(document.querySelector('[price-element]').textContent);
    const productImage = document.getElementById('mainImage').src;

    // Call the global cart function
    window.addToCart({
        id: productId,
        name: productName,
        price: price,
        image: productImage,
        size: size,
        color: color,
        quantity: qty
    });
}
```

## Available Global Functions

### `addToCart(product)`
Adds a product to the cart and opens the cart modal.
- **Parameters**: product object with id, name, price, image, quantity
- **Returns**: None
- **Side Effect**: Opens cart modal automatically

### `updateQuantity(index, change)`
Updates the quantity of an item in the cart.
- **Parameters**: 
  - `index`: Item index in cart array
  - `change`: Amount to change (+1 or -1)
- **Returns**: None

### `removeFromCart(index)`
Removes an item from the cart.
- **Parameters**: `index` - Item index in cart array
- **Returns**: None

### `openCart()`
Opens the cart modal.
- **Parameters**: None
- **Returns**: None

### `closeCart()`
Closes the cart modal.
- **Parameters**: None
- **Returns**: None

### `updateCartUI()`
Updates the cart display and badge.
- **Parameters**: None
- **Returns**: None

### `loadCart()`
Loads cart from localStorage.
- **Parameters**: None
- **Returns**: Array of cart items

### `saveCart(cart)`
Saves cart to localStorage.
- **Parameters**: `cart` - Cart array to save
- **Returns**: None

## CSS Classes

### `.cart-sidebar`
Main cart container - use for styling

### `.cart-sidebar.active`
Applied when cart is open

### `.cart-item`
Individual cart item container

### `.cart-overlay`
Background overlay - use for styling

### `.cart-overlay.active`
Applied when cart is open

## Data Structure

### Cart Item Object
```javascript
{
    id: 123,                    // Product ID
    name: "Product Name",       // Product name
    price: 99.99,               // Unit price
    image: "/path/to/image",    // Product image URL
    quantity: 2,                // Quantity in cart
    size: "M",                  // Optional: size
    color: "Red"                // Optional: color
}
```

## Storage Key
- **localStorage key**: `shopping_cart`
- **Value**: JSON stringified array of cart items

## Styling Customization

All styling is defined in `resources/views/frontend/layouts/app.blade.php` within the `<style>` section.

Key CSS variables you can customize:
- `--accent-gold`: Primary accent color
- `--primary-dark`: Primary dark color
- `--text-muted`: Muted text color

## Responsive Design

- **Desktop**: Cart sidebar is 400px wide
- **Mobile**: Cart sidebar takes full width (100%)
- **Navbar**: Padding adjusted for mobile

## Integration Points

### Product Pages
- Update the `addToCart()` function to extract product details from your page
- Pass the product object to `window.addToCart()`

### Shop Page
- Add click handlers to product cards
- Call `addToCart()` with product information

### Components
- Reusable across all product listing pages
- Works with any product layout

## Browser Support

- Works in all modern browsers (Chrome, Firefox, Safari, Edge)
- Uses localStorage (IE9+)
- Uses CSS transitions and transforms (IE10+)

## Notes

- Cart persists across browser sessions
- Each item quantity is tracked separately
- Duplicate products increase quantity instead of adding new item
- Total is calculated client-side

## Future Enhancements

Possible additions:
1. Cart persistence to database (logged in users)
2. Coupon code support
3. Wishlist functionality
4. Compare products
5. Cart abandonment tracking
