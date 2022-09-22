<template>
  <div class="circle-spinner inline-block w-4 h-4 border-t-2 rounded-full border-c1-500"></div>
</template>

<script>
export default {
  name: "Spinner",
}
</script>

<style scoped>
@keyframes bouncedelay {
  0%,
  80%,
  100% {
    transform: scale(0);
  }
  40% {
    transform: scale(1);
  }
}

/* circle spinner */
.circle-spinner {
  animation: spin 1s ease-in-out infinite;
}
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style>
