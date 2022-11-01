<template>
  <div v-if="loading">
    <div v-for="item in rows" :key="item" class="flex w-full mb-2">
      <div class="input w-8/12 bg-c4-50 h-10 rounded-lg skeleton"></div>
      <div class="button ml-3 bg-c4-50 h-10 w-4/12 rounded-lg skeleton"></div>
    </div>
  </div>
  <slot v-else></slot>
</template>

<style>
.skeleton {
  animation: skeleton-loading 1s linear infinite alternate;
}

@keyframes skeleton-loading {
  0% {
    background-color: rgb(252, 252, 252);
  }

  100% {
    background-color: rgb(225, 225, 225);
  }
}
</style>
<script>
export default {
  name: "RowSkeletonLoader",
  props: {
    loading: {
      type: Boolean,
      default: true,
    },

    rows: {
      type: Number,
      default: 1,
    },
  },
}
</script>
