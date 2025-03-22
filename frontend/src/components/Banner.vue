<script setup>
import { computed, ref } from 'vue';

const backgroundImageUrl = ref('url(/src/assets/images/banner-1.jpg)');

const sectionStyle = computed(() => ({
  backgroundImage: backgroundImageUrl.value,
  backgroundRepeat: 'no-repeat',
  backgroundSize: 'cover'
}));
</script>
<template>
    <!-- <section style="background-image: url('images/banner-1.jpg');background-repeat: no-repeat;background-size: cover;"> -->
      <section :style="sectionStyle">
      <div class="container-lg">
        <div class="row">
          <div class="col-lg-6 pt-5 mt-5">
            <h2 class="display-1 ls-1"><span class="fw-bold text-primary">Organic</span> Foods at your <span class="fw-bold">Doorsteps</span></h2>      
          </div>
        </div>
    </div>       
    </section>
</template>