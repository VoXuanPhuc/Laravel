<template>
  <EcBox class="mb-5 font-medium text-lg">
    <EcFlex>
      <EcFlex v-for="(item, index) in items" :key="index">
        <EcText class="text-c1-500 mr-3 cursor-pointer hover:underline" @click="navigateTo(item)">
          {{ item.label }}
        </EcText>
        <EcText class="text-c0-500 mr-3">/</EcText>
      </EcFlex>
    </EcFlex>
  </EcBox>
</template>

<script>
export default {
  name: "RBreadcrumb",
  props: {
    items: {
      type: Array,
      required: true,
      default: () => [],
    },
  },
  methods: {
    navigateTo(item) {
      this.$router.push(item.navigation)
    },
  },
}
</script>
