import { useState, useCallback } from 'react';

export interface OrderCreationState {
  isLoading: boolean;
  error: string | null;
  success: boolean;
}

/**
 * Custom hook for order creation operations
 * Handles API calls and state management
 */
export const useOrderCreation = () => {
  const [state, setState] = useState<OrderCreationState>({
    isLoading: false,
    error: null,
    success: false,
  });

  /**
   * Create order on backend
   * @param customerId - Customer ID
   * @param items - Order items
   * @param total - Order total
   */
  const createOrder = useCallback(
    async (
      customerId: string,
      items: any[],
      total: number
    ): Promise<{ success: boolean; orderId?: string }> => {
      setState({ isLoading: true, error: null, success: false });

      try {
        const response = await fetch('/api/orders', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '',
          },
          body: JSON.stringify({
            customer_id: customerId,
            items: items.map((item) => ({
              product_id: item.productId,
              product_type: item.productType,
              size: item.size,
              quantity: item.quantity,
              unit_price: item.unitPrice,
              cloth_details: item.clothDetails,
              stitching_details: item.stitchingDetails,
              subtotal: item.subtotal,
            })),
            total,
          }),
        });

        if (!response.ok) {
          const error = await response.json();
          throw new Error(error.message || 'Failed to create order');
        }

        const data = await response.json();
        setState({ isLoading: false, error: null, success: true });
        return { success: true, orderId: data.id };
      } catch (err) {
        const errorMessage = err instanceof Error ? err.message : 'An unknown error occurred';
        setState({ isLoading: false, error: errorMessage, success: false });
        return { success: false };
      }
    },
    []
  );

  /**
   * Search products
   * @param query - Search term
   * @param productType - Filter by type
   * @param page - Page number
   */
  const searchProducts = useCallback(
    async (query: string, productType: string = 'ALL', page: number = 1) => {
      try {
        const params = new URLSearchParams({
          q: query,
          type: productType,
          page: page.toString(),
          per_page: '6',
        });

        const response = await fetch(`/api/products/search?${params}`);
        if (!response.ok) throw new Error('Failed to search products');
        return await response.json();
      } catch (err) {
        console.error('Search error:', err);
        return { products: [], total: 0 };
      }
    },
    []
  );

  /**
   * Get product details
   * @param productId - Product ID
   */
  const getProductDetails = useCallback(async (productId: string) => {
    try {
      const response = await fetch(`/api/products/${productId}`);
      if (!response.ok) throw new Error('Failed to fetch product');
      return await response.json();
    } catch (err) {
      console.error('Product fetch error:', err);
      return null;
    }
  }, []);

  /**
   * Validate stock availability
   * @param productId - Product ID
   * @param quantity - Requested quantity
   */
  const checkStock = useCallback(async (productId: string, quantity: number) => {
    try {
      const response = await fetch(`/api/products/${productId}/stock?quantity=${quantity}`);
      if (!response.ok) throw new Error('Stock check failed');
      return await response.json();
    } catch (err) {
      console.error('Stock check error:', err);
      return { available: false };
    }
  }, []);

  return {
    state,
    createOrder,
    searchProducts,
    getProductDetails,
    checkStock,
  };
};

export default useOrderCreation;
