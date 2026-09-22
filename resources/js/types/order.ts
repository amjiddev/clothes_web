/**
 * Order Creation Types and Interfaces
 */

export type ProductType = 'READY_MADE' | 'CLOTH_AND_STITCHING';

/**
 * Product definition
 * Every product must have a productType that determines how it's ordered
 */
export interface Product {
  /** Unique product identifier */
  id: string;

  /** Product display name */
  name: string;

  /** Stock keeping unit (SKU) for inventory */
  sku: string;

  /** Product category (Shirts, Sarees, Kurtas, etc.) */
  category: string;

  /** Base price in Rs. */
  price: number;

  /** Current stock quantity available */
  stock: number;

  /** Product image URL (lazy-loaded) */
  imageUrl: string;

  /** Product type determines order flow */
  productType: ProductType;

  /** Available sizes (for READY_MADE only) */
  availableSizes?: string[];

  /** Product description (optional) */
  description?: string;

  /** Additional metadata */
  metadata?: Record<string, any>;
}

/**
 * Cloth details for CLOTH_AND_STITCHING products
 * Captures fabric information and pricing
 */
export interface ClothDetails {
  /** Type of fabric (Cotton, Silk, Wool, etc.) */
  fabricType: string;

  /** Fabric color */
  color: string;

  /** Length in meters */
  meters: number;

  /** Price of cloth in Rs. */
  clothPrice: number;

  /** Optional: supplier or batch info */
  batch?: string;
}

/**
 * Stitching measurements and details
 * Used for CLOTH_AND_STITCHING products
 */
export interface StitchingDetails {
  /** Chest circumference in cm (optional) */
  chest?: number;

  /** Waist circumference in cm (optional) */
  waist?: number;

  /** Shoulder width in cm (optional) */
  shoulder?: number;

  /** Sleeve length in cm (optional) */
  sleeveLength?: number;

  /** Full shirt/kurta length in cm (optional) */
  shirtLength?: number;

  /** Inseam for trousers in cm (optional) */
  inseam?: number;

  /** Additional length measurements (optional) */
  additionalMeasurements?: Record<string, number>;

  /** Stitching charge in Rs. */
  stitchingCharge: number;

  /** Special stitching instructions (optional) */
  instructions?: string;

  /** Reference to customer's saved measurement profile (optional) */
  measurementProfileId?: string;
}

/**
 * Single line item in an order
 * Represents one selected product with all relevant details
 */
export interface OrderItem {
  /** Unique ID for this order item (within order context) */
  id: string;

  /** Reference to the product */
  productId: string;

  /** Product name snapshot (denormalized for display) */
  productName: string;

  /** Type of product (determines available fields) */
  productType: ProductType;

  /** ===== READY_MADE FIELDS ===== */

  /** Selected size (for READY_MADE) */
  size?: string;

  /** Quantity ordered */
  quantity: number;

  /** Unit price at time of order (for auditing) */
  unitPrice: number;

  /** ===== CLOTH_AND_STITCHING FIELDS ===== */

  /** Cloth details (for CLOTH_AND_STITCHING) */
  clothDetails?: ClothDetails;

  /** Stitching details (for CLOTH_AND_STITCHING) */
  stitchingDetails?: StitchingDetails;

  /** ===== TOTALS ===== */

  /** Line item total = unitPrice * quantity (for READY_MADE)
   *  OR = clothPrice + stitchingCharge (for CLOTH_AND_STITCHING)
   */
  subtotal: number;

  /** Timestamp when item was added to order */
  addedAt?: Date;

  /** Optional notes specific to this item */
  notes?: string;
}

/**
 * Complete order to be submitted
 */
export interface Order {
  /** Customer ID (reference to users table) */
  customerId: string;

  /** Order items */
  items: OrderItem[];

  /** Order total in Rs. */
  total: number;

  /** Overall delivery date (optional) */
  deliveryDate?: Date;

  /** Overall order notes (optional) */
  notes?: string;

  /** Payment method (optional) */
  paymentMethod?: 'CASH' | 'CARD' | 'ONLINE' | 'CREDIT';

  /** Discount percentage or amount (optional) */
  discount?: number;

  /** Tax amount (optional) */
  tax?: number;
}

/**
 * API request payload for order creation
 */
export interface CreateOrderRequest {
  customer_id: string;
  items: Array<{
    product_id: string;
    product_type: ProductType;
    size?: string;
    quantity: number;
    unit_price: number;
    cloth_details?: ClothDetails;
    stitching_details?: StitchingDetails;
    subtotal: number;
  }>;
  total: number;
  delivery_date?: string;
  notes?: string;
  payment_method?: string;
  discount?: number;
  tax?: number;
}

/**
 * API response for order creation
 */
export interface CreateOrderResponse {
  id: string;
  order_number: string;
  customer_id: string;
  items: OrderItem[];
  total: number;
  status: 'pending' | 'confirmed' | 'processing' | 'completed';
  created_at: string;
  updated_at: string;
}

/**
 * Paginated product search response
 */
export interface ProductSearchResponse {
  products: Product[];
  total: number;
  page: number;
  pageSize: number;
  totalPages: number;
}

/**
 * Product search request parameters
 */
export interface ProductSearchParams {
  query?: string;
  productType?: 'ALL' | ProductType;
  category?: string;
  page?: number;
  pageSize?: number;
  sortBy?: 'name' | 'price' | 'stock';
  sortOrder?: 'asc' | 'desc';
}

/**
 * Stock availability response
 */
export interface StockCheckResponse {
  available: boolean;
  currentStock: number;
  requestedQuantity: number;
  message?: string;
}

/**
 * Error response from API
 */
export interface ErrorResponse {
  success: false;
  message: string;
  code?: string;
  details?: Record<string, any>;
}

/**
 * Success response from API
 */
export interface SuccessResponse<T> {
  success: true;
  data: T;
}

/**
 * Combined API response type
 */
export type ApiResponse<T> = SuccessResponse<T> | ErrorResponse;
