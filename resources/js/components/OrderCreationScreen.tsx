import React, { useState, useCallback, useEffect, useReducer } from 'react';
import {
  Search,
  ChevronLeft,
  ChevronRight,
  Plus,
  X,
  ShoppingCart,
  Trash2,
  AlertCircle,
} from 'lucide-react';
import debounce from 'lodash/debounce';

// Types
type ProductType = 'READY_MADE' | 'CLOTH_AND_STITCHING';

interface Product {
  id: string;
  name: string;
  sku: string;
  category: string;
  price: number;
  stock: number;
  imageUrl: string;
  productType: ProductType;
  availableSizes?: string[];
}

interface OrderItem {
  id: string;
  productId: string;
  productName: string;
  productType: ProductType;
  size?: string;
  quantity: number;
  unitPrice: number;
  clothDetails?: {
    fabricType: string;
    color: string;
    meters: number;
    clothPrice: number;
  };
  stitchingDetails?: {
    chest?: number;
    waist?: number;
    shoulder?: number;
    sleeveLength?: number;
    shirtLength?: number;
    stitchingCharge: number;
    instructions?: string;
  };
  subtotal: number;
}

// Mock API
const mockProducts: Product[] = [
  {
    id: '1',
    name: 'Classic Formal Shirt',
    sku: 'SHIRT-001',
    category: 'Shirts',
    price: 1500,
    stock: 19,
    imageUrl: 'https://via.placeholder.com/300x300?text=Formal+Shirt',
    productType: 'READY_MADE',
    availableSizes: ['S', 'M', 'L', 'XL', 'XXL'],
  },
  {
    id: '2',
    name: 'Premium Cotton Fabric',
    sku: 'FABRIC-001',
    category: 'Fabrics',
    price: 800,
    stock: 45,
    imageUrl: 'https://via.placeholder.com/300x300?text=Cotton+Fabric',
    productType: 'CLOTH_AND_STITCHING',
  },
  {
    id: '3',
    name: 'Casual T-Shirt',
    sku: 'TSHIRT-001',
    category: 'T-Shirts',
    price: 800,
    stock: 0,
    imageUrl: 'https://via.placeholder.com/300x300?text=T-Shirt',
    productType: 'READY_MADE',
    availableSizes: ['S', 'M', 'L', 'XL'],
  },
  {
    id: '4',
    name: 'Silk Kurta with Stitching',
    sku: 'KURTA-001',
    category: 'Kurtas',
    price: 2500,
    stock: 8,
    imageUrl: 'https://via.placeholder.com/300x300?text=Silk+Kurta',
    productType: 'CLOTH_AND_STITCHING',
  },
  {
    id: '5',
    name: 'Designer Trousers',
    sku: 'TROUSER-001',
    category: 'Trousers',
    price: 1200,
    stock: 12,
    imageUrl: 'https://via.placeholder.com/300x300?text=Trousers',
    productType: 'READY_MADE',
    availableSizes: ['28', '30', '32', '34', '36'],
  },
  {
    id: '6',
    name: 'Handloom Saree',
    sku: 'SAREE-001',
    category: 'Sarees',
    price: 3500,
    stock: 5,
    imageUrl: 'https://via.placeholder.com/300x300?text=Handloom+Saree',
    productType: 'CLOTH_AND_STITCHING',
  },
];

// Pagination reducer
interface PaginationState {
  currentPage: number;
  pageSize: number;
}

type PaginationAction = { type: 'NEXT' } | { type: 'PREV' } | { type: 'GOTO'; page: number } | { type: 'RESET' };

const paginationReducer = (
  state: PaginationState,
  action: PaginationAction
): PaginationState => {
  switch (action.type) {
    case 'NEXT':
      return { ...state, currentPage: state.currentPage + 1 };
    case 'PREV':
      return { ...state, currentPage: Math.max(1, state.currentPage - 1) };
    case 'GOTO':
      return { ...state, currentPage: action.page };
    case 'RESET':
      return { ...state, currentPage: 1 };
    default:
      return state;
  }
};

