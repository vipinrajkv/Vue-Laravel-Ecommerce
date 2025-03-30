<script setup>
import { useProductStore } from '@/stores/product';
import { computed } from 'vue';
const productStore = useProductStore();

const cartList = computed(() => productStore.cartItems || []);
console.log(cartList.value);
console.log(cartList.value.length);
const getProductQuantity = (productId) => {
    if (!cartList.value || cartList.value.length === 0) {
        return 0;
    }
    const product = cartList.value.find(item => item.productId === productId);

    return product ? product.productQty : 0;
}
const baseUrl = import.meta.env.VITE_API_BACKEND_URL;
const cartItemsCount = computed(() => productStore.cartItemsCount);
const incrementCartItem = async (id) => {
    productStore.increment(id);
};

const decrementCartItem = (id) => {
    productStore.decrement(id);
};
const cartListTotal = computed(() => {
  return productStore.cartItems.reduce((total, item) => {
    return total + item.totalPrice;
  }, 0);
})

</script>
<template>
    <div class="cart-wrapper">
        <div class="container">
            <div class="row g-4">
                <!-- Cart Items Section -->
                <div class="col-lg-8">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">Shopping Cart</h4>
                        <span class="text-muted">{{ cartItemsCount }} items</span>
                    </div>


                    <div v-if="cartList.length === 0" class="d-flex flex-column gap-3">
                        <div class="product-card p-3 shadow-sm">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <h6 class="mb-1">No Items In Cart</h6>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Product Cards -->
                    <div v-else class="d-flex flex-column gap-3">
                        <div v-for="item in cartList" :key="item.id" class="product-card p-3 shadow-sm">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <img :src="`${baseUrl}images/products/${item.productImage}`" alt="Product" class="product-image">
                                </div>
                                <div class="col-md-4">
                                    <h6 class="mb-1">{{ item.productName }}</h6>
                                    <p class="text-muted mb-0">{{ item.productQty }} X {{ item.productPrice }}</p>
                                    <!-- <span class="discount-badge mt-2">20% OFF</span> -->
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <button class="quantity-btn" @click="decrementCartItem(item.productId)">-</button>
                                        <input type="text" class="quantity-input" :value="getProductQuantity(item.productId)">
                                        <button class="quantity-btn" @click="incrementCartItem(item.productId)">+</button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <span class="fw-bold">${{ item.productPrice * item.productQty }}</span>
                                </div>
                                <div class="col-md-1">
                                    <i class="bi bi-trash remove-btn"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Summary Section -->
                <div class="col-lg-4">
                    <div class="summary-card p-4 shadow-sm">
                        <h5 class="mb-4">Order Summary</h5>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Subtotal</span>
                            <span>${{ cartListTotal }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Discount</span>
                            <span class="text-success">- 0.00</span>
                        </div>
                        <!-- <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Shipping</span>
                            <span>$5.00</span>
                        </div> -->
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold">${{ cartListTotal }}</span>
                        </div>

                        <!-- Promo Code -->
                        <!-- <div class="mb-4">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Promo code">
                                <button class="btn btn-outline-secondary" type="button">Apply</button>
                            </div>
                        </div> -->

                        <button class="btn btn-primary checkout-btn w-100 mb-3">
                            Proceed to Checkout
                        </button>

                        <div class="d-flex justify-content-center gap-2">
                            <i class="bi bi-shield-check text-success"></i>
                            <small class="text-muted">Secure checkout</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>