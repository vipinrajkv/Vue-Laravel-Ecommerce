<script setup >
import axiosInstance from '../axiosInstance';
import { ref } from 'vue';
import { onMounted } from 'vue';
import { useProductStore } from '@/stores/product'; 

 const baseUrl = import.meta.env.VITE_API_BACKEND_URL;
const products = ref([]);
const productStore = useProductStore();
console.log(productStore);
onMounted(()=>{
  axiosInstance.get('/products').then((response)=>{
    console.log(response.data.data);
    products.value = response.data.data
  })
})

const addItemToCart = async (id) => {
      try {
        const response = await axiosInstance.get(`products/${id}`);
        const productData = response.data.data;

        const transformedData = {
          productId: productData.id,
          productQty: 1,
          productName: productData.product_name,
          productImage: productData.product_image,
          productPrice: productData.product_price,
          totalPrice: 0,
        };

        productStore.addToCart(transformedData);
      } catch (error) {
        console.error('Error adding item to cart:', error);
      }
    };

    const incrementCartItem = async (id) => {
        productStore.increment(id);
    };

    const decrementCartItem = async (id) => {
        productStore.decrement(id);
    };

</script>
<template>
    <section class="pb-5">
      <div class="container-lg">
        <div class="row">
          <div class="col-md-12">

            <div class="section-header d-flex flex-wrap justify-content-between my-4">
              
              <!-- <h2 class="section-title">Best selling products</h2>

              <div class="d-flex align-items-center">
                <a href="#" class="btn btn-primary rounded-1">View All</a>
              </div> -->
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5">
              <div  v-for="product in products" :key="product.id" class="col">
                <div class="product-item">
                  <figure>
                    <a href="index.html" title="Product Title">
                      <img :src="`${baseUrl}images/products/${product.product_image}`" alt="Product Thumbnail" class="tab-image">
                    </a>
                  </figure>
                  <div class="d-flex flex-column text-center">
                    <h3 class="fs-6 fw-normal">{{ product.product_name }}</h3>                 
                    <div class="d-flex justify-content-center align-items-center gap-2">
                      <!-- <del>$24.00</del> -->
                      <span class="text-dark fw-semibold">$18.00</span>
                      <!-- <span class="badge border border-dark-subtle rounded-0 fw-normal px-1 fs-7 lh-1 text-body-tertiary">10% OFF</span> -->
                    </div>
                    <div class="button-area p-3 pt-0">
                      <div class="row g-1 mt-2">
                        <div class="col-2">
                        <button class="quantity-btn"  @click="decrementCartItem(product.id)" >-</button>
                        </div>
                        <div class="col-2"><input type="text" name="quantity" class="form-control border-dark-subtle input-number quantity" value="1">
                        </div>
                        <div class="col-2">
                        <button class="quantity-btn"  @click="incrementCartItem(product.id)" >+</button>
                        </div>
                        <div class="col-6"><a href="#" class="btn btn-primary rounded-1 p-2 fs-7 btn-cart"  @click="addItemToCart(product.id)">
                          <svg width="18" height="18"><use xlink:href="#cart"></use></svg> Add to Cart</a></div>
                        <!-- <div class="col-2"><a href="#" class="btn btn-outline-dark rounded-1 p-2 fs-6"><svg width="18" height="18"><use xlink:href="#heart"></use></svg></a></div> -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              
            </div>

          </div>
        </div>


      </div>
    </section>
</template>