// Product Card Component
const ProductCard: React.FC<{
  product: Product;
  isSelected: boolean;
  onAddClick: () => void;
}> = ({ product, isSelected, onAddClick }) => {
  const isOutOfStock = product.stock === 0;
  const badgeColor = product.productType === 'READY_MADE' ? 'bg-blue-500' : 'bg-purple-500';

  return (
    <div
      className={`relative bg-white rounded-lg shadow-md overflow-hidden transition-all ${
        isSelected ? 'ring-2 ring-green-500 shadow-lg' : 'hover:shadow-lg'
      }`}
    >
      {/* Image */}
      <div className="relative bg-gray-100 h-48 overflow-hidden">
        <img
          src={product.imageUrl}
          alt={product.name}
          className="w-full h-full object-cover"
          loading="lazy"
        />
        {isSelected && (
          <div className="absolute top-2 right-2 bg-green-500 text-white rounded-full p-1">
            <svg
              className="w-5 h-5"
              fill="currentColor"
              viewBox="0 0 20 20"
            >
              <path
                fillRule="evenodd"
                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                clipRule="evenodd"
              />
            </svg>
          </div>
        )}
      </div>

      {/* Content */}
      <div className="p-4">
        <p className="text-xs text-gray-500 mb-1">{product.category}</p>
        <h3 className="font-bold text-sm mb-2 truncate">{product.name}</h3>
        <p className="text-xs text-gray-600 mb-2">SKU: {product.sku}</p>

        {/* Price and Stock */}
        <div className="flex justify-between items-center mb-3">
          <span className="font-bold text-lg">Rs. {product.price}</span>
          <span
            className={`text-xs px-2 py-1 rounded ${
              isOutOfStock
                ? 'bg-red-100 text-red-700'
                : product.stock <= 5
                ? 'bg-yellow-100 text-yellow-700'
                : 'bg-green-100 text-green-700'
            }`}
          >
            {isOutOfStock ? 'Out of Stock' : `${product.stock} left`}
          </span>
        </div>

        {/* Type Badge */}
        <div className="flex gap-2 mb-3">
          <span className={`text-xs text-white px-2 py-1 rounded ${badgeColor}`}>
            {product.productType === 'READY_MADE' ? 'Ready Made' : 'Cloth + Stitching'}
          </span>
        </div>

        {/* Add Button */}
        <button
          onClick={onAddClick}
          disabled={isOutOfStock}
          className={`w-full py-2 rounded font-semibold text-sm transition-all ${
            isOutOfStock
              ? 'bg-gray-200 text-gray-500 cursor-not-allowed'
              : isSelected
              ? 'bg-green-500 text-white hover:bg-green-600'
              : 'bg-blue-500 text-white hover:bg-blue-600'
          }`}
        >
          {isSelected ? '✓ Added' : '+ Add'}
        </button>
      </div>
    </div>
  );
};

// Ready Made Modal
const ReadyMadeModal: React.FC<{
  product: Product;
  onConfirm: (size: string, quantity: number) => void;
  onCancel: () => void;
}> = ({ product, onConfirm, onCancel }) => {
  const [size, setSize] = useState(product.availableSizes?.[0] || 'M');
  const [quantity, setQuantity] = useState(1);

  const handleConfirm = () => {
    if (quantity > product.stock) {
      alert(`Only ${product.stock} items in stock`);
      return;
    }
    onConfirm(size, quantity);
  };

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div className="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
        <div className="flex justify-between items-center mb-4">
          <h2 className="text-xl font-bold">{product.name}</h2>
          <button onClick={onCancel} className="text-gray-500 hover:text-gray-700">
            <X size={24} />
          </button>
        </div>

        <div className="space-y-4">
          {/* Size Selection */}
          <div>
            <label className="block text-sm font-semibold mb-2">Size</label>
            <select
              value={size}
              onChange={(e) => setSize(e.target.value)}
              className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              {product.availableSizes?.map((s) => (
                <option key={s} value={s}>
                  {s}
                </option>
              ))}
            </select>
          </div>

          {/* Quantity */}
          <div>
            <label className="block text-sm font-semibold mb-2">Quantity</label>
            <input
              type="number"
              min="1"
              max={product.stock}
              value={quantity}
              onChange={(e) => setQuantity(Math.max(1, parseInt(e.target.value) || 1))}
              className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>

          {/* Subtotal */}
          <div className="bg-gray-100 p-3 rounded">
            <p className="text-sm text-gray-600">Subtotal</p>
            <p className="text-2xl font-bold">Rs. {(product.price * quantity).toLocaleString()}</p>
          </div>

          {/* Buttons */}
          <div className="flex gap-3 pt-4">
            <button
              onClick={onCancel}
              className="flex-1 px-4 py-2 border border-gray-300 rounded font-semibold hover:bg-gray-50"
            >
              Cancel
            </button>
            <button
              onClick={handleConfirm}
              className="flex-1 px-4 py-2 bg-blue-500 text-white rounded font-semibold hover:bg-blue-600"
            >
              Add to Order
            </button>
          </div>
        </div>
      </div>
    </div>
  );
};

