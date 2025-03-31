import { ref, computed } from 'vue';
import axiosInstance from "../axiosInstance";
import { defineStore } from 'pinia'

const getCartLocalStorageData = () => {
  const storedData = localStorage.getItem('cartData');

  if (storedData) {
    const parsedData = JSON.parse(storedData);
    
    return {
      cartItems: parsedData.cart || [],
      cartItemsTotal: parsedData.cartTotalAmount || 0
    };
  }

  return {
    cartItems: [],
    cartItemsTotal: 0
  };
}

const storeCartItemToLocalStorage = (data,totalAmount = 0) => {
  const cartData = {
      cart: data,
      cartTotalAmount: totalAmount
  };
  localStorage.setItem('cartData',JSON.stringify(cartData));
}

export const useProductStore = defineStore('product', {
  state: () => {
    const { cartItems, cartItemsTotal } = getCartLocalStorageData();

    return {
      cartItems,
      cartItemsTotal,
      cartCount: 0
    };
  },
  getters: {
    cartItemsCount: (state) => state.cartItems.length,
  },
  actions: {
    addToCart(item) {
      const existingItem = this.cartItems.find(cartItem => cartItem.productId === item.productId);

      if (existingItem) {
        existingItem.productQty += 1;
        existingItem.totalPrice = existingItem.productQty * existingItem.productPrice;
        storeCartItemToLocalStorage(this.cartItems, this.cartItemsTotal);

        return ;
      } else {
        this.cartItems.push({
          ...item,
          productQty: 1,
          totalPrice: item.productPrice,
        });

        storeCartItemToLocalStorage(this.cartItems, this.cartItemsTotal);
      }

      this.cartCount = this.cartItems.length;
      this.cartItemsTotal = this.cartItems.reduce((total, item) => total + item.totalPrice, 0);
    },

    increment(itemId) {
      const updatedCartList = this.cartItems.map((item) => {

        if (item.productId === itemId) {
          item.productQty += 1;
          item.totalPrice = item.productQty * item.productPrice;
        }

        return item;
      });
      const totalAmount = updatedCartList.reduce(
        (cartTotal, cartItem) => cartTotal + cartItem.totalPrice,
        0
      );
      this.cartList = updatedCartList;
      this.cartItemsTotal = totalAmount;
    },
    decrement(itemId) {
      const updatedCartList = this.cartItems.map((item) => {

        if (item.productId === itemId) {
          item.productQty -= 1;
          item.totalPrice = item.productQty * item.productPrice;
        }

        return item;
      });
      const totalAmount = updatedCartList.reduce(
        (cartTotal, cartItem) => cartTotal + cartItem.totalPrice,
        0
      );
      this.cartList = updatedCartList;
      this.cartItemsTotal = totalAmount;
    },
  },
})
