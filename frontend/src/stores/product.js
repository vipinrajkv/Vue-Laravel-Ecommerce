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
          // Check if the item is already in the cart
          const existingItem = this.cartItems.find(cartItem => cartItem.productId === item.productId);
    
          if (existingItem) {
            // If item exists, increment quantity and update totalPrice
            existingItem.productQty += 1;
            existingItem.totalPrice = existingItem.productQty * existingItem.productPrice;
          } else {
            // If item doesn't exist, add new item with productQty of 1 and calculate totalPrice
            this.cartItems.push({
              ...item,
              productQty: 1,
              totalPrice: item.productPrice 
            });
          }
  
          this.cartCount = this.cartItems.length;
          this.cartItemsTotal = this.cartItems.reduce((total, item) => total + item.totalPrice, 0);         
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