// Cloth & Stitching Modal
const ClothStitchingModal: React.FC<{
  product: Product;
  onConfirm: (
    clothDetails: OrderItem['clothDetails'],
    stitchingDetails: OrderItem['stitchingDetails']
  ) => void;
  onCancel: () => void;
}> = ({ product, onConfirm, onCancel }) => {
  const [clothDetails, setClothDetails] = useState({
    fabricType: 'Cotton',
    color: 'White',
    meters: 2,
    clothPrice: product.price,
  });

  const [stitchingDetails, setStitchingDetails] = useState({
    chest: undefined as number | undefined,
    waist: undefined as number | undefined,
    shoulder: undefined as number | undefined,
    sleeveLength: undefined as number | undefined,
    shirtLength: undefined as number | undefined,
    stitchingCharge: 500,
    instructions: '',
  });

  const handleConfirm = () => {
    onConfirm(clothDetails, stitchingDetails);
  };

  const itemTotal = clothDetails.clothPrice + stitchingDetails.stitchingCharge;

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 overflow-y-auto">
      <div className="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 my-8 p-6">
        <div className="flex justify-between items-center mb-4">
          <h2 className="text-xl font-bold">{product.name}</h2>
          <button onClick={onCancel} className="text-gray-500 hover:text-gray-700">
            <X size={24} />
          </button>
        </div>

        <div className="space-y-6">
          {/* Cloth Section */}
          <div className="border-b pb-6">
            <h3 className="text-lg font-semibold mb-4 text-blue-600">Cloth Details</h3>
            <div className="grid grid-cols-2 gap-4">
              <div>
                <label className="block text-sm font-semibold mb-2">Fabric Type</label>
                <input
                  type="text"
                  value={clothDetails.fabricType}
                  onChange={(e) =>
                    setClothDetails({ ...clothDetails, fabricType: e.target.value })
                  }
                  className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              <div>
                <label className="block text-sm font-semibold mb-2">Color</label>
                <input
                  type="text"
                  value={clothDetails.color}
                  onChange={(e) =>
                    setClothDetails({ ...clothDetails, color: e.target.value })
                  }
                  className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              <div>
                <label className="block text-sm font-semibold mb-2">Meters</label>
                <input
                  type="number"
                  step="0.5"
                  value={clothDetails.meters}
                  onChange={(e) =>
                    setClothDetails({ ...clothDetails, meters: parseFloat(e.target.value) })
                  }
                  className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
              <div>
                <label className="block text-sm font-semibold mb-2">Cloth Price (Rs.)</label>
                <input
                  type="number"
                  value={clothDetails.clothPrice}
                  onChange={(e) =>
                    setClothDetails({ ...clothDetails, clothPrice: parseFloat(e.target.value) })
                  }
                  className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
            </div>
          </div>

          {/* Stitching Section */}
          <div className="border-b pb-6">
            <h3 className="text-lg font-semibold mb-4 text-purple-600">Stitching Details</h3>
            <div className="grid grid-cols-2 gap-4 mb-4">
              <div>
                <label className="block text-sm font-semibold mb-2">Chest (cm)</label>
                <input
                  type="number"
                  value={stitchingDetails.chest || ''}
                  onChange={(e) =>
                    setStitchingDetails({
                      ...stitchingDetails,
                      chest: e.target.value ? parseFloat(e.target.value) : undefined,
                    })
                  }
                  className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                />
              </div>
              <div>
                <label className="block text-sm font-semibold mb-2">Waist (cm)</label>
                <input
                  type="number"
                  value={stitchingDetails.waist || ''}
                  onChange={(e) =>
                    setStitchingDetails({
                      ...stitchingDetails,
                      waist: e.target.value ? parseFloat(e.target.value) : undefined,
                    })
                  }
                  className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                />
              </div>
              <div>
                <label className="block text-sm font-semibold mb-2">Shoulder (cm)</label>
                <input
                  type="number"
                  value={stitchingDetails.shoulder || ''}
                  onChange={(e) =>
                    setStitchingDetails({
                      ...stitchingDetails,
                      shoulder: e.target.value ? parseFloat(e.target.value) : undefined,
                    })
                  }
                  className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                />
              </div>
              <div>
                <label className="block text-sm font-semibold mb-2">Sleeve Length (cm)</label>
                <input
                  type="number"
                  value={stitchingDetails.sleeveLength || ''}
                  onChange={(e) =>
                    setStitchingDetails({
                      ...stitchingDetails,
                      sleeveLength: e.target.value ? parseFloat(e.target.value) : undefined,
                    })
                  }
                  className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                />
              </div>
              <div>
                <label className="block text-sm font-semibold mb-2">Shirt Length (cm)</label>
                <input
                  type="number"
                  value={stitchingDetails.shirtLength || ''}
                  onChange={(e) =>
                    setStitchingDetails({
                      ...stitchingDetails,
                      shirtLength: e.target.value ? parseFloat(e.target.value) : undefined,
                    })
                  }
                  className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                />
              </div>
              <div>
                <label className="block text-sm font-semibold mb-2">Stitching Charge (Rs.)</label>
                <input
                  type="number"
                  value={stitchingDetails.stitchingCharge}
                  onChange={(e) =>
                    setStitchingDetails({
                      ...stitchingDetails,
                      stitchingCharge: parseFloat(e.target.value),
                    })
                  }
                  className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                />
              </div>
            </div>

            <div>
              <label className="block text-sm font-semibold mb-2">Special Instructions</label>
              <textarea
                value={stitchingDetails.instructions}
                onChange={(e) =>
                  setStitchingDetails({
                    ...stitchingDetails,
                    instructions: e.target.value,
                  })
                }
                rows={3}
                placeholder="e.g., Add contrast collar, use button xyz..."
                className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
              />
            </div>
          </div>

          {/* Summary */}
          <div className="bg-gray-100 p-4 rounded">
            <div className="flex justify-between mb-2">
              <span className="text-sm">Cloth Price:</span>
              <span className="font-semibold">Rs. {clothDetails.clothPrice.toLocaleString()}</span>
            </div>
            <div className="flex justify-between border-b pb-2 mb-2">
              <span className="text-sm">Stitching Charge:</span>
              <span className="font-semibold">Rs. {stitchingDetails.stitchingCharge.toLocaleString()}</span>
            </div>
            <div className="flex justify-between">
              <span className="text-lg font-bold">Item Total:</span>
              <span className="text-2xl font-bold text-green-600">Rs. {itemTotal.toLocaleString()}</span>
            </div>
          </div>

          {/* Buttons */}
          <div className="flex gap-3 pt-4">
            <button
              onClick={onCancel}
              className="flex-1 px-4 py-2 border border-gray-300 rounded font-semibold hover:bg-gray-50"
            >
              Cancel
            </button>
            <button
              onClick={handleConfirm}
              className="flex-1 px-4 py-2 bg-blue-500 text-white rounded font-semibold hover:bg-blue-600"
            >
              Add to Order
            </button>
          </div>
        </div>
      </div>
    </div>
  );
};

