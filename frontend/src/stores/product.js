import { ref, computed } from 'vue';
import axiosInstance from "../axiosInstance";
import { defineStore } from 'pinia'

export const useProductStore = defineStore('product', {
    state: () => ({
        cartCount : 0,
        cartItems: [],
        cartItemsTotal: 0
      }),
      getters: {
        cartItemsCount: (state) => state.cartItems.length,
      },
      actions: {
        addToCart(item) {
          // Push the item to the cart
          this.cartItems.push(item);
          return this.cartItemsCount
        },

        increment(itemId) {
          alert(itemId);
          console.log(this.cartItems);
          const updatedCartList = this.cartItems.map((item) => {
            if (item.productId === itemId) {
                item.productQty += 1;
                item.totalPrice = item.productQty * item.productPrice;
            }
            return item;
        });
        this.cartList = updatedCartList;

        },
        decrement(itemId) {
          alert(itemId);
          const updatedCartList = this.cartItems.map((item) => {
            if (item.productId === itemId) {
                item.productQty -= 1;
                item.totalPrice = item.productQty * item.productPrice;
            }
            return item;
        });
        this.cartList = updatedCartList;

        },
      },
})
