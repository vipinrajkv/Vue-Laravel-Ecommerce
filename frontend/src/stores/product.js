import { ref, computed } from 'vue';
import axiosInstance from "../../axiosInstance";
import { defineStore } from 'pinia'

export const useProductStore = defineStore('product', {
    state: () => ({
        cartCount : 0,
        itemsCount : 0,
        cartItemsTotal: 0
      }),
      getters: {

      },
      actions: {

      },
})