// Order Summary Panel
const OrderSummaryPanel: React.FC<{
  items: OrderItem[];
  onUpdateQuantity: (itemId: string, quantity: number) => void;
  onRemove: (itemId: string) => void;
  onCreateOrder: () => void;
}> = ({ items, onUpdateQuantity, onRemove, onCreateOrder }) => {
  const total = items.reduce((sum, item) => sum + item.subtotal, 0);

  return (
    <div className="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col h-full">
      {/* Header */}
      <div className="bg-blue-600 text-white p-4 flex items-center gap-2">
        <ShoppingCart size={24} />
        <h2 className="text-lg font-bold">Order Summary</h2>
      </div>

      {/* Items List */}
      <div className="flex-1 overflow-y-auto p-4 space-y-3">
        {items.length === 0 ? (
          <div className="text-center py-8 text-gray-500">
            <ShoppingCart size={48} className="mx-auto mb-2 opacity-50" />
            <p>No items added yet</p>
          </div>
        ) : (
          items.map((item) => (
            <div key={item.id} className="border rounded-lg p-3 bg-gray-50">
              <div className="flex justify-between items-start mb-2">
                <div>
                  <p className="font-semibold text-sm">{item.productName}</p>
                  {item.size && (
                    <p className="text-xs text-gray-600">Size: {item.size}</p>
                  )}
                  {item.clothDetails && (
                    <p className="text-xs text-gray-600">
                      {item.clothDetails.fabricType} - {item.clothDetails.color}
                    </p>
                  )}
                  {item.stitchingDetails && (
                    <p className="text-xs text-gray-600">
                      Chest: {item.stitchingDetails.chest}cm, Waist:{' '}
                      {item.stitchingDetails.waist}cm
                    </p>
                  )}
                </div>
                <button
                  onClick={() => onRemove(item.id)}
                  className="text-red-500 hover:text-red-700"
                >
                  <Trash2 size={18} />
                </button>
              </div>

              {/* Quantity Control */}
              <div className="flex items-center gap-2 mb-2">
                <span className="text-xs text-gray-600">Qty:</span>
                <input
                  type="number"
                  min="1"
                  value={item.quantity}
                  onChange={(e) =>
                    onUpdateQuantity(item.id, Math.max(1, parseInt(e.target.value) || 1))
                  }
                  className="w-12 border border-gray-300 rounded px-2 py-1 text-xs"
                />
              </div>

              {/* Subtotal */}
              <div className="text-right">
                <p className="font-bold text-sm">Rs. {item.subtotal.toLocaleString()}</p>
              </div>
            </div>
          ))
        )}
      </div>

      {/* Footer */}
      <div className="border-t p-4 bg-gray-50 space-y-3">
        {/* Total */}
        <div className="bg-blue-50 p-3 rounded border border-blue-200">
          <div className="flex justify-between items-center">
            <span className="text-lg font-bold">TOTAL:</span>
            <span className="text-2xl font-bold text-blue-600">Rs. {total.toLocaleString()}</span>
          </div>
        </div>

        {/* Create Order Button */}
        <button
          onClick={onCreateOrder}
          disabled={items.length === 0}
          className={`w-full py-3 rounded font-bold text-white transition-all ${
            items.length === 0
              ? 'bg-gray-300 cursor-not-allowed'
              : 'bg-green-500 hover:bg-green-600'
          }`}
        >
          CREATE ORDER
        </button>
      </div>
    </div>
  );
};

