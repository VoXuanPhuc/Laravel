<template>
  <div v-if="loading">
    <!-- Pinned -->
    <EcFlex v-for="item in rows" :key="item" class="rounded-3xl p-4 mt-8 bg-c4-50">
      <EcBox>
        <EcBox class="h-16 w-16 bg-c1-50 rounded-full skeleton" />
      </EcBox>
      <EcBox class="w-10/12 ml-3">
        <EcLabel class="w-full rounded h-6 bg-c1-50 mb-2 skeleton"> </EcLabel>
        <EcText class="w-full rounded h-6 bg-c1-50 skeleton"> </EcText>
        <EcText class="mt-3 rounded w-2/4 h-6 bg-c1-50 skeleton"></EcText>
      </EcBox>
    </EcFlex>
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
  name: "NotificationItemSkeleton",
  props: {
    loading: {
      type: Boolean,
      default: true,
    },

    rows: {
      type: Number,
      default: 3,
    },
  },
}
</script>