// Main Component
export const OrderCreationScreen: React.FC = () => {
  const [searchTerm, setSearchTerm] = useState('');
  const [selectedProductType, setSelectedProductType] = useState<'ALL' | ProductType>('ALL');
  const [filteredProducts, setFilteredProducts] = useState(mockProducts);
  const [orderItems, setOrderItems] = useState<OrderItem[]>([]);
  const [activeModal, setActiveModal] = useState<{
    type: 'READY_MADE' | 'CLOTH_AND_STITCHING' | null;
    product: Product | null;
  }>({ type: null, product: null });
  const [paginationState, paginationDispatch] = useReducer(paginationReducer, {
    currentPage: 1,
    pageSize: 6,
  });

  // Debounced search
  const debouncedSearch = useCallback(
    debounce((term: string) => {
      let results = mockProducts;

      // Filter by search term
      if (term) {
        const lowerTerm = term.toLowerCase();
        results = results.filter(
          (p) =>
            p.name.toLowerCase().includes(lowerTerm) ||
            p.sku.toLowerCase().includes(lowerTerm) ||
            p.category.toLowerCase().includes(lowerTerm)
        );
      }

      // Filter by product type
      if (selectedProductType !== 'ALL') {
        results = results.filter((p) => p.productType === selectedProductType);
      }

      setFilteredProducts(results);
      paginationDispatch({ type: 'RESET' });
    }, 300),
    [selectedProductType]
  );

  useEffect(() => {
    debouncedSearch(searchTerm);
  }, [searchTerm, debouncedSearch]);

  // Pagination logic
  const totalPages = Math.ceil(filteredProducts.length / paginationState.pageSize);
  const startIdx = (paginationState.currentPage - 1) * paginationState.pageSize;
  const paginatedProducts = filteredProducts.slice(
    startIdx,
    startIdx + paginationState.pageSize
  );

  // Handle add product
  const handleAddProduct = (product: Product) => {
    setActiveModal({
      type: product.productType,
      product,
    });
  };

  // Handle confirm ready made
  const handleConfirmReadyMade = (size: string, quantity: number) => {
    if (!activeModal.product) return;

    const existingItem = orderItems.find(
      (item) =>
        item.productId === activeModal.product!.id &&
        item.size === size &&
        item.productType === 'READY_MADE'
    );

    if (existingItem) {
      setOrderItems(
        orderItems.map((item) =>
          item.id === existingItem.id
            ? {
                ...item,
                quantity: item.quantity + quantity,
                subtotal: (item.quantity + quantity) * item.unitPrice,
              }
            : item
        )
      );
    } else {
      const newItem: OrderItem = {
        id: `${activeModal.product.id}-${Date.now()}`,
        productId: activeModal.product.id,
        productName: activeModal.product.name,
        productType: 'READY_MADE',
        size,
        quantity,
        unitPrice: activeModal.product.price,
        subtotal: activeModal.product.price * quantity,
      };
      setOrderItems([...orderItems, newItem]);
    }

    setActiveModal({ type: null, product: null });
  };

  // Handle confirm cloth & stitching
  const handleConfirmClothStitching = (
    clothDetails: OrderItem['clothDetails'],
    stitchingDetails: OrderItem['stitchingDetails']
  ) => {
    if (!activeModal.product) return;

    const newItem: OrderItem = {
      id: `${activeModal.product.id}-${Date.now()}`,
      productId: activeModal.product.id,
      productName: activeModal.product.name,
      productType: 'CLOTH_AND_STITCHING',
      quantity: 1,
      unitPrice: clothDetails!.clothPrice + stitchingDetails!.stitchingCharge,
      clothDetails,
      stitchingDetails,
      subtotal: clothDetails!.clothPrice + stitchingDetails!.stitchingCharge,
    };
    setOrderItems([...orderItems, newItem]);
    setActiveModal({ type: null, product: null });
  };

  // Handle update quantity
  const handleUpdateQuantity = (itemId: string, newQuantity: number) => {
    setOrderItems(
      orderItems.map((item) =>
        item.id === itemId
          ? {
              ...item,
              quantity: newQuantity,
              subtotal: item.unitPrice * newQuantity,
            }
          : item
      )
    );
  };

  // Handle remove
  const handleRemove = (itemId: string) => {
    setOrderItems(orderItems.filter((item) => item.id !== itemId));
  };

  // Handle create order
  const handleCreateOrder = () => {
    console.log('Creating order with items:', orderItems);
    alert(`Order created with ${orderItems.length} items. Total: Rs. ${orderItems.reduce((sum, item) => sum + item.subtotal, 0)}`);
    // In real app, send to API
  };

  const selectedProductIds = new Set(orderItems.map((item) => item.productId));

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <div className="bg-white shadow">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
          <h1 className="text-3xl font-bold text-gray-900">Create Order</h1>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {/* Search and Filters */}
        <div className="bg-white rounded-lg shadow mb-6 p-6">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            {/* Search Bar */}
            <div className="md:col-span-2">
              <div className="relative">
                <Search className="absolute left-3 top-3 text-gray-400" size={20} />
                <input
                  type="text"
                  placeholder="Search by name, SKU, or category..."
                  value={searchTerm}
                  onChange={(e) => setSearchTerm(e.target.value)}
                  className="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
            </div>

            {/* Product Type Filter */}
            <div>
              <select
                value={selectedProductType}
                onChange={(e) =>
                  setSelectedProductType(e.target.value as 'ALL' | ProductType)
                }
                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="ALL">All Products</option>
                <option value="READY_MADE">Ready Made</option>
                <option value="CLOTH_AND_STITCHING">Cloth + Stitching</option>
              </select>
            </div>
          </div>

          {/* Results Info */}
          <div className="mt-4 text-sm text-gray-600">
            Showing {filteredProducts.length} product{filteredProducts.length !== 1 ? 's' : ''}
          </div>
        </div>

        {/* Main Content */}
        <div className="grid grid-cols-1 lg:grid-cols-4 gap-6">
          {/* Product Grid */}
          <div className="lg:col-span-3">
            {paginatedProducts.length === 0 ? (
              <div className="bg-white rounded-lg shadow p-12 text-center">
                <AlertCircle size={48} className="mx-auto mb-4 text-gray-400" />
                <p className="text-gray-600 text-lg">No products found</p>
              </div>
            ) : (
              <>
                {/* Grid */}
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                  {paginatedProducts.map((product) => (
                    <ProductCard
                      key={product.id}
                      product={product}
                      isSelected={selectedProductIds.has(product.id)}
                      onAddClick={() => handleAddProduct(product)}
                    />
                  ))}
                </div>

                {/* Pagination */}
                <div className="flex justify-center items-center gap-2">
                  <button
                    onClick={() => paginationDispatch({ type: 'PREV' })}
                    disabled={paginationState.currentPage === 1}
                    className="p-2 border border-gray-300 rounded hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <ChevronLeft size={20} />
                  </button>

                  {Array.from({ length: totalPages }, (_, i) => i + 1).map((page) => (
                    <button
                      key={page}
                      onClick={() => paginationDispatch({ type: 'GOTO', page })}
                      className={`px-3 py-1 rounded font-semibold transition-all ${
                        paginationState.currentPage === page
                          ? 'bg-blue-500 text-white'
                          : 'border border-gray-300 hover:bg-gray-100'
                      }`}
                    >
                      {page}
                    </button>
                  ))}

                  <button
                    onClick={() => paginationDispatch({ type: 'NEXT' })}
                    disabled={paginationState.currentPage === totalPages}
                    className="p-2 border border-gray-300 rounded hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    <ChevronRight size={20} />
                  </button>
                </div>
              </>
            )}
          </div>

          {/* Order Summary Sidebar */}
          <div className="lg:col-span-1">
            <div className="sticky top-6">
              <OrderSummaryPanel
                items={orderItems}
                onUpdateQuantity={handleUpdateQuantity}
                onRemove={handleRemove}
                onCreateOrder={handleCreateOrder}
              />
            </div>
          </div>
        </div>
      </div>

      {/* Modals */}
      {activeModal.type === 'READY_MADE' && activeModal.product && (
        <ReadyMadeModal
          product={activeModal.product}
          onConfirm={handleConfirmReadyMade}
          onCancel={() => setActiveModal({ type: null, product: null })}
        />
      )}

      {activeModal.type === 'CLOTH_AND_STITCHING' && activeModal.product && (
        <ClothStitchingModal
          product={activeModal.product}
          onConfirm={handleConfirmClothStitching}
          onCancel={() => setActiveModal({ type: null, product: null })}
        />
      )}
    </div>
  );
};

export default OrderCreationScreen;